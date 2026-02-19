@extends('layouts.app')

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
      <div class="col-12 col-xl-6 d-flex flex-column justify-content-center auth-form-left resetpwd-form-left">
        <div class="d-block mb-4">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <h4 class="mb-0 auth-title">{{__('auth.reset_password')}}</h4>
        <small class="btn-block mt-2 pb-3 title_login">{{ __('auth.reset_pass_subtitle') }}</small>
        @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{url('password/reset')}}" id="passwordResetForm">
          @csrf
          <input type="hidden" name="token" value="{{$token}}">

          <div class="form-group mb-3 mt-3">
            <div class="mb-1">
              <span>{{__('auth.email')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
            </div>
          </div>

          <div class="form-group">
            <div class="mb-1">
              <span>{{__('general.new_password')}}</span>
            </div>
            <div class="input-group input-group-alternative" id="showHidePassword">
              <input name="password" type="password" class="form-control" required placeholder="{{__('auth.password')}}" oninput="checkStrength(this.value)">
              <div class="input-group-append">
                <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
              </div>
            </div>
            <div class="strength-bar">
              <div id="strengthFill" class="strength-fill"></div>
            </div>
            <div id="strengthText" class="strength-text">Very Weak</div>
            <div id="strengthHint" class="strength-hint">
              Please enter strong password include Capital Letters, Numbers and signs
            </div>
          </div>

          <div class="form-group">
            <div class="mb-1">
              <span>{{__('auth.confirm_password')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input name="password_confirmation" type="password" class="form-control" required placeholder="{{__('auth.confirm_password')}}">
            </div>
          </div>

          <div class="text-center">
            @if ($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('passwordResetForm', __('auth.reset_password'), ['data-size' => 'invisible', 'class' => 'btn btn-primary my-4 w-100']) !!}
            {!! NoCaptcha::renderJs() !!}
            @else
            <button type="submit" class="btn btn-primary my-4 w-100">{{__('auth.reset_password')}}</button>
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
