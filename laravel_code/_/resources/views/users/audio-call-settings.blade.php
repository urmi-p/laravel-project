@extends('layouts.app')

@section('css')
<style type="text/css">
  .input-group { overflow: hidden; }
select { width: 100%; max-width: 100%; }
@media (max-width:576px){ select{ font-size:16px; } }
</style>

@endsection

@section('title') {{__('general.audio_call_settings')}} -@endsection

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
              <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-2"> {{__('general.audio_call_settings')}}</h2>
              <p class="lead mt-0 font_weight_400 fs-14">{{__('general.subtitle_audio_call_settings')}}</p>
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

          <form method="POST" action="{{ url()->current() }}">
            @csrf
                <div class="form-group mb-4">
                  <label class="w-100 fs-16">{{__('general.price_audio_call')}} *</label>
                  <div class="input-group input-group-sub mb-2">
                    
                    <!-- <div class="input-group-prepend">
                      <span class="input-group-text">{{$settings->currency_symbol}}</span>
                    </div> -->
                        <input value="{{ auth()->user()->price_audio_call }}" class="form-control form-control-md isNumber brd-12" required name="price_audio_call" autocomplete="off" placeholder="{{$settings->currency_symbol}} {{__('general.price_audio_call')}}" type="text">
                    </div>
                    <small class="btn-block text-lime fs-16">
                      * {{ __('general.minimum') }} {{ Helper::priceWithoutFormat($settings->audio_call_min_price) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat($settings->audio_call_max_price) }}

                      @if ($settings->wallet_format != 'real_money')
						            <strong>({{Helper::equivalentMoney($settings->wallet_format)}})</strong>
					            @endif
                    </small>
                </div>

                <div class="form-group mb-4">
                    <label class="w-100 fs-16">{{__('general.duration')}}</label>
                    <div class="w-100">
                      <div class="input-group input-group-sub mb-2">
                        <!-- <div class="input-group-prepend">
                        <span class="input-group-text"><i class="bi-clock"></i></span>
                        </div> -->
                        <select name="audio_call_duration" class="form-control custom-select brd-12">
                            @for ($i = 5; $i <= $settings->audio_call_max_duration; $i+=5)
                            <option @selected(auth()->user()->audio_call_duration == $i) value="{{ $i }}">{{$i}} {{ __('general.minutes') }}</option>
                            @endfor
                        </select>
                      </div>
                      <small class="btn-block text-lime fs-16">
                      * {{ __('general.minimum') }} 5 - {{ __('general.maximum') }} {{ $settings->audio_call_max_duration }}

                    </small>
                    </div>
                </div><!-- End Row Form Group -->

                <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

          </form>
        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
