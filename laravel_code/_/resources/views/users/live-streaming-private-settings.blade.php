@extends('layouts.app')

@section('css')
<style type="text/css">
  @media (max-width: 576px) {
  .form-control::placeholder {
    font-size: 12px;
    line-height: 1.3;
  }
}
</style>

@endsection

@section('title') {{__('general.live_stream_private_settings')}} -@endsection

@section('content')
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
    <div class="row">
       @include('includes.cards-settings')
      <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
        <div class="row mb-sm">
          <div class="col-lg-8">
            <h2 class="mb-0 font-montserrat pb-3 font_weight_700 fs-24">{{__('general.live_stream_private_settings')}}</h2>
            <p class="lead mt-0 font_weight_400 fs-14">{{__('general.subtitle_live_stream_private_settings')}}</p>
          </div>
        </div>
        @if (session('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{ url()->current() }}" class="my-3">
          @csrf
          <div class="form-group">
            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="allow_live_streaming_private" value="on" @if (auth()->user()->allow_live_streaming_private == 'on') checked @endif id="allow_live_streaming_private">
                <label class="custom-control-label switch fs-16" for="allow_live_streaming_private">{{ __('general.allow_live_streaming_private') }}</label>
              </div>
            </div>
          </div>

          <div class="form-group mb-4">
            <label class="w-100 fs-16">{{__('general.price_live_streaming_private')}} *</label>
            <div class="input-group mb-2">

              <!-- <div class="input-group-prepend">
                <span class="input-group-text">{{$settings->currency_symbol}}</span>
              </div> -->
              <input value="{{ auth()->user()->price_live_streaming_private }}" class="form-control form-control-md isNumber brd-12" required name="price_live_streaming_private" autocomplete="off" placeholder="{{$settings->currency_symbol}} {{__('general.price_live_streaming_private')}}" type="text">
            </div>
            <small class="btn-block font_weight_400 fs-16 text-lime">
              * {{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->live_streaming_minimum_price_private) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat($settings->live_streaming_max_price_private) }}

              @if ($settings->wallet_format != 'real_money')
              <strong>({{Helper::equivalentMoney($settings->wallet_format)}})</strong>
              @endif
            </small>
          </div>

          <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

        </form>
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection