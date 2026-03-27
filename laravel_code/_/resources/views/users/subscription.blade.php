@extends('layouts.app')

@section('title')
    {{ __('users.my_subscriptions') }} -
@endsection

@section('content')
<section class="section section-sm subscription-settings-page">
    {{-- for mobile header --}}
    @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
           
            <div class="row">

                @include('includes.cards-settings')
                @if (auth()->user()->verified_id == 'yes')
                <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-12 pt-2 pb-4 pt-lg-4 pb-lg-4">
                            <div class="subscription-settings-header d-flex flex-column gap-3">
                                <h2 class="mb-0 font-montserrat fw-bold subscription-settings-title">{{ __('users.my_subscriptions') }}</h2>
                                <p class="mb-0 fw-normal subscription-settings-subtitle">{{ __('users.my_subscriptions_subtitle') }}</p>
                            </div>
                        </div>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>

                            <i class="far fa-times-circle mr-2"></i> {{ trans('auth.error_desc') }}
                        </div>
                    @endif

                    @if (auth()->user()->verified_id == 'no' && $settings->requests_verify_account == 'on')
                        <div class="alert alert-danger mb-3">
                            <ul class="list-unstyled m-0">
                                <li><i class="fa fa-exclamation-triangle"></i> {{ trans('general.verified_account_info') }}
                                    <a href="{{ url('settings/verify/account') }}"
                                        class="text-white link-border">{{ trans('general.verify_account') }}</a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    @if (auth()->user()->free_subscription == 'no' && auth()->user()->verified_id == 'yes')
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <i class="fa fa-info-circle mr-2"></i>
                            <span>{{ trans('general.user_gain', ['percentage' => auth()->user()->custom_fee == 0 ? 100 - $settings->fee_commission : 100 - auth()->user()->custom_fee]) }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('settings/subscription') }}">

                        @csrf

                        @php
                            $subscriptionPlans = [
                                [
                                    'label' => __('general.subscription_price_week'),
                                    'name' => 'price_weekly',
                                    'status_name' => 'status_weekly',
                                    'status_id' => 'customSwitchWeekly',
                                    'interval' => 'weekly',
                                    'error' => 'price_weekly',
                                ],
                                [
                                    'label' => __('general.subscription_price_month'),
                                    'name' => 'price',
                                    'status_name' => 'status',
                                    'status_id' => 'customSwitchMonthly',
                                    'interval' => 'monthly',
                                    'error' => 'price',
                                ],
                                [
                                    'label' => __('general.subscription_price_quarter'),
                                    'name' => 'price_quarterly',
                                    'status_name' => 'status_quarterly',
                                    'status_id' => 'customSwitchQuarterly',
                                    'interval' => 'quarterly',
                                    'error' => 'price_quarterly',
                                ],
                                [
                                    'label' => __('general.subscription_price_biannual'),
                                    'name' => 'price_biannually',
                                    'status_name' => 'status_biannually',
                                    'status_id' => 'customSwitchBiannually',
                                    'interval' => 'biannually',
                                    'error' => 'price_biannually',
                                ],
                            ];
                        @endphp

                        <div class="form-group">
                            <div class="row g-4 subscription-settings-grid">
                                @foreach ($subscriptionPlans as $plan)
                                    <div class="col-12 col-md-6">
                                        <div class="subscription-plan-card h-100 d-flex flex-column">
                                            <div class="subscription-plan-status">
                                                <div class="subscription-plan-dot {{ auth()->user()->getPlan($plan['interval'], 'status') ? 'is-active' : 'is-inactive' }}"></div>

                                                <div class="subscription-plan-toggle">
                                                    <input
                                                        type="checkbox"
                                                        class="subscription-plan-toggle-input"
                                                        @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif
                                                        name="{{ $plan['status_name'] }}"
                                                        value="1"
                                                        @if (auth()->user()->getPlan($plan['interval'], 'status')) checked @endif
                                                        id="{{ $plan['status_id'] }}">
                                                    <label class="subscription-plan-toggle-label" for="{{ $plan['status_id'] }}" aria-label="{{ __('general.status') }}"></label>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center gap-3 subscription-plan-head">
                                                <h3 class="mb-0 subscription-plan-title">{{ $plan['label'] }}</h3>
                                            </div>

                                            <div class="d-flex flex-column gap-3 subscription-plan-body">
                                                <div class="subscription-plan-input-wrap">
                                                    <div class="input-group input-group-sub subscription-plan-input-group mb-0">
                                                        <div class="input-group-prepend">
                                                            <span class="input-sub-text subscription-plan-currency">{{ $settings->currency_symbol }}</span>
                                                        </div>
                                                        <input
                                                            class="form-control light_mode_form isNumber subscriptionPrice subscription-plan-input"
                                                            @if (auth()->user()->verified_id == 'no' ||
                                                                    auth()->user()->verified_id == 'reject' ||
                                                                    auth()->user()->free_subscription == 'yes') disabled @endif
                                                            name="{{ $plan['name'] }}"
                                                            placeholder="0.00"
                                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan($plan['interval'], 'price')) : auth()->user()->getPlan($plan['interval'], 'price') }}"
                                                            type="text">
                                                    </div>

                                                    @error($plan['error'])
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div>
                                                    <button type="button" class="btn subscription-plan-action">{{ __('general.set_price') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4 subscription-settings-footer">
                                <div class="mb-1 mt-1">
                                    <div class="subscription-free-toggle d-inline-flex align-items-center gap-3">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="free_subscription"
                                            value="yes" @if (auth()->user()->free_subscription == 'yes') checked @endif
                                            id="customSwitchFreeSubscription">
                                        <label class="subscription-plan-toggle-label subscription-plan-toggle-label-free"
                                            for="customSwitchFreeSubscription"></label>
                                        <label class="mb-0 subscription-free-label" for="customSwitchFreeSubscription">{{ trans('general.free_subscription') }}</label>
                                    </div>

                                    @if (auth()->user()->totalSubscriptionsActive() != 0)
                                        @if (auth()->user()->free_subscription == 'yes')
                                            <div class="alert alert-warning display-none mt-3" role="alert"
                                                id="alertDisableFreeSubscriptions">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <span>{{ trans('general.alert_disable_free_subscriptions') }}</span>
                                            </div>
                                        @else
                                            <div class="alert alert-warning display-none mt-3" role="alert"
                                                id="alertDisablePaidSubscriptions">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <span>{{ trans('general.alert_disable_paid_subscriptions') }}</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <button class="btn subscription-save-button" @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif
                                    onClick="this.form.submit(); this.disabled=true; this.innerText='{{ trans('general.please_wait') }}';"
                                    type="submit">
                                    {{ trans('general.save_changes') }}
                                </button>
                            </div>
                        </div><!-- End form-group -->


                    </form>
                </div><!-- end col-md-6 -->
                @endif
            </div>
        </div>
    </section>
@endsection
