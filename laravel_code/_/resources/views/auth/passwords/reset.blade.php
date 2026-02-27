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
<div class="jumbotron m-0 auth-shell reset-password-page">
  <div class="container auth-shell-container reset-password-container h-100">
    <div class="row auth-shell-row reset-password-row gx-0">
      <div class="col-12 col-md-6 col-xl-6 d-flex flex-column justify-content-center auth-form-left resetpwd-form-left reset-password-left">
        <div class="d-block mb-0 reset-logo-wrap">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <div class="reset-password-content w-100">
          <h4 class="mb-0 auth-title">Password &amp; login</h4>
          <small class="btn-block mt-2 pb-0 title_login">Use a strong, unique password to keep your account safe. You will be signed out from other devices when you change it.</small>
        </div>
        @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{url('password/reset')}}" id="passwordResetForm" class="reset-password-form d-flex flex-column">
          @csrf
          <input type="hidden" name="token" value="{{$token}}">

          <div class="form-group mb-0 mt-0">
            <div class="mb-1">
              <span>{{__('auth.email')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
            </div>
          </div>

          <div class="form-group mb-0">
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

          <div class="form-group mb-0">
            <div class="mb-1">
              <span>{{__('auth.confirm_password')}}</span>
            </div>
            <div class="input-group input-group-alternative">
              <input name="password_confirmation" type="password" class="form-control" required placeholder="{{__('auth.confirm_password')}}">
            </div>
          </div>

          <div class="text-center reset-submit-wrap">
            @if ($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('passwordResetForm', 'Save Changes', ['data-size' => 'invisible', 'class' => 'btn btn-primary w-100 reset-submit-btn']) !!}
            {!! NoCaptcha::renderJs() !!}
            @else
            <button type="submit" class="btn btn-primary w-100 reset-submit-btn">Save Changes</button>
            @endif
          </div>
        </form>

        @if ($settings->captcha == 'on')
        <small class="btn-block text-center">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
        @endif
      </div>

      <div class="col-12 col-md-6 col-xl-6 right-side auth-right-panel reset-password-right d-none d-md-flex flex-column">
        <img src="{{url('img', $settings->logo)}}" class="img-center d-lg-block mt-3 auth-hero-logo" width="356" height="120" alt="{{$settings->title}}">
        <span class="h5 mb-5 d-lg-block title_home_login">Join now and start making money with your content!</span>
        <div class="image-stack">
          <img src="{{url('img', $settings->home_index)}}" class="img-center img-fluid d-lg-block stack-img" alt="Hero">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
