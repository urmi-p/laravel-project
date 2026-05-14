@extends('layouts.app')

@section('title') {{__('auth.sign_up')}} -@endsection

@section('content')
<div class="jumbotron m-0 auth-shell">
  <div class="container pt-lg-md auth-shell-container">
    <div class="row auth-shell-row">
      <div class="col-12 col-xl-6 login-form-left auth-form-left">
        <div class="d-block mb-4">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <h4 class="auth-title mb-2">{{__('auth.welcome_back')}}</h4>
        <small class="btn-block pb-4 title_login">{{ __('auth.signup_welcome') }}</small>

        @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        @if($settings->apple_login == 'on' || $settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
        <div class="mb-2 w-100">
          @if ($settings->apple_login == 'on')
          <a href="{{url('oauth/apple')}}" class="btn btn-apple auth-form-btn mb-2 w-100">
            <i class="fab fa-apple mr-2"></i> {{ __('auth.sign_up_with') }}<span class="auth-provider-name">{{ __('general.apple') }}</span>
          </a>
          @endif

          @if ($settings->google_login == 'on')
          <a href="{{url('oauth/google')}}" class="btn btn-google auth-form-btn mb-2 w-100">
            <img src="{{ url('img/google.svg') }}" class="mr-2" width="18" height="18"> {{ __('auth.sign_up_with') }}<span class="auth-provider-name">{{ __('general.google') }}</span>
          </a>
          @endif

          @if ($settings->facebook_login == 'on')
          <a href="{{url('oauth/facebook')}}" class="btn btn-facebook auth-form-btn mb-2 w-100">
            <i class="fab fa-facebook mr-2"></i> {{ __('auth.sign_up_with') }}<span class="auth-provider-name">{{ __('general.facebook') }}</span>
          </a>
          @endif

          @if ($settings->twitter_login == 'on')
          <a href="{{url('oauth/twitter')}}" class="btn btn-twitter auth-form-btn mb-2 w-100">
            <i class="bi-twitter-x mr-2"></i> {{ __('auth.sign_up_with') }}<span class="auth-provider-name">{{ __('general.twitter') }}</span>
          </a>
          @endif
        </div>

        @if (! $settings->disable_login_register_email)
        <small class="btn-block text-center my-3 login-form-or">{{__('general.or')}}</small>
        @endif
        @endif

        @if (! $settings->disable_login_register_email)
        <form method="POST" action="{{ route('register') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister">
          @csrf

          <div class="form-group mb-3">
            <div class="mb-1">
              <span>{{__('auth.full_name')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control" value="{{ old('name')}}" placeholder="{{__('auth.full_name')}}" name="name" type="text" required>
            </div>
          </div>

          <div class="form-group mb-3">
            <div class="mb-1">
              <span>{{__('auth.email')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" type="text" required>
            </div>
          </div>

          <div class="form-group">
            <div class="mb-1">
              <span>{{__('auth.password')}}</span>
            </div>
            <div class="input-group input-group-alternative" id="showHidePassword">
              <input name="password" type="password" class="form-control" placeholder="{{__('auth.password')}}" required>
              <div class="input-group-append">
                <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
              </div>
            </div>
          </div>

          <div class="custom-control custom-control-alternative custom-checkbox mt-3">
            <input class="custom-control-input" id="customCheckRegister" type="checkbox" name="agree_gdpr" required>
            <label class="custom-control-label" for="customCheckRegister">
              <span>
                {{__('admin.i_agree_gdpr')}}
                <a href="{{$settings->link_terms}}" target="_blank">{{__('admin.terms_conditions')}}</a>
                {{ __('general.and') }}
                <a href="{{$settings->link_privacy}}" target="_blank">{{__('admin.privacy_policy')}}</a>
              </span>
            </label>
          </div>

          <div class="alert alert-danger display-none mb-0 mt-3" id="errorLogin">
            <ul class="list-unstyled m-0" id="showErrorsLogin"></ul>
          </div>

          <div class="alert alert-success mb-0 mt-3 display-none" id="checkAccount"></div>

          @if ($settings->captcha == 'on')
          <div class="d-flex justify-content-center mt-3">
            {!! NoCaptcha::display() !!}
          </div>
          {!! NoCaptcha::renderJs() !!}
          @endif

          <div class="text-center">
            <button type="submit" class="btn btn-primary mt-4 w-100" id="btnLoginRegister"><i></i> {{__('auth.sign_up')}}</button>
          </div>
        </form>

        @if ($settings->captcha == 'on')
        <small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
        @endif

        <div class="text-center auth-switch mt-3">
          <span>{{__('auth.already_have_an_account')}}</span>
          <a href="{{url('login')}}" class="text-red text-capitalize ml-1">{{__('auth.sign_in')}}</a>
        </div>
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
