<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plans;
use App\Models\Deposits;
use App\Models\Transactions;
use App\Models\PromoCodeUsages;
use Laravel\Cashier\Cashier;
use App\Models\Notifications;
use Illuminate\Http\Response;
use App\Models\PaymentGateways;
use App\Services\PromoCodeService;
use Laravel\Cashier\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Http\Controllers\WebhookController;

class StripeWebHookController extends WebhookController
{
  use Traits\Functions;

  protected $promoCodeService;

  public function __construct()
  {
    $this->promoCodeService = app(PromoCodeService::class);
  }

  /**
   *
   * customer.subscription.deleted
   *
   * @param array $payload
   * @return Response|\Symfony\Component\HttpFoundation\Response
   */
  public function handleCustomerSubscriptionDeleted(array $payload)
  {
    $user = $this->getUserByStripeId($payload['data']['object']['customer']);
    if ($user) {
      $user->subscriptions->filter(function ($subscription) use ($payload) {
        return $subscription->stripe_id === $payload['data']['object']['id'];
      })->each(function ($subscription) {
        $subscription->markAsCanceled();
      });
    }
    return new Response('Webhook Handled', 200);
  }

  /**
   *
   * WEBHOOK Insert the information of each payment in the Payments table when successfully generating an invoice in Stripe
   *
   * @param array $payload
   * @return Response|\Symfony\Component\HttpFoundation\Response
   */
  public function handleInvoicePaymentSucceeded($payload)
  {
    try {
      $data     = $payload['data'];
      $object   = $data['object'];
      $customer = $object['customer'];
      $user     = $this->getUserByStripeId($customer);
      $subscriptionId = $object['subscription'] ?? $object['lines']['data'][0]['parent']['subscription_item_details']['subscription'] ?? null;
      $metadata = $this->resolveSubscriptionMetadata($object, $subscriptionId);
      $amountPaid = $this->normalizeStripeAmount($object['amount_paid'] ?? $object['subtotal'] ?? 0);
      $interval = $metadata['interval'] ?? 'monthly';
      $creatorId = $metadata['creator_id'] ?? null;
      $taxes    = $metadata['taxes'] ?? null;
      $promoUsageToken = $metadata['promo_usage_token'] ?? null;
      $chargedAmount = (float) ($metadata['charged_amount'] ?? $amountPaid);


      if ($user && $subscriptionId && $creatorId) {
        $subscription = Subscription::whereStripeId($subscriptionId)->first();
        if ($subscription) {

          // Get creator
          $getCreator = Plans::with(['creator:id,status,verified_id,custom_fee,balance'])->whereName($subscription->stripe_price)->first();

          if ($getCreator) {
            $creator = $getCreator->creator;
          } else {
            $creator = User::whereId($creatorId)
            ->select(['id', 'status', 'verified_id', 'custom_fee', 'balance'])
            ->where('status', 'active')
            ->where('verified_id', 'yes')
            ->firstOrFail();
          }

          $subscription->stripe_status = 'active';
          $subscription->creator_id = $creator->id;
          $subscription->interval = $interval;
          $subscription->taxes = $taxes;
          $subscription->save();

          $transaction = Transactions::where('txn_id', $object['id'])->first();
          $platformCommissionAmount = $transaction ? $transaction->earning_net_admin : null;

          if (! $transaction) {
            $earnings = $this->earningsAdminUser($creator->custom_fee, $chargedAmount, null, null);
            $platformCommissionAmount = $earnings['admin'];

            $transaction = $this->transaction(
              $object['id'],
              $subscription->user_id,
              $subscription->id,
              $creator->id,
              $chargedAmount,
              $earnings['user'],
              $earnings['admin'],
              'Stripe',
              'subscription',
              $earnings['percentageApplied'],
              $taxes ?? null
            );

            $creator->increment('balance', $earnings['user']);
          }

          if ($promoUsageToken) {
            $promoUsage = PromoCodeUsages::where('checkout_token', $promoUsageToken)->first();

            if ($promoUsage && $promoUsage->status !== 'completed') {
              $this->promoCodeService->markUsageCompleted($promoUsage, [
                'subscription_id' => $subscription->id,
                'transaction_id' => $transaction->id,
                'gateway_reference' => $object['id'],
                'platform_commission_amount' => $platformCommissionAmount,
              ]);

              $this->promoCodeService->createHistory(
                $promoUsage->promo_code_id,
                $subscription->user_id,
                'system',
                'used',
                null,
                [
                  'subscription_id' => $subscription->id,
                  'transaction_id' => $transaction->id,
                  'gateway_name' => 'Stripe',
                  'charged_amount' => $chargedAmount,
                ]
              );
            }
          }

          // Send Notification to user
          if ($object['billing_reason'] == 'subscription_cycle') {
            // Notify to user - destination, author, type, target
            Notifications::send($creator->id, $subscription->user_id, 12, $subscription->user_id);
          }
        }
        return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);
      }
      return new Response('Webhook Handled but user not found: {handleInvoicePaymentSucceeded}', 200);
    } catch (\Exception $exception) {
      Log::debug($exception->getMessage());
      return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
    }
  }

  /**
   *
   * checkout.session.completed
   *
   * @param array $payload
   * @return Response|\Symfony\Component\HttpFoundation\Response
   */
  public function handleCheckoutSessionCompleted($payload)
  {
    try {
      $data     = $payload['data'];
      $object   = $data['object'];
      $user     = $object['metadata']['user'] ?? null;
      $amount   = $object['metadata']['amount'] ?? null;
      $taxes    = $object['metadata']['taxes'] ?? null;
      $type     = $object['metadata']['type'] ?? null;

      if (! isset($type)) {
        return new Response('Webhook Handled with error: type transaction not defined', 500);
      }

      // Add funds (Deposit)
      if (isset($type) && $type == 'deposit') {
        if ($object['payment_status'] == 'paid' && isset($user)) {
          $amount_total = in_array(config('settings.currency_code'), config('currencies.zero-decimal')) ? $object['amount_total'] : $object['amount_total'] / 100;
          if (isset($amount) && $amount_total >= $amount) {
            // Check transaction
            $verifiedTxnId = Deposits::where('txn_id', $object['payment_intent'])->first();
            if (! $verifiedTxnId) {
              // Insert Deposit
              $this->deposit($user, $object['payment_intent'], $amount, 'Stripe', $taxes);

              // Add Funds to User
              User::find($user)->increment('wallet', $amount);
            }
          }
        }
      }

      return new Response('Webhook Handled: {handleInvoicePaymentSucceeded}', 200);
    } catch (\Exception $exception) {
      Log::debug($exception->getMessage());
      return new Response('Webhook Unhandled: {handleInvoicePaymentSucceeded}', $exception->getCode());
    }
  }

  /**
   *
   * charge.refunded
   *
   * @param array $payload
   * @return Response|\Symfony\Component\HttpFoundation\Response
   */
  public function handleChargeRefunded($payload)
  {
    try {
      $subscriptionId = $payload['data']['object']['subscription'] ?? $payload['lines']['data'][0]['parent']['subscription_item_details']['subscription'] ?? null;
      $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
      $stripe->subscriptions->cancel($subscriptionId, []);

      return new Response('Webhook Handled: {handleChargeRefunded}', 200);
    } catch (\Exception $exception) {
      Log::debug("Exception Webhook {handleChargeRefunded}: " . $exception->getMessage() . ", Line: " . $exception->getLine() . ', File: ' . $exception->getFile());
      return new Response('Webhook Handled with error: {handleChargeRefunded}', 400);
    }
  }

  /**
   * WEBHOOK Manage the SCA by notifying the user by email
   *
   * @param  array  $payload
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function handleInvoicePaymentActionRequired(array $payload)
  {
    $subscriptionId = $payload['data']['object']['subscription'] ?? $payload['lines']['data'][0]['parent']['subscription_item_details']['subscription'] ?? null;
    $subscription = Subscription::whereStripeId($subscriptionId)->first();
    if ($subscription) {
      $subscription->stripe_status = "incomplete";
      $subscription->last_payment = $payload['data']['object']['payment_intent'];
      $subscription->save();
    }

    if (is_null($notification = config('cashier.payment_notification'))) {
      return $this->successMethod();
    }

    if ($user = $this->getUserByStripeId($payload['data']['object']['customer'])) {
      if (in_array(Notifiable::class, class_uses_recursive($user))) {
        $payment = new \Laravel\Cashier\Payment(Cashier::stripe()->paymentIntents->retrieve(
          $payload['data']['object']['payment_intent']
        ));

        $user->notify(new $notification($payment));
      }
    }
    return $this->successMethod();
  }

  protected function resolveSubscriptionMetadata(array $invoice, ?string $subscriptionId): array
  {
    $metadata = [];
    $lines = $invoice['lines']['data'] ?? [];

    foreach ($lines as $line) {
      $lineSubscriptionId = $line['subscription']
        ?? $line['parent']['subscription_item_details']['subscription']
        ?? null;

      if ($subscriptionId && $lineSubscriptionId === $subscriptionId && ! empty($line['metadata'])) {
        $metadata = array_merge($metadata, $line['metadata']);
      }
    }

    if (! empty($invoice['subscription_details']['metadata'] ?? [])) {
      $metadata = array_merge($metadata, $invoice['subscription_details']['metadata']);
    }

    if (! empty($invoice['parent']['subscription_details']['metadata'] ?? [])) {
      $metadata = array_merge($metadata, $invoice['parent']['subscription_details']['metadata']);
    }

    if ($subscriptionId) {
      $stripeSubscription = $this->retrieveStripeSubscription($subscriptionId);

      if ($stripeSubscription && ! empty($stripeSubscription->metadata)) {
        $metadata = array_merge($metadata, $stripeSubscription->metadata->toArray());
      }
    }

    foreach ($lines as $line) {
      if (! empty($metadata['creator_id'])) {
        break;
      }

      if (! empty($line['metadata']['creator_id'])) {
        $metadata = array_merge($metadata, $line['metadata']);
      }
    }

    return $metadata;
  }

  protected function retrieveStripeSubscription(string $subscriptionId): ?\Stripe\Subscription
  {
    try {
      $payment = PaymentGateways::whereName('Stripe')
        ->whereEnabled(1)
        ->where('key_secret', '<>', '')
        ->first();

      if (! $payment) {
        return null;
      }

      return (new \Stripe\StripeClient($payment->key_secret))
        ->subscriptions
        ->retrieve($subscriptionId, []);
    } catch (\Exception $exception) {
      Log::debug('Stripe subscription metadata lookup failed: ' . $exception->getMessage());

      return null;
    }
  }

  protected function normalizeStripeAmount($amount): float
  {
    if (in_array(config('settings.currency_code'), config('currencies.zero-decimal'))) {
      return (float) $amount;
    }

    return round(((float) $amount) / 100, 2);
  }
}
