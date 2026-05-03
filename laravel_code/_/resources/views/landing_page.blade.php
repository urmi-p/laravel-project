<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  @php
    $shareTitle = trim($settings->title) !== '' ? $settings->title : 'Close Only';
    $shareDescription = __('general.landing_hero_text');
    $shareImageUrl = route('social.share-image', ['v' => '6']);
  @endphp
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="{{ config('settings.theme_color_pwa') }}">
  <title>{{ auth()->check() && User::notificationsCount() ? '('.User::notificationsCount().') ' : '' }}{{ $shareTitle }}</title>
  @hasSection('social_meta')
    @yield('social_meta')
  @else
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="{{ $settings->title }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $shareTitle }}" />
    <meta property="og:description" content="{{ $shareDescription }}" />
    <meta property="og:image" content="{{ $shareImageUrl }}" />
    <meta property="og:image:secure_url" content="{{ $shareImageUrl }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $shareTitle }}" />
    <meta name="twitter:description" content="{{ $shareDescription }}" />
    <meta name="twitter:image" content="{{ $shareImageUrl }}" />
    <meta name="twitter:image:alt" content="{{ $settings->title }}" />
  @endif
<!-- Favicon -->
  <link href="{{ url('img', $settings->favicon) }}" rel="icon">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('css/styles.css') }}?v={{ $settings->version ?? time() }}" rel="stylesheet">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  @if ($settings->google_tag_manager_head != '')
  {!! $settings->google_tag_manager_head !!}
  @endif
  @if ($settings->status_pwa)
    @laravelPWA
  @endif
   @if ($settings->google_analytics != '')
  {!! $settings->google_analytics !!}
  @endif
