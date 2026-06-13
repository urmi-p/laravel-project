<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\User;
use App\Models\Plans;
use Yabacon\Paystack;
use Yabacon\Paystack\Event;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\PromoCodeUsages;
use App\Models\Subscriptions;
use App\Models\PaymentGateways;
use App\Services\PromoCodeService;

class PaystackController extends Controller
{
  use Traits\Functions;

  protected $promoCodeService;

  public function __construct(AdminSettings $settings, Request $request)
  {
    $this->settings = $settings::first();
    $this->request = $request;
    $this->promoCodeService = app(PromoCodeService::class);
  }

  // Card Authorization
  public function cardAuthorization()
  {
    $pystk = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();

    $paystack = new Paystack($pystk->key_secret);

    try {
      $chargeAmount = ['NGN' => '50.00', 'GHS' => '0.10', 'ZAR' => '1', 'USD' => 0.20];

      if (array_key_exists($this->settings->currency_code, $chargeAmount)) {
        $chargeAmount = $chargeAmount[$this->settings->currency_code];
      } else {
        return back()->withErrorMessage(__('general.error_currency'));
      }

      $tranx = $paystack->transaction->initialize([
        'reusable' => true,
        'email' => auth()->user()->email,
        'amount' => $chargeAmount * 100,
        'currency' => $this->settings->currency_code,
        'callback_url' => url('paystack/card/authorization/verify')
      ]);

      // Redirect url
      $urlRedirect = $tranx->data->authorization_url;

      return redirect($urlRedirect);
    } catch (\Exception $e) {
      return back()->withErrorMessage($e->getMessage());
    }
  }

  // Card Authorization Verify
  public function cardAuthorizationVerify()
  {
    $pystk = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();

    if (!$this->request->reference) {
      die('No reference supplied');
    }

    // initiate the Library's Paystack Object
    $paystack = new Paystack($pystk->key_secret);
    try {
      // verify using the library
      $tranx = $paystack->transaction->verify([
        'reference' => $this->request->reference, // unique to transactions
      ]);
    } catch (\Exception $e) {
      die($e->getMessage());
    }

    if ('success' === $tranx->data->status) {

      $user = User::find(auth()->id());
      $user->paystack_authorization_code = $tranx->data->authorization->authorization_code;
      $user->paystack_last4 = $tranx->data->authorization->last4;
      $user->paystack_exp = $tranx->data->authorization->exp_month . '/' . $tranx->data->authorization->exp_year;
      $user->paystack_card_brand = trim($tranx->data->authorization->card_type);
      $user->save();
    }

    return redirect('my/cards')->withSuccessMessage(__('general.success'));
  }



  /**
   * Redirect the User to Paystack Payment Page
   * @return Url
   */
  public function show()
  {
    if (!$this->request->expectsJson()) {
      abort(404);
    }

    if (auth()->user()->paystack_authorization_code == '') {
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
      ->firstOrFail();

    $checkout = $this->buildCheckoutContext($user, $plan);

    if (! $checkout['success']) {
      return response()->json([
        'success' => false,
        'errors' => ['error' => $checkout['error']]
      ]);
    }

    $payment = PaymentGateways::whereName('Paystack')
      ->whereEnabled(1)
      ->firstOrFail();

    try {
      // initiate the Library's Paystack Object
      $paystack = new Paystack($payment->key_secret);
      $pricing = $checkout['pricing'];
      $promoCode = $checkout['promo_code'];

      //========== Create Plan if no exists
      $planCode = $this->resolvePaystackPlan($paystack, $user, $plan);

      if ($promoCode) {
        $totalDue = round((float) $pricing['charged_amount'] + (float) $pricing['tax_amount'], 2);
        $usage = $this->promoCodeService->createUsage($promoCode, [
          'user_id' => auth()->id(),
          'plan_id' => $plan->id,
          'plan_interval' => $plan->interval,
          'gateway_name' => 'Paystack',
          'original_amount' => $pricing['original_amount'],
          'discount_amount' => $pricing['discount_amount'],
          'charged_amount' => $pricing['charged_amount'],
          'creator_earning_impact' => $pricing['discount_amount'],
          'tax_amount' => $pricing['tax_amount'],
          'checkout_token' => str_random(40),
          'gateway_reference' => 'pstkpromo_' . str_random(25),
        ]);

        if ($totalDue <= 0) {
          $subscriptionStartDate = $user->planInterval($plan->interval);
          $gatewaySubscription = $paystack->subscription->create([
            'plan' => $planCode,
            'customer' => auth()->user()->email,
            'authorization' => auth()->user()->paystack_authorization_code,
            'start_date' => $subscriptionStartDate->toIso8601String(),
          ]);

          $subscription = Subscriptions::firstOrNew([
            'subscription_id' => $gatewaySubscription->data->subscription_code,
          ]);

          $subscription->user_id = auth()->id();
          $subscription->creator_id = $user->id;
          $subscription->stripe_price = $plan->name;
          $subscription->subscription_id = $gatewaySubscription->data->subscription_code;
          $subscription->ends_at = $subscriptionStartDate;
          $subscription->interval = $plan->interval;
          $subscription->save();

          $this->updatePaystackPlanDescription($paystack, $planCode, [
            'user' => auth()->id(),
            'creator' => $user->id,
            'plan' => $plan->name,
            'interval' => $plan->interval,
            'subsId' => $gatewaySubscription->data->subscription_code,
          ]);

          $baseEarnings = $this->earningsAdminUser($user->custom_fee, $pricing['original_amount'], null, null);
          $earnings = $this->promoCodeService->buildNetEarningsSnapshot(0, $baseEarnings, $payment->fee, $payment->fee_cents);

          $txn = $this->transaction(
            $usage->gateway_reference,
            auth()->id(),
            $subscription->id,
            $user->id,
            0,
            $earnings['user'],
            $earnings['admin'],
            'Paystack',
            'subscription',
            $earnings['percentageApplied'],
            null
          );

          $this->promoCodeService->markUsageCompleted($usage, [
            'subscription_id' => $subscription->id,
            'transaction_id' => $txn->id,
            'used_at' => now(),
            'platform_commission_amount' => $earnings['admin'],
          ]);

          $this->promoCodeService->createHistory(
            $usage->promo_code_id,
            auth()->id(),
            'system',
            'used',
            null,
            [
              'subscription_id' => $subscription->id,
              'transaction_id' => $txn->id,
              'gateway_name' => 'Paystack',
              'charged_amount' => 0,
            ]
          );

          Subscriptions::sendEmailAndNotify(auth()->user()->name, $user->id);
          $this->sendWelcomeMessageAction($user, auth()->id());

          return response()->json([
            'success' => true,
            'url' => route('subscription.success', ['user' => $user->username, 'delay' => 'paystack'])
          ]);
        }

        $paystack->transaction->charge([
          'reference' => $usage->gateway_reference,
          'authorization_code' => auth()->user()->paystack_authorization_code,
          'email' => auth()->user()->email,
          'amount' => (int) round($totalDue * 100),
          'metadata' => json_encode([
            'promo_usage_token' => $usage->checkout_token,
            'plan_id' => $plan->id,
            'creator_id' => $user->id,
            'subscriber_id' => auth()->id(),
          ]),
        ]);

        return response()->json([
          'success' => true,
          'url' => route('subscription.success', ['user' => $user->username, 'delay' => 'paystack'])
        ]);
      }

      //========== Create Subscription
      $subscription = $paystack->subscription->create([
        'plan' => $planCode,
        'customer' => auth()->user()->email,
        'start_date' => now(),
        'authorization' => auth()->user()->paystack_authorization_code
      ]);

      $this->updatePaystackPlanDescription($paystack, $planCode, [
        'user' => $this->request->user()->id,
        'creator' => $user->id,
        'plan' => $plan->name,
        'interval' => $plan->interval,
        'subsId' => $subscription->data->subscription_code,
      ]);

      // Send Email to User and Notification
      Subscriptions::sendEmailAndNotify(auth()->user()->name, $user->id);

      $this->sendWelcomeMessageAction($user, auth()->id());
    } catch (\Exception $exception) {
      if (isset($usage) && $usage instanceof PromoCodeUsages) {
        $this->promoCodeService->markUsageFailed($usage, $exception->getMessage());
      }

      return response()->json([
        'success' => false,
        'errors' => ['error' => $exception->getMessage()]
      ]);
    }

    return response()->json([
      'success' => true,
      'url' => route('subscription.success', ['user' => $user->username, 'delay' => 'paystack'])
    ]);
  }

  // PayStack webhooks
  public function webhooks()
  {
    // Get Payment Gateway
    $payment = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();

    // Retrieve the request's body and parse it as JSON
    $event = Event::capture();
    http_response_code(200);

    /* It is a important to log all events received. Add code *
     * here to log the signature and body to db or file       */
    openlog('MyPaystackEvents', LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
    syslog(LOG_INFO, $event->raw);
    closelog();

    /* Verify that the signature matches one of your keys*/
    $my_keys = [
      'live' => $payment->key_secret,
      'test' => $payment->key_secret,
    ];
    $owner = $event->discoverOwner($my_keys);
    if (!$owner) {
      // None of the keys matched the event's signature
      die();
    }

    switch ($event->obj->event) {
        // subscription.create
      case 'subscription.create':

        // Get all data
        $data = $event->obj->data;
        // Amount
        $amount = $data->amount / 100;
        // Subscription ID
        $subscrId = $data->subscription_code;
        // Metadata
        parse_str($data->plan->description ?? null, $metadata);

        if ($metadata) {
          $subscription = Subscriptions::where('subscription_id', $subscrId)->first();

          if (! $subscription) {
            $subscription = new Subscriptions();
            $subscription->user_id = $metadata['user'];
            $subscription->creator_id = $metadata['creator'];
            $subscription->stripe_price = $metadata['plan'];
            $subscription->subscription_id = $subscrId;
            $subscription->ends_at = null;
            $subscription->interval = $metadata['interval'];
            $subscription->save();
          }
        }

        break;

        // charge.success
      case 'charge.success':

        if ('success' !== $event->obj->data->status) {
          return false;
        }

        // Get all data
        $data = $event->obj->data;
        // Amount
        $amount = ($data->amount / 100);
        $promoUsage = PromoCodeUsages::where('gateway_name', 'Paystack')
          ->where('gateway_reference', $data->reference)
          ->where('status', 'pending')
          ->first();

        if ($promoUsage) {
          $subscriber = User::find($promoUsage->user_id);
          $creator = User::whereId($promoUsage->creator_id)->whereVerifiedId('yes')->first();
          $plan = Plans::whereId($promoUsage->plan_id)->first();

          if ($subscriber && $creator && $plan) {
            $planCode = $this->resolvePaystackPlan(new Paystack($payment->key_secret), $creator, $plan);
            $subscriptionStartDate = $creator->planInterval($plan->interval);

            $gatewaySubscription = (new Paystack($payment->key_secret))->subscription->create([
              'plan' => $planCode,
              'customer' => $subscriber->email,
              'authorization' => $subscriber->paystack_authorization_code,
              'start_date' => $subscriptionStartDate->toIso8601String(),
            ]);

            $subscription = Subscriptions::firstOrNew([
              'subscription_id' => $gatewaySubscription->data->subscription_code,
            ]);

            $subscription->user_id = $subscriber->id;
            $subscription->creator_id = $creator->id;
            $subscription->stripe_price = $plan->name;
            $subscription->subscription_id = $gatewaySubscription->data->subscription_code;
            $subscription->ends_at = $subscriptionStartDate;
            $subscription->interval = $plan->interval;
            $subscription->save();

            $this->updatePaystackPlanDescription(new Paystack($payment->key_secret), $planCode, [
              'user' => $subscriber->id,
              'creator' => $creator->id,
              'plan' => $plan->name,
              'interval' => $plan->interval,
              'subsId' => $gatewaySubscription->data->subscription_code,
            ]);

            $baseEarnings = $this->earningsAdminUser($creator->custom_fee, $promoUsage->original_amount, null, null);
            $earnings = $this->promoCodeService->buildNetEarningsSnapshot($promoUsage->charged_amount, $baseEarnings, $payment->fee, $payment->fee_cents);

            $txn = $this->transaction(
              $data->reference,
              $subscriber->id,
              $subscription->id,
              $creator->id,
              $promoUsage->charged_amount,
              $earnings['user'],
              $earnings['admin'],
              'Paystack',
              'subscription',
              $earnings['percentageApplied'],
              null
            );

            $creator->increment('balance', $txn->earning_net_user);

            $this->promoCodeService->markUsageCompleted($promoUsage, [
              'subscription_id' => $subscription->id,
              'transaction_id' => $txn->id,
              'platform_commission_amount' => $earnings['admin'],
              'used_at' => now(),
            ]);

            $this->promoCodeService->createHistory(
              $promoUsage->promo_code_id,
              $subscriber->id,
              'system',
              'used',
              null,
              [
                'subscription_id' => $subscription->id,
                'transaction_id' => $txn->id,
                'gateway_name' => 'Paystack',
                'charged_amount' => $promoUsage->charged_amount,
              ]
            );

            Subscriptions::sendEmailAndNotify($subscriber->name, $creator->id);
            $this->sendWelcomeMessageAction($creator, $subscriber->id);
          }

          break;
        }

        // Metadata
        parse_str($data->plan->description ?? null, $metadata);

        //======== Renew subscription
        if (get_object_vars($data->plan)) {
          // Transaction reference
          $txnId = $data->reference;

          // Get subscription
          $subscription = Subscriptions::where('subscription_id', $metadata['subsId'])->firstOrFail();

          // User Plan
          $plan = Plans::whereName($subscription->stripe_price)->firstOrFail();

          // Admin and user earnings calculation
          $earnings = $this->earningsAdminUser($plan->user()->custom_fee, $amount, $payment->fee, $payment->fee_cents);

          // Insert Transaction
          $txn = $this->transaction(
            $txnId,
            $subscription->user_id,
            $subscription->id,
            $subscription->creator_id,
            $amount,
            $earnings['user'],
            $earnings['admin'],
            'Paystack',
            'subscription',
            $earnings['percentageApplied'],
            null
          );

          // Add Earnings to User
          $plan->user()->increment('balance', $txn->earning_net_user);

          // Update subscription
          $subscription->ends_at = $plan->user()->planInterval($plan->interval);
          $subscription->save();

          // Notify to user - destination, author, type, target
          Notifications::send($txn->subscribed, $txn->user_id, 12, $txn->user_id);
        }

        break;

      // invoice.payment_failed
      case 'invoice.payment_failed':

        // Get all data
        $data = $event->obj->data;
        // Subscription ID
        $subscrId = $data->subscription->subscription_code;

        // Update subscription
        $subscription = Subscriptions::where('subscription_id', $subscrId)->firstOrFail();
        $subscription->cancelled = 'yes';
        $subscription->save();

        break;

        // subscription.not_renew
      case 'subscription.not_renew':

        // Get all data
        $data = $event->obj->data;
        // Subscription ID
        $subscrId = $data->subscription_code;

        // Update subscription
        $subscription = Subscriptions::where('subscription_id', $subscrId)->firstOrFail();
        $subscription->cancelled = 'yes';
        $subscription->save();

        break;
    }
  }

  protected function resolvePaystackPlan(Paystack $paystack, User $creator, Plans $plan): string
  {
    if (!$plan->paystack) {
      $userPlan = $paystack->plan->create([
        'name' => __('general.subscription_for') . ' @' . $creator->username,
        'amount' => ($plan->price * 100),
        'interval' => $this->paystackInterval($plan->interval),
        'currency' => $this->settings->currency_code,
        'description' => http_build_query([
          'user' => auth()->id(),
          'creator' => $creator->id,
          'plan' => $plan->name,
          'interval' => $plan->interval,
        ])
      ]);

      $plan->paystack = $userPlan->data->plan_code;
      $plan->save();

      return $plan->paystack;
    }

    $planCode = $plan->paystack;
    $planCurrent = $paystack->plan->fetch(['id' => $planCode]);
    $pricePlanOnPaystack = ($planCurrent->data->amount / 100);

    if ($pricePlanOnPaystack != $plan->price) {
      $paystack->plan->update([
        'name' => __('general.subscription_for') . ' @' . $creator->username,
        'amount' => ($plan->price * 100),
      ], ['id' => $planCode]);
    }

    return $planCode;
  }

  protected function updatePaystackPlanDescription(Paystack $paystack, string $planCode, array $description): void
  {
    $paystack->plan->update([
      'description' => http_build_query($description),
    ], [
      'id' => $planCode
    ]);
  }

  protected function paystackInterval(string $interval): string
  {
    switch ($interval) {
      case 'weekly':
        return 'weekly';
      case 'monthly':
        return 'monthly';
      case 'quarterly':
        return 'quarterly';
      case 'biannually':
        return 'biannually';
      case 'yearly':
        return 'annually';
      default:
        return 'monthly';
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

  public function cancelSubscription($id)
  {
    $payment = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();

    try {
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/subscription/" . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
          "Authorization: Bearer " . $payment->key_secret,
          "Cache-Control: no-cache",
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);

      if ($err) {
        throw new \Exception("cURL Error #:" . $err);
      } else {
        $result = json_decode($response);
      }

      // initiate the Library's Paystack Object
      $paystack = new Paystack($payment->key_secret);

      $paystack->subscription->disable([
        'code' => $id,
        'token' => $result->data->email_token
      ]);
    } catch (\Exception $e) {
      session()->put('subscription_cancel', $e->getMessage());

      return back();
    }

    session()->put('subscription_cancel', __('general.subscription_cancel'));

    \Artisan::call('cache:clear');

    return back();
  }

  public function deletePaymentCard()
  {
    $payment = PaymentGateways::whereName('Paystack')->whereEnabled(1)->firstOrFail();

    $url = "https://api.paystack.co/customer/deactivate_authorization";
    $fields = [
      "authorization_code" => auth()->user()->paystack_authorization_code
    ];
    $fields_string = http_build_query($fields);
    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Authorization: Bearer " . $payment->key_secret,
      "Cache-Control: no-cache",
    ));

    //So that curl_exec returns the contents of the cURL; rather than echoing it
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    $result = json_decode($response);

    if ($err) {
      throw new \Exception("cURL Error #:" . $err);
    } else {
      if ($result->status) {

        $user = User::find(auth()->id());
        $user->paystack_authorization_code = '';
        $user->paystack_last4 = '';
        $user->paystack_exp = '';
        $user->paystack_card_brand = '';
        $user->save();

        return redirect('my/cards')->withSuccessRemoved(__('general.successfully_removed'));
      } else {
        return back()->withErrorMessage($result->message);
      }
    }
  }
}
