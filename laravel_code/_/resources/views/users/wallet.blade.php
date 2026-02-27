@extends('layouts.app')

@section('css')
<style type="text/css">
  .payment-card-custom {
    padding: 12px;
    border-radius: 24px;
    margin-bottom: 30px;
    transition: all 0.3s ease;
  }
  
  [data-bs-theme="dark"] .payment-card-custom {
    background-color: #000;
    color: #fff;
  }
  [data-bs-theme="light"] .payment-card-custom {
    color: #111 !important;
    /* border: 1px solid #e9ecef; */
  }

  .payment-label {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    margin-bottom: 12px;
    display: block;
  }

  [data-bs-theme="light"] .payment-label {
    color: #111 !important;
  }

  .amt-input-container {
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    transition: all 0.3s ease;
  }

  [data-bs-theme="light"] .amt-input-container {
    background: #fff !important;
    border: 1px solid #ddd !important;
  }

  .amt-input-container:focus-within {
     border-color: #f1415d;
  }

  .amt-input-container input {
    background: transparent !important;
    border: none !important;
    color: #fff !important;
    font-size: 16px !important;
    box-shadow: none !important;
    width: 100%;
  }

  [data-bs-theme="light"] .amt-input-container input {
    color: #111 !important;
  }

  .amt-input-container input::placeholder {
    color: #333;
  }

  [data-bs-theme="light"] .amt-input-container input::placeholder {
    color: #aaa;
  }

  .amt-helper-text {
    font-size: 12px;
    color: #666;
    margin-bottom: 30px;
    display: block;
  }

  .payment-list-custom {
    margin-top: 20px;
  }

  .payment-item-wrapper {
    position: relative;
    margin-bottom: 0;
  }

  .payment-item-custom {
    display: flex;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid #1a1a1a;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none !important;
    margin-bottom: 0;
  }

  [data-bs-theme="light"] .payment-item-custom {
    border-bottom: 1px solid #eee !important;
  }

  .payment-item-custom:hover {
    background: rgba(255,255,255,0.02);
  }

  [data-bs-theme="light"] .payment-item-custom:hover {
    background: rgba(0,0,0,0.02) !important;
  }

  .payment-icon-wrapper {
    width: 48px;
    height: 48px;
    background: #1a1a1a;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 18px;
    flex-shrink: 0;
  }

  [data-bs-theme="light"] .payment-icon-wrapper {
    background: #eee !important;
  }

  .payment-icon-wrapper i {
    font-size: 22px;
    color: #fff;
  }

  [data-bs-theme="light"] .payment-icon-wrapper i {
    color: #111 !important;
  }

  .payment-icon-wrapper img {
    max-width: 24px;
    max-height: 24px;
    object-fit: contain;
  }

  .payment-info-wrapper {
    flex-grow: 1;
  }

  .payment-name-text {
    display: block;
    color: #fff;
    font-weight: 600;
    font-size: 17px;
    margin-bottom: 2px;
  }

  [data-bs-theme="light"] .payment-name-text {
    color: #111 !important;
  }

  .payment-desc-text {
    display: block;
    color: #666;
    font-size: 13px;
  }

  .pay-btn-mini {
    background: transparent;
    border: 1.5px solid #FFFFFF;
    color: #fff;
    border-radius: 6px;
    padding: 6px 32px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
  }
  @media (max-width: 480px) {
    .pay-btn-mini {
      padding: 6px 16px !important;
    }
  }
  [data-bs-theme="light"] .pay-btn-mini {
    border: 1px solid #ddd !important;
    color: #111 !important;
  }

  .payment-radio-custom:checked + .payment-item-custom .pay-btn-mini {
    background: #fff;
    color: #000;
    border-color: #fff;
  }

  [data-bs-theme="light"] .payment-radio-custom:checked + .payment-item-custom .pay-btn-mini {
    background: #111 !important;
    color: #fff !important;
    border-color: #111 !important;
  }

  .payment-radio-custom:checked + .payment-item-custom {
    background: rgba(255,255,255,0.03);
  }

  [data-bs-theme="light"] .payment-radio-custom:checked + .payment-item-custom {
    background: rgba(0,0,0,0.03) !important;
  }

  .payment-radio-custom {
    display: none;
    position: absolute;
  }

  .btn-recharge-custom {
    background: #E2394C;
    color: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 19px;
    font-weight: 600;
    width: 100%;
    border: none;
    margin-top: 40px;
    transition: all 0.3s;
  }

  .btn-recharge-custom:hover {
    background: #d8354f;
    transform: translateY(-1px);
    color: #fff;
  }

    .transfer_balance {
      background-color: #fff;
      color: #000 !important;
      border-radius: 18px;
      height: 60px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 19px;
      font-weight: 600;
      width: 100%;
      border: none;
      margin-top: 16px;
      transition: all 0.3s;
  }

  .transfer_balance:hover, .transfer_balance:active {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    color: #000 !important;
  }
  .bank-box-custom {
    background: #0a0a0a;
    border: 1px solid #222;
    border-radius: 20px;
    padding: 25px;
    margin-top: 20px;
    margin-bottom: 20px;
    color: #888;
    transition: all 0.3s ease;
  }

  [data-bs-theme="light"] .bank-box-custom {
    background: #f8f9fa !important;
    border: 1px solid #eee !important;
    color: #666 !important;
  }

  .bank-box-custom h5 {
    color: #fff;
    font-size: 18px;
    margin-bottom: 20px;
  }

  [data-bs-theme="light"] .bank-box-custom h5 {
    color: #111 !important;
  }

  .total-summary-custom {
    margin-top: 30px;
    padding: 20px;
    background: #080808;
    border-radius: 16px;
    border: 1px dashed #222;
    transition: all 0.3s ease;
  }

  [data-bs-theme="light"] .total-summary-custom {
    background: #fff !important;
    border: 1px dashed #ddd !important;
  }

  .total-summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #888;
    font-size: 14px;
  }

  .total-summary-item.main-total {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #1a1a1a;
    color: #fff;
    font-weight: 700;
    font-size: 18px;
  }

  [data-bs-theme="light"] .total-summary-item.main-total {
    border-top: 1px solid #eee !important;
    color: #111 !important;
  }

  /* Theme Support for other wallet elements */
  [data-bs-theme="light"] .wallet_ac_detail {
    background-color: #f8f9fa !important;
    border: 1px solid #eee !important;
    color: #111 !important;
  }
  [data-bs-theme="light"] .theme-subtitle {
     color: #444 !important;
  }
  .theme-subtitle {
     color: #fff;
  }
  .center_align{
    text-align: center;
  }
  .currency_class{
      font-size:42px !important;
      line-height: 2.01 !important;
      font-weight:700;
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
  }
</style>
@endsection

@section('title') {{__('general.wallet')}} -@endsection

@section('content')
<section class="section section-sm">
  @include('includes.header-mobile')
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
        <div class="row app-main-row">
          @if (auth()->user()->verified_id == 'yes')
            @include('includes.cards-settings')
          @else
            <div class="col-lg-3 col-md-4 side_bar_box_shadow">
                @include('includes.menu-sidebar-home')
            </div>
          @endif
          <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
            <div class="row mb-sm">
              <div class="col-lg-8">
                <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">{{__('general.wallet')}}</h2>
                <p class="mt-0 font_weight_400 fs-14 theme-subtitle">{{__('general.wallet_desc')}}</p>
              </div>
            </div>
            @include('errors.errors-forms')

            @if (session('error_message'))
            <div class="alert alert-danger mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>

              {{ session('error_message') }}
            </div>
            @endif

            @if (session('success_message'))
            <div class="alert alert-success mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>

              {{ session('success_message') }}
            </div>
            @endif

            <div class="alert text_color_white alert-custom shadow overflow-hidden position-relative alert_custom w-100 w-lg-auto" role="alert">

                <div class="inner-wrap">
                  <span>
                    <h2 class="text_color_white font_weight_700 wallet_inner_text balance-wrap">
                      <span class="balance-amount">
                        {{ Helper::userWallet() }}
                      </span>
                      <span class="currency_class">
                        {{ $settings->wallet_format == 'real_money' ? config('settings.currency_code') : null}}
                      </span>
                    </h2>

                    <span class="w-100 d-block font_weight_400 fs-24 text_color_white mobile_fund_text center_align">
                      {{__('general.funds_available')}}
                    </span>
                     
                    @if ($equivalent_money)
                      <span>
                        <strong>{{ $equivalent_money }}</strong>
                      </span>
                    @endif
                    
                    
                  </span>
                </div>

                <span class="icon_wrap"><img src="{{url('/img/wallet-bg.png')}}" /></span>

            </div><!-- /alert -->
            {{-- 
            <div class="mb-3 wallet_ac_detail">
              <p>Wallet Account No:</p>
              <p>KM2231391031038108310481903819023830913803123</p>
            </div>
            --}}
            <div class="payment-card-custom mt-4">
              <form method="POST" action="{{ url('add/funds') }}" id="formAddFunds">
                @csrf

                <div class="form-group mb-0">
                  <label class="payment-label">Amount *</label>
                  <div class="amt-input-container">
                    {{-- <div class="input-group-prepend">
                      <span class="input-group-text">{{$settings->currency_symbol}}</span>
                    </div> --}}
                    <input class="form-control amt_input" required id="onlyNumber" name="amount" min="{{ $settings->min_deposits_amount }}" max="{{ $settings->max_deposits_amount }}" autocomplete="off" placeholder="{{__('admin.amount')}} ({{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->min_deposits_amount) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat($settings->max_deposits_amount) }})" type="number">
                  </div>
                  <small class="amt-helper-text">
                    <i class="bi-arrow-up-square mr-1 amount-increase" style="cursor:pointer;"></i> 
                    <i class="bi-arrow-down-square mr-1 amount-decrease" style="cursor:pointer;"></i> 
                    {{ __('general.increase_decrease_amount') }}
                  </small>
                </div>

                <div class="payment-list-custom">
                  @foreach (PaymentGateways::where('enabled', '1')->orderBy('type', 'DESC')->get() as $payment)
                    @php
                    $paymentLogo = '';
                    $paymentNameShow = $payment->name;
                    $paymentDescription = '';

                    if ($payment->type == 'card' ) {
                      $paymentLogo = '<i class="far fa-credit-card"></i>';
                      $paymentNameShow = __('general.debit_credit_card');
                      $paymentDescription = 'Powered by ' . $payment->name;
                    } elseif ($payment->type == 'bank') {
                      $paymentLogo = '<i class="fa fa-university"></i>';
                      $paymentNameShow = __('general.bank_transfer');
                      $paymentDescription = __('general.make_payment_bank');
                    } else if ($payment->name == 'PayPal') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'paypal-white.png').'"/>';
                      $paymentDescription = 'You will be redirected to the PayPal website';
                    } else if ($payment->name == 'Coinpayments') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinpayments-white.png').'"/>';
                      $paymentDescription = 'Pay with Cryptocurrency';
                    } else if ($payment->name == 'Coinbase') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinbase-white.png').'"/>';
                      $paymentDescription = 'Pay with Cryptocurrency';
                    } else if ($payment->name == 'NowPayments') {
                      $paymentLogo = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'nowpayments-white.png').'"/>';
                      $paymentDescription = 'Pay with Cryptocurrency';
                    } else if ($payment->name == 'Mercadopago') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'mercadopago-white.png').'"/>';
                    } else if ($payment->name == 'Flutterwave') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'flutterwave-white.png').'"/>';
                    } else if ($payment->name == 'Mollie') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'mollie-white.png').'"/>';
                    } else if ($payment->name == 'Razorpay') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'razorpay-white.png').'"/>';
                    } else if ($payment->name == 'Payway') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'payway-white.svg').'"/>';
                    } else if ($payment->name == 'Atlos') {
                      $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'atlos-white.png').'"/>';
                    } else {
                      $paymentLogo = '<img src="'.url('img/payments', $payment->logo).'"/>';
                    }
                    @endphp

                    <div class="payment-item-wrapper">
                      <input type="radio" name="payment_gateway" required value="{{$payment->name}}" id="tip_radio{{$payment->name}}" @if (PaymentGateways::where('enabled', '1')->count() == 1) checked @endif class="payment-radio-custom">
                      <label class="payment-item-custom" for="tip_radio{{$payment->name}}">
                        <div class="payment-icon-wrapper">
                          {!! $paymentLogo !!}
                        </div>
                        <div class="payment-info-wrapper">
                          <span class="payment-name-text">{{ $paymentNameShow }}</span>
                          <span class="payment-desc-text">
                            {{ $paymentDescription }}
                            @if($payment->fee != 0.00 || $payment->fee_cents != 0.00)
                                <small class="ml-1">({{ $payment->fee != 0.00 ? $payment->fee.'%' : '' }} {{ $payment->fee_cents != 0.00 ? '+ '. Helper::amountFormatDecimal($payment->fee_cents) : '' }})</small>
                            @endif
                          </span>
                        </div>
                        <div class="payment-action">
                          <span class="pay-btn-mini">Pay</span>
                        </div>
                      </label>
                    </div>

                    @if ($payment->type == 'bank')
                      <div class="bank-box-custom @if (PaymentGateways::where('enabled', '1')->count() != 1) display-none @endif" id="bankTransferBox">
                        <h5><i class="fa fa-university mr-2"></i> {{__('general.make_payment_bank')}}</h5>
                        <div class="mb-4">
                          {!! nl2br($payment->bank_info) !!}
                        </div>
                        
                        <div class="mb-3">
                          <span class="d-block mb-3" id="previewImage"></span>
                          <input type="file" name="image" id="fileBankTransfer" accept="image/*" class="visibility-hidden">
                          <button class="btn btn-outline-primary btn-block border-dashed py-3" onclick="$('#fileBankTransfer').trigger('click');" type="button" id="btnFilePhoto">
                            <i class="bi-cloud-arrow-up mr-2"></i> {{__('general.upload_image')}} (JPG, PNG, GIF)
                          </button>
                          <small class="text-muted d-block mt-2">{{__('general.info_bank_transfer')}}</small>
                        </div>

                        <div class="mt-3 pt-3 border-top">
                          <p class="mb-2 text-white">{{ __('general.total') }}: <strong>{{ Helper::symbolPositionLeft() }}<span id="total2">0</span>{{ Helper::symbolPositionRight() }}</strong></p>
                          @if ($equivalent_money)
                              <p class="small text-muted mb-0"><strong>{{ $equivalent_money }}</strong></p>
                          @endif
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>

                {{-- <p class="help-block margin-bottom-zero fee-wrap">
                  <span class="d-block w-100">
                  {{ __('general.transaction_fee') }}:
                  <span class="float-right"><strong>{{ Helper::symbolPositionLeft() }}<span id="handlingFee">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
                </span>
                @if (auth()->user()->isTaxable()->count() && $settings->tax_on_wallet)
                  @foreach (auth()->user()->isTaxable() as $tax)
                  <span class="d-block w-100 isTaxableWallet percentageAppliedTaxWallet{{$loop->iteration}}" data="{{ $tax->percentage }}">
                    {{ $tax->name }} {{ $tax->percentage }}%:
                    <span class="float-right">
                    <strong>{{ Helper::symbolPositionLeft() }}<span class="percentageTax{{$loop->iteration}}">0</span>{{ Helper::symbolPositionRight() }}</strong>
                  </span>
                </span>
                  @endforeach
                @endif
                  <span class="d-block w-100">
                    {{ __('general.total') }}:
                    <span class="float-right">
                    <strong>{{ Helper::symbolPositionLeft() }}<span id="total">0</span>{{ Helper::symbolPositionRight() }}</strong>
                  </span>
                </span>
                </p> --}}

                <div class="total-summary-custom">
                  <div class="total-summary-item">
                    <span>{{ __('general.transaction_fee') }}</span>
                    <span><strong>{{ Helper::symbolPositionLeft() }}<span id="handlingFee">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
                  </div>

                  @if (auth()->user()->isTaxable()->count() && $settings->tax_on_wallet)
                    @foreach (auth()->user()->isTaxable() as $tax)
                      <div class="total-summary-item isTaxableWallet percentageAppliedTaxWallet{{$loop->iteration}}" data="{{ $tax->percentage }}">
                        <span>{{ $tax->name }} {{ $tax->percentage }}%</span>
                        <span><strong>{{ Helper::symbolPositionLeft() }}<span class="percentageTax{{$loop->iteration}}">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
                      </div>
                    @endforeach
                  @endif

                  <div class="total-summary-item main-total">
                    <span>{{ __('general.total') }}</span>
                    <span><strong>{{ Helper::symbolPositionLeft() }}<span id="total">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
                  </div>
                </div>

                <div class="alert alert-danger display-none mt-3" id="errorAddFunds">
                  <ul class="list-unstyled m-0 text-break" id="showErrorsFunds"></ul>
                </div>

                {{-- <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" required id=" customCheckLogin" name="agree_terms" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span>{{__('general.i_agree_with')}} <a href="{{$settings->link_terms}}" target="_blank">{{__('admin.terms_conditions')}}</a></span>
                    </label>
                </div> --}}

                <button class="btn-recharge-custom" id="addFundsBtn" type="submit">
                  {{__('general.add_funds')}}
                </button>
                @if (auth()->user()->balance != 0.00)
                      <a href="#" data-toggle="modal" data-target="#modalTransfer" class="btn btn-1 btn-success mb-2 text-decoration-none e-none transfer_balance">
                        {{ __('general.transfer_balance') }}
                      </a>
                      @endif
              </form>
            </div>

            @if ($data->count() != 0)
              <h6 class="text-center mt-5 font-weight-light">{{ __('general.history_deposits') }}</h6>

              <div class="card shadow-sm mb-2">
                <div class="table-responsive">
                  <table class="table table-striped m-0">
                    <thead>
                      <th scope="col">ID</th>
                      <th scope="col">{{ __('admin.amount') }}</th>
                      <th scope="col">{{ __('general.payment_gateway') }}</th>
                      <th scope="col">{{ __('admin.date') }}</th>
                      <th scope="col">{{ __('admin.status') }}</th>
                      <th> {{__('general.invoice')}}</th>
                    </thead>

                    <tbody>
                      @foreach ($data as $deposit)

                        <tr>
                          <td>{{ str_pad($deposit->id, 4, "0", STR_PAD_LEFT) }}</td>
                          <td>{{ App\Helper::amountFormat($deposit->amount) }}</td>
                          <td>{{ $deposit->payment_gateway == 'Bank Transfer' || $deposit->payment_gateway == 'Bank' ? __('general.bank_transfer') : $deposit->payment_gateway }}</td>
                          <td>{{ date('d M, Y', strtotime($deposit->date)) }}</td>

                          @php

                          if ($deposit->status == 'pending' ) {
                                $mode    = 'warning';
                                $_status = __('admin.pending');
                              } else {
                                $mode = 'success';
                                $_status = __('general.success');
                              }

                          @endphp

                          <td><span class="badge badge-pill badge-{{$mode}} text-uppercase">{{ $_status }}</span></td>

                          <td>
                            @if ($deposit->status == 'active')
                            <a href="{{url('deposits/invoice', $deposit->id)}}" target="_blank"><i class="far fa-file-alt"></i> {{__('general.invoice')}}</a>
                          </td>
                        @else
                          {{__('general.no_available')}}
                            @endif
                        </tr><!-- /.TR -->
                        @endforeach
                    </tbody>
                  </table>
                </div><!-- table-responsive -->
              </div><!-- card -->
              <small class="w-100 d-block mt-2">{{ __('general.transaction_fee_info') }}</small>

              @if ($data->hasPages())
                <div class="mt-3">
                  {{ $data->links() }}
                </div>
              @endif

            @endif
          </div><!-- end col-md-6 -->
        </div><!-- end row -->
    </div>
