@extends('layouts.app')

@section('title') {{trans('general.my_cards')}} -@endsection

@section('content')
<section class="section section-sm">
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
    <div class="row">

      @include('includes.cards-settings')

      <div class="col-md-12 col-lg-9 mb-5 mb-lg-0  my-card">
        <div class="col-lg-8 title-div">
          <h2 class="mb-0  font_weight_700 fs-24 pb-3"> {{trans('general.my_cards')}}</h2>
          <p class="mt-0 font_weight_400 fs-14">{{trans('general.info_my_cards')}}</p>
        </div>
        @if (session('success_removed'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ session('success_removed') }}
        </div>
        @endif

        @if (session('success_message'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ trans('general.payment_card_success') }}
        </div>
        @endif

        @if (session('error_message'))
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ session('error_message') }}
        </div>
        @endif

        @if ($key_secret)
        @if (auth()->user()->pm_type != '')

        <div class="card-container">
          <div class="credit-card">
            <!-- Chip -->
            <div class="card-chip"></div>

            <!-- Brand -->
            <div class="card-brand">{{ auth()->user()->pm_type }}</div>

            <!-- Card Number -->
            <div class="card-number">
              **** **** **** {{ auth()->user()->pm_last_four }}
            </div>

            <!-- Bottom Info -->
            <div class="card-footer">
              <div>
                <span class="label">Card Holder name</span>
                <span class="value">Noman Manzoor</span>
              </div>
              <div>
                <span class="label">Expiry Date</span>
                <span class="value">{{ $expiration }}</span>
              </div>
            </div>
          </div>
        </div>
        @endif
        <div class="card mb-4 my-card-not-card-added py-4">
          <div class="card-body">

            <p class="card-text">
              @if (auth()->user()->pm_type == '')
              {{ trans('general.not_card_added') }}
              @endif
            </p>
            @if (auth()->user()->pm_type != '')
            <div class="card-actions">
              @endif
              <div class="add-card">
                <a href="{{ url('settings/payments/card') }}"
                  class="btn btn-success btn-sm">
                  {{ auth()->user()->pm_type == '' ? __('general.add') : __('admin.edit') }}
                </a>
              </div>

              @if (auth()->user()->pm_type != '')
              <form method="POST"
                action="{{ url('stripe/delete/card') }}"
                id="formDeleteCardStripe">
                @csrf
                <input type="button"
                  class="btn btn-danger btn-sm"
                  id="deleteCardStripe"
                  value="{{ __('admin.delete') }}">
              </form>
              @endif
              @if (auth()->user()->pm_type != '')
            </div>
            @endif

          </div>
        </div>

        @endif

        @if ($paystackPayment)
        <div class="card">
          <div class="card-body">
            <p class="card-text">
              @if (auth()->user()->paystack_card_brand != '')
              <img src="{{ asset('img/payments/brands/'.strtolower(auth()->user()->paystack_card_brand).'.svg')}}" class="mr-1">
              <strong class="text-capitalize">{{ auth()->user()->paystack_card_brand }}</strong> <br> •••• •••• •••• {{ auth()->user()->paystack_last4 }}
              <small class="float-right d-block">{{ trans('general.expiry') }}: {{ auth()->user()->paystack_exp }}</small>

              @else
              {{ trans('general.not_card_added') }}
              @endif

              <small class="alert alert-primary w-100 d-block mt-1">
                <i class="fa fa-info-circle mr-2"></i> {{ __('general.notice_charge_to_card', ['amount' => Helper::amountWithoutFormat($chargeAmountPaystack). ' '.$settings->currency_code ]) }}
              </small>

            <form method="POST" action="{{ url('paystack/card/authorization') }}" class="d-inline">
              @csrf
              <input type="submit" class="btn btn-success btn-sm" value="{{ auth()->user()->paystack_card_brand == '' ? __('general.add') : __('admin.edit') }}">
            </form>

            @if (auth()->user()->paystack_card_brand != '')
            <form method="POST" action="{{ url('paystack/delete/card') }}" class="d-inline" id="formDeleteCardPaystack">
              @csrf
              <input type="button" class="btn btn-danger btn-sm" id="deleteCardPaystack" value="{{ __('admin.delete') }}">
            </form>
            @endif
            </p>
          </div>
        </div>
        @endif
        <div class="btn-block mt-2">
          <small>{{ trans('general.info_payment_card') }}</small>
        </div>
        @if (! $key_secret && ! $paystackPayment)

        <div class="alert alert-primary text-center" role="alert">
          <i class="fa fa-info-circle mr-2"></i> {{ trans('general.not_card_added') }}
        </div>
        @endif
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection