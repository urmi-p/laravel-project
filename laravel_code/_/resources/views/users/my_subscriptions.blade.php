@extends('layouts.app')

@section('title')
    {{ __('users.my_subscriptions') }} -
@endsection

@section('css')
    <style type="text/css">
        .my_subscription_card {
            background-color: #1e1e1e !important;
            color: #fff !important;
            border: 1px solid #333 !important;
            border-radius: 8px !important;
            padding: 20px !important;
            margin-bottom: 20px !important;
        }

        .my_subscription_card_header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }

        .my_subscription_card_header strong {
            font-size: 16px;
            font-weight: 600;
        }

        .my_subscription_card_content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            row-gap: 12px;
        }

        .my_subscription_card_content .label {
            color: #FFFFFF;
            font-size: 14px;
            font-weight:500;
            opacity: unset;
        }

        .my_subscription_card_content .value {
            text-align: right;
            color: #fff;
            font-size: 14px;
            font-weight:300;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            justify-content: flex-end;
        }

        [data-bs-theme="light"] .my_subscription_card {
            background-color: #fff !important;
            border: 1px solid #e2e2e2 !important;
            color: #111 !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        [data-bs-theme="light"] .my_subscription_card_content .label {
            color: #666 !important;
        }

        [data-bs-theme="light"] .my_subscription_card_content .value {
            color: #111 !important;
        }

        [data-bs-theme="light"] .my_subscription_card_header {
            border-bottom: 1px solid #eee !important;
        }

        .my_subscription_card a {
            color: #fff !important;
            text-decoration: none !important;
        }

        [data-bs-theme="light"] .my_subscription_card a {
            color: #111 !important;
        }

        .my_subscription_card a:hover {
            opacity: 0.8;
        }

        .theme-subtitle {
            color: #fff;
        }

        [data-bs-theme="light"] .theme-subtitle {
            color: #444 !important;
        }
    </style>
@endsection

@section('content')
    <section class="section section-sm">
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2 px-lg-5">

            <div class="row">
                <div class="col-lg-3 col-md-2 side_bar_box_shadow">
                    @include('includes.menu-sidebar-home')
                </div>
                {{-- @include('includes.cards-settings') --}}

                <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-lg-8">
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">{{ __('users.my_subscriptions') }}</h2>
                            <p class="lead mt-0 font_weight_400 fs-14 theme-subtitle">{{ __('users.my_subscriptions_subtitle') }}</p>
                        </div>
                    </div>
                    {{-- The dummy cards were removed from here --}}
                    <!-- dummy sub end -->
                    @if ($subscriptions->count() != 0)
                        @if (session('message'))
                            <div class="alert alert-success mb-3">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                                </button>
                                <i class="fa fa-check mr-1"></i> {{ session('message') }}
                            </div>
                        @endif

                        @if (session('error_message'))
                            <div class="alert alert-danger mb-3">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                                </button>
                                <i class="fa fa-check mr-1"></i> {{ session('error_message') }}
                            </div>
                        @endif

                        
                        
                        <div class="subscriptions-cards">
                            @foreach ($subscriptions as $subscription)
                                <div class="my_subscription_card">
                                    <div class="my_subscription_card_header">
                                        <strong>Subscriptions Detail</strong>
                                        {{-- Using Close Only as placeholder text for consistency with your dummy design --}}
                                        <span style="color: #aaa; cursor: pointer; font-size: 13px;"></span>
                                    </div>

                                    <div class="my_subscription_card_content">
                                        <div class="label">{{ __('users.subscribed') }}</div>
                                        <div class="value">
                                            @if (!isset($subscription->creator->username))
                                                {{ __('general.no_available') }}
                                            @else
                                                <a href="{{ url($subscription->creator->username) }}">
                                                    <img src="{{ Helper::getFile(config('path.avatar') . $subscription->creator->avatar) }}"
                                                        width="24" height="24" class="rounded-circle mr-1">
                                                    {{ $subscription->creator->hide_name == 'yes' ? $subscription->creator->username : $subscription->creator->name }}
                                                </a>
                                            @endif
                                        </div>

                                        <div class="label">{{ __('admin.date') }}</div>
                                        <div class="value">{{ Helper::formatDate($subscription->created_at) }}</div>

                                        <div class="label">{{ __('general.interval') }}</div>
                                        <div class="value">
                                            {{ $subscription->free == 'yes' ? __('general.not_applicable') : __('general.' . $subscription->interval) }}
                                        </div>

                                        <div class="label">{{ __('admin.ends_at') }}</div>
                                        <div class="value">
                                            @if ($subscription->ends_at)
                                                {{ Helper::formatDate($subscription->ends_at) }}
                                            @elseif ($subscription->free == 'yes')
                                                {{ __('general.free_subscription') }}
                                            @elseif ($subscription->stripe_id != '' && !$subscription->ends_at && $subscription->stripe_status != 'incomplete')
                                                {{ Helper::formatDate(auth()->user()->subscription('main', $subscription->stripe_price)->asStripeSubscription()->current_period_end, true) }}
                                            @else
                                                {{ __('general.no_available') }}
                                            @endif
                                        </div>

                                        <div class="label">{{ __('admin.status') }}:</div>
                                        <div class="value">
                                            @if (
                                                ($subscription->stripe_id == '' &&
                                                    strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s')) &&
                                                    $subscription->cancelled == 'no') ||
                                                    ($subscription->stripe_id != '' && $subscription->stripe_status == 'active') ||
                                                    ($subscription->stripe_id == '' && $subscription->free == 'yes'))
                                                <div class="status-wrapper text-success">
                                                    <span class="status-dot bg-success"></span>
                                                    {{ __('general.active') }}
                                                </div>
                                            @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
                                                <div class="status-wrapper text-warning">
                                                    <span class="status-dot bg-warning"></span>
                                                    {{ __('general.incomplete') }}
                                                </div>
                                                <div class="mt-1">
                                                    <a class="badge badge-pill badge-success text-uppercase"
                                                        href="{{ route('cashier.payment', $subscription->last_payment) }}">
                                                        {{ __('general.confirm_payment') }}
                                                    </a>
                                                </div>
                                            @else
                                                <div class="status-wrapper text-danger">
                                                    <span class="status-dot bg-danger"></span>
                                                    {{ __('general.cancelled') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- card -->

                        @if ($subscriptions->hasPages())
                            {{ $subscriptions->links() }}
                        @endif
                    @else
                        <div class="text-center main-no-updates">
                            <div class="sub-no-updates">
                                <span class="btn-block mb-3">
                                    <i class="feather icon-user-check ico-no-result bg_black"></i>
                                </span>
                                <h4 class="font_weight_400 font_size_18 text_color_white">{{ __('users.not_subscribed') }} <a
                                        href="{{ url('creators') }}"
                                       class="text_color_white text_decor_underline" >{{ __('general.explore_creators') }}</a>
                                </h4>

                            </div>
                        </div>
                    @endif

                </div><!-- end col-md-6 -->

            </div>
        </div>
    </section>
@endsection