<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Plans;
use App\Models\PromoCodeUsages;
use App\Models\Subscriptions;
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
          'tax_amount' => $pricing['tax_amount'],
          'checkout_token' => str_random(40),
        ]);

        $couponId = $this->createPromoCoupon($payment->key_secret, $promoUsage->checkout_token, $pricing);
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
      ];

      $subscriptionBuilder = auth()->user()->newSubscription('main', $userPlan)
        ->withMetadata($metadata);

      if ($couponId) {
        $subscriptionBuilder->withCoupon($couponId);
      }

      $subscriptionBuilder->create();

      // Send Email to User and Notification
      Subscriptions::sendEmailAndNotify(auth()->user()->name, $user->id);

      $this->sendWelcomeMessageAction($user, auth()->id());

      sleep(3);

      return response()->json([
        'success' => true,
        'url' => url('buy/subscription/success', $user->username)
      ]);
    } catch (IncompletePayment $exception) {
      // Insert ID Last Payment
      $subscriptions = Subscriptions::whereUserId(auth()->id())
        ->whereStripePrice($userPlan)
        ->whereStripeStatus('incomplete')
        ->first();

      if ($subscriptions) {
        $subscriptions->last_payment = $exception->payment->id;
        $subscriptions->save();
      }

      return response()->json([
        'success' => true,
        'url' => url('stripe/payment', $exception->payment->id), // Redirect customer to page confirmation payment (SCA)
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

  private function createPromoCoupon(string $keySecret, string $checkoutToken, array $pricing): string
  {
    try {
      $stripe = new \Stripe\StripeClient($keySecret);

      $payload = [
        'duration' => 'once',
        'name' => 'Promo ' . substr($checkoutToken, 0, 34),
        'metadata' => [
          'promo_usage_token' => $checkoutToken,
        ],
      ];

      if ($pricing['discount_amount'] <= 0) {
        throw new \Exception('Invalid promo discount amount for Stripe.');
      }

      if (in_array(config('settings.currency_code'), config('currencies.zero-decimal'))) {
        $payload['amount_off'] = (int) round($pricing['discount_amount']);
      } else {
        $payload['amount_off'] = (int) round($pricing['discount_amount'] * 100);
      }

      $payload['currency'] = strtolower(config('settings.currency_code'));

      $coupon = $stripe->coupons->create($payload);

      return $coupon->id;
    } catch (\Exception $e) {
      throw new \Exception($e->getMessage());
    }
  }

  protected function buildCheckoutContext(User $creator, Plans $plan): array
  {
    $originalAmount = round((float) $plan->price, 2);
    $grossAmount = (float) Helper::amountGross($originalAmount);
    $taxAmount = round(max($grossAmount - $originalAmount, 0), 2);

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
}