</head>
<body class="landing-page">
  @if ($settings->google_tag_manager_body != '')
  {!! $settings->google_tag_manager_body !!}
  @endif
    <section class="hero">
        <div class="container">

            <!-- NAVBAR -->
            <nav class="landing-navbar navbar navbar-expand-lg navbar-dark">
                <a class="landing-navbar-brand navbar-brand" href="#">
                    <svg width="166" height="55" viewBox="0 0 166 55" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="165.5" height="54.1352" fill="url(#pattern0_6002_1885)"/>
                        <defs>
                        <pattern id="pattern0_6002_1885" patternContentUnits="objectBoundingBox" width="1" height="1">
                        <use xlink:href="#image0_6002_1885" transform="scale(0.000976562 0.00298551)"/>
                        </pattern>
                        <image id="image0_6002_1885" width="1024" height="335" preserveAspectRatio="none" xlink:href="{{ asset('img/landing-inline/landing-inline-01.png') }}"/>
                        </defs>
                    </svg>

                </a>

                <div class="landing-navbar-collapse collapse navbar-collapse">
                    <ul class="landing-navbar-nav navbar-nav mx-auto">
                    <li class="nav-item"><a class="landing-nav-link nav-link" href="#">{{ __('general.landing_home') }}</a></li>
                    <li class="nav-item"><a class="landing-nav-link nav-link" href="#">{{ __('general.landing_features') }}</a></li>
                    <li class="nav-item"><a class="landing-nav-link nav-link" href="#">{{ __('general.landing_reviews') }}</a></li>
                    <li class="nav-item"><a class="landing-nav-link nav-link" href="{{ url('contact') }}">{{ __('general.contact') }}</a></li>
                    </ul>
                </div>

                <a href="{{ route('guest.auth') }}" class="login-btn">{{ __('general.sign_in_or_sign_up') }}</a>
            </nav>

            <!-- HERO CONTENT -->
            <div class="row align-items-center">
                <div class="col-lg-6">

                    <h1 class="hero-title hero-title-mobile-lockup">
                        <span class="hero-title-line hero-title-line-1">{{ __('general.landing_hero_title_line_1') }}</span>
                        <span class="hero-title-line hero-title-line-2">{{ __('general.landing_hero_title_line_2') }} <span class="hero-title-highlight">{{ __('general.landing_hero_title_highlight') }}</span></span>
                    </h1>

                    <p class="hero-text">
                    {{ __('general.landing_hero_text') }}
                    </p>

                    <div class="store-buttons d-flex gap-3 mb-4">
                        <a href="#" class="store-btn text-decoration-none text-white" id="installWebAppButton" role="button">
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="bi bi-phone"></i>
                                <span id="installWebAppLabel">{{ __('general.install_web_app') }}</span>
                            </span>
                        </a>
                    </div>
                    <small class="text-neutral d-block mb-4" id="installWebAppHelp">
                        {{ __('general.install_web_app_help') }}
                    </small>

                    <!-- RATINGS -->
                    <div class="hero-metric d-flex align-items-center gap-3">
                        <div class="avatars d-flex">
                            <img src="https://i.pravatar.cc/40?1">
                            <img src="https://i.pravatar.cc/40?2">
                            <img src="https://i.pravatar.cc/40?3">
                            <img src="https://i.pravatar.cc/40?3">
                            <img src="https://i.pravatar.cc/40?3">
                        </div>
                        <div>
                            <small class="text-neutral">{{ __('general.landing_people_loved_app') }}</small>
                        </div>

                    </div>
                    <div class="hero-metric d-flex align-items-center gap-3">
                        <div>
                            <div class="stars">
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.51172 0L11.7569 6.90983H19.0223L13.1444 11.1803L15.3896 18.0902L9.51172 13.8197L3.63387 18.0902L5.87901 11.1803L0.00115395 6.90983H7.26658L9.51172 0Z" fill="#EEC75C"/>
                                </svg>
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.51172 0L11.7569 6.90983H19.0223L13.1444 11.1803L15.3896 18.0902L9.51172 13.8197L3.63387 18.0902L5.87901 11.1803L0.00115395 6.90983H7.26658L9.51172 0Z" fill="#EEC75C"/>
                                </svg>
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.51172 0L11.7569 6.90983H19.0223L13.1444 11.1803L15.3896 18.0902L9.51172 13.8197L3.63387 18.0902L5.87901 11.1803L0.00115395 6.90983H7.26658L9.51172 0Z" fill="#EEC75C"/>
                                </svg>
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.51172 0L11.7569 6.90983H19.0223L13.1444 11.1803L15.3896 18.0902L9.51172 13.8197L3.63387 18.0902L5.87901 11.1803L0.00115395 6.90983H7.26658L9.51172 0Z" fill="#EEC75C"/>
                                </svg>
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.51172 0L11.7569 6.90983H19.0223L13.1444 11.1803L15.3896 18.0902L9.51172 13.8197L3.63387 18.0902L5.87901 11.1803L0.00115395 6.90983H7.26658L9.51172 0Z" fill="#EEC75C"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div>
                            <small class="text-neutral">{{ __('general.landing_store_rating_text') }}</small>
                        </div>
                    </div>
                </div>

                <!-- HERO MEDIA GRID -->
                <div class="col-lg-6 position-relative">
                    <div class="phones">
                        <div class="hero-media-grid" aria-label="Featured creator previews">
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-1.jpg') }}" alt="Featured creator preview one">
                            </div>
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-2.jpg') }}" alt="Featured creator preview two">
                            </div>
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-3.jpg') }}" alt="Featured creator preview three">
                            </div>
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-4.jpg') }}" alt="Featured creator preview four">
                            </div>
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-5.jpg') }}" alt="Featured creator preview five">
                            </div>
                            <div class="hero-media-card">
                                <img src="{{ asset('img/Web Page Design-6.jpg') }}" alt="Featured creator preview six">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $featureRows = [
                    [
                        [
                            'type' => 'emoji',
                            'icon' => '🤝',
                            'label' => "Anonymity\nGuaranteed",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🔐',
                            'label' => "RPG-\nFriendly",
                        ],
                        [
                            'type' => 'whatsapp',
                            'label' => "24/7 support\nvia WhatsApp",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '👱‍♀️',
                            'label' => "100%\nFemale Staff",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🔑',
                            'label' => "Free Anti-Leak\nProtection",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🔥',
                            'label' => 'Reduced Commission',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '📈',
                            'label' => 'Detailed Statistics',
                        ],
                    ],
                    [
                        [
                            'type' => 'emoji',
                            'icon' => '📱',
                            'label' => "Mobile\nApplication",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🏖️',
                            'label' => 'No Bank Fees',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🗓️',
                            'label' => 'Exclusive Events',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '📣',
                            'label' => 'Featured Offer',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '👀',
                            'label' => "5% Lifetime\nAffiliate Bonus",
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🛟',
                            'label' => 'Human Support',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '🇫🇷',
                            'label' => 'French Platform',
                        ],
                        [
                            'type' => 'emoji',
                            'icon' => '✅',
                            'label' => 'Certified Agencies',
                        ],
                    ],
                ];
            @endphp

            <div class="feature-marquee" aria-label="{{ __('general.landing_features') }}">
                @foreach ($featureRows as $rowIndex => $rowCards)
                    <div class="feature-marquee__row{{ $rowIndex === 1 ? ' is-reverse' : '' }}">
                        <div class="feature-marquee__track">
                            @for ($duplicate = 0; $duplicate < 2; $duplicate++)
                                <div class="feature-marquee__group" @if ($duplicate === 1) aria-hidden="true" @endif>
                                    @foreach ($rowCards as $card)
                                        <article class="feature-marquee__card">
                                            <div class="feature-marquee__icon-wrap">
                                                @if ($card['type'] === 'whatsapp')
                                                    <svg class="feature-marquee__icon feature-marquee__icon--whatsapp" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                        <path d="M18.497 4.409a10 10 0 0 1 -10.36 16.828l-.223 -.098l-4.759 .849l-.11 .011a1 1 0 0 1 -.11 0l-.102 -.013l-.108 -.024l-.105 -.037l-.099 -.047l-.093 -.058l-.014 -.011l-.012 -.007l-.086 -.073l-.077 -.08l-.067 -.088l-.056 -.094l-.034 -.07l-.04 -.108l-.028 -.128l-.012 -.102a1 1 0 0 1 0 -.125l.012 -.1l.024 -.11l.045 -.122l1.433 -3.304l-.009 -.014a10 10 0 0 1 1.549 -12.454l.215 -.203a10 10 0 0 1 13.226 -.217m-8.997 3.09a1.5 1.5 0 0 0 -1.5 1.5v1a6 6 0 0 0 6 6h1a1.5 1.5 0 0 0 0 -3h-1l-.144 .007a1.5 1.5 0 0 0 -1.128 .697l-.042 .074l-.022 -.007a4.01 4.01 0 0 1 -2.435 -2.435l-.008 -.023l.075 -.041a1.5 1.5 0 0 0 .704 -1.272v-1a1.5 1.5 0 0 0 -1.5 -1.5"/>
                                                    </svg>
                                                @else
                                                    <span class="feature-marquee__emoji" aria-hidden="true">{{ $card['icon'] }}</span>
                                                @endif
                                            </div>
                                            <h3 class="feature-marquee__label">{!! nl2br(e($card['label'])) !!}</h3>
                                        </article>
                                    @endforeach
                                </div>
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>


    <section class="keypoints">
        <div class="container">

            <!-- TITLE -->
            <div class="text-center mb-5">
                <h2>{{ __('general.landing_key_points_title') }}</h2>
                <small>{{ __('general.landing_key_points_subtitle') }}</small>
            </div>

            <!-- TOP ROW -->
            <div class="key-row row position-relative g-4">

            <div class="landing-kp-col col-lg-6">
                <div class="kp-card">
                    <div class="kp-title kp-title-instant">
                        <span class="kp-title-instant-part">{{ __('general.landing_instant_profile_title_line_1') }}</span><br class="kp-title-instant-break"><span class="kp-title-instant-part kp-title-instant-part-2">{{ __('general.landing_instant_profile_title_line_2') }}</span>
                    </div>
                    <div class="kp-text">
                        {{ __('general.landing_instant_profile_text') }}
                    </div>

                    <div class="d-flex flex-column gap-2 mt-3">
                        <a href="{{ route('guest.auth') }}" class="btn btn-red">{{ __('general.lets_get_started') }}</a>
                        <a href="{{ route('guest.auth') }}" class="btn btn-white">{{ __('auth.login') }}</a>
                    </div>

                    <div class="phone">
                        <img src="{{ asset('img/image_index_4-1759780320.png') }}" alt="{{ __('general.landing_instant_profile_title_line_1') }}">
                    </div>
                </div>
            </div>

            <div class="landing-kp-col col-lg-6">
                <div class="kp-card">
                <div class="kp-title kp-title-mobile-two-line">
                    @if (app()->getLocale() === 'en')
                        <span class="mobile-title-line">Unlock exclusive content prenium 💥</span>
                    @else
                        {{ __('general.discover_exclusive_content_title') }}
                    @endif
                </div>
                <div class="kp-text ">
                    {{ __('general.private_space_reserved_for_subscribers') }}
                </div>

                 <div class="phone">
                        <img src="{{ asset('img/img_custom_3.png') }}" alt="{{ __('general.discover_exclusive_content_title') }}">
                    </div>
                </div>
            </div>

            <div class="kp-divider d-none d-lg-block"></div>
            </div>

            <!-- BOTTOM CARD -->
            <div class="kp-wide">
            <div class="row align-items-center">

                <div class="col-lg-6">
                <h1 class="fw-bold mb-3 kp-wide-title">
                    <span class="kp-wide-title-line">Enjoy exclusive interactions</span>
                    <span class="kp-wide-title-line">like never before</span>
                </h1>
                <p class="">
