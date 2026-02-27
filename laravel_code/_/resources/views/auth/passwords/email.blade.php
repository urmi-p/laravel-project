@extends('layouts.app')

@section('title') {{__('auth.password_recover')}} -@endsection

@section('css')
<script type="text/javascript">
  var error_scrollelement = {{ count($errors) > 0 ? 'true' : 'false' }};
</script>
@endsection

@section('content')

<div class="m-0 forgot-password-page">
  <div class="container forgot-password-container">
    <div class="row g-0 forgot-password-row">
      <div class="col-12 col-md-6 d-flex flex-column forgot-password-left">
        <div class="d-md-none d-flex align-items-center forgot-mobile-header">
          <a href="{{ url()->previous() }}" class="d-flex align-items-center text-decoration-none me-3 forgot-back-link">
            <small><i class="fas fa-arrow-left"></i></small>
          </a>
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="forgot-header-logo" />
        </div>

        <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="d-none d-md-block forgot-brand-logo" />

        <form method="POST" action="{{ route('password.email') }}" id="passwordEmailForm" class="forgot-password-form d-flex flex-column align-items-start">
          @csrf
          <div class="forgot-title-wrap d-flex flex-column align-items-start">
            <a href="{{ url()->previous() }}" class="d-none d-md-flex align-items-center text-decoration-none forgot-back-link">
              <small><i class="fas fa-arrow-left"></i></small>
            </a>
            <h4 class="mb-0 forgot-title">{{__('auth.password_recover')}}</h4>
            <small class="d-block forgot-subtitle">{{ __('auth.recover_pass_subtitle') }}</small>
          </div>

          @if (session('status'))
          <div class="alert alert-success w-100 mb-0">
            {{{ session('status') }}}
          </div>
          @endif

          @include('errors.errors-forms')

          <div class="mb-0">
            <div class="mb-1">
              <span class="form-label mb-0 forgot-label">{{__('auth.email')}}</span>
            </div>
            <div class="input-group forgot-input-wrap">
              <input class="form-control forgot-input @if (count($errors) > 0) is-invalid @endif" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" required type="text">
            </div>
          </div>

          <div>
            @if ($settings->captcha == 'on')
            {!! NoCaptcha::displaySubmit('passwordEmailForm', __('auth.send_pass_reset'), ['data-size' => 'invisible', 'class' => 'btn btn-primary forgot-submit-btn']) !!}
            {!! NoCaptcha::renderJs() !!}
            @else
            <button type="submit" class="btn btn-primary forgot-submit-btn">{{__('auth.send_pass_reset')}}</button>
            @endif
          </div>

          @if ($settings->captcha == 'on')
          <small class="d-block forgot-recaptcha">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>
          @endif
        </form>
      </div>

      <div class="col-md-6 d-none d-md-flex flex-column forgot-password-right">
        <img src="{{url('img', $settings->logo)}}" class="d-block forgot-hero-logo" alt="{{$settings->title}}">
        <span class="d-block forgot-hero-title">{{__('general.title_home_login')}}</span>
        <div>
          <img src="{{url('img', $settings->home_index)}}" class="img-fluid forgot-stack-img" alt="Hero">
        </div>
      </div>
    </div>
  </div>
</div>
  @endsection
