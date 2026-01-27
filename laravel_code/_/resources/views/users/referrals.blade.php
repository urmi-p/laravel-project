@extends('layouts.app')

@section('title') {{__('general.referrals')}} -@endsection

@section('content')
<style>
	.transactions-container {
		background:
			radial-gradient(90% 140% at 50% -30%,
				rgba(255, 255, 255, 0.12),
				transparent 60%),
			#000000;
		border-radius: 14px;
		padding: 18px 20px 10px;
		width: 100%;
	}

	/* HEADER */
	.transactions-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 14px;
	}

	.transactions-header .title {
		color: #ffffff;
		font-size: 15px;
		font-weight: 500;
	}

	/* VIEW ALL BUTTON */
	.view-all {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		background: #ffffff;
		color: #000000 !important;
		font-size: 12px;
		font-weight: 500;
		padding: 6px 12px;
		border-radius: 999px;
		text-decoration: none;
		line-height: 1;
	}

	[data-bs-theme="light"] .view-all  {
		border: 1px solid #6c757d;
	}

	/* TABLE WRAPPER */
	.transactions-table-wrapper {
		width: 100%;
	}

	/* TABLE */
	.transactions-table {
		width: 100%;
		border-collapse: collapse;
	}

	/* TABLE HEADER */
	.transactions-table thead th {
		color: #9ca3af;
		font-size: 11px;
		font-weight: 500;
		padding: 10px 0;
		text-align: left;
		border-bottom: 1px solid rgba(255, 255, 255, 0.12);
	}

	/* TABLE BODY ROWS */
	.transactions-table tbody tr {
		border-bottom: 1px solid rgba(255, 255, 255, 0.12);
	}

	.transactions-table tbody tr:last-child {
		border-bottom: none;
	}

	/* TABLE CELLS */
	.transactions-table td {
		color: #d1d5db;
		font-size: 13px;
		padding: 14px 0;
	}

	/* RIGHT ALIGN */
	.text-right {
		text-align: right;
		color: #a1a1aa;
	}

	/* EMPTY STATE */
	.transactions-table .empty {
		text-align: center;
		padding: 24px 0;
		color: #6b7280;
		font-size: 13px;
	}
