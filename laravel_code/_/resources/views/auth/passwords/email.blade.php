@extends('layouts.app')

@section('title') {{__('auth.password_recover')}} -@endsection

@section('css')
<script type="text/javascript">
  var error_scrollelement = {
    {
      count($errors) > 0 ? 'true' : 'false'
    }
  };
</script>
@endsection

@section('content')

<div class="jumbotron m-0 auth-shell">
  <div class="container pt-lg-md auth-shell-container">
    <div class="row auth-shell-row">
      <div class="col-12 col-xl-6 d-flex flex-column justify-content-center auth-form-left forgotpwd-form-left">
        <div class="d-flex align-items-center mb-4">
          <a href="{{ url()->previous() }}" class="text-light d-flex align-items-center mr-3">
            <small><i class="fas fa-arrow-left"></i></small>
          </a>
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <h4 class="auth-title mb-2">{{__('auth.password_recover')}}</h4>
        <small class="btn-block pb-4 title_login">{{ __('auth.recover_pass_subtitle') }}</small>

        @if (session('status'))
        <div class="alert alert-success">
          {{{ session('status') }}}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{ route('password.email') }}" id="passwordEmailForm">
          @csrf
          <div class="form-group mb-3">
            <div class="mb-1">
              <span>{{__('auth.email')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control @if (count($errors) > 0) is-invalid @endif" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
            </div>
          </div>

          <div class="text-center">
            @if ($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('passwordEmailForm', __('auth.send_pass_reset'), ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}
            {!! NoCaptcha::renderJs() !!}
            @else
            <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.send_pass_reset')}}</button>
            @endif
          </div>
        </form>

        @if ($settings->captcha == 'on')
        <small class="btn-block text-center">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
        @endif
      </div>

      <div class="col-xl-6 right-side auth-right-panel d-none d-xl-block">
        <img src="{{url('img', $settings->logo)}}" class="img-center d-lg-block mt-3 auth-hero-logo" width="356" height="120" alt="{{$settings->title}}">
        <span class="h5 mb-5 d-lg-block title_home_login">{{__('general.title_home_login')}}</span>
        <div class="image-stack">
          <img src="{{url('img', $settings->home_index)}}" class="img-center img-fluid d-lg-block stack-img" alt="Hero">
        </div>
      </div>
    </div>
  </div>
</div>
  @endsection