Step into a private space where your connection with creators reaches a whole new level. More than just access to content, Close Only offers a fully immersive and personalized experience designed to bring you closer to the creators you follow. Discover exclusive content, interact directly with creators, and access unique, authentic moments reserved for a privileged community. Every interaction becomes more personal, more direct, and far more memorable.
                </p>
                </div>

                <div class="col-lg-6">
                    <div class="phones">
                         <img src="{{ asset('img/img_custom_1.png') }}" alt="Enjoy exclusive interactions">
                    
                    </div>
                </div>

            </div>
            </div>

        </div>
    </section>

<!-- HERO SECTION -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <!-- Left -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1 class="hero-title hero-title-mobile-two-line">
                    @if (app()->getLocale() === 'en')
                        <span class="mobile-title-line">Enter a universe</span> <span class="mobile-title-line">built for connection</span>
                    @else
                        {{ __('general.landing_creator_section_title') }}
                    @endif
                </h1>
                <p class="hero-text">
                {{ __('general.landing_creator_section_text') }}
                </p>

                <div class="d-flex gap-3 mt-4">
                    <a href="{{ route('guest.auth') }}" class="btn btn-primary-custom">{{ __('general.lets_get_started') }}</a>
                    <a href="{{ route('guest.auth') }}" class="btn btn-outline-custom">{{ __('auth.login') }}</a>
                </div>
            </div>

            <!-- Right (Image Placeholder) -->
            <div class="col-lg-6 text-center">
 <img src="{{ asset('img/img_custom_2.png') }}" alt="{{ __('general.landing_creator_section_title') }}">
                    
            </div>

        </div>
    </div>
