@extends('layouts.app')

@section('title') {{__('general.conversations')}} -@endsection

@section('css')
<style type="text/css">
  .fileuploader {
    display: block;
    padding: 0;
  }

  .fileuploader-items-list {
    margin: 10px 0 0 0;
  }

  .fileuploader-theme-dragdrop .fileuploader-input p {
    color: #A4A8AB !important;
    font-weight:400;
    font-size: 10px;
  }
  .fileuploader-theme-dragdrop .fileuploader-input h3 {
    color: #FFFFFF !important;
    font-weight:500;
    font-size: 10px;
  }

  [data-bs-theme="light"] .fileuploader-theme-dragdrop .fileuploader-input h3  {
		color: #5B5B7B !important;
	}
  .browse-link {
    color: #E2394C;
    text-decoration: underline;
    cursor: pointer;
  }
  .fileuploader-icon-main{
    color:#E2394C !important;
  }
  [data-bs-theme="dark"] .fileuploader-theme-dragdrop .fileuploader-input{
      background: #222 !important;
  }
</style>
@endsection

@section('content')
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
  <div class="container-fluid pt-lg-5 pt-2">
    <div class="row">
      @include('includes.cards-settings')
      <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
        <div class="row mb-sm">
          <div class="col-lg-8">
            <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">
              <!-- <i class="feather icon-send mr-2"></i>  -->
              {{__('general.conversations')}}
            </h2>
            <p class="mt-0 font_weight_400 fs-14">{{__('general.subtitle_conversations')}}</p>
          </div>
        </div>
        @if (session('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          <i class="bi-check2 mr-2"></i> {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{ route('settings.conversations_update') }}">
          @csrf
          <div class="form-group">
            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="allow_dm" value="1" @checked(auth()->user()->allow_dm) id="allow_dm">
                <label class="custom-control-label switch fs-16 font_weight_500" for="allow_dm">{{ __('general.receive_private_messages') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="send_welcome_message" value="1" @checked(auth()->user()->send_welcome_message) id="send_welcome_message">
                <label class="custom-control-label switch fs-16 font_weight_500" for="send_welcome_message">{{ __('general.send_welcome_message_new_subscribers') }}</label>
              </div>
            </div>
          </div>

          <div class="form-group mb-4">
            <label class="w-100 fs-16 font_weight_500">{{__('general.price_welcome_message')}} ({{ __('general.optional') }})</label>
            <div class="input-group mb-2">
              <!-- <div class="input-group-prepend">
                <span class="input-group-text currency_span">{{$settings->currency_symbol}}</span>
              </div> -->
              <input value="{{ auth()->user()->price_welcome_message }}" class="form-control form-control-md isNumber brd-12" name="price_welcome_message" autocomplete="off" placeholder="{{$settings->currency_symbol}} 0.00" type="text">
            </div>
            <small class="btn-block text-lime">
              * {{ __('general.minimum') }} {{ Helper::priceWithoutFormat(config('settings.min_ppv_amount')) }} - {{ __('general.maximum') }} {{ Helper::priceWithoutFormat(config('settings.max_ppv_amount')) }}

              @if ($settings->wallet_format != 'real_money')
              <strong>({{Helper::equivalentMoney($settings->wallet_format)}})</strong>
              @endif
            </small>
          </div>

          <div class="form-group">
            <label class="w-100 font_weight_500 fs-20">{{ __('general.add_file') }} ({{ __('general.optional') }})</label>

            @if ($settings->video_encoding == 'on')
            <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
              <i class="fa fa-info-circle mr-2"></i>
              {{ __('general.info_video_encode_welcome_msg') }}
            </div>
            @endif

            <input @if ($preloadedFile) data-fileuploader-files='{!! $preloadedFile !!}' @endif type="file" name="media" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3">
          </div>

          <div class="form-group">
            <label class="w-100 font_weight_500 fs-20 ">{{__('general.welcome_message_new_subs')}}</label>
            <textarea name="message" rows="5" cols="40" class="form-control textareaAutoSize brd-8">{{auth()->user()->welcome_message_new_subs ? auth()->user()->welcome_message_new_subs : old('welcome_message_new_subs') }}</textarea>
          </div>

          <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

        </form>
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection

@section('javascript')
<script src="{{ asset('js/fileuploader/fileuploader-welcome-msg.js') }}"></script>

@if (session('encode'))
<script type="text/javascript">
  swal({
    type: 'info',
    title: video_on_way,
    text: video_processed_info,
    confirmButtonText: ok
  });
</script>
@endif
@endsection