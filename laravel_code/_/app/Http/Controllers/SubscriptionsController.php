<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Plans;
use Illuminate\Http\Request;
use App\Services\PromoCodeService;
use App\Models\Notifications;
use App\Models\PromoCodeUsages;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use App\Models\SubscriptionDeleted;
use Illuminate\Support\Facades\Validator;

class SubscriptionsController extends Controller
{
  use Traits\Functions;

  protected const PROMO_SUBSCRIPTION_GATEWAYS = ['wallet', 'PayPal', 'Stripe'];

  protected $promoCodeService;

  public function __construct(Request $request)
  {
    $this->request = $request;
    $this->promoCodeService = app(PromoCodeService::class);
  }

  /**
   * Buy subscription
   *
   * @return Response
   */
  public function buy()
  {
    // Find the User
    $user = User::whereVerifiedId('yes')
      ->whereId($this->request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    // Check if Plan exists
    $plan = $user->plans()
      ->whereInterval($this->request->interval)
      ->firstOrFail();

    if (!$plan->status) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.subscription_not_available')],
      ]);
    }

    // Check if subscription exists
    $checkSubscription = auth()->user()->userSubscriptions()
      ->whereStripePrice($plan->name)
      ->where('ends_at', '>=', now())
      ->first();

    if ($checkSubscription) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => __('general.subscription_exists')],
      ]);
    }

    //<---- Validation
    $validator = Validator::make($this->request->all(), [
      'payment_gateway' => 'required|in:' . implode(',', self::PROMO_SUBSCRIPTION_GATEWAYS),
      'agree_terms' => 'required',
      'promo_code' => 'nullable|string|max:100',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'errors' => $validator->getMessageBag()->toArray(),
      ]);
    }

    $checkout = $this->buildCheckoutContext($user, $plan);

    if (! $checkout['success']) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => $checkout['error']],
      ]);
    }

    // Wallet
    if ($this->request->payment_gateway == 'wallet') {
      return $this->sendWallet($checkout);
    }

    // Get name of Payment Gateway
    $payment = PaymentGateways::whereIn('name', ['PayPal', 'Stripe'])
      ->whereName($this->request->payment_gateway)
      ->whereEnabled(1)
      ->firstOrFail();

    // Send data to the payment processor
    return redirect()->route(str_slug($payment->name), $this->request->except(['_token']));
  }

  public function previewPromo()
  {
    $validator = Validator::make($this->request->all(), [
      'id' => 'required|integer',
      'interval' => 'required|string',
      'promo_code' => 'nullable|string|max:100',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => __('general.unable_validate_promo_code'),
        'errors' => $validator->getMessageBag()->toArray(),
      ], 422);
    }

    $creator = User::whereVerifiedId('yes')
      ->whereId($this->request->id)
      ->where('id', '<>', auth()->id())
      ->firstOrFail();

    $plan = $creator->plans()
      ->whereInterval($this->request->interval)
      ->firstOrFail();

    if (!$plan->status) {
      return response()->json([
        'success' => true,
        'valid' => false,
        'message' => __('general.subscription_not_available'),
      ]);
    }

    $checkSubscription = auth()->user()->userSubscriptions()
      ->whereStripePrice($plan->name)
      ->where('ends_at', '>=', now())
      ->first();

    if ($checkSubscription) {
      return response()->json([
        'success' => true,
        'valid' => false,
        'message' => __('general.subscription_exists'),
      ]);
    }

    $checkout = $this->buildCheckoutContext($creator, $plan);

    if (! $checkout['success']) {
      return response()->json([
        'success' => true,
        'valid' => false,
        'message' => $checkout['error'],
      ]);
    }

    return response()->json([
      'success' => true,
      'valid' => true,
      'message' => __('general.promo_code_valid'),
      'interval' => $plan->interval,
      'is_free_checkout' => ((round((float) $checkout['pricing']['charged_amount'] + (float) $checkout['pricing']['tax_amount'], 2)) <= 0),
      'pricing' => [
        'has_discount' => ((float) $checkout['pricing']['discount_amount'] > 0),
        'original_amount' => $checkout['pricing']['original_amount'],
        'charged_amount' => $checkout['pricing']['charged_amount'],
        'discount_amount' => $checkout['pricing']['discount_amount'],
        'formatted_original_amount' => Helper::formatPrice($checkout['pricing']['original_amount'], true),
        'formatted_charged_amount' => Helper::formatPrice($checkout['pricing']['charged_amount'], true),
        'formatted_discount_amount' => Helper::formatPrice($checkout['pricing']['discount_amount'], true),
        'renewal_text' => $this->subscriptionRenewalText($plan->interval, (float) $checkout['pricing']['original_amount']),
      ],
      'button_label' => $this->subscriptionButtonLabel(
        $plan->interval,
        (float) $checkout['pricing']['charged_amount'],
        ((round((float) $checkout['pricing']['charged_amount'] + (float) $checkout['pricing']['tax_amount'], 2)) <= 0)
      ),
    ]);
  }

  /**
   * Free subscription
   *
   */
  public function subscriptionFree()
  {
    // Find user
    $creator = User::whereId($this->request->id)
      ->whereFreeSubscription('yes')
      ->whereVerifiedId('yes')
      ->firstOrFail();

    // Verify plan no is empty
    if (!$creator->plan || $creator->plan == 'user_' . $creator->id) {
      $creator->plan = 'plan_user_' . $creator->id;
      $creator->save();
    }

    // Check if not plans
    if ($creator->plans()->count() == 0) {
      Plans::updateOrCreate(
        [
          'user_id' => $creator->id,
          'name' => 'plan_user_' . $creator->id
        ],
        [
          'interval' => 'monthly',
          'status' => '1'
        ]
      );
    }

    // Verify subscription exists
    $subscription = Subscriptions::whereUserId(auth()->id())
      ->whereStripePrice($creator->plan)
      ->whereFree('yes')
      ->first();

    if ($subscription) {
      return response()->json([
        'success' => false,
        'error' => __('general.subscription_exists'),
      ]);
    }

    // Insert DB
    $sql = new Subscriptions();
    $sql->user_id = auth()->id();
    $sql->creator_id  = $creator->id;
    $sql->stripe_price = $creator->plan;
    $sql->free = 'yes';
    $sql->save();

    // Send Email to User and Notification
    Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);

    $this->sendWelcomeMessageAction($creator, auth()->id());

    return response()->json([
      'success' => true,
    ]);
  }

  public function cancelFreeSubscription($id)
  {
    $checkSubscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = User::whereId($checkSubscription->creator_id)->first();

    $this->subscriptionDeleted($creator->id, auth()->id());

    $checkSubscription->delete();

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    return redirect($creator->username);
  }

  public function cancelWalletSubscription($id)
  {
    $subscription = auth()->user()->userSubscriptions()->whereId($id)->firstOrFail();
    $creator = Plans::whereUserId($subscription->creator_id)->first();

    // Delete Subscription
    $subscription->cancelled = 'yes';
    $subscription->save();

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    return redirect($creator->user()->username);
  }

  /**
   *  Subscription via Wallet
   *
   * @return Response
   */
  protected function sendWallet(array $checkout)
  {
    $creator = $checkout['creator'];
    $plan = $checkout['plan'];
    $pricing = $checkout['pricing'];
    $promoCode = $checkout['promo_code'];
    $amount = $pricing['charged_amount'];

    // Verify plan no is empty
    if (!$creator->plan) {
      $creator->plan = 'plan_user_' . $creator->id;
      $creator->save();
    }

    $walletCharge = $pricing['charged_amount'] + $pricing['tax_amount'];

    if (auth()->user()->wallet < $walletCharge) {
      return response()->json([
        "success" => false,
        "errors" => ['error' => __('general.not_enough_funds')]
      ]);
    }

    // Insert DB
    $subscription              = new Subscriptions();
    $subscription->user_id     = auth()->id();
    $subscription->creator_id  = $creator->id;
    $subscription->stripe_price = $plan->name;
    $subscription->ends_at     = $creator->planInterval($plan->interval);
    $subscription->rebill_wallet = 'on';
    $subscription->interval = $plan->interval;
    $subscription->taxes = $checkout['taxes'];
    $subscription->save();

    $baseEarnings = $this->earningsAdminUser($creator->custom_fee, $pricing['original_amount'], null, null);
    $earnings = $this->promoCodeService->buildNetEarningsSnapshot($pricing['charged_amount'], $baseEarnings);

    // Insert Transaction
    $txn = $this->transaction(
      'subw_' . str_random(25),
      auth()->id(),
      $subscription->id,
      $creator->id,
      $pricing['charged_amount'],
      $earnings['user'],
      $earnings['admin'],
      'Wallet',
      'subscription',
      $earnings['percentageApplied'],
      $checkout['taxes']
    );

    // Subtract user funds
    auth()->user()->decrement('wallet', $walletCharge);

    // Add Earnings to User
    $creator->increment('balance', $earnings['user']);

    if ($promoCode) {
      $usage = $this->promoCodeService->createUsage($promoCode, [
        'user_id' => auth()->id(),
        'subscription_id' => $subscription->id,
        'transaction_id' => $txn->id,
        'plan_id' => $plan->id,
        'plan_interval' => $plan->interval,
        'gateway_name' => 'Wallet',
        'original_amount' => $pricing['original_amount'],
        'discount_amount' => $pricing['discount_amount'],
        'charged_amount' => $pricing['charged_amount'],
        'creator_earning_impact' => $pricing['discount_amount'],
        'platform_commission_amount' => $earnings['admin'],
        'tax_amount' => $pricing['tax_amount'],
        'status' => 'completed',
        'used_at' => now(),
      ]);

      $this->promoCodeService->markUsageCompleted($usage);
      $this->promoCodeService->createHistory(
        $promoCode->id,
        auth()->id(),
        'system',
        'used',
        null,
        [
          'subscription_id' => $subscription->id,
          'transaction_id' => $txn->id,
          'gateway_name' => 'Wallet',
          'charged_amount' => $pricing['charged_amount'],
        ]
      );
    }

    // Send Email to User and Notification
    Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);

    $this->sendWelcomeMessageAction($creator, auth()->id());

    return response()->json([
      'success' => true,
      'url' => url('buy/subscription/success', $creator->username)
    ]);
  }

  protected function subscriptionDeleted($creatorId, $userId)
  {
    SubscriptionDeleted::firstOrCreate([
      'creator_id' => $creatorId,
      'user_id' => $userId
    ]);

    Notifications::whereDestination($creatorId)
      ->whereAuthor($userId)
      ->whereType(1)
      ->delete();
  }

  protected function sendZeroPaymentSubscription(array $checkout)
  {
    $creator = $checkout['creator'];
    $plan = $checkout['plan'];
    $pricing = $checkout['pricing'];
    $promoCode = $checkout['promo_code'];

    if (!$creator->plan) {
      $creator->plan = 'plan_user_' . $creator->id;
      $creator->save();
    }

    $subscription = new Subscriptions();
    $subscription->user_id = auth()->id();
    $subscription->creator_id = $creator->id;
    $subscription->stripe_price = $plan->name;
    $subscription->ends_at = $creator->planInterval($plan->interval);
    $subscription->interval = $plan->interval;
    $subscription->taxes = $checkout['taxes'];
    $subscription->save();

    if ($promoCode) {
      $usage = $this->promoCodeService->createUsage($promoCode, [
        'user_id' => auth()->id(),
        'subscription_id' => $subscription->id,
        'plan_id' => $plan->id,
        'plan_interval' => $plan->interval,
        'gateway_name' => 'PromoZeroPay',
        'original_amount' => $pricing['original_amount'],
        'discount_amount' => $pricing['discount_amount'],
        'charged_amount' => 0,
        'creator_earning_impact' => $pricing['discount_amount'],
        'platform_commission_amount' => 0,
        'tax_amount' => $pricing['tax_amount'],
        'status' => 'completed',
        'used_at' => now(),
      ]);

      $this->promoCodeService->markUsageCompleted($usage);
      $this->promoCodeService->createHistory(
        $promoCode->id,
        auth()->id(),
        'system',
        'used',
        null,
        [
          'subscription_id' => $subscription->id,
          'gateway_name' => 'PromoZeroPay',
          'charged_amount' => 0,
        ]
      );
    }

    Subscriptions::sendEmailAndNotify(auth()->user()->name, $creator->id);
    $this->sendWelcomeMessageAction($creator, auth()->id());

    return response()->json([
      'success' => true,
      'url' => url('buy/subscription/success', $creator->username)
    ]);
  }

  protected function buildCheckoutContext(User $creator, Plans $plan): array
  {
    $originalAmount = round((float) $plan->price, 2);
    $grossAmount = (float) Helper::amountGross($originalAmount);
    $taxAmount = round(max($grossAmount - $originalAmount, 0), 2);
    $taxes = auth()->user()->taxesPayable();

    $pricing = [
      'original_amount' => $originalAmount,
      'discount_amount' => 0.00,
      'charged_amount' => $originalAmount,
      'tax_amount' => $taxAmount,
    ];

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
      $pricing = array_merge($pricing, $result['pricing']);
    }

    return [
      'success' => true,
      'creator' => $creator,
      'plan' => $plan,
      'promo_code' => $promoCode,
      'pricing' => $pricing,
      'taxes' => $taxes,
    ];
  }

  protected function promoValidationMessage(string $reason): string
  {
    switch ($reason) {
      case 'disabled':
        return __('general.promo_code_disabled');
      case 'expired':
        return __('general.promo_code_expired');
      case 'limit_total_reached':
        return __('general.promo_code_limit_total_reached');
      case 'limit_per_user_reached':
        return __('general.promo_code_limit_per_user_reached');
      case 'invalid':
      default:
        return __('general.promo_code_invalid');
    }
  }

  protected function subscriptionButtonLabel(string $interval, float $amount, bool $isFreeCheckout): string
  {
    if ($isFreeCheckout) {
      return __('general.subscribe_for_free');
    }

    $translationKey = $interval === 'monthly'
      ? 'general.subscribe_month'
      : 'general.subscribe_' . $interval;

    return __($translationKey, ['price' => Helper::formatPrice($amount, true)]);
  }

  protected function subscriptionRenewalText(string $interval, float $amount): string
  {
    $intervalLabel = [
      'weekly' => __('general.subscription_interval_unit_week'),
      'monthly' => __('general.subscription_interval_unit_month'),
      'quarterly' => __('general.subscription_interval_unit_quarterly'),
      'biannually' => __('general.subscription_interval_unit_biannually'),
      'yearly' => __('general.subscription_interval_unit_year'),
    ][$interval] ?? $interval;

    return __('general.subscription_renews_at', [
      'price' => Helper::formatPrice($amount, true),
      'interval' => $intervalLabel,
    ]);
  }

}
