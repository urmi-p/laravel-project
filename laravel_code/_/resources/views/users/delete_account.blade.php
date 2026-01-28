@extends('layouts.app')

@section('title') {{__('general.delete_account')}} -@endsection

@section('content')
<section class="section section-sm">
  <div class="container-fluid">
    <div class="row mb-sm">
      <div class="col-lg-8 py-5">
        <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3"><i class="feather icon-user-x mr-2"></i> {{__('general.delete_account')}}</h2>
        <p class="lead mt-0 font_weight_400 fs-14">{{__('general.subtitle_delete_account')}}</p>
      </div>
    </div>
    <div class="row justify-content-center">

      <div class="col-md-7 mb-5 mb-lg-0">

        @if (session('incorrect_pass'))
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="bi bi-x"></i>
          </button>
          {{ session('incorrect_pass') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <div class="alert alert-warning" role="alert">
          <i class="fa fa-exclamation-triangle"></i> {{ __('general.notice_delete_account') }}
        </div>

        <form method="POST" id="formSend" action="{{ url()->current() }}">

          @csrf
          <div class="form-group">
            <div class="input-group mb-2 input-group-sub">
              <div class="input-group-prepend">
                <span class="input-sub-text"><i class="fa fa-lock"></i></span>
              </div>
              <input class="form-control" name="password" required placeholder="{{__('general.enter_password')}}" type="password" required>
            </div>
          </div>

          <button class="btn btn-1 btn-danger btn-block" id="buttonDeleteAccount" type="submit">{{__('general.delete_account')}}</button>

          <div class="text-center mt-3">
            <a href="{{ url('privacy/security') }}">{{ __('admin.cancel') }}</a>
          </div>
        </form>
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection