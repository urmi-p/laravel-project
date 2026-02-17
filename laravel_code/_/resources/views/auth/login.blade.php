@extends('layouts.app')

@section('title') {{__('auth.login')}} -@endsection

@section('content')
<div class="jumbotron m-0 auth-shell">
  <div class="container pt-lg-md auth-shell-container">
    <div class="row auth-shell-row">
      <div class="col-12 col-xl-6 login-form-left auth-form-left">
        <div class="d-block mb-4">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <h4 class="auth-title mb-2">{{__('auth.welcome_back')}}</h4>
        <small class="btn-block pb-4 title_login">{{ __('auth.login_welcome') }}</small>

        @if (session('login_required'))
        <div class="alert alert-danger" id="dangerAlert">
          <i class="fa fa-exclamation-triangle"></i> {{session('login_required')}}
        </div>
        @endif

        @if (session('error_social_login'))
        <div class="alert alert-danger" id="dangerAlert">
          <i class="fa fa-exclamation-triangle"></i> {{__('general.error')}} "{{ session('error_social_login') }}"
        </div>
        @endif

        @include('errors.errors-forms')

        @if ($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
        <div class="mb-2 w-100">
          @if ($settings->google_login == 'on')
          <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn mb-2 w-100">
            <img src="{{ url('img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.login_with') }} Google
          </a>
          @endif

          @if ($settings->facebook_login == 'on')
          <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn mb-2 w-100">
            <i class="fab fa-facebook mr-2"></i> {{ __('auth.login_with') }} Facebook
          </a>
          @endif

          @if ($settings->twitter_login == 'on')
          <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
            <i class="bi-twitter-x mr-2"></i> {{ __('auth.login_with') }} Twitter
          </a>
          @endif
        </div>

        @if (! $settings->disable_login_register_email)
        <small class="btn-block text-center my-3 login-form-or">{{__('general.or')}}</small>
        @endif
        @endif

        @if (! $settings->disable_login_register_email || request()->route()->named('login.admin'))
        <form method="POST" action="{{ route('login') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister" enctype="multipart/form-data">
          @csrf

          <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

          <div class="form-group mb-3" id="username_email">
            <div class="mb-1">
              <span>Email</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control" required value="{{ old('username_email') }}" placeholder="{{ __('auth.username_or_email') }}" name="username_email" type="text">
            </div>
          </div>

          <div class="form-group">
            <div class="mb-1">
              <span>{{ __('auth.password') }}</span>
            </div>
            <div class="input-group input-group-alternative" id="showHidePassword">
              <input name="password" required type="password" class="form-control" placeholder="{{ __('auth.password') }}">
              <div class="input-group-append">
                <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
              </div>
            </div>
          </div>

          <div class="custom-control custom-control-alternative custom-checkbox" id="remember">
            <div class="d-flex justify-content-between flex-nowrap align-items-center">
              <div>
                <input class="custom-control-input" id="customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="customCheckLogin">
                  <span>{{__('auth.remember_me')}}</span>
                </label>
              </div>
              <a href="{{url('password/reset')}}" id="forgotPassword">{{__('auth.forgot_password')}}</a>
            </div>
          </div>

          <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
            <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
          </div>

          <div class="text-center">
            @if ($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.login'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100']) !!}
            {!! NoCaptcha::renderJs() !!}
            @else
            <button id="btnLoginRegister" type="submit" class="btn btn-primary mt-4 w-100">
              <i></i> {{__('auth.login')}}
            </button>
            @endif
          </div>
        </form>

        @if ($settings->captcha == 'on')
        <small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
        @endif

        @if ($settings->registration_active == '1')
        <div class="text-center auth-switch mt-3">
          <span id="loginSpan">{{__('auth.not_have_account')}}</span>
          <a href="{{url('signup')}}" class="text-red text-capitalize ml-1">{{__('auth.sign_up')}}</a>
        </div>
        @endif
        @endif
      </div>

      <div class="col-xl-6 right-side auth-right-panel d-none d-xl-block">
        <img src="{{url('img', $settings->logo)}}" class="img-center d-lg-block mt-3 auth-hero-logo" width="356" height="120" alt="{{$settings->title}}">
        <span class="h5 mb-5 d-lg-block title_home_login">{{__('general.title_home_login')}}</span>
        <div class="image-stack">
          <img src="{{url('img', $settings->home_index)}}" class="img-center img-fluid d-lg-block img-login-background stack-img" alt="Hero">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