</style>
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
	<div class="container-fluid pt-lg-5 pt-2">
		<div class="row mb-sm">
			@include('includes.cards-settings')
			<div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
				<h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">
					{{__('general.referrals')}}
				</h2>

				@if ($settings->referral_system == 'on')
				<p class="lead mt-1 font_weight_400 fs-14">
					{{__('general.referrals_welcome_desc', ['percentage' => auth()->user()->custom_profit_referral ?: $settings->percentage_referred])}}
				</p>

				@else
				<div class="alert alert-danger mt-3">
					<span class="alert-inner--text">
						<i class="fa fa-exclamation-triangle mr-1"></i> {{ __('general.referral_system_disabled') }}
					</span>
				</div>
				@endif
				
				<div class="row">
					<div class="col-lg-3 mb-2">
						<div class="ref-card">
							<div class="ref-card-body current_balance">
								<span class="small-text mb-2">{{ __('general.current_balance') }}</span>
								<h5 class="my-2 py-2">
									{{Helper::amountFormatDecimal(auth()->user()->balance)}}
								</h5>
								<!-- <small>{{ __('general.balance') }}</small> -->
								@if (auth()->user()->balance >= $settings->amount_min_withdrawal)
								<a href="{{ url('settings/withdrawals')}}" class="link-border color-link"> {{ __('general.make_withdrawal') }}</a>

								@else
								<a href="javascript:;" class="color-link text-muted" data-toggle="tooltip" title="{{__('general.amount_min_withdrawal')}} {{Helper::amountWithoutFormat($settings->amount_min_withdrawal)}} {{$settings->currency_code}}">
									{{ __('general.make_withdrawal') }}
								</a>
								@endif
							</div>
						</div>
					</div><!-- card 1 -->

					<div class="col-lg-3 mb-2">
						<div class="ref-card">
							<div class="ref-card-body">
								<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="22" cy="22" r="22" fill="#FFEDE1" />
									<circle cx="22" cy="22" r="12" fill="#FFBF9A" />
									<circle cx="22" cy="19" r="2" stroke="#D96E30" stroke-width="0.75" />
									<ellipse cx="22" cy="24.5" rx="3.5" ry="2" stroke="#D96E30" stroke-width="0.75" />
								</svg>
								<div class="mt-2 d-flex flex-column gap-4">
									<span class="mt-2">{{ __('general.total_registered_users') }}</span>
									<h5 class="mt-2">
										{{ number_format(auth()->user()->referrals()->count()) }}
									</h5>
								</div>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-3 mb-2">
						<div class="ref-card">
							<div class="ref-card-body">
								<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="22" cy="22" r="22" fill="#EBFFE8" />
									<circle cx="22" cy="22" r="12" fill="#A3ECA7" />
									<circle cx="22" cy="19.4546" r="2" stroke="#17971E" stroke-width="0.75" />
									<ellipse cx="22" cy="24.9546" rx="3.5" ry="2" stroke="#17971E" stroke-width="0.75" />
								</svg>
								<div class="mt-2 d-flex flex-column gap-4">
									<span class="mt-2">{{ __('general.total_transactions') }}</span>
									<h5 class="mt-2">{{ number_format(auth()->user()->referralTransactions()->count()) }}</h5>
								</div>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->

					<div class="col-lg-3 mb-2">
						<div class="ref-card">
							<div class="ref-card-body">
								<svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="22" cy="22" r="22" fill="#FEE4E2" />
									<circle cx="22" cy="22" r="12" fill="#FDA29B" />
									<circle cx="22" cy="19" r="2" stroke="#D92D20" stroke-width="0.75" />
									<ellipse cx="22" cy="24.5" rx="3.5" ry="2" stroke="#D92D20" stroke-width="0.75" />
								</svg>
								<div class="mt-2 d-flex flex-column gap-4">
									<span class="mt-2">{{ __('general.earnings_total') }}</span>
									<h5 class="mt-2"> {{ Helper::amountFormatDecimal(auth()->user()->referralTransactions()->sum('earnings')) }}</h5>
								</div>
							</div>
						</div><!-- card 1 -->
					</div><!-- col-lg-4 -->
				</div><!-- col-lg-4 -->
				<div class="ref-card mt-3">
					<div class="">
						<div class="d-flex justify-content-between v-div">
							<h4>
								{{ __('admin.transactions') }}
							</h4>
							<a href="#" class="view-all" style=" display:flex; align-items:center; gap:6px; font-size:12px; white-space:nowrap; ">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none"
									xmlns="http://www.w3.org/2000/svg">
									<path d="M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z"
										stroke="#475467" stroke-width="1.5" />
									<path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
										stroke="#475467" stroke-width="1.5" />
								</svg>
								View All
							</a>
						</div>

						<div class="table-responsive">
							<table class="table table-striped m-0">
								<thead>
									<tr>
										<th scope="col">{{__('admin.type')}}</th>
										<th scope="col">{{__('admin.date')}}</th>
										<th scope="col">{{__('general.earnings')}}</th>
									</tr>
								</thead>

								<tbody>
									@if ($transactions->count() != 0)
									@foreach ($transactions as $referred)
									<tr>
										<td>{{ __('general.'.$referred->type) }}</td>
										<td>{{ Helper::formatDate($referred->created_at) }}</td>
										<td>{{ Helper::amountFormatDecimal($referred->earnings) }}</td>
									</tr>
									@endforeach

									@else
									<tr>
										<td colspan="12" class="text-center">{{ __('general.no_transactions_yet') }}</td>
									</tr>
									@endif
								</tbody>
							</table>
						</div>
					</div>
				</div><!-- card -->

				@if ($transactions->hasPages())
				{{ $transactions->links() }}
				@endif
			</div>
		</div>
	
	</div>
	</div>
	</div>
</section>
@endsection