@extends('layouts.app')

@section('title')
    {{ trans('general.subscription_price') }} -
@endsection

@section('css')
    <style type="text/css">
        .btn-save-custom {
            background-color: #fff !important;
            color: #000 !important;
            border-radius: 12px !important;
            border: none !important;
            padding: 12px 35px !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: auto !important;
            line-height: normal !important;
        }

        .btn-save-custom:hover {
            background-color: #f8f8f8 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            color: #000 !important;
        }

        .subscription-card {
            position: relative;
            background: #1a1a1a;
            border: 1px solid #333;
            transition: background 0.3s ease, border 0.3s ease;
        }
        
        [data-bs-theme="light"] .subscription-card {
            background: #fff !important;
            border: 1px solid #e2e2e2 !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .subscription-card-label {
            color: #fff;
        }

        [data-bs-theme="light"] .subscription-card-label {
            color: #111 !important;
        }

        .theme-subtitle {
            color: #fff;
        }

        [data-bs-theme="light"] .theme-subtitle {
            color: #444 !important;
        }

        .status-dot-pos {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
@endsection

@section('content')
    <section class="section section-sm">
        <div class="container-fluid pt-lg-5 pt-2">
           
            <div class="row">

                @include('includes.cards-settings')

                <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-lg-8 py-5">
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3"><i class="bi bi-cash-stack mr-2"></i>
                                {{ trans('general.subscription_price') }}</h2>
                            <p class="lead text-muted mt-0 fs-14 font_weight_400 theme-subtitle">{{ trans('general.info_subscription') }}</p>
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

                        <div class="form-group">

                            <div class="subscription_card_main_div">
                                <div class="subscription-card">
                                    <span class="status-dot-pos {{ auth()->user()->getPlan('weekly', 'status') ? 'bg-success' : 'bg-danger' }}"></span>
                                    <label class="font_weight_600 fs-30 subscription-card-label">{{ trans('general.subscription_price_week') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                        </div>
                                        <input class="form-control form-control-lg isNumber subscriptionPrice"
                                            @if (auth()->user()->verified_id == 'no' ||
                                                    auth()->user()->verified_id == 'reject' ||
                                                    auth()->user()->free_subscription == 'yes') disabled @endif name="price_weekly"
                                            placeholder="0.00"
                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan('weekly', 'price')) : auth()->user()->getPlan('weekly', 'price') }}"
                                            type="text">
                                        @error('price_weekly')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="status_weekly"
                                            value="1" @if (auth()->user()->getPlan('weekly', 'status')) checked @endif
                                            id="customSwitchWeekly">
                                        <label class="custom-control-label switch"
                                            for="customSwitchWeekly">{{ trans('general.status') }}</label>
                                    </div>
                                </div>
                                <div class="subscription-card">
                                    <span class="status-dot-pos {{ auth()->user()->getPlan('monthly', 'status') ? 'bg-success' : 'bg-danger' }}"></span>
                                    <label class="font_weight_600 fs-30 subscription-card-label">{{ trans('general.subscription_price_month') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                        </div>
                                        <input class="form-control form-control-lg isNumber subscriptionPrice"
                                            @if (auth()->user()->verified_id == 'no' ||
                                                    auth()->user()->verified_id == 'reject' ||
                                                    auth()->user()->free_subscription == 'yes') disabled @endif name="price"
                                            placeholder="0.00"
                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan('monthly', 'price')) : auth()->user()->getPlan('monthly', 'price') }}"
                                            type="text">
                                        @error('price')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="subscription_card_main_div">
                                <div class="subscription-card">
                                    <span class="status-dot-pos {{ auth()->user()->getPlan('quarterly', 'status') ? 'bg-success' : 'bg-danger' }}"></span>
                                    <label
                                        class="font_weight_600 fs-30 subscription-card-label">{{ trans('general.subscription_price_quarter') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                        </div>
                                        <input class="form-control form-control-lg isNumber subscriptionPrice"
                                            @if (auth()->user()->verified_id == 'no' ||
                                                    auth()->user()->verified_id == 'reject' ||
                                                    auth()->user()->free_subscription == 'yes') disabled @endif name="price_quarterly"
                                            placeholder="0.00"
                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan('quarterly', 'price')) : auth()->user()->getPlan('quarterly', 'price') }}"
                                            type="text">
                                        @error('price_quarterly')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="status_quarterly"
                                            value="1" @if (auth()->user()->getPlan('quarterly', 'status')) checked @endif
                                            id="customSwitchQuarterly">
                                        <label class="custom-control-label switch"
                                            for="customSwitchQuarterly">{{ trans('general.status') }}</label>
                                    </div>
                                </div>
                                <div class="subscription-card">
                                    <span class="status-dot-pos {{ auth()->user()->getPlan('biannually', 'status') ? 'bg-success' : 'bg-danger' }}"></span>
                                    <label
                                        class="font_weight_600 fs-30 subscription-card-label">{{ trans('general.subscription_price_biannual') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                        </div>
                                        <input class="form-control form-control-lg isNumber subscriptionPrice"
                                            @if (auth()->user()->verified_id == 'no' ||
                                                    auth()->user()->verified_id == 'reject' ||
                                                    auth()->user()->free_subscription == 'yes') disabled @endif name="price_biannually"
                                            placeholder="0.00"
                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan('biannually', 'price')) : auth()->user()->getPlan('biannually', 'price') }}"
                                            type="text">
                                        @error('price_biannually')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="status_biannually"
                                            value="1" @if (auth()->user()->getPlan('biannually', 'status')) checked @endif
                                            id="customSwitchBiannually">
                                        <label class="custom-control-label switch"
                                            for="customSwitchBiannually">{{ trans('general.status') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="subscription_card_main_div">
                                <div class="subscription-card">
                                    <span class="status-dot-pos {{ auth()->user()->getPlan('yearly', 'status') ? 'bg-success' : 'bg-danger' }}"></span>
                                    <label
                                        class="font_weight_600 fs-30 subscription-card-label">{{ trans('general.subscription_price_year') }}</label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                        </div>
                                        <input class="form-control form-control-lg isNumber subscriptionPrice"
                                            @if (auth()->user()->verified_id == 'no' ||
                                                    auth()->user()->verified_id == 'reject' ||
                                                    auth()->user()->free_subscription == 'yes') disabled @endif name="price_yearly"
                                            placeholder="0.00"
                                            value="{{ $settings->currency_code == 'JPY' ? round(auth()->user()->getPlan('yearly', 'price')) : auth()->user()->getPlan('yearly', 'price') }}"
                                            type="text">
                                        @error('price_yearly')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="status_yearly"
                                            value="1" @if (auth()->user()->getPlan('yearly', 'status')) checked @endif
                                            id="customSwitchYearly">
                                        <label class="custom-control-label switch"
                                            for="customSwitchYearly">{{ trans('general.status') }}</label>
                                    </div>
                                </div>
                                <div></div>
                            </div>
                            <div class="subscription_card_main_div_no_gap">
                                <div class="mb-1 mt-1">
                                    <div class="custom-control custom-switch custom-switch-lg">
                                        <input type="checkbox" class="custom-control-input"
                                            @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif name="free_subscription"
                                            value="yes" @if (auth()->user()->free_subscription == 'yes') checked @endif
                                            id="customSwitchFreeSubscription">
                                        <label class="custom-control-label switch"
                                            for="customSwitchFreeSubscription">{{ trans('general.free_subscription') }}</label>
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
                                <button class="btn btn-1 btn-save-custom mr-3" @if (auth()->user()->verified_id == 'no' || auth()->user()->verified_id == 'reject') disabled @endif
                                    onClick="this.form.submit(); this.disabled=true; this.innerText='{{ trans('general.please_wait') }}';"
                                    type="submit">
                                    {{ trans('general.save_changes') }}
                                </button>
                            </div>
                        </div><!-- End form-group -->


                    </form>
                </div><!-- end col-md-6 -->
            </div>
        </div>
    </section>
@endsection