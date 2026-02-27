@extends('layouts.app')

@section('css')
    <style type="text/css">
        .payment-method-container {
            background: #000;
            padding: 20px;
            border-radius: 8px;
            color: #fff;
        }

        .payment-header span {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #bbb;
        }

        .payment-select {
            width: 100%;
            border: 1px solid ;
            background: #1c1c1c;
            padding: 10px;
            color: #aaa;
            border-radius: 6px;
        }

        .payment-footer {
            display: flex;
            justify-content: start;
            gap: 15PX;
            margin-top: 15px;
            
        }
        .btn-back {
            background: #191919;
            padding: 14px 64px;
            color: #FCFCFC;
            border-radius: 12px;
        }

        .btn-transfer {
            background: #E2394C;
            border: none;
            padding: 14px 64px;
            border-radius: 12px;
            color: #FCFCFC;
        }
        [data-bs-theme="light"] .payment-method-container  {
            background: #ffffff !important;
        }
        [data-bs-theme="light"] .payment-select {
            background: #ffffff !important;
        }
        [data-bs-theme="light"] .btn-back {
            background: #ffffff !important;
            color:black;
            border: 1px solid #1e1e1e2e;
        }
        .center_align{
            text-align: center;
        }
        .balance-wrap {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 8px;
        }
        @media (max-width: 480px) {
            .currency_class{
                font-size:18px !important;
                line-height: 2.01 !important;
            }
            .mobile_fund_text{
                font-size:19.51px !important;
            }
            .wallet_inner_text{
                padding-left:28px;
            }
        }
    </style>
@endsection

@section('title')
    {{ __('general.balance') }} -
@endsection

@section('content')
    <section class="section section-sm">
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
            <div class="row">
                @include('includes.cards-settings')
                <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-lg-8">
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">{{ __('general.balance') }}</h2>
                            <p class="lead mt-0 font_weight_400 fs-14 theme-subtitle">
                                {{ __('general.balance_desc') }}</p>
                        </div>
                    </div>

                    {{-- top side wallet with image bg --}}
                    <div class="alert text_color_white alert-custom shadow overflow-hidden position-relative alert_custom w-100 w-lg-auto"
                        role="alert">
                        <div class="inner-wrap" >
                            <span>
                                <h2 class="text_color_white font_weight_700 wallet_inner_text balance-wrap">
                                    <strong>{{ Helper::userWallet() }}</strong>
                                    <small class="h1 currency_class">{{ $settings->wallet_format == 'real_money' ? config('settings.currency_code') : null }}</small>
                                </h2>

                                <span class="w-100 d-block font_weight_400 fs-24 text_color_white mobile_small_fs center_align mobile_fund_text">
                                    Amount minimum withdrawal <p class="font_weight_900 fs-32 d-inline mobile_small_fs">20€ EUR</p>
                                </span>

                                @if ($equivalent_money)
                                    <span>
                                        <strong>{{ $equivalent_money }}</strong>
                                    </span>
                                @endif

                                <span class="w-100 d-block mt-2 center_align mobile_fund_text">
                                    <p class="fs-24 font_weight_400 mobile_small_fs">your payment would be avaliable in 4 business day’s</p>
                                    {{-- @if (auth()->user()->balance != 0.0)
                                        <a href="#" data-toggle="modal" data-target="#modalTransfer"
                                            class="btn btn-1 btn-success mb-2 text-decoration-none">
                                            <i class="bi bi-arrow-left-right mr-2"></i> {{ __('general.transfer_balance') }}
                                        </a>
                                    @endif --}}
                                </span>
                            </span>
                        </div>
                        <span class="icon_wrap"><img src="{{ url('/img/wallet-bg.png') }}" /></span>
                    </div><!-- /alert -->

                    {{-- start form --}}
                    <div class="payment-method-container">
                        <div class="payment-header">
                            <span>Payment Method</span>
                            <div class="input-group-sub">
                                <select class="payment-select" id="paymentSelect">
                                    <option value="">Please select your payment method</option>
                                    @foreach (PaymentGateways::where('enabled', '1')->orderBy('type', 'DESC')->get() as $payment)
                                        <option value="{{ $payment->name }}">
                                            {{ ucfirst($payment->type) }} - {{ $payment->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="payment-footer">
                            <!-- <button class="btn-back">Go Back</button> -->
                            <button class="btn-transfer">Transfer Funds</button>
                        </div>
                    </div>

                    {{-- end form --}}
                </div>
            </div>
        </div>
    </section>
@endsection
