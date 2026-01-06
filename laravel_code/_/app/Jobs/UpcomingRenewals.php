<?php

namespace App\Jobs;

use App\Models\Subscriptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\SubscriptionUpcomingRenewals;

class UpcomingRenewals implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Cache::lock('upcoming-renewals', 10)->get(function () {
            $upcomingRenewals = Subscriptions::with('subscriber:id,username,name,email_upcoming_renewals', 'creator:id,username')
                ->whereRaw('HOUR(TIMEDIFF(ends_at,now() )) <= 24')
                ->whereRaw('ends_at < now() + INTERVAL 24 HOUR')
                ->whereCancelled('no')
                ->latest()
                ->whereIn('id', function ($q) {
                    $q->selectRaw('MAX(id) FROM subscriptions GROUP BY creator_id, user_id');
                })
                ->get();

            if ($upcomingRenewals) {
                foreach ($upcomingRenewals as $subscription) {
                    if ($subscription->subscriber->email_upcoming_renewals) {
                        $subscription->subscriber->notify(new SubscriptionUpcomingRenewals($subscription->creator));
                    }
                }
            }
        });
    }
}
