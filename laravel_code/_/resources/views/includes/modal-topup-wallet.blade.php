@if ($settings->disable_wallet == 'off')
<div class="modal fade" id="modalTopupWallet" tabindex="-1" role="dialog" aria-labelledby="modalTopupWalletLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content shadow-lg topup-wallet-modal">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title" id="modalTopupWalletLabel">{{ __('general.add_funds') }}</h5>
        <button type="button" class="close close-inherit" data-dismiss="modal" aria-label="{{ __('general.close') }}">
          <span aria-hidden="true"><i class="bi bi-x-lg"></i></span>
        </button>
      </div>
      <div class="modal-body pt-0">
        <div class="payment-card-custom">
          <form method="POST" action="{{ url('add/funds') }}" id="topupFormAddFunds">
            @csrf

            <div class="form-group mb-0">
              <label class="payment-label">{{ __('admin.amount') }} *</label>
              <div class="amt-input-container">
                <input class="form-control amt_input" required id="topupOnlyNumber" name="amount"
                  min="{{ $settings->min_deposits_amount }}" max="{{ $settings->max_deposits_amount }}"
                  autocomplete="off"
                  placeholder="{{ __('admin.amount') }} ({{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->min_deposits_amount) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat($settings->max_deposits_amount) }})"
                  type="number">
              </div>
              <small class="amt-helper-text">
                <i class="bi-arrow-up-square mr-1 topup-amount-increase" style="cursor:pointer;"></i>
                <i class="bi-arrow-down-square mr-1 topup-amount-decrease" style="cursor:pointer;"></i>
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
                  $paymentDescription = __('general.powered_by') . ' ' . $payment->name;
                } elseif ($payment->type == 'bank') {
                  $paymentLogo = '<i class="fa fa-university"></i>';
                  $paymentNameShow = __('general.bank_transfer');
                  $paymentDescription = __('general.make_payment_bank');
                } else if ($payment->name == 'PayPal') {
                  $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'paypal-white.png').'"/>';
                  $paymentDescription = __('general.redirected_to_paypal_website');
                } else if ($payment->name == 'Coinpayments') {
                  $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinpayments-white.png').'"/>';
                  $paymentDescription = __('general.pay_with_cryptocurrency');
                } else if ($payment->name == 'Coinbase') {
                  $paymentLogo = '<img src="'.url('img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'coinbase-white.png').'"/>';
                  $paymentDescription = __('general.pay_with_cryptocurrency');
                } else if ($payment->name == 'NowPayments') {
                  $paymentLogo = '<img src="'.url('public/img/payments', auth()->user()->dark_mode == 'off' ? $payment->logo : 'nowpayments-white.png').'"/>';
                  $paymentDescription = __('general.pay_with_cryptocurrency');
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
                  <input type="radio" name="payment_gateway" required value="{{$payment->name}}" id="topup_radio{{$payment->name}}"
                    @if (PaymentGateways::where('enabled', '1')->count() == 1) checked @endif class="payment-radio-custom">
                  <label class="payment-item-custom" for="topup_radio{{$payment->name}}">
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
                      <span class="pay-btn-mini">{{ __('general.pay') }}</span>
                    </div>
                  </label>
                </div>

                @if ($payment->type == 'bank')
                  <div class="bank-box-custom @if (PaymentGateways::where('enabled', '1')->count() != 1) display-none @endif" id="topupBankTransferBox">
                    <h5><i class="fa fa-university mr-2"></i> {{__('general.make_payment_bank')}}</h5>
                    <div class="mb-4">
                      {!! nl2br($payment->bank_info) !!}
                    </div>

                    <div class="mb-3">
                      <span class="d-block mb-3" id="topupPreviewImage"></span>
                      <input type="file" name="image" id="topupFileBankTransfer" accept="image/*" class="visibility-hidden">
                      <button class="btn btn-outline-primary btn-block border-dashed py-3" onclick="$('#topupFileBankTransfer').trigger('click');" type="button" id="topupBtnFilePhoto">
                        <i class="bi-cloud-arrow-up mr-2"></i> {{__('general.upload_image')}} (JPG, PNG, GIF)
                      </button>
                      <small class="text-muted d-block mt-2">{{__('general.info_bank_transfer')}}</small>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                      <p class="mb-2 text-white">{{ __('general.total') }}: <strong>{{ Helper::symbolPositionLeft() }}<span id="topupTotal2">0</span>{{ Helper::symbolPositionRight() }}</strong></p>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>

            <div class="total-summary-custom">
              <div class="total-summary-item">
                <span>{{ __('general.transaction_fee') }}</span>
                <span><strong>{{ Helper::symbolPositionLeft() }}<span id="topupHandlingFee">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
              </div>

              @if (auth()->user()->isTaxable()->count() && $settings->tax_on_wallet)
                @foreach (auth()->user()->isTaxable() as $tax)
                  <div class="total-summary-item topupTaxable topupPercentageAppliedTax{{$loop->iteration}}" data="{{ $tax->percentage }}">
                    <span>{{ $tax->name }} {{ $tax->percentage }}%</span>
                    <span><strong>{{ Helper::symbolPositionLeft() }}<span class="topupPercentageTax{{$loop->iteration}}">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
                  </div>
                @endforeach
              @endif

              <div class="total-summary-item main-total">
                <span>{{ __('general.total') }}</span>
                <span><strong>{{ Helper::symbolPositionLeft() }}<span id="topupTotal">0</span>{{ Helper::symbolPositionRight() }}</strong></span>
              </div>
            </div>

            <div class="alert alert-danger display-none mt-3" id="topupErrorAddFunds">
              <ul class="list-unstyled m-0 text-break" id="topupShowErrorsFunds"></ul>
            </div>

            <button class="btn-recharge-custom" id="topupAddFundsBtn" type="submit">
              {{ __('general.add_funds') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  #modalTopupWallet .payment-card-custom {
    padding: 12px;
    border-radius: 24px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
  }
  #modalTopupWallet .payment-label {
    font-size: 16px;
    color: #fff;
    font-weight: 500;
    margin-bottom: 12px;
    display: block;
  }
  #modalTopupWallet .amt-input-container {
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    transition: all 0.3s ease;
  }
  #modalTopupWallet .amt-input-container:focus-within {
    border-color: #f1415d;
  }
  #modalTopupWallet .amt-input-container input {
    background: transparent !important;
    border: none !important;
    color: #fff !important;
    font-size: 16px !important;
    box-shadow: none !important;
    width: 100%;
  }
  #modalTopupWallet .amt-input-container input::placeholder {
    color: #333;
  }
  #modalTopupWallet .amt-helper-text {
    font-size: 12px;
    color: #666;
    margin-bottom: 30px;
    display: block;
  }
  #modalTopupWallet .payment-list-custom {
    margin-top: 20px;
  }
  #modalTopupWallet .payment-item-wrapper {
    position: relative;
    margin-bottom: 0;
  }
  #modalTopupWallet .payment-item-custom {
    display: flex;
    align-items: center;
    padding: 20px 0;
    border-bottom: 1px solid #1a1a1a;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none !important;
    margin-bottom: 0;
  }
  #modalTopupWallet .payment-item-custom:hover {
    background: rgba(255,255,255,0.02);
  }
  #modalTopupWallet .payment-icon-wrapper {
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
  #modalTopupWallet .payment-icon-wrapper i {
    font-size: 22px;
    color: #fff;
  }
  #modalTopupWallet .payment-icon-wrapper img {
    max-width: 24px;
    max-height: 24px;
    object-fit: contain;
  }
  #modalTopupWallet .payment-info-wrapper {
    flex-grow: 1;
    min-width: 0;
    padding-right: 12px;
  }
  #modalTopupWallet .payment-name-text {
    display: block;
    color: #fff;
    font-weight: 600;
    font-size: 17px;
    margin-bottom: 2px;
  }
  #modalTopupWallet .payment-desc-text {
    display: block;
    color: #666;
    font-size: 13px;
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
    line-height: 1.35;
  }
  #modalTopupWallet .pay-btn-mini {
    background: transparent;
    border: 2px solid #FFFFFF;
    color: #fff;
    border-radius: 8px;
    width: 88px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0;
    font-size: 14px;
    font-weight: 600;
    line-height: 1;
    transition: all 0.2s;
  }
  #modalTopupWallet .payment-action {
    flex-shrink: 0;
    margin-left: 16px;
    display: flex;
    align-items: center;
  }
  #modalTopupWallet .payment-radio-custom:checked + .payment-item-custom .pay-btn-mini {
    background: #fff;
    color: #000;
    border-color: #fff;
  }
  #modalTopupWallet .payment-radio-custom:checked + .payment-item-custom {
    background: rgba(255,255,255,0.03);
  }
  #modalTopupWallet .payment-radio-custom {
    display: none;
    position: absolute;
  }
  #modalTopupWallet .btn-recharge-custom {
    background: #E2394C;
    color: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    font-weight: 600;
    width: 100%;
    border: none;
    margin-top: 30px;
    transition: all 0.3s;
  }
  #modalTopupWallet .btn-recharge-custom:hover {
    background: #d8354f;
    transform: translateY(-1px);
    color: #fff;
  }
  #modalTopupWallet .bank-box-custom {
    background: #0a0a0a;
    border: 1px solid #222;
    border-radius: 20px;
    padding: 25px;
    margin-top: 20px;
    margin-bottom: 20px;
    color: #888;
    transition: all 0.3s ease;
  }
  #modalTopupWallet .bank-box-custom h5 {
    color: #fff;
    font-size: 18px;
    margin-bottom: 20px;
  }
  #modalTopupWallet .total-summary-custom {
    margin-top: 30px;
    padding: 20px;
    background: #080808;
    border-radius: 16px;
    border: 1px dashed #222;
    transition: all 0.3s ease;
  }
  #modalTopupWallet .total-summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #888;
    font-size: 14px;
  }
  #modalTopupWallet .total-summary-item.main-total {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid #1a1a1a;
    color: #fff;
    font-weight: 700;
    font-size: 18px;
  }
  @media (max-width: 480px) {
    #modalTopupWallet .payment-name-text {
      font-size: 16px;
      line-height: 1.2;
    }
    #modalTopupWallet .payment-desc-text {
      font-size: 12px;
      line-height: 1.35;
    }
    #modalTopupWallet .pay-btn-mini {
      width: 92px;
      height: 36px;
      font-size: 14px;
    }
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-card-custom {
    background-color: #fff;
    color: #111;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-label {
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .amt-input-container {
    background: #fff !important;
    border: 1px solid #ddd !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .amt-input-container input {
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .amt-input-container input::placeholder {
    color: #aaa;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-item-custom {
    border-bottom: 1px solid #eee !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-item-custom:hover {
    background: rgba(0,0,0,0.02) !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-icon-wrapper {
    background: #eee !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-icon-wrapper i {
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-name-text {
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .pay-btn-mini {
    border: 1px solid #ddd !important;
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-radio-custom:checked + .payment-item-custom .pay-btn-mini {
    background: #111 !important;
    color: #fff !important;
    border-color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .payment-radio-custom:checked + .payment-item-custom {
    background: rgba(0,0,0,0.03) !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .bank-box-custom {
    background: #f8f9fa !important;
    border: 1px solid #eee !important;
    color: #666 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .bank-box-custom h5 {
    color: #111 !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .total-summary-custom {
    background: #fff !important;
    border: 1px dashed #ddd !important;
  }
  [data-bs-theme="light"] #modalTopupWallet .total-summary-item.main-total {
    border-top: 1px solid #eee !important;
    color: #111 !important;
  }
</style>

@endif
