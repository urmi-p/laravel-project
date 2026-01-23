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
<div class="jumbotron home m-0 bg-gradient">
  <div class="container pt-lg-md">
    <div class="row">
      <div class="col-lg-6 d-flex flex-column justify-content-center inline-padding resetpwd-form-left">
        <div class="d-block px-3 px-lg-5 w-100 px-mobile-1 ">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>
        <div class="card bg-white shadow border-0 b-radio-custom">

          <div class="card-body px-lg-5 py-lg-5">
            <h4 class=" mb-0 font-weight-bold reset_pass_title">
              {{__('auth.reset_password')}}
            </h4>
            <small class="btn-block mt-2 reset_pass_subtitle">{{ __('auth.reset_pass_subtitle') }}</small>
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
                <div class="">
                  <span class="">Email</span>
                </div>
                <div class="input-group input-group-alternative">
                  <input class="form-control" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
                </div>
              </div>

              <div class="form-group">
                <div class="">
                  <span class="">New Password</span>
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
                <div class="">
                  <span class="">Confirm New Password</span>
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
        </div>
      </div>
      <div class="col-lg-6 right-side">
        <img src="{{url('img', $settings->logo)}}" class="img-center d-lg-block d-none mt-3" width="356" height="120">
        <span class="text-lime h5 mb-5 d-lg-block d-none title_home_login">{{__('general.title_home_login')}}</span>
        <div class="image-stack">
          <img src="{{url('img', $settings->home_index)}}" class="img-center img-fluid d-lg-block d-none stack-img">
        </div>
      </div>
    </div>
  </div>
</div>
@endsection