<?php

namespace App\Providers;

use App\Helper;
use App\Models\Gift;
use App\Models\Reel;
use App\Models\Blogs;
use App\Models\Reports;
use App\Models\Updates;
use App\Models\Deposits;
use App\Models\TaxRates;
use App\Models\Languages;
use App\Models\Categories;
use App\Models\Advertising;
use App\Models\Withdrawals;
use App\Models\AdminSettings;
use App\Models\LiveStreamings;
use App\Models\PaymentGateways;
use App\Models\VerificationRequests;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot()
	{
		try {
            \DB::connection()->getPdo();
        } catch (\Exception $e) {
			return false;
        }

		try {
			// Admin Settings
			$settings = AdminSettings::first();

			// Updates pending count on Panel Admin
			$updatesPendingCount = Schema::hasTable('updates')
				? Updates::selectRaw('COUNT(id) as total')->whereStatus('pending')->pluck('total')->first()
				: 0;

			// Deposits pending count on Panel Admin
			$depositsPendingCount = Schema::hasTable('deposits')
				? Deposits::selectRaw('COUNT(id) as total')->whereStatus('pending')->pluck('total')->first()
				: 0;

			// Reports on Panel Admin
			$reports = Schema::hasTable('reports')
				? Reports::selectRaw('COUNT(id) as total')->pluck('total')->first()
				: 0;

			// Withdrawals pending count on Panel Admin
			$withdrawalsPendingCount = Schema::hasTable('withdrawals')
				? Withdrawals::selectRaw('COUNT(id) as total')->whereStatus('pending')->pluck('total')->first()
				: 0;

			// Verification Requests count on Panel Admin
			$verificationRequestsCount = Schema::hasTable('verification_requests')
				? VerificationRequests::selectRaw('COUNT(id) as total')->whereStatus('pending')->pluck('total')->first()
				: 0;

			// Payment Gateways
			$paymentsGateways = Schema::hasTable('payment_gateways') ? PaymentGateways::all() : collect();

			// Payment Gateways Subscription, Tips, PPV
			$paymentGatewaysSubscription = Schema::hasTable('payment_gateways')
				? PaymentGateways::where('enabled', '1')->whereSubscription('yes')->get()
				: collect();

			// Blogs Count
			$blogsCount = Schema::hasTable('blogs') ? Blogs::count() : 0;

			// Categories Count
			$categoriesCount = Schema::hasTable('categories') ? Categories::count() : 0;

			// Al categories
			$categoriesFooter = Schema::hasTable('categories')
				? Categories::where('mode', 'on')->orderBy('name')->take(6)->get()
				: collect();

			// Languages
			$languages = Schema::hasTable('languages') ? Languages::orderBy('name')->get() : collect();

			// Tax Rates
			$taxRatesCount = Schema::hasTable('tax_rates') ? TaxRates::whereStatus('1')->count() : 0;

			// Show Section My Cards
			$showSectionMyCards = Helper::showSectionMyCards();

			// Get Current Live
			$getCurrentLiveCreators = Schema::hasTable('live_streamings')
				? LiveStreamings::whereType('normal')
					->where('updated_at', '>', now()->subMinutes(5))
					->whereStatus('0')
					->pluck('user_id')
					->toArray()
				: [];

			// Get Advertising 
			$advertising = Schema::hasTable('advertising')
				? Advertising::where('expired_at', '>', now())
					->whereStatus(1)
					->inRandomOrder()
					->take(1)
					->get()
				: collect();

			// Get Gifts
			$gifts = Schema::hasTable('gifts') ? Gift::whereStatus(true)->orderBy('price', 'asc')->get() : collect();

			// Reels public
			$reelsPublic = Schema::hasTable('reels') ? Reel::whereStatus('active')->whereType('public')->count() : 0;
		} catch (\Exception $e) {
			return false;
		}

		view()->share(
			compact(
				'settings',
				'updatesPendingCount',
				'depositsPendingCount',
				'reports',
				'withdrawalsPendingCount',
				'verificationRequestsCount',
				'paymentsGateways',
				'blogsCount',
				'categoriesCount',
				'categoriesFooter',
				'languages',
				'showSectionMyCards',
				'paymentGatewaysSubscription',
				'taxRatesCount',
				'getCurrentLiveCreators',
				'advertising',
				'gifts',
				'reelsPublic'
			)
		);
	}
}
