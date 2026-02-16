@extends('layouts.app')

@section('title') {{trans('general.payment_card')}} -@endsection

@section('css')
<script type="text/javascript">
  var key_stripe = "{{ $key }}";
</script>
@endsection

@section('content')
<section class="section section-sm">
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
    <div class="row">
      @include('includes.cards-settings')

      <div class="col-md-6 col-lg-9 pb-4 add-card">
        <h2 class="mb-0  font_weight_700 fs-24 pb-3">{{trans('general.payment_card')}}</h2>
        <p class="lead text-muted mt-0 theme-sub-title font_weight_400 fs-14">{{trans('general.payment_card_subtitle')}}</p>
        <div class="bg-white rounded-lg shadow-sm  py-4 px-3">

          <div class="alert alert-success display-none" id="success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>

            {{ trans('general.payment_card_success') }}
          </div>

          @php
          switch (auth()->user()->pm_type) {
          case 'amex':
          $paymentDefault = '<img src="'.asset('img/payments/brands/amex.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'diners':
          $paymentDefault = '<img src="'.asset('img/payments/brands/diners.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'discover':
          $paymentDefault = '<img src="'.asset('img/payments/brands/discover.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'jcb':
          $paymentDefault = '<img src="'.asset('img/payments/brands/jcb.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'mastercard':
          $paymentDefault = '<img src="'.asset('img/payments/brands/mastercard.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'unionpay':
          $paymentDefault = '<img src="'.asset('img/payments/brands/unionpay.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          case 'visa':
          $paymentDefault = '<img src="'.asset('img/payments/brands/visa.svg').'"> •••• •••• •••• '.auth()->user()->pm_last_four;
          break;

          default:
          $paymentDefault = trans('general.not_card_added');
          break;
          }
          @endphp


          <div class="pb-2">
            <span class="lbl-card">Enter your card details</span>
          </div>
          <!-- Stripe Elements Placeholder -->
          <div id="card-element"></div>
          <div id="card-errors" class="alert alert-danger display-none" role="alert"></div>
          <div class="payment-actions mt-3">
            <div class="go_back">
              <a href="{{ url()->previous() }}">{{ trans('general.go_back') }}</a>
            </div>
            <button id="card-button" class="btn btn-1 btn-primary save_payment_card" data-secret="{{ $intent->client_secret }}">
              <i></i> {{ trans('general.save_payment_card') }}
            </button>
          </div>

        </div>

        <div class="btn-block mt-2">
          <small>{{ trans('general.info_payment_card') }}</small>
        </div>

      </div><!-- end col-md-8 -->

    </div>
  </div>
</section>
@endsection

@section('javascript')
<script src="{{ asset('js/add-payment-card.js') }}"></script>
@endsection