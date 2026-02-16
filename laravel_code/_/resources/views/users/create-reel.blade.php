@extends('layouts.app')

@section('title') {{ __('general.create_reel') }} -@endsection

@section('css')

<style type="text/css">

  .fileuploader { display:block; padding: 0; }

  .fileuploader-items-list {margin: 10px 0 0 0;}

  .fileuploader-theme-dragdrop .fileuploader-input {

    background: {{ auth()->user()->dark_mode == 'on'? '#222' : '#fff' }};  

  }
  [data-bs-theme="dark"] .light_mode_form {
      background-color: #111 !important;
    }

</style>

@endsection

@section('content')
<section class="section section-sm">

    <div class="container-fluid">

      <div class="row justify-content-center text-center mb-sm">

        <div class="col-lg-12 py-5">

          <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">

            {{ __('general.create_reel') }}

          </h2>

          <p class="lead mt-0 font_weight_400 fs-14">

            {{ __('general.create_reel_subtitle') }}

        </p>

        </div>

      </div>

      <div class="row justify-content-center">

        <div class="col-lg-7">

            <form action="{{ url()->current() }}" method="post" enctype="multipart/form-data" id="addReelForm">

              @csrf

              <input type="hidden" name="duration" id="videoDurarion" value="">

              <input type="hidden" name="video_thumbnail" id="videoThumbnail" value="">

              <div class="form-group">

                <input type="file" name="media" accept="video/mp4,video/x-m4v,video/quicktime">

              </div>

              <div class="form-group mb-4">

                <input type="text" class="form-control" name="title" id="title" placeholder="{{ __('general.title') }} ({{ __('general.optional') }})">

              </div>

              <div class="form-group mb-4">

                  <select name="type" class="form-control custom-select light_mode_form">

                    <option value="private" selected>{{ __('general.available_only_for_subscribers') }}</option>

                    <option value="public">{{ __('general.available_everyone') }}</option>

                  </select>

                </div>

              <!-- Alert -->

            <div class="alert alert-danger my-3 display-none" id="errorCreateReel">

               <ul class="list-unstyled m-0" id="showErrorsCreateReel"><li></li></ul>

             </div><!-- Alert -->

              <button class="btn btn-1 btn-primary btn-block" id="createReelBtn" type="submit"><i></i> {{ __('users.create') }}</button>

            </form>

        </div><!-- end col-md-12 -->

      </div>

    </div>

  </section>

@endsection

@section('javascript')

  <script src="{{ asset('js/fileuploader/fileuploader-reel-file.js') }}?v={{ $settings->version }}"></script> 

  <script src="{{ asset('js/reels/create-reel.js') }}?v={{ $settings->version }}"></script>

@endsection

