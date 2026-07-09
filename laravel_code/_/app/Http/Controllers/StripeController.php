<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Plans;
use App\Models\PromoCodeUsages;
use App\Models\Subscriptions;
use App\Models\Transactions;
use App\Services\PromoCodeService;
use App\Models\PaymentGateways;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
  use Traits\Functions;

  protected $promoCodeService;

  public function __construct(Request $request)
  {
    $this->request = $request;
    $this->promoCodeService = app(PromoCodeService::class);
  }

  /**
   * Show/Send data Stripe
   *
   * @return response
   */
  protected function show()
  {
    if (!$this->request->expectsJson()) {
      abort(404);
    }

    if (!auth()->user()->hasPaymentMethod('card')) {
      return response()->json([
        "success" => false,
        'errors' => ['error' => __('general.please_add_payment_card')]
      ]);
    }

    // Find the user to subscribe
    $user = User::whereVerifiedId('yes')
      ->whereId($this->request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    // Check if Plan exists
    $plan = $user->plans()
      ->whereInterval($this->request->interval)
      ->latest()
      ->firstOrFail();

    $checkout = $this->buildCheckoutContext($user, $plan);

    if (! $checkout['success']) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => $checkout['error']]
      ]);
    }

    $payment = PaymentGateways::whereName($this->request->payment_gateway)->whereEnabled(1)->firstOrFail();

    try {
      $userPlan = $this->createPlan($payment->key_secret, $plan, $user);
      $pricing = $checkout['pricing'];
      $promoCode = $checkout['promo_code'];
      $promoUsage = null;
      $couponId = null;
      $defaultPaymentMethod = auth()->user()->defaultPaymentMethod();
      $subscriptionOptions = [];
      $stripeTaxRates = auth()->user()->taxRates();
      $stripeTaxPercentage = (float) auth()->user()->isTaxable()->sum('percentage');

      if (! $defaultPaymentMethod) {
        return response()->json([
          "success" => false,
          'errors' => ['error' => __('general.please_add_payment_card')]
        ]);
      }

      // Check Payment Incomplete
      if (auth()->user()
        ->userSubscriptions()
        ->where('stripe_price', $userPlan)
        ->whereStripeStatus('incomplete')
        ->first()
      ) {
        return response()->json([
          "success" => false,
          'errors' => ['error' => __('general.please_confirm_payment')]
        ]);
      }

      if ($promoCode) {
        $promoUsage = $this->promoCodeService->createUsage($promoCode, [
          'user_id' => auth()->id(),
          'plan_id' => $plan->id,
          'plan_interval' => $plan->interval,
          'gateway_name' => 'Stripe',
          'original_amount' => $pricing['original_amount'],
          'discount_amount' => $pricing['discount_amount'],
          'charged_amount' => $pricing['charged_amount'],
          'creator_earning_impact' => $pricing['discount_amount'],
          'gateway_fee_amount' => $pricing['gateway_fee_amount'],
          'final_paid_amount' => $pricing['total_due'],
          'tax_amount' => $pricing['tax_amount'],
          'checkout_token' => str_random(40),
        ]);

        $couponId = $this->createPromoCoupon(
          $payment->key_secret,
          $promoUsage->checkout_token,
          (float) $plan->price,
          $pricing
        );
      }

      if ((float) $pricing['gateway_fee_amount'] > 0) {
        $gatewayFeeAmount = (float) $pricing['gateway_fee_amount'];

        // Stripe applies the subscription default tax rates to invoice items too.
        // Convert the stored gross gateway fee into a pre-tax amount so the final
        // taxed Stripe total matches the website preview.
        if (! empty($stripeTaxRates) && $stripeTaxPercentage > 0) {
          $gatewayFeeAmount = round($gatewayFeeAmount / (1 + ($stripeTaxPercentage / 100)), 2);
        }

        $gatewayFeeAmount = in_array(config('settings.currency_code'), config('currencies.zero-decimal'))
          ? (int) round($gatewayFeeAmount)
          : (int) round($gatewayFeeAmount * 100);
        $gatewayFeeProductId = $this->createGatewayFeeProduct($payment->key_secret, $user, $plan);

        $subscriptionOptions['add_invoice_items'] = [[
          'price_data' => [
            'currency' => strtolower(config('settings.currency_code')),
            'product' => $gatewayFeeProductId,
            'unit_amount' => $gatewayFeeAmount,
          ],
          'quantity' => 1,
          'discountable' => false,
          'tax_rates' => [],
          'metadata' => [
            'creator_id' => $user->id,
            'plan_id' => $plan->id,
            'type' => 'subscription_gateway_fee',
          ],
        ]];
      }

      if (! empty($stripeTaxRates)) {
        $subscriptionOptions['default_tax_rates'] = $stripeTaxRates;
      }

      // Create New subscription
      $metadata = [
        'interval' => $plan->interval,
        'creator_id' => $user->id,
        'taxes' => auth()->user()->taxesPayable(),
        'promo_usage_token' => optional($promoUsage)->checkout_token,
        'original_amount' => $pricing['original_amount'],
        'discount_amount' => $pricing['discount_amount'],
        'charged_amount' => $pricing['charged_amount'],
        'tax_amount' => $pricing['tax_amount'],
        'gateway_fee_amount' => $pricing['gateway_fee_amount'],
        'total_due' => $pricing['total_due'],
      ];

      $subscriptionBuilder = auth()->user()->newSubscription('main', $userPlan)
        ->withMetadata($metadata);

      if ($couponId) {
        $subscriptionBuilder->withCoupon($couponId);
      }

      $subscription = $subscriptionBuilder->create($defaultPaymentMethod->id, [], $subscriptionOptions);
      $taxes = auth()->user()->taxesPayable();
      $taxRate = (float) auth()->user()->isTaxable()->sum('percentage');
      $this->syncLocalSubscriptionContext($subscription->stripe_id, $user->id, $plan->interval, $taxes);
      $localSubscription = Subscriptions::where('stripe_id', $subscription->stripe_id)->first();

      if ($localSubscription) {
        $pendingTransaction = $this->createPendingSubscriptionTransaction(
          auth()->id(),
          $localSubscription->id,
          $user->id,
          (float) $pricing['total_due'],
          'Stripe',
          $taxes
        );

        if ($promoUsage) {
          $promoUsage->subscription_id = $localSubscription->id;
          $promoUsage->transaction_id = $pendingTransaction->id;
          $promoUsage->save();
        }
      }

      $settled = $this->settleInitialSubscription(
        $payment->key_secret,
        $subscription->stripe_id,
        $user,
        $plan,
        $pricing,
        $promoUsage,
        $taxes,
        $taxRate
      );

      if ($settled) {
        // Only notify once local settlement is confirmed.
        Subscriptions::sendEmailAndNotify(auth()->user()->name, $user->id);
        $this->sendWelcomeMessageAction($user, auth()->id());
      }

      sleep(3);

      return response()->json([
        'success' => true,
        'url' => $settled
          ? url('buy/subscription/success', $user->username)
          : route('subscription.success', ['user' => $user->username, 'delay' => 'stripe'])
      ]);
    } catch (IncompletePayment $exception) {
      // Insert ID Last Payment
      $subscriptions = Subscriptions::whereUserId(auth()->id())
        ->whereStripePrice($userPlan)
        ->whereStripeStatus('incomplete')
        ->first();

      if ($subscriptions) {
        $taxes = auth()->user()->taxesPayable();
        $this->syncLocalSubscriptionContext($subscriptions->stripe_id, $user->id, $plan->interval, $taxes);
        $subscriptions->last_payment = $exception->payment->id;
        $subscriptions->save();

        $pendingTransaction = $this->createPendingSubscriptionTransaction(
          auth()->id(),
          $subscriptions->id,
          $user->id,
          (float) $pricing['total_due'],
          'Stripe',
          $taxes
        );

        if (isset($promoUsage) && $promoUsage instanceof PromoCodeUsages) {
          $promoUsage->subscription_id = $subscriptions->id;
          $promoUsage->transaction_id = $pendingTransaction->id;
          $promoUsage->save();
        }
      }

      return response()->json([
        'success' => true,
        'url' => route('cashier.payment', [
          'id' => $exception->payment->id,
          'redirect' => route('subscription.stripe.return', ['user' => $user->username]),
        ]), // Redirect customer to page confirmation payment (SCA)
      ]);
    } catch (\Exception $exception) {
      if (isset($promoUsage) && $promoUsage instanceof PromoCodeUsages) {
        $this->promoCodeService->markUsageFailed($promoUsage, $exception->getMessage());
      }

      \Log::debug($exception);

      return response()->json([
        'success' => false,
        'errors' => ['error' => $exception->getMessage()]
      ]);
    }
  }

  private function createPlan($keySecret, $plan, $user)
  {
    try {
      $stripe = new \Stripe\StripeClient($keySecret);

      switch ($plan->interval) {
        case 'weekly':
          $interval = 'day';
          $interval_count = 7;
          break;

        case 'monthly':
          $interval = 'month';
          $interval_count = 1;
          break;

        case 'quarterly':
          $interval = 'month';
          $interval_count = 3;
          break;

        case 'biannually':
          $interval = 'month';
          $interval_count = 6;
          break;

        case 'yearly':
          $interval = 'year';
          $interval_count = 1;
          break;
      }

      // If it does not exist we create the plan
      $response = $stripe->plans->create([
        'currency' => config('settings.currency_code'),
        'interval' => $interval,
        'interval_count' => $interval_count,
        "product" => [
          "name" => __('general.subscription_for') . ' @' . $user->username,
        ],
        'amount' => in_array(config('settings.currency_code'), config('currencies.zero-decimal')) ? $plan->price : ($plan->price * 100),
      ]);

      return $response->id;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  protected function settleInitialSubscription(
    string $keySecret,
    string $stripeSubscriptionId,
    User $creator,
    Plans $plan,
    array $pricing,
    ?PromoCodeUsages $promoUsage,
    ?string $taxes = null,
    ?float $taxRate = null
  ): bool {
    $localSubscription = Subscriptions::where('stripe_id', $stripeSubscriptionId)->first();

    if (! $localSubscription) {
      return false;
    }

    $stripeSubscription = (new \Stripe\StripeClient($keySecret))
      ->subscriptions
      ->retrieve($stripeSubscriptionId, ['expand' => ['latest_invoice.payment_intent']]);

    $invoice = $stripeSubscription->latest_invoice ?? null;

    if (! $invoice) {
      return false;
    }

    $paymentIntentStatus = $invoice->payment_intent->status ?? null;
    $invoiceStatus = $invoice->status ?? null;
    $amountPaid = $this->normalizeStripeAmount($invoice->amount_paid ?? 0);
    $expectedAmount = round((float) $pricing['total_due'], 2);

    if ($invoiceStatus !== 'paid' && $paymentIntentStatus !== 'succeeded') {
      return false;
    }

    if ($amountPaid + 0.01 < $expectedAmount) {
      return false;
    }

    $localSubscription->stripe_status = 'active';
    $localSubscription->creator_id = $creator->id;
    $localSubscription->interval = $plan->interval;
    $localSubscription->taxes = $taxes;
    $localSubscription->save();

    $txnId = $invoice->id ?? ($invoice->payment_intent->id ?? null);

    if (! $txnId) {
      return false;
    }

    $transaction = Transactions::where('txn_id', $txnId)->first();
    $earnings = $this->earningsAdminUser($creator->custom_fee, (float) $pricing['charged_amount'], null, null);

    if (! $transaction) {
      $pendingTransaction = Transactions::where('subscriptions_id', $localSubscription->id)
        ->where('payment_gateway', 'Stripe')
        ->where('type', 'subscription')
        ->where('approved', '0')
        ->first() ?: $this->createPendingSubscriptionTransaction(
          $localSubscription->user_id,
          $localSubscription->id,
          $creator->id,
          (float) $pricing['total_due'],
          'Stripe',
          $taxes,
          $earnings['percentageApplied']
        );

      $transaction = $this->finalizePendingSubscriptionTransaction(
        $pendingTransaction,
        $txnId,
        (float) $pricing['total_due'],
        (float) $earnings['user'],
        (float) $earnings['admin'],
        'Stripe',
        $earnings['percentageApplied'],
        $taxes,
        $creator->id
      );

      $creator->increment('balance', $earnings['user']);
    }

    if ($promoUsage && $promoUsage->status !== 'completed') {
      $originalPricing = $this->buildSubscriptionPricing((float) $plan->price, 'Stripe', 0.0, $taxRate);
      $originalEarnings = $this->earningsAdminUser($creator->custom_fee, $originalPricing['charged_amount'], null, null);
      $usageSnapshot = $this->promoCodeService->buildUsageCompletionSnapshot(
        (float) $pricing['total_due'],
        (float) $pricing['gateway_fee_amount'],
        $originalEarnings,
        $earnings
      );

      $this->promoCodeService->markUsageCompleted($promoUsage, array_merge([
        'subscription_id' => $localSubscription->id,
        'transaction_id' => $transaction->id,
        'gateway_reference' => $txnId,
        'platform_commission_amount' => $earnings['admin'],
      ], $usageSnapshot));

      $this->promoCodeService->createHistory(
        $promoUsage->promo_code_id,
        $localSubscription->user_id,
        'system',
        'used',
        null,
        [
          'subscription_id' => $localSubscription->id,
          'transaction_id' => $transaction->id,
          'gateway_name' => 'Stripe',
          'charged_amount' => (float) $pricing['charged_amount'],
          'final_paid_amount' => (float) $pricing['total_due'],
        ]
      );
    }

    return true;
  }

  private function createPromoCoupon(string $keySecret, string $checkoutToken, float $originalPlanAmount, array $pricing): string
  {
    try {
      $stripe = new \Stripe\StripeClient($keySecret);
      $discountAmount = round(max($originalPlanAmount - (float) $pricing['charged_amount'], 0), 2);

      $payload = [
        'duration' => 'once',
        'name' => 'Promo ' . substr($checkoutToken, 0, 34),
        'metadata' => [
          'promo_usage_token' => $checkoutToken,
        ],
      ];

      if ($discountAmount <= 0) {
        throw new \Exception('Invalid promo discount amount for Stripe.');
      }

      if (in_array(config('settings.currency_code'), config('currencies.zero-decimal'))) {
        $payload['amount_off'] = (int) round($discountAmount);
      } else {
        $payload['amount_off'] = (int) round($discountAmount * 100);
      }

      $payload['currency'] = strtolower(config('settings.currency_code'));

      $coupon = $stripe->coupons->create($payload);

      return $coupon->id;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  private function createGatewayFeeProduct(string $keySecret, User $user, Plans $plan): string
  {
    try {
      $stripe = new \Stripe\StripeClient($keySecret);

      $product = $stripe->products->create([
        'name' => 'Payment gateway fee',
        'metadata' => [
          'creator_id' => $user->id,
          'plan_id' => $plan->id,
          'type' => 'subscription_gateway_fee',
        ],
      ]);

      return $product->id;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  protected function buildCheckoutContext(User $creator, Plans $plan): array
  {
    $originalAmount = round((float) $plan->price, 2);
    $pricing = $this->buildSubscriptionPricing(
      $originalAmount,
      $this->request->input('payment_gateway')
    );

    $promoCode = null;

    if ($this->request->filled('promo_code')) {
      $result = $this->promoCodeService->validateForCheckout(
        $creator->id,
        auth()->id(),
        $this->request->promo_code,
        $originalAmount
      );

      if (! $result['valid']) {
        return [
          'success' => false,
          'error' => $this->promoValidationMessage($result['reason']),
        ];
      }

      $promoCode = $result['promo_code'];
      $pricing = $this->buildSubscriptionPricing(
        $originalAmount,
        $this->request->input('payment_gateway'),
        (float) $result['pricing']['discount_amount']
      );
    }

    return [
      'success' => true,
      'creator' => $creator,
      'plan' => $plan,
      'promo_code' => $promoCode,
      'pricing' => $pricing,
    ];
  }

  protected function promoValidationMessage(string $reason): string
  {
    switch ($reason) {
      case 'disabled':
        return 'This promo code is disabled.';
      case 'expired':
        return 'This promo code has expired.';
      case 'limit_total_reached':
        return 'This promo code has reached its usage limit.';
      case 'limit_per_user_reached':
        return 'You have already used this promo code the maximum allowed times.';
      case 'invalid':
      default:
        return 'The promo code is invalid.';
    }
  }

  protected function syncLocalSubscriptionContext(string $stripeSubscriptionId, int $creatorId, string $interval, ?string $taxes): void
  {
    Subscriptions::where('stripe_id', $stripeSubscriptionId)->update([
      'creator_id' => $creatorId,
      'interval' => $interval,
      'taxes' => $taxes,
    ]);
  }

  protected function normalizeStripeAmount($amount): float
  {
    if (in_array(config('settings.currency_code'), config('currencies.zero-decimal'))) {
      return (float) $amount;
    }

    return round(((float) $amount) / 100, 2);
  }

  public function subscriptionReturn($user)
  {
    $status = $this->request->query('status');
    $success = $this->request->query('success') === 'true';
    $message = $this->request->query('message');
    $paymentIntentId = $this->request->query('payment_intent');

    if ($success) {
      $settled = $paymentIntentId
        ? $this->settleSuccessfulStripeReturn($paymentIntentId)
        : false;

      return redirect()->route('subscription.success', [
        'user' => $user,
        'delay' => $settled ? null : 'stripe',
      ]);
    }

    if ($status === 'processing') {
      $settled = $paymentIntentId
        ? $this->settleSuccessfulStripeReturn($paymentIntentId)
        : false;

      return redirect()->route('subscription.success', [
        'user' => $user,
        'delay' => $settled ? null : 'stripe',
      ]);
    }

    if ($paymentIntentId) {
      $this->cancelIncompleteStripeReturn($paymentIntentId);
    }

    session()->put('subscription_cancel', $message ?: __('general.subscription_cancel'));

    return redirect($user);
  }

  protected function settleSuccessfulStripeReturn(string $paymentIntentId): bool
  {
    $payment = PaymentGateways::whereName('Stripe')
      ->whereEnabled(1)
      ->where('key_secret', '<>', '')
      ->first();

    if (! $payment) {
      return false;
    }

    $stripe = new \Stripe\StripeClient($payment->key_secret);
    $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId, ['expand' => ['invoice.subscription']]);

    $stripeSubscriptionId = $paymentIntent->invoice->subscription->id
      ?? $paymentIntent->invoice->subscription
      ?? null;

    $localSubscription = Subscriptions::where('last_payment', $paymentIntentId)->first();

    if (! $localSubscription && $stripeSubscriptionId) {
      $localSubscription = Subscriptions::where('stripe_id', $stripeSubscriptionId)->first();
    }

    if (! $localSubscription) {
      return false;
    }

    $creator = User::whereId($localSubscription->creator_id)
      ->where('status', 'active')
      ->where('verified_id', 'yes')
      ->first();

    if (! $creator) {
      return false;
    }

    $promoUsage = PromoCodeUsages::where('subscription_id', $localSubscription->id)
      ->where('gateway_name', 'Stripe')
      ->latest('id')
      ->first();

    $plan = $promoUsage && $promoUsage->plan
      ? $promoUsage->plan
      : Plans::where('user_id', $creator->id)
        ->where('interval', $localSubscription->interval ?: 'monthly')
        ->latest()
        ->first();

    if (! $plan) {
      return false;
    }

    $taxRate = $this->promoCodeService->resolveTaxRateFromIds($localSubscription->taxes);
    $pricing = $promoUsage
      ? [
        'original_amount' => (float) $promoUsage->original_amount,
        'discount_amount' => (float) $promoUsage->discount_amount,
        'charged_amount' => (float) $promoUsage->charged_amount,
        'tax_amount' => (float) $promoUsage->tax_amount,
        'gateway_fee_amount' => (float) $promoUsage->gateway_fee_amount,
        'total_due' => (float) $promoUsage->final_paid_amount,
      ]
      : $this->buildSubscriptionPricing((float) $plan->price, 'Stripe', 0.0, $taxRate);

    $hadApprovedTransaction = Transactions::where('subscriptions_id', $localSubscription->id)
      ->where('payment_gateway', 'Stripe')
      ->where('type', 'subscription')
      ->where('approved', '1')
      ->exists();

    $settled = $this->settleInitialSubscription(
      $payment->key_secret,
      $localSubscription->stripe_id,
      $creator,
      $plan,
      $pricing,
      $promoUsage,
      $localSubscription->taxes,
      $taxRate
    );

    if ($settled && ! $hadApprovedTransaction) {
      Subscriptions::sendEmailAndNotify(
        optional($localSubscription->subscriber)->name ?? auth()->user()->name ?? __('general.someone'),
        $creator->id
      );
      $this->sendWelcomeMessageAction($creator, $localSubscription->user_id);
    }

    return $settled;
  }

  protected function cancelIncompleteStripeReturn(string $paymentIntentId): void
  {
    $subscription = Subscriptions::where('last_payment', $paymentIntentId)
      ->where('stripe_status', 'incomplete')
      ->first();

    if (! $subscription) {
      return;
    }

    $subscription->cancelled = 'yes';
    $subscription->stripe_status = 'canceled';
    $subscription->save();

    $pendingTransactions = Transactions::where('subscriptions_id', $subscription->id)
      ->where('payment_gateway', 'Stripe')
      ->where('type', 'subscription')
      ->where('approved', '0')
      ->get();

    foreach ($pendingTransactions as $pendingTransaction) {
      $pendingTransaction->approved = '2';
      $pendingTransaction->save();
    }

    $promoUsageQuery = PromoCodeUsages::where('gateway_name', 'Stripe')
      ->where('status', 'pending')
      ->where('subscription_id', $subscription->id);

    if ($pendingTransactions->count()) {
      $promoUsageQuery->orWhere(function ($query) use ($pendingTransactions) {
        $query->where('gateway_name', 'Stripe')
          ->where('status', 'pending')
          ->whereIn('transaction_id', $pendingTransactions->pluck('id')->all());
      });
    }

    $promoUsageQuery->get()->each(function (PromoCodeUsages $usage) {
      $this->promoCodeService->markUsageFailed(
        $usage,
        'Stripe payment confirmation was canceled by the user before completion.'
      );
    });
  }
}
