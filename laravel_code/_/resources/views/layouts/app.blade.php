<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no" class="notranslate" data-bs-theme="{{ auth()->check() ? (auth()->user()->dark_mode == 'on' ? 'dark' : 'light') : 'dark' }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
  <meta name="google" content="notranslate">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif">
  <meta name="keywords" content="@yield('keywords_custom'){{ trans('seo.keywords') }}" />
  <meta name="theme-color" content="{{ config('settings.theme_color_pwa') }}">
  <title>{{ auth()->check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}@section('title')@show {{$settings->title.' - '.__('seo.slogan')}}</title>
  @hasSection('social_meta')
    @yield('social_meta')
  @else
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $settings->title }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ trim($__env->yieldContent('title')) !== '' ? trim($__env->yieldContent('title')) . ' ' . $settings->title . ' - ' . __('seo.slogan') : $settings->title . ' - ' . __('seo.slogan') }}" />
    <meta property="og:description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif" />
    <meta property="og:image" content="{{ route('social.share-image', ['v' => '6']) }}" />
    <meta property="og:image:secure_url" content="{{ route('social.share-image', ['v' => '6']) }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ trim($__env->yieldContent('title')) !== '' ? trim($__env->yieldContent('title')) . ' ' . $settings->title . ' - ' . __('seo.slogan') : $settings->title . ' - ' . __('seo.slogan') }}" />
    <meta name="twitter:description" content="@yield('description_custom')@if(!Request::route()->named('seo') && !Request::route()->named('profile')){{trans('seo.description')}}@endif" />
    <meta name="twitter:image" content="{{ route('social.share-image', ['v' => '6']) }}" />
    <meta name="twitter:image:alt" content="{{ $settings->title }}" />
  @endif
  <!-- Favicon -->
  <link href="{{ url('img', $settings->favicon) }}" rel="icon">

  {{-- FONTS --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

  @if ($settings->google_tag_manager_head != '')
  {!! $settings->google_tag_manager_head !!}
  @endif

  @include('includes.css_general')

  @if ($settings->status_pwa)
    @laravelPWA
  @endif

  @yield('css')

 @if ($settings->google_analytics != '')
  {!! $settings->google_analytics !!}
  @endif
</head>

<body class="@yield('body_class') @auth app-auth-shell @if(auth()->user()->verified_id == 'yes') creator-mobile-shell @endif @endauth">
  @if ($settings->google_tag_manager_body != '')
  {!! $settings->google_tag_manager_body !!}
  @endif

  @if ($settings->disable_banner_cookies == 'off')
  <div class="btn-block text-center showBanner padding-top-10 pb-3 display-none">
    <i class="fa fa-cookie-bite"></i> {{trans('general.cookies_text')}}
    @if ($settings->link_cookies != '')
      <a href="{{$settings->link_cookies}}" class="mr-2 text-white link-border" target="_blank">{{ trans('general.cookies_policy') }}</a>
    @endif
    <button class="btn btn-sm btn-primary" id="close-banner">{{trans('general.go_it')}}
    </button>
  </div>
@endif

  <div id="mobileMenuOverlay" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"></div>

  @auth
    @if (!request()->is('live/*'))
      @include('includes.menu-mobile')
    @endif
  @endauth

  @if (auth()->guest() && $settings->alert_adult == 'on' && !$settings->age_verification_status)
    <div class="modal fade" tabindex="-1" id="alertAdult">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body p-4">
            <p>{{ __('general.alert_content_adult') }}</p>
          </div>
          <div class="modal-footer border-0 pt-0">
            <a href="https://google.com" class="btn e-none p-0 mr-3">{{trans('general.leave')}}</a>
            <button type="button" class="btn btn-primary" id="btnAlertAdult">{{trans('general.i_am_age')}}</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if (auth()->check() && !request()->is('guest/auth') && session('show_age_verification_after_register') && $settings->age_verification_status && $settings->show_modal_age_verification)
    <div class="modal fade" tabindex="-1" id="alertAgeVerification">
      <div class="modal-dialog">
        <div class="modal-content text-center">
          <div class="modal-body pt-4 px-4 pb-0">
            <h2><i class="fa fa-exclamation-triangle mb-2 text-warning"></i></h2>
            <h4>
              {{ __('general.alert_age_verification_title') }}
            </h4>
            <p>{{ __('general.alert_age_verification') }}</p>
          </div>
          <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
            <a href="{{ route('verify.age') }}" class="btn btn-primary">
            {{__('general.start_age_verification')}}
          </a>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if (
    auth()->check()
    && !request()->is('guest/auth')
    && session('show_language_after_register')
    && $languages->count() > 1
    && !($settings->age_verification_status && $settings->show_modal_age_verification)
  )
    @include('includes.modal-language-preference')
  @endif


  <div class="popout popout-error font-default"></div>

@php
  $hideGlobalChrome = request()->is('login') || request()->is('signup') || request()->is('register') || request()->is('password/*');
  $isLiveRoute = request()->is('live/*');
@endphp

@if (!$hideGlobalChrome && !$isLiveRoute)
  @include('includes.navbar')
  @endif

  @if (!$hideGlobalChrome && !$isLiveRoute)
    @include('includes.header-mobile')
  @endif

  <main @if (request()->is('messages/*') || request()->is('live/*')) style="h-100" @endif role="main">
    @yield('content')

    @if (!$hideGlobalChrome && !$isLiveRoute)
      <div class="app-footer-shell">
        @if (auth()->guest() && $settings->who_can_see_content == 'users')
          <div class="text-center py-3 px-3">
            @include('includes.footer-tiny')
          </div>
        @else
          @include('includes.footer')
        @endif
      </div>
    @endif

  @guest

  @if (Helper::showLoginFormModal())
      @include('includes.modal-login')
    @endif

  @endguest

  @auth

    @if ($settings->disable_tips == 'off')
     @include('includes.modal-tip')
   @endif

   @if (auth()->user()->verified_id == 'yes')
     @include('includes.modal-creator-publish')
   @endif

   @if ($settings->gifts)
     @include('includes.modal-gifts')
   @endif

    @if (! request()->is('my/wallet'))
      @include('includes.modal-topup-wallet')
    @endif

    @include('includes.modal-payperview')

    @if ($settings->live_streaming_status == 'on')
      @include('includes.modal-live-stream')
    @endif

    @if ($settings->allow_scheduled_posts)
      @include('includes.modal-scheduled-posts')
    @endif

    @if ($settings->video_call_status)
      @include('includes.modal-video-call-incoming')
    @endif

    @if ($settings->audio_call_status)
      @include('includes.modal-audio-call-incoming')
    @endif

    @if ($settings->allow_vault)
      @include('includes.modal-vault')
    @endif
    
  @endauth

  @guest
    @include('includes.modal-2fa')
  @endguest
</main>

  @include('includes.javascript_general')

  @yield('javascript')
  
@auth
  <div id="bodyContainer"></div>
@endauth
</body>
</html>
