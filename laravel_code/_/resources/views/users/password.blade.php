@extends('layouts.app')

@section('title') {{__('general.password')}} -@endsection

@section('content')
<div class="jumbotron m-0 auth-shell">
  <div class="container pt-lg-md auth-shell-container">
    <div class="row auth-shell-row">
      <div class="col-12 col-xl-6 d-flex flex-column justify-content-center auth-form-left">
        <div class="d-block mb-4">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1" width="125" height="42" />
        </div>

        <h4 class="mb-0 auth-title">{{__('general.password')}} & {{__('auth.login')}}</h4>
        <small class="btn-block mt-2 pb-3 title_login">{{__('auth.update_your_password')}}</small>

        @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (session('incorrect_pass'))
        <div class="alert alert-danger">{{ session('incorrect_pass') }}</div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{ url('settings/password') }}">
          @csrf

          @if (auth()->user()->password != '')
          <div class="form-group">
            <div class="mb-1">
              <span>{{ __('general.old_password') }}</span>
            </div>
            <div class="input-group input-group-alternative mb-2">
              <input class="form-control" name="old_password" placeholder="{{__('general.enter_your_old_password')}}" type="password" required>
            </div>
          </div>
          @endif

          <div class="form-group">
            <div class="mb-1">
              <span>{{ __('general.new_password') }}</span>
            </div>
            <div class="input-group input-group-alternative mb-2" id="showHidePassword">
              <input class="form-control" name="new_password" placeholder="{{__('general.enter_your_new_password')}}" type="password" required oninput="checkStrength(this.value)">
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
              <span>{{ __('auth.confirm_password') }}</span>
            </div>
            <div class="input-group input-group-alternative mb-4">
              <input class="form-control" name="confirm_password" placeholder="{{ __('general.enter_your_new_password') }}" type="password" required>
            </div>

            @error('confirm_password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>

          <button class="btn btn-primary btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>
        </form>
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