</section>

@if (auth()->user()->balance != 0.00)
  @include('includes.modal-transfer')
@endif

@endsection

@section('javascript')
<script async src="https://atlos.io/packages/app/atlos.js"></script>

<script type="text/javascript">
@if (in_array(config('settings.currency_code'), config('currencies.zero-decimal')))
  $decimal = 0;
  @else
  $decimal = 2;
  @endif

  function toFixed(number, decimals) {
        var x = Math.pow(10, Number(decimals) + 1);
        return (Number(number) + (1 / x)).toFixed(decimals);
      }

  $('input[name=payment_gateway]').on('click', function() {
    var valueOriginal = $('#onlyNumber').val();
    var value = parseFloat($('#onlyNumber').val());
    var element = $(this).val();

    if (element == 'Bank Transfer' || element == 'Bank') {
      $('#bankTransferBox').fadeIn();
    } else {
      $('#bankTransferBox').fadeOut();
    }

    //==== Start Taxes
    var taxes = $('span.isTaxableWallet').length;
    var totalTax = 0;

    if (valueOriginal.length == 0
				|| valueOriginal == ''
				|| value < {{ $settings->min_deposits_amount }}
				|| value > {{$settings->max_deposits_amount}}
      ) {
        // Reset
  			for (var i = 1; i <= taxes; i++) {
  				$('.percentageTax'+i).html('0');
  			}
        $('#handlingFee, #total, #total2').html('0');
      } else {
        // Taxes
        for (var i = 1; i <= taxes; i++) {
          var percentage = $('.percentageAppliedTaxWallet'+i).attr('data');
          var valueFinal = (value * percentage / 100);
          $('.percentageTax'+i).html(toFixed(valueFinal, $decimal));
          totalTax += valueFinal;
        }
        var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
      }
      //==== End Taxes

      // Service Fee
      
    if (element != ''
        && value <= {{ $settings->max_deposits_amount }}
        && value >= {{ $settings->min_deposits_amount }}
        && valueOriginal != ''
      ) {
      // Fees
      switch (element) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case '{{$payment->name}}':
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var amountFinal = toFixed(amount, $decimal);

      var total = (parseFloat(value) + parseFloat(amountFinal) + parseFloat(totalTaxes));

      if (valueOriginal.length != 0
  				|| valueOriginal != ''
  				|| value >= {{ $settings->min_deposits_amount }}
  				|| value <= {{$settings->max_deposits_amount}}
        ) {
        $('#handlingFee').html(amountFinal);
        $('#total, #total2').html(total.toFixed($decimal));
      }
    }

});

//<-------- * TRIM * ----------->

$('#onlyNumber').on('keyup', function() {

    var valueOriginal = $(this).val();
    var value = parseFloat($(this).val());
    var paymentGateway = $('input[name=payment_gateway]:checked').val();

    if (value > {{ $settings->max_deposits_amount }} || valueOriginal.length == 0) {
      $('#handlingFee').html('0');
      $('#total, #total2').html('0');
    }

    //==== Start Taxes
    var taxes = $('span.isTaxableWallet').length;
    var totalTax = 0;

    if (valueOriginal.length == 0
				|| valueOriginal == ''
				|| value < {{ $settings->min_deposits_amount }}
				|| value > {{$settings->max_deposits_amount}}
      ) {
        // Reset
  			for (var i = 1; i <= taxes; i++) {
  				$('.percentageTax'+i).html('0');
  			}
        $('#handlingFee, #total, #total2').html('0');
      } else {
        // Taxes
        for (var i = 1; i <= taxes; i++) {
          var percentage = $('.percentageAppliedTaxWallet'+i).attr('data');
          var valueFinal = (value * percentage / 100);
          $('.percentageTax'+i).html(toFixed(valueFinal, $decimal));
          totalTax += valueFinal;
        }
        var totalTaxes = (Math.round(totalTax * 100) / 100).toFixed(2);
      }
      //==== End Taxes

    if (paymentGateway
        && value <= {{ $settings->max_deposits_amount }}
        && value >= {{ $settings->min_deposits_amount }}
        && valueOriginal != ''
      ) {

      switch(paymentGateway) {
        @foreach (PaymentGateways::where('enabled', '1')->get(); as $payment)
        case '{{$payment->name}}':
          $fee   = {{$payment->fee}};
          $cents =  {{$payment->fee_cents}};
          break;
        @endforeach
      }

      var amount = (value * $fee / 100) + $cents;
      var amountFinal = toFixed(amount, $decimal);

      var total = (parseFloat(value) + parseFloat(amountFinal) + parseFloat(totalTaxes));

      if (valueOriginal.length != 0
  				|| valueOriginal != ''
  				|| value >= {{ $settings->min_deposits_amount }}
  				|| value <= {{$settings->max_deposits_amount}}
        ) {
        $('#handlingFee').html(amountFinal);
        $('#total, #total2').html(total.toFixed($decimal));
      } else {
        $('#handlingFee, #total, #total2').html('0');
        }
    }
});

@if (session('payment_process'))
   swal({
     html:true,
     title: "{{ __('general.congratulations') }}",
     text: "{!! __('general.payment_process_wallet') !!}",
     type: "success",
     confirmButtonText: "{{ __('users.ok') }}"
     });
  @endif

  // Submission feedback
  $('#formAddFunds').on('submit', function() {
    $('#addFundsBtn').attr('disabled', 'disabled').html('<i class="spinner-border spinner-border-sm mr-2"></i> {{__("general.processing")}}');
  });


$('.amount-increase').click(function () {
  let input = $('#onlyNumber');
  input.val(parseInt(input.val() || 0) + 1);
});

$('.amount-decrease').click(function () {
  let input = $('#onlyNumber');
  let val = parseInt(input.val() || 0);
  if (val > 1) input.val(val - 1);
});
</script>
@endsection
