@extends('layouts.app')

@section('body_class', 'home-login-page dark-theme')


@section('content')

<div class="jumbotron m-0 auth-shell login-page-theme">
  <div class="container login-figma-container">
    <div class="row auth-shell-row g-0 align-items-stretch login-figma-row">
      <div class="col-12 col-md-6 col-lg-6 d-flex flex-column login-figma-left-col">
        <div class="d-block w-100 auth-logo-wrap">
          <img src="{{url('img', $settings->logo)}}" alt="{{$settings->title}}" class="logo align-baseline mb-1 login-figma-logo" width="125" height="42" />
        </div>
        <div class="login-figma-left-inner">
          <div class="card-body login-figma-card">
            <h4 class="auth-title mb-0">{{ __('auth.welcome_back') }}</h4>
            <small class="btn-block pb-4 h6 text-lime title_login login-figma-subtitle">{{ __('general.title_login') }}</small>

            @if (session('login_required'))
            <div class="alert alert-danger" id="dangerAlert">
              <i class="fa fa-exclamation-triangle"></i> {{session('login_required')}}
            </div>
            @endif
            @if ($settings->facebook_login == 'on' || $settings->google_login == 'on' || $settings->twitter_login == 'on')
            <div class="mb-2 w-100 login-figma-social">
              @if ($settings->google_login == 'on')
              <a href="{{url('oauth/google')}}" class="btn btn-google login-figma-social-btn mb-2 w-100">
                <img src="{{ url('img/google.svg') }}" class="mr-2" width="18" height="18"> <span>{{ __('auth.login_with') }} {{ __('general.google') }}</span>
              </a>
              @endif

              @if ($settings->facebook_login == 'on')
              <a href="{{url('oauth/facebook')}}" class="btn btn-facebook login-figma-social-btn mb-2 w-100">
                <i class="fab fa-facebook mr-2"></i> <span>{{ __('auth.login_with') }} {{ __('general.facebook') }}</span>
              </a>
              @endif
              @if ($settings->twitter_login == 'on')
              <a href="{{url('oauth/twitter')}}" class="btn btn-twitter login-figma-social-btn mb-2 w-100">
                <i class="bi-twitter-x mr-2"></i> <span>{{ __('auth.login_with') }} {{ __('general.twitter') }}</span>
              </a>
              @endif
            </div>
            @if (! $settings->disable_login_register_email)
            <small class="btn-block text-center my-3 text-text-capitalize login-form-or login-figma-or">{{__('general.or')}}</small>
            @endif
            @endif
            @if (! $settings->disable_login_register_email)

            <form method="POST" action="{{ route('login') }}" data-url-login="{{ route('login') }}" data-url-register="{{ route('register') }}" id="formLoginRegister" enctype="multipart/form-data">

              @csrf



              <input type="hidden" name="return" value="{{ count($errors) > 0 ? old('return') : url()->previous() }}">

              <div class="form-group mb-3 display-none login-figma-field" id="full_name">
                <div class="mb-1 login-figma-label-wrap">
                  <span class="login-figma-label">{{ __('auth.full_name1') }}</span>
                </div>
                <div class="input-group input-group-alternative login-figma-input-wrap">
                  <input class="form-control login-figma-input" value="{{ old('name')}}" placeholder="{{__('auth.full_name')}}" name="name" type="text" autocomplete="name">
                </div>
              </div>

              <div class="form-group mb-3 display-none login-figma-field" id="email">
                <div class="mb-1 login-figma-label-wrap">
                  <span class="login-figma-label">{{ __('auth.email') }}</span>
                </div>
                <div class="input-group input-group-alternative login-figma-input-wrap">
                  <input class="form-control login-figma-input" value="{{ old('email')}}" placeholder="{{__('auth.email')}}" name="email" type="text" autocomplete="email">
                </div>
              </div>

              <div class="form-group mb-3 login-figma-field" id="username_email">
                <div class="mb-1 login-figma-label-wrap">
                  <span class="login-figma-label">{{ __('auth.email') }}</span>
                </div>
                <div class="input-group input-group-alternative login-figma-input-wrap">
                  <input class="form-control login-figma-input" value="{{ old('username_email') }}" placeholder="{{ __('auth.username_or_email') }}" name="username_email" type="text" autocomplete="username">
                </div>
              </div>

              <div class="form-group login-figma-field">
                <div class="mb-1 login-figma-label-wrap">
                  <span class="login-figma-label">{{ __('general.password') }}</span>
                </div>
                <div class="input-group input-group-alternative login-figma-input-wrap" id="showHidePassword">
                  <input name="password" type="password" class="form-control login-figma-input" placeholder="{{ __('auth.password') }}" autocomplete="current-password">
                  <div class="input-group-append">
                    <span class="input-group-text c-pointer login-figma-pass-toggle"><i class="feather icon-eye-off"></i></span>
                  </div>
                </div>
              </div>



              <div class="form-group d-none">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-globe"></i></span>
                  </div>
                  <select name="countries_id" class="form-control custom-select">
                    <option value="">{{__('general.select_your_country')}}</option>
                    @foreach( Countries::orderBy('country_name')->get() as $country )
                    <option id="{{$country->country_code}}" value="{{$country->id}}">{{ $country->country_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="custom-control custom-control-alternative custom-checkbox login-figma-remember" id="remember">
                <div class="d-flex justify-content-between flex-nowrap login-figma-meta">
                  <input class="custom-control-input" id="customCheckLogin" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="custom-control-label" for="customCheckLogin">
                    <span>{{__('auth.remember_me')}}</span>
                  </label>
                  <a href="{{url('password/reset')}}" id="forgotPassword">
                    {{__('auth.forgot_password')}}
                  </a>

                </div>
              </div>


              <div class="custom-control custom-control-alternative custom-checkbox display-none login-figma-remember login-figma-gdpr" id="agree_gdpr">

                <input class="custom-control-input d2" id="customCheckRegister" type="checkbox" name="agree_gdpr">

                <label class="custom-control-label login-figma-gdpr-label" for="customCheckRegister">

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



              <div class="alert alert-success display-none mb-0 mt-3" id="checkAccount"></div>



              <div class="text-center">

                @if ($settings->captcha == 'on')

                {!! NoCaptcha::displaySubmit('formLoginRegister', '<i></i> '.__('auth.login'), ['data-size' => 'invisible', 'id' => 'btnLoginRegister', 'class' => 'btn btn-primary mt-4 w-100 login-figma-submit']) !!}

                {!! NoCaptcha::renderJs() !!}

                @else

                <button type="submit" id="btnLoginRegister" class="btn btn-primary mt-4 w-100 login-figma-submit"><i></i> {{__('auth.login')}}</button>

                @endif

              </div>

            </form>



            @if ($settings->captcha == 'on')

            <small class="btn-block text-center mt-3">{{__('auth.protected_recaptcha')}} <a href="https://policies.google.com/privacy" target="_blank">{{__('general.privacy')}}</a> - <a href="https://policies.google.com/terms" target="_blank">{{__('general.terms')}}</a></small>

            @endif



            @if ($settings->registration_active == '1')

            <div class="row mt-3 login-figma-switch-row">

              <div class="col-12 text-center">


                <span id="loginSpan">{{__('auth.not_have_account')}}</span>

                <a href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}" class="text-red text-capitalize login-figma-switch-link" data-text-signin="{{__('auth.sign_in')}}" data-text-signup="{{__('auth.register')}}">{{__('auth.register')}}</a>

              </div>

            </div>

            @endif



            @else
            <div class="row mt-3">

              <div class="col-12 text-center">

                <a href="javascript:void(0);" id="toggleLogin" data-not-account="{{__('auth.not_have_account')}}" data-already-account="{{__('auth.already_have_an_account')}}" data-text-login="{{__('auth.login')}}" data-text-register="{{__('auth.sign_up')}}">

                  <span>{{__('auth.not_have_account')}}</span>

                </a>

              </div>

            </div>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6 col-lg-6 d-none d-md-flex flex-column login-figma-right-col">
        <img src="{{url('img/main.png')}}" alt="User" class="img-fluid d-block login-figma-hero-logo" width="356" height="120">
        <span class="h5 mb-5 d-block login-figma-hero-title">{{__('general.title_home_login')}}</span>
        <div class="image-stack login-figma-hero-stack">
          <img src="{{url('img', $settings->home_index)}}" class="img-center img-fluid d-block img-login-background stack-img login-figma-stack-img" />
        </div>
      </div>
    </div>
  </div>
</div>



<!-- <div class="section py-5 py-large bg-gradient">

    <div class="container">

      <div class="row align-items-center">

      <div class="col-12 col-lg-5">

        <h1 class="m-0 card-profile text-white mb-3">{{__('general.header_box_3')}}</h1>

        <a href="javascript:void(0);" onclick="scrollToTop();" class="btn-arrow btn btn-lg btn-main btn-primary btn-w px-4 mr-2">

          {{__('general.getting_started')}}

        </a>

        <a href="javascript:void(0);" onclick="scrollToTop();" class="btn-arrow btn btn-lg btn-main btn-outline-light btn-w px-4">

          {{__('auth.login')}}

        </a>

      </div>

      <div class="col-12 col-lg-7 text-center mb-3">

        <img src="{{url('img', $settings->img_4)}}" alt="User" class="img-fluid">

      </div>

    </div>

    </div>

  </div> -->



<!-- @if ($settings->earnings_simulator == 'on') -->

<!-- Earnings simulator -->

<!-- <div class="section py-5 py-large bg-gradient text-white">

  <div class="container mb-4">

    <div class="btn-block text-center">

      <h1>{{__('general.earnings_simulator')}}</h1>

      <p>

        {{__('general.earnings_simulator_subtitle')}}

      </p>

    </div>

    <div class="row">

      <div class="col-md-6">

        <label for="rangeNumberFollowers" class="w-100">

          {{ __('general.number_followers') }}

          <i class="feather icon-facebook mr-1"></i>

          <i class="feather icon-twitter mr-1"></i>

          <i class="feather icon-instagram"></i>

          <span class="float-right">

            #<span id="numberFollowers">1000</span>

          </span>

        </label>

        <input type="range" class="custom-range" value="0" min="1000" max="1000000" id="rangeNumberFollowers" onInput="$('#numberFollowers').html($(this).val())">

      </div>



      <div class="col-md-6">

        <label for="rangeMonthlySubscription" class="w-100">{{ __('general.monthly_subscription_price') }}

          <span class="float-right">

            {{ $settings->currency_position == 'left' ? $settings->currency_symbol : null }}<span id="monthlySubscription">{{ $settings->min_subscription_amount }}</span>{{ $settings->currency_position == 'right' ? $settings->currency_symbol : null }}

        </span>

        </label>

        <input type="range" class="custom-range" value="0" onInput="$('#monthlySubscription').html($(this).val())" min="{{ $settings->min_subscription_amount }}" max="{{ $settings->max_subscription_amount }}" id="rangeMonthlySubscription">

      </div>



      <div class="col-md-12 text-center mt-4">

        <h3 class="font-weight-light">{{__('general.earnings_simulator_subtitle_2')}}

          <span class="font-weight-bold"><span id="estimatedEarn"></span> <small>{{$settings->currency_code}}</small></span>

          {{ __('general.per_month') }}*</h3>

        <p class="mb-1">

          * {{__('general.earnings_simulator_subtitle_3')}}

        </p>

        @if ($settings->fee_commission != 0)

          <small class="w-100 d-block">* {{__('general.include_platform_fee', ['percentage' => $settings->fee_commission])}}</small>

        @endif

      </div>

    </div>

  </div>

</div> -->

@endif



<!-- <div class="section py-5 py-large bg-gradient">

    <div class="container">

      <div class="row align-items-center">

      <div class="col-12 col-lg-5">

        <h1 class="m-0 card-profile text-white">{{__('custom.header_box_1')}}</h1>

        <div class="col-lg-9 col-xl-8 p-0">

          <p class="py-4 m-0 text-muted">{{__('custom.desc_box_1')}}</p>

        </div>

      </div>

      <div class="col-12 col-lg-7 text-center mb-3">

        <img src="{{url('img/img_custom_1.png')}}?v={{time()}}" alt="User" class="img-fluid">

      </div>

    </div>

    </div>

  </div> -->



<!-- <div class="section py-5 py-large bg-gradient">

    <div class="container">

      <div class="row align-items-center">

      <div class="col-12 col-lg-7 text-center mb-3">

        <img src="{{url('img/img_custom_2.png')}}?v={{time()}}" alt="User" class="img-fluid">

      </div>

      <div class="col-12 col-lg-5">

        <h1 class="m-0 card-profile text-white">{{__('custom.header_box_2')}}</h1>

        <div class="col-lg-9 col-xl-8 p-0">

          <p class="py-4 m-0 text-muted">{{__('custom.desc_box_2')}}</p>

        </div>

      </div>

    </div>

    </div>

  </div> -->



<!-- <div class="section py-5 py-large bg-gradient">

    <div class="container">

      <div class="row align-items-center">

      <div class="col-12 col-lg-5">

        <h1 class="m-0 card-profile text-white">{{__('custom.header_box_3')}}</h1>

        <div class="col-lg-9 col-xl-8 p-0">

          <p class="py-4 m-0 text-muted">{{__('custom.desc_box_3')}}</p>

        </div>

      </div>

      <div class="col-12 col-lg-7 text-center mb-3">

        <img src="{{url('img/img_custom_3.png')}}?v={{time()}}" alt="User" class="img-fluid">

      </div>

    </div>

    </div>

  </div> -->





@endsection



@section('javascript')

<script type="text/javascript">
  $.ajax({

    url: "https://geolocation-db.com/jsonp",

    jsonpCallback: "callback",

    dataType: "jsonp",

    success: function(location) {

      $('#' + location.country_code).attr('selected', 'selected');

    }

  });



  @if(session('success_verify'))

  swal({

    title: "{{ __('general.welcome') }}",

    text: "{{ __('users.account_validated') }}",

    type: "success",

    confirmButtonText: "{{ __('users.ok') }}"

  });

  @endif



  @if(session('error_verify'))

  swal({

    title: "{{ __('general.error_oops') }}",

    text: "{{ __('users.code_not_valid') }}",

    type: "error",

    confirmButtonText: "{{ __('users.ok') }}"

  });

  @endif



  function scrollToTop() {

    window.scrollTo({

      top: 0,

      behavior: 'smooth'

    });

  }



  @if($settings -> earnings_simulator == 'on')

  function decimalFormat(nStr)

  {

    @if($settings -> decimal_format == 'dot')

    var $decimalDot = '.';

    var $decimalComma = ',';

    @else

    var $decimalDot = ',';

    var $decimalComma = '.';

    @endif



    @if($settings -> currency_position == 'left')

    var currency_symbol_left = '{{$settings->currency_symbol}}';

    var currency_symbol_right = '';

    @else

    var currency_symbol_right = '{{$settings->currency_symbol}}';

    var currency_symbol_left = '';

    @endif



    nStr += '';

    var x = nStr.split('.');

    var x1 = x[0];

    var x2 = x.length > 1 ? $decimalDot + x[1] : '';

    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1)) {

      var x1 = x1.replace(rgx, '$1' + $decimalComma + '$2');

    }

    return currency_symbol_left + x1 + x2 + currency_symbol_right;

  }



  function earnAvg() {

    var fee = {
      {
        $settings -> fee_commission
      }
    };

    @if($settings -> currency_code == 'JPY')

    $decimal = 0;

    @else

    $decimal = 2;

    @endif



    var monthlySubscription = parseFloat($('#rangeMonthlySubscription').val());

    var numberFollowers = parseFloat($('#rangeNumberFollowers').val());



    var estimatedFollowers = (numberFollowers * 5 / 100)

    var followersAndPrice = (estimatedFollowers * monthlySubscription);

    var percentageAvgFollowers = (followersAndPrice * fee / 100);

    var earnAvg = followersAndPrice - percentageAvgFollowers;



    return decimalFormat(earnAvg.toFixed($decimal));

  }

  $('#estimatedEarn').html(earnAvg());



  $("#rangeNumberFollowers, #rangeMonthlySubscription").on('change', function() {



    $('#estimatedEarn').html(earnAvg());



  });

  @endif
</script>

@endsection