</section>

<!-- FAQ -->
<div class="container">
    <h2 class="faq-title">{{ __('general.faq') }}</h2>

    <div class="accordion" id="faqAccordion">

        <!-- Item 1 -->
        <div class="faq-item">
            <button class="faq-btn" data-bs-toggle="collapse" data-bs-target="#faq1">
                <span>{{ __('general.landing_faq_question_1') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq1" class="collapse show" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_1') }}
                </div>
            </div>
        </div>

        <!-- Item 2 -->
        <div class="faq-item">
            <button class="faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#faq2">
                <span>{{ __('general.landing_faq_question_2') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq2" class="collapse" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_2') }}
                </div>
            </div>
        </div>

        <!-- Item 3 -->
        <div class="faq-item">
            <button class="faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#faq3">
                <span>{{ __('general.landing_faq_question_3') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq3" class="collapse" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_3') }}
                </div>
            </div>
        </div>

    
        <!-- Item 4 -->
        <div class="faq-item">
            <button class="faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#faq4">
                <span>{{ __('general.landing_faq_question_4') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq4" class="collapse" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_4') }}
                </div>
            </div>
        </div>

        <!-- Item 5 -->
        <div class="faq-item">
            <button class="faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#faq5">
                <span>{{ __('general.landing_faq_question_5') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq5" class="collapse" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_5') }}
                </div>
            </div>
        </div>

        <!-- Item 6 -->
        <div class="faq-item">
            <button class="faq-btn collapsed" data-bs-toggle="collapse" data-bs-target="#faq6">
                <span>{{ __('general.landing_faq_question_6') }}</span>
                <span class="faq-icon">
                    <i class="bi bi-plus"></i>
                    <i class="bi bi-dash"></i>
                </span>
            </button>

            <div id="faq6" class="collapse" data-bs-parent="#faqAccordion">
                <div class="faq-body">
                    {{ __('general.landing_faq_answer_6') }}
                </div>
            </div>
        </div>
</div>
</div>

<script>
  (function () {
    var installButton = document.getElementById('installWebAppButton');
    var installLabel = document.getElementById('installWebAppLabel');
    var installHelp = document.getElementById('installWebAppHelp');
    var deferredPrompt = null;
    var installText = {
      button: @json(__('general.install_web_app')),
      installed: @json(__('general.installed')),
      installedHelp: @json(__('general.install_web_app_installed_help')),
      defaultHelp: @json(__('general.install_web_app_help')),
      promptHelp: @json(__('general.install_web_app_prompt_help')),
      promptMissingHelp: @json(__('general.install_web_app_prompt_missing_help')),
      iosButton: @json(__('general.add_to_home_screen')),
      iosHelp: @json(__('general.add_to_home_screen_help')),
      browserHelp: @json(__('general.install_web_app_browser_help'))
    };

    if (!installButton || !installLabel || !installHelp) {
      return;
    }

    function isStandalone() {
      return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    }

    function isIos() {
      return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
    }

    function isSafari() {
      var ua = window.navigator.userAgent;
      return /safari/i.test(ua) && !/chrome|crios|android/i.test(ua);
    }

    function setInstallState(label, helpText, hidden) {
      installLabel.textContent = label;
      installHelp.textContent = helpText;
      installButton.hidden = !!hidden;
    }

    if (isStandalone()) {
      setInstallState(installText.installed, installText.installedHelp, false);
      installButton.classList.add('disabled');
      installButton.setAttribute('aria-disabled', 'true');
      return;
    }

    setInstallState(installText.button, installText.defaultHelp, false);

    window.addEventListener('beforeinstallprompt', function (event) {
      event.preventDefault();
      deferredPrompt = event;
      setInstallState(installText.button, installText.promptHelp, false);
    });

    installButton.addEventListener('click', async function (event) {
      event.preventDefault();

      if (deferredPrompt) {
        deferredPrompt.prompt();

        try {
          await deferredPrompt.userChoice;
        } catch (error) {
        }

        deferredPrompt = null;
        setInstallState(installText.button, installText.promptMissingHelp, false);
        return;
      }

      if (isIos() && isSafari()) {
        setInstallState(installText.iosButton, installText.iosHelp, false);
        return;
      }

      setInstallState(installText.button, installText.browserHelp, false);
    });
  })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @include('includes.footer')
</body>
</html>
