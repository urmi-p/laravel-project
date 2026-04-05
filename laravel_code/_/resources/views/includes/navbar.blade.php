<header class=" @if (request()->is('password/reset')) forgotpwd @endif">
    <style>
        @media (max-width: 767.98px) {
            .navbar-brand {
                margin-right: 0 !important;
            }
            .navbar-brand .logo {
                width: 92px !important;
                max-width: 92px !important;
                max-height: 30px !important;
            }
            .navbar-left {
                max-width: 96px;
                overflow: hidden;
            }
            .main_head_search {
                top: 16px !important;
                right: 8px !important;
                margin-right: 0 !important;
                gap: 3px !important;
                max-width: calc(100vw - 104px);
            }
            .main_head_search > .d-md-none > .btn_mobile_nav,
            .main_head_search > .d-md-none > a.btn_mobile_nav,
            .main_head_search > .d-md-none > a.btn-mobile-nav {
                width: 26px !important;
                height: 26px !important;
                min-width: 26px !important;
                min-height: 26px !important;
                padding: 0 !important;
                border-radius: 7px !important;
            }
            .main_head_search > .d-md-none > .btn_mobile_nav svg,
            .main_head_search > .d-md-none > a.btn_mobile_nav svg,
            .main_head_search > .d-md-none > a.btn-mobile-nav svg {
                width: 14px;
                height: 14px;
            }
            .main_head_search > .d-md-none > .btn_mobile_nav .wallet-icon,
            .main_head_search > .d-md-none > a.btn_mobile_nav .wallet-icon,
            .main_head_search > .d-md-none > a.btn-mobile-nav .wallet-icon {
                width: 22px;
                height: 22px;
                display: block;
                margin: 0 auto;
                transform: translateY(1px);
            }
        }
    </style>
    <nav
        class="navbar navbar-expand-md navbar-inverse modern-navbar site-header p-nav @if (auth()->guest() && request()->path() == '/') scroll @else p-3 @if (request()->is('live/*')) d-none @endif  @if (request()->is('messages/*')) shadow-sm @elseif(request()->is('messages')) shadow-sm @else shadow-custom @endif {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
        <div class="container-fluid d-flex align-items-center">

            <div class="d-flex justify-content-between">
                <div class="navbar-left d-flex align-items-center">
                    <a class="navbar-brand" href="{{ url('/') }}">

                        @if (auth()->check() && auth()->user()->dark_mode == 'on')
                            <img src="{{ url('img', $settings->logo) }}" data-logo="{{ $settings->logo }}"
                                data-logo-2="{{ $settings->logo_2 }}" alt="{{ $settings->title }}"
                                class="logo align-bottom max-w-100" />
                        @else
                            <img src="{{ url('img', auth()->guest() && request()->path() == '/' ? $settings->logo : $settings->logo_2) }}"
                                data-logo="{{ $settings->logo }}" data-logo-2="{{ $settings->logo_2 }}"
                                alt="{{ $settings->title }}" class="logo align-bottom max-w-100" />
                        @endif
                    </a>
                </div>
                @auth
                    <div>
                        <div class="position-absolute d-flex d-md-none main_head_search"
                            style="top: 25px; right: 35px;gap:6px;margin-right:20px">
                            <div class="d-md-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#mobileCreatorSearch"
                                    data-bs-toggle="collapse" data-bs-target="#mobileCreatorSearch" aria-controls="mobileCreatorSearch"
                                    aria-expanded="false" role="button">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.625 15.75C12.56 15.75 15.75 12.56 15.75 8.625C15.75 4.68997 12.56 1.5 8.625 1.5C4.68997 1.5 1.5 4.68997 1.5 8.625C1.5 12.56 4.68997 15.75 8.625 15.75Z"
                                            stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M16.5 16.5L15 15" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                            <div class="d-md-none">
                                <a class="topup-wallet btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    @if (! request()->is('my/wallet'))
                                        data-toggle="modal" data-target="#modalTopupWallet"
                                        data-bs-toggle="modal" data-bs-target="#modalTopupWallet"
                                    @else
                                        data-toggle="collapse" data-target="#navbarCollapse"
                                        data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                                    @endif
                                    aria-controls="navbarCollapse" aria-expanded="false" role="button">
                                    <svg class="wallet-icon" width="18" height="18" viewBox="0 0 18 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path
                                            d="M11.625 4.875C11.0967 4.49589 10.4629 4.29199 9.81262 4.29199C8.08312 4.29199 6.57597 5.73303 6.07422 7.5C5.95853 7.90756 5.95853 8.34244 6.07422 8.75C6.57597 10.517 8.08312 11.958 9.81262 11.958C10.4629 11.958 11.0967 11.7541 11.625 11.375"
                                            stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M5.625 7.125H10.5" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M5.625 9.125H10.125" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                            <div class="d-md-none">
                                <a href="{{ url('notifications') }}" class="position-relative btn_mobile_nav"
                                    title="{{ trans('general.notifications') }}">
                                    <span
                                        class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                        {{ auth()->user()->unseenNotifications() }}
                                    </span>
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M13.6044 8.24994C14.0402 12.2812 15.7539 13.4999 15.7539 13.4999H2.25391C2.25391 13.4999 4.50391 11.9002 4.50391 6.29994C4.50391 5.02719 4.97791 3.80619 5.82166 2.90619C6.66616 2.00619 7.81141 1.49994 9.00391 1.49994C9.25666 1.49994 9.50791 1.52244 9.75391 1.56744M10.3014 15.7499C10.1697 15.9774 9.98049 16.1663 9.75276 16.2976C9.52503 16.4289 9.26678 16.498 9.00391 16.498C8.74103 16.498 8.48278 16.4289 8.25505 16.2976C8.02732 16.1663 7.83811 15.9774 7.70641 15.7499M14.2539 5.99994C14.8506 5.99994 15.4229 5.76289 15.8449 5.34093C16.2669 4.91897 16.5039 4.34668 16.5039 3.74994C16.5039 3.1532 16.2669 2.58091 15.8449 2.15895C15.4229 1.73699 14.8506 1.49994 14.2539 1.49994C13.6572 1.49994 13.0849 1.73699 12.6629 2.15895C12.241 2.58091 12.0039 3.1532 12.0039 3.74994C12.0039 4.34668 12.241 4.91897 12.6629 5.34093C13.0849 5.76289 13.6572 5.99994 14.2539 5.99994Z"
                                            stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                </a>
                            </div>
                            <div class="d-md-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse"
                                    data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M3.42578 4.28571H17.1401" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" />
                                        <path d="M3.42578 10.2857H17.1401" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" />
                                        <path d="M3.42578 16.2857H17.1401" stroke="#A3A3A3" stroke-width="1.2"
                                            stroke-linecap="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>


            @guest

                <li class="nav-item mr-1">

                    <a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif
                        class="nav-link login-btn @if ($settings->registration_active == '0') btn btn-main btn-primary pr-3 pl-3 @endif"
                        href="{{ url('login') }}">

                        {{ __('auth.login') }}

                    </a>

                </li>
                
            @endguest

            @guest
                <button class="navbar-toggler @if (auth()->guest() && request()->path() == '/') text-white @endif" type="button"
                    data-toggle="collapse" data-target="#navbarCollapse"
                    data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
            @endguest
            @auth
                @if ((auth()->guest() && $settings->who_can_see_content == 'all') || auth()->check())
                    @if (!$settings->disable_creators_section)
                        @if (!$settings->disable_search_creators)
                            <div class="collapse d-md-none w-100 mt-2 mb-1" id="mobileCreatorSearch">
                                <form class="form-inline my-lg-0 position-relative" method="get"
                                    action="{{ url('creators') }}">

                                    <input id="searchCreatorNavbarMobile"
                                        class="form-control search-bar w-100 @if (auth()->guest() && request()->path() == '/') border-0 @endif"
                                        type="text" required name="q" autocomplete="off" minlength="3"
                                        placeholder="{{ __('general.search') }}" aria-label="Search">

                                    <button class="btn btn-outline-success my-sm-0 button-search e-none"
                                        type="submit"><i class="bi bi-search"></i></button>

                                    <div class="dropdown-menu dd-menu-user position-absolute"
                                        style="width: 95%; top: 48px;" id="dropdownCreatorsMobile">

                                        <button type="button" class="d-none" id="triggerBtnMobile" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false"></button>

                                        <div class="w-100 text-center display-none py-2" id="spinnerSearchMobile">

                                            <span
                                                class="spinner-border spinner-border-sm align-middle text-primary"></span>

                                        </div>
                                        <div id="containerCreatorsMobile"></div>

                                        <div id="viewAllMobile" class="display-none mt-2">

                                            <a class="dropdown-item border-top py-2 text-center"
                                                href="#">{{ __('general.view_all') }}</a>

                                        </div>

                                    </div><!-- dropdown-menu -->

                                </form>
                            </div>
                        @endif
                    @endif
                @endif
            @endauth
                <div class="justify-content-between collapse navbar-collapse navbar-mobile site-header-collapse" id="navbarCollapse">
                    <div class="d-md-none text-right pr-2 mb-2">

                        <button type="button" class="navbar-toggler close-menu-mobile" data-toggle="collapse"
                            data-target="#navbarCollapse" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    @if ((auth()->guest() && $settings->who_can_see_content == 'all') || auth()->check())

                        <ul class="navbar-nav site-header-search">
                            @if (!$settings->disable_creators_section)

                                @if (!$settings->disable_search_creators)
                                    <form class="form-inline my-lg-0 position-relative d-none d-md-flex" method="get"
                                        action="{{ url('creators') }}">

                                        <input id="searchCreatorNavbar"
                                            class="form-control search-bar @if (auth()->guest() && request()->path() == '/') border-0 @endif"
                                            type="text" required name="q" autocomplete="off" minlength="3"
                                            placeholder="{{ __('general.search') }}" aria-label="Search">

                                        <button class="btn btn-outline-success my-sm-0 button-search e-none"
                                            type="submit"><i class="bi bi-search"></i></button>

                                        <div class="dropdown-menu dd-menu-user position-absolute"
                                            style="width: 95%; top: 48px;" id="dropdownCreators">

                                            <button type="button" class="d-none" id="triggerBtn" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false"></button>

                                            <div class="w-100 text-center display-none py-2" id="spinnerSearch">

                                                <span
                                                    class="spinner-border spinner-border-sm align-middle text-primary"></span>

                                            </div>
                                            <div id="containerCreators"></div>



                                            <div id="viewAll" class="display-none mt-2">

                                                <a class="dropdown-item border-top py-2 text-center"
                                                    href="#">{{ __('general.view_all') }}</a>

                                            </div>

                                        </div><!-- dropdown-menu -->

                                    </form>
                                @endif

                            @endif
                        </ul>
                    @endif

                    <ul class="navbar-nav site-header-center d-none d-md-flex">
                        @auth
                            @if (auth()->user()->verified_id == 'yes')
                                <li class="nav-item dropdown">
                                    <a class="nav-link navbar_mid_link px-2 {{ request()->is('dashboard') ? 'font_bold' : 'font_normal' }}"
                                        href="{{ url('dashboard') }}" title="{{ __('admin.dashboard') }}">
                                        {{ __('admin.dashboard') }}
                                    </a>
                                </li>
                            @else
                                <li class="nav-item dropdown">
                                    <a class="nav-link navbar_mid_link px-2 {{ request()->is('/') ? 'font_bold' : 'font_normal' }}"
                                        href="{{ url('/') }}" title="{{ __('admin.dashboard') }}">
                                        {{ __('admin.dashboard') }}
                                    </a>
                                </li>
                            @endif
                        @endauth
                        @if (!$settings->disable_creators_section)
                            <li class="nav-item dropdown">
                                <a class="nav-link navbar_mid_link px-2 {{ request()->is('creators*') ? 'font_bold' : 'font_normal' }}"
                                    href="{{ url('creators') }}" title="{{ __('general.explore_creators') }}">
                                    {{ __('general.explore_creators') }}
                                </a>
                            </li>
                        @endif
                        @if ($settings->shop)
                            <li class="nav-item dropdown">
                                <a class="nav-link navbar_mid_link px-2 {{ request()->is('shop*') ? 'font_bold' : 'font_normal' }}"
                                    href="{{ url('shop') }}" title="{{ __('general.explore_shop') }}">
                                    {{ __('general.explore_shop') }}
                                </a>
                            </li>
                        @endif
                    </ul>

                    <ul class="navbar-nav site-header-actions">
                        {{-- need only for mobile sidebar --}}
                        @auth
                            <li class="sidebar-user mb-3 d-md-none">
                                <div class="user_avatar">
                                    <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                        alt="User Avatar">
                                </div>

                                <div class="user_info">
                                    <h6 class="mb-0">
                                        {{ Auth::user()->name }}
                                    </h6>
                                    <small>
                                        {{ Auth::user()->email }}
                                    </small>
                                    <a href="{{ url('profile', auth()->user()->username) }}" class="btn_profile"
                                        style="display: inline-block;">
                                        {{ __('general.my_profile') }}
                                    </a>
                                </div>
                            </li>
                            @if (auth()->user()->verified_id == 'yes')
                                <li class="sidebar-card d-md-none">
                                    <div class="card-icon">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div class="card-info">
                                        <small>{{__('general.earnings')}}</small>
                                        <strong>{{ Helper::amountFormatDecimal(auth()->user()->myPaymentsReceived()->sum('earning_net_user')) }}</strong>
                                    </div>
                                </li>
                            @endif

                            {{-- WALLET BALANCE --}}
                            <li class="sidebar-card d-md-none">
                                <div class="card-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M19 12C19 12.2652 18.8946 12.5196 18.7071 12.7071C18.5196 12.8946 18.2652 13 18 13C17.7348 13 17.4804 12.8946 17.2929 12.7071C17.1054 12.5196 17 12.2652 17 12C17 11.7348 17.1054 11.4804 17.2929 11.2929C17.4804 11.1054 17.7348 11 18 11C18.2652 11 18.5196 11.1054 18.7071 11.2929C18.8946 11.4804 19 11.7348 19 12Z"
                                            fill="currentcolor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.944 3.25H13.056C14.894 3.25 16.35 3.25 17.489 3.403C18.661 3.561 19.61 3.893 20.359 4.641C21.283 5.566 21.578 6.804 21.685 8.411C22.262 8.664 22.698 9.201 22.745 9.881C22.75 9.942 22.75 10.007 22.75 10.067V13.933C22.75 13.993 22.75 14.058 22.746 14.118C22.698 14.798 22.262 15.336 21.685 15.59C21.578 17.196 21.283 18.434 20.359 19.359C19.61 20.107 18.661 20.439 17.489 20.597C16.349 20.75 14.894 20.75 13.056 20.75H9.944C8.106 20.75 6.65 20.75 5.511 20.597C4.339 20.439 3.39 20.107 2.641 19.359C1.893 18.61 1.561 17.661 1.403 16.489C1.25 15.349 1.25 13.894 1.25 12.056V11.944C1.25 10.106 1.25 8.65 1.403 7.511C1.561 6.339 1.893 5.39 2.641 4.641C3.39 3.893 4.339 3.561 5.511 3.403C6.651 3.25 8.106 3.25 9.944 3.25ZM20.168 15.75H18.23C16.085 15.75 14.249 14.122 14.249 12C14.249 9.878 16.085 8.25 18.229 8.25H20.167C20.053 6.909 19.796 6.2 19.297 5.702C18.874 5.279 18.294 5.025 17.288 4.89C16.261 4.752 14.906 4.75 12.999 4.75H9.999C8.092 4.75 6.738 4.752 5.709 4.89C4.704 5.025 4.124 5.279 3.701 5.702C3.278 6.125 3.025 6.705 2.89 7.71C2.752 8.738 2.75 10.092 2.75 11.999C2.75 13.906 2.752 15.261 2.89 16.289C3.025 17.294 3.279 17.874 3.702 18.297C4.125 18.72 4.705 18.974 5.711 19.109C6.739 19.247 8.093 19.249 10 19.249H13C14.907 19.249 16.262 19.247 17.29 19.109C18.295 18.974 18.875 18.72 19.298 18.297C19.797 17.799 20.054 17.091 20.168 15.749M5.25 8C5.25 7.80109 5.32902 7.61032 5.46967 7.46967C5.61032 7.32902 5.80109 7.25 6 7.25H10C10.1989 7.25 10.3897 7.32902 10.5303 7.46967C10.671 7.61032 10.75 7.80109 10.75 8C10.75 8.19891 10.671 8.38968 10.5303 8.53033C10.3897 8.67098 10.1989 8.75 10 8.75H6C5.80109 8.75 5.61032 8.67098 5.46967 8.53033C5.32902 8.38968 5.25 8.19891 5.25 8ZM20.924 9.75H18.23C16.806 9.75 15.749 10.809 15.749 12C15.749 13.191 16.806 14.25 18.229 14.25H20.947C21.153 14.237 21.242 14.098 21.249 14.014V9.986C21.242 9.902 21.153 9.763 20.947 9.751L20.924 9.75Z"
                                            fill="currentcolor" />
                                    </svg>

                                </div>
                                <div class="card-info">
                                    <small>{{__('general.wallet_balance')}}</small>
                                    <strong>{{ Helper::userWallet() }}</strong>
                                </div>
                            </li>
                        @endauth
                        {{-- end for mobile sidebar --}}
                    </ul>

                    <ul class="navbar-nav">

                        @guest

                            <li class="nav-item mr-1">

                                <a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif
                                    class="nav-link login-btn @if ($settings->registration_active == '0') btn btn-main btn-primary pr-3 pl-3 @endif"
                                    href="{{ url('login') }}">

                                    {{ __('auth.login') }}

                                </a>

                            </li>
                            @if ($settings->registration_active == '1')
                                <li class="nav-item">

                                    <a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif
                                        class="toggleRegister nav-link btn btn-main @if (request()->path() == '/') btn-light @else btn-primary @endif btn-register-menu pr-3 pl-3 btn-arrow btn-arrow-sm"
                                        href="{{ url('signup') }}">

                                        {{ __('general.getting_started') }}

                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- ============ Menu Mobile ============-->
                            @if (auth()->user()->role == 'admin')
                                <li class="nav-item dropdown d-md-none mt-2">
                                    <a href="{{ url('panel/admin') }}" class="nav-link px-2 link-menu-mobile py-1">
                                        <div>
                                            <i class="bi-speedometer2 me-2 mr-2"></i>
                                            <span class="d-md-none">{{ __('admin.admin') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif

                            {{-- <li class="nav-item dropdown d-md-none @if (auth()->user()->role != 'admin') mt-2 @endif">
                                <a href="{{ url('profile', auth()->user()->username) }}"
                                    class="nav-link px-2 link-menu-mobile py-1 url-user">
                                    <div>
                                        <svg class="margin-right-4" width="19" height="19" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.0002 12.75C8.83016 12.75 6.25016 10.17 6.25016 7C6.25016 3.83 8.83016 1.25 12.0002 1.25C15.1702 1.25 17.7502 3.83 17.7502 7C17.7502 10.17 15.1702 12.75 12.0002 12.75ZM12.0002 2.75C9.66016 2.75 7.75016 4.66 7.75016 7C7.75016 9.34 9.66016 11.25 12.0002 11.25C14.3402 11.25 16.2502 9.34 16.2502 7C16.2502 4.66 14.3402 2.75 12.0002 2.75ZM20.5902 22.75C20.1802 22.75 19.8402 22.41 19.8402 22C19.8402 18.55 16.3202 15.75 12.0002 15.75C7.68016 15.75 4.16016 18.55 4.16016 22C4.16016 22.41 3.82016 22.75 3.41016 22.75C3.00016 22.75 2.66016 22.41 2.66016 22C2.66016 17.73 6.85016 14.25 12.0002 14.25C17.1502 14.25 21.3402 17.73 21.3402 22C21.3402 22.41 21.0002 22.75 20.5902 22.75Z"
                                                fill="currentcolor" />
                                        </svg>
                                        <span class="d-md-none">{{ __('users.my_profile') }}</span>
                                    </div>
                                </a>
                            </li> --}}

                            @if (auth()->user()->verified_id == 'yes')
                                <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                    <a href="{{ url('dashboard') }}"
                                        class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('dashboard')) active disabled @endif">
                                        <div>
                                            <svg class="margin-right-4" width="19" height="19" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13 9V3H21V9H13ZM3 13V3H11V13H3ZM13 21V11H21V21H13ZM3 21V15H11V21H3ZM5 11H9V5H5V11ZM15 19H19V13H15V19ZM15 7H19V5H15V7ZM5 19H9V17H5V19Z"
                                                    fill="white" />
                                            </svg>
                                            <span class="d-md-none">{{ __('admin.dashboard') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                <a href="{{ url('my/purchases') }}"
                                    class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('my/purchases')) active disabled @endif">
                                    <div>
                                        <i class="bi bi-bag-check mr-2"></i>
                                        <span class="d-md-none">{{ __('general.purchased') }}</span>
                                    </div>
                                </a>
                            </li>
                            {{-- <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                <a href="{{ url('messages') }}"
                                    @if (request()->is('messages')) class="nav-link px-2 link-menu-mobile py-1 active disabled" @else class="nav-link px-2 link-menu-mobile py-1" @endif>
                                    <div>
                                        <i class="bi bi-chat-square-text mr-2"></i>
                                        <span class="d-md-none">{{ __('general.messages') }}</span>
                                    </div>
                                </a>
                            </li> --}}
                            @if ($settings->disable_wallet == 'off')
                                <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                    <a href="{{ url('my/wallet') }}"
                                        @if (request()->is('my/wallet')) class="nav-link px-2 link-menu-mobile py-1 active disabled" @else class="nav-link px-2 link-menu-mobile py-1" @endif>
                                        <div>
                                            <i class="iconmoon icon-Wallet mr-2"></i>
                                            <span class="d-md-none">{{ __('general.wallet') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif
                            

                            <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                <a href="{{ url('my/subscriptions') }}"
                                    @if (request()->is('my/subscriptions')) class="nav-link px-2 link-menu-mobile py-1 active disabled" @else class="nav-link px-2 link-menu-mobile py-1" @endif>
                                    <div>
                                        <i class="bi bi-cart mr-2"></i>
                                        <span class="d-md-none">{{ __('admin.subscriptions') }}</span>
                                    </div>
                                </a>
                            </li>

                            @if (auth()->user()->verified_id == 'yes')
                                <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                    <a href="{{ url('my/commission') }}"
                                        @if (request()->is('my/commission')) class="nav-link px-2 link-menu-mobile py-1 active disabled" @else class="nav-link px-2 link-menu-mobile py-1" @endif>
                                        <div>
                                            <svg class="margin-right-4" width="19" height="19" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentcolor" stroke-width="1.5"/>
                                                <path d="M14.7102 10.0611C14.6111 9.29844 13.7354 8.06622 12.1608 8.06619C10.3312 8.06616 9.56136 9.07946 9.40515 9.58611C9.16145 10.2638 9.21019 11.6571 11.3547 11.809C14.0354 11.999 15.1093 12.3154 14.9727 13.956C14.836 15.5965 13.3417 15.951 12.1608 15.9129C10.9798 15.875 9.04764 15.3325 8.97266 13.8733M11.9734 6.99805V8.06982M11.9734 15.9031V16.998" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"/>
                                            </svg>
                                            <span class="d-md-none">{{ __('admin.commission') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                <a href="{{ url('my/bookmarks') }}"
                                    class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('my/bookmarks')) active disabled @endif">
                                    <div>
                                        <i class="feather icon-bookmark mr-2"></i>
                                        <span class="d-md-none">{{ __('general.bookmarks') }}</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item dropdown d-md-none menu_mobile_active_link">

                                <a href="{{ route('user.settings') }}"
                                    class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('settings/user')) active disabled @endif">

                                    <div>

                                        <i class="bi-shield-check mr-2"></i>

                                        <span class="d-md-none">{{ __('general.settings') }}</span>

                                    </div>

                                </a>

                            </li>
                            @if (auth()->user()->verified_id == 'yes')
                                <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                    <a href="{{ url('my/posts') }}"
                                        class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('my/posts')) active disabled @endif">
                                        <div>
                                            <i class="feather icon-feather mr-2"></i>
                                            <span class="d-md-none">{{ __('general.my_posts') }}</span>
                                        </div>
                                    </a>
                                </li>

                                @if ($settings->allow_vault)
                                    <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                        <a href="{{ url('my/vault') }}"
                                            class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('my/vault')) active disabled @endif">
                                            <div>
                                                <i class="feather icon-archive mr-2"></i>
                                                <span class="d-md-none">{{ __('general.vault') }}</span>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endif

                            
                            <li class="nav-item dropdown d-md-none menu_mobile_active_link">
                                <a href="{{ url('my/likes') }}" class="nav-link px-2 link-menu-mobile py-1 @if (request()->is('my/likes')) active disabled @endif">
                                    <div>
                                        <i class="feather icon-heart mr-2"></i>
                                        <span class="d-md-none">{{ __('general.likes') }}</span>
                                    </div>
                                </a>
                            </li>

                            {{-- for mobile menu --}}
                           

                            {{-- @if (auth()->user()->verified_id == 'yes')
                            <li class="nav-item dropdown d-md-none">
                                <a class="nav-link px-2 link-menu-mobile py-1 balance">
                                    <div>
                                        <i class="iconmoon icon-Dollar mr-2"></i>
                                        <span class="d-md-none balance">{{ __('general.balance') }}:
                                            {{ Helper::amountFormatDecimal(auth()->user()->balance) }}</span>

                                    </div>
                                </a>
                            </li>
                        @endif --}}

                            {{-- @if (($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.0) || $settings->disable_wallet == 'off')
                            <li class="nav-item dropdown d-md-none">
                                <a @if ($settings->disable_wallet == 'off') href="{{ url('my/wallet') }}" @endif
                                    class="nav-link px-2 link-menu-mobile py-1">

                                    <div>
                                        <i class="iconmoon icon-Wallet mr-2"></i>

                                        {{ __('general.wallet') }}: <span
                                            class="balanceWallet">{{ Helper::userWallet() }}</span>

                                    </div>
                                </a>
                            </li>
                        @endif --}}

                            {{-- @if (auth()->user()->verified_id == 'yes')
                            <li class="nav-item dropdown d-md-none">
                                <a href="{{ url('my/subscribers') }}" class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <i class="feather icon-users mr-2"></i>
                                        <span class="d-md-none">{{ __('users.my_subscribers') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif --}}

                            {{-- <li class="nav-item dropdown d-md-none">
                            <a href="{{ url('my/subscriptions') }}" class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i class="feather icon-user-check mr-2"></i>
                                    <span class="d-md-none">{{ __('users.my_subscriptions') }}</span>
                                </div>
                            </a>
                        </li> --}}

                            @if (auth()->user()->verified_id == 'no' && auth()->user()->verified_id != 'reject')
                                <li class="nav-item dropdown d-md-none">
                                    <a href="{{ url('settings/verify/account') }}"
                                        class="nav-link px-2 link-menu-mobile py-1">
                                        <div>
                                            <i class="feather icon-star mr-2"></i>
                                            <span class="d-md-none">{{ __('general.become_creator') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item d-md-none mobile-menu-actions-item">
                                <div class="mobile-menu-actions">
                                    <div class="theme-toggle-group">
                                        <a href="{{ url('mode/light') }}"
                                            class="theme-toggle-btn {{ auth()->user()->dark_mode == 'off' ? 'active' : '' }}"
                                            data-mode="light"
                                            title="{{ __('general.light_mode') }}">
                                            <i class="feather icon-sun icon-navbar"></i>
                                        </a>

                                        <a href="{{ url('mode/dark') }}"
                                            class="theme-toggle-btn {{ auth()->user()->dark_mode == 'on' ? 'active' : '' }}"
                                            data-mode="dark"
                                            title="{{ __('general.dark_mode') }}">
                                            <i class="feather icon-moon icon-navbar"></i>
                                        </a>
                                    </div>

                                    <a href="{{ url('logout') }}" class="mobile-menu-logout-btn"
                                        title="{{ __('auth.logout') }}">
                                        <i class="feather icon-log-out"></i>
                                    </a>
                                </div>
                            </li>

                            <!-- =========== End Menu Mobile ============-->

                            <li class="nav-item dropdown d-md-block d-none">
                                <div class="theme-toggle-group">
                                    <a href="{{ url('mode/light') }}"
                                        class="theme-toggle-btn {{ auth()->user()->dark_mode == 'off' ? 'active' : '' }}"
                                        data-mode="light"
                                        title="Light mode">
                                        <i class="feather icon-sun icon-navbar"></i>
                                    </a>

                                    <a href="{{ url('mode/dark') }}"
                                        class="theme-toggle-btn {{ auth()->user()->dark_mode == 'on' ? 'active' : '' }}"
                                        data-mode="dark"
                                        title="Dark mode">
                                        <i class="feather icon-moon icon-navbar"></i>
                                    </a>

                                </div>
                            </li>
                            <li class="nav-item dropdown d-md-block d-none">
                                @if (($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.0) || $settings->disable_wallet == 'off')
                                    @if ($settings->disable_wallet == 'off')
                                        <a class="nav-link px-2" href="{{ url('my/wallet') }}">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M13 3.5H14C14.93 3.5 15.395 3.5 15.7765 3.60222C16.8117 3.87962 17.6204 4.68827 17.8978 5.72354C18 6.10504 18 6.57003 18 7.5H5C3.89543 7.5 3 6.60457 3 5.5C3 4.39543 3.89543 3.5 5 3.5H8"
                                                    stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M3 5.5V15.5C3 18.3284 3 19.7426 3.87868 20.6213C4.75736 21.5 6.17157 21.5 9 21.5H15C17.8284 21.5 19.2426 21.5 20.1213 20.6213C21 19.7426 21 18.3284 21 15.5V13.5C21 10.6716 21 9.25736 20.1213 8.37868C19.2426 7.5 17.8284 7.5 15 7.5H7"
                                                    stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M21 12.5H19C18.535 12.5 18.3025 12.5 18.1118 12.5511C17.5941 12.6898 17.1898 13.0941 17.0511 13.6118C17 13.8025 17 14.035 17 14.5C17 14.965 17 15.1975 17.0511 15.3882C17.1898 15.9059 17.5941 16.3102 18.1118 16.4489C18.3025 16.5 18.535 16.5 19 16.5H21"
                                                    stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M10.5 2.5C12.433 2.5 14 4.067 14 6C14 6.5368 13.8792 7.04537 13.6632 7.5H7.33682C7.12085 7.04537 7 6.5368 7 6C7 4.067 8.567 2.5 10.5 2.5Z"
                                                    stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="dropdown-item dropdown-navbar balance">
                                            <i class="bi bi-credit-card icon-navbar"></i>
                                        </span>
                                    @endif
                                @endif
                            </li>
                            <li class="nav-item dropdown d-md-block d-none">
                                <a href="{{ url('messages') }}" class="nav-link px-2" title="{{ __('general.messages') }}">

                                    <span class="noti_msg notify @if (auth()->user()->messagesInbox() != 0) d-block @endif">

                                        {{ auth()->user()->messagesInbox() }}

                                    </span>

                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                            stroke="currentcolor" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7 8H17" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M7 13H13" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                    <span class="d-md-none align-middle ml-1">{{ __('general.messages') }}</span>

                                </a>

                            </li>
                            <li class="nav-item dropdown d-md-block d-none">
                                <a href="{{ url('notifications') }}" class="nav-link px-2"
                                    title="{{ __('general.notifications') }}">
                                    <span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                        {{ auth()->user()->unseenNotifications() }}
                                    </span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.134 11C18.715 16.375 21 18 21 18H3C3 18 6 15.867 6 8.4C6 6.703 6.632 5.075 7.757 3.875C8.883 2.675 10.41 2 12 2C12.337 2 12.672 2.03 13 2.09M13.73 21C13.5544 21.3033 13.3021 21.5552 12.9985 21.7302C12.6948 21.9053 12.3505 21.9974 12 21.9974C11.6495 21.9974 11.3052 21.9053 11.0015 21.7302C10.6979 21.5552 10.4456 21.3033 10.27 21M19 8C19.7956 8 20.5587 7.68393 21.1213 7.12132C21.6839 6.55871 22 5.79565 22 5C22 4.20435 21.6839 3.44129 21.1213 2.87868C20.5587 2.31607 19.7956 2 19 2C18.2044 2 17.4413 2.31607 16.8787 2.87868C16.3161 3.44129 16 4.20435 16 5C16 5.79565 16.3161 6.55871 16.8787 7.12132C17.4413 7.68393 18.2044 8 19 8Z"
                                            stroke="currentcolor" stroke-width="1.2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>

                                    <span class="d-md-none align-middle ml-1">{{ __('general.notifications') }}</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown d-md-block d-none">

                                <a class="nav-link" href="#" id="nav-inner-success_dropdown_1" role="button"
                                    data-toggle="dropdown">

                                    <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                        alt="User" class="rounded-circle avatarUser mr-1" width="28" height="28">

                                    <span class="d-md-none">{{ auth()->user()->first_name }}</span>

                                    <i class="feather icon-chevron-down m-0 align-middle"></i>

                                </a>

                                <div class="dropdown-menu mb-1 dropdown-menu-right dd-menu-user"
                                    aria-labelledby="nav-inner-success_dropdown_1">

                                    @if (auth()->user()->role == 'admin')
                                        <a class="dropdown-item dropdown-navbar" href="{{ url('panel/admin') }}">
                                            <svg width="13" height="13" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg" class="lay-dash-icon mr-2">
                                                <path
                                                    d="M10 6V0H18V6H10ZM0 10V0H8V10H0ZM10 18V8H18V18H10ZM0 18V12H8V18H0ZM2 8H6V2H2V8ZM12 16H16V10H12V16ZM12 4H16V2H12V4ZM2 16H6V14H2V16Z"
                                                    fill="currentcolor" />
                                            </svg>
                                            <span>{{ __('admin.admin') }}</span>

                                        </a>

                                        <div class="dropdown-divider"></div>
                                    @endif



                                    @if (auth()->user()->verified_id == 'yes')
                                        <span class="dropdown-item dropdown-navbar balance">

                                            <i class="iconmoon icon-Dollar mr-2"></i> {{ __('general.balance') }}:
                                            {{ Helper::amountFormatDecimal(auth()->user()->balance) }}

                                        </span>
                                    @endif



                                    @if (($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.0) || $settings->disable_wallet == 'off')
                                        @if ($settings->disable_wallet == 'off')
                                            <a class="dropdown-item dropdown-navbar" href="{{ url('my/wallet') }}">

                                                <i class="iconmoon icon-Wallet mr-2"></i> {{ __('general.wallet') }}:

                                                <span class="balanceWallet">{{ Helper::userWallet() }}</span>

                                            </a>
                                        @else
                                            <span class="dropdown-item dropdown-navbar balance">

                                                <i class="iconmoon icon-Wallet mr-2"></i> {{ __('general.wallet') }}:

                                                <span class="balanceWallet">{{ Helper::userWallet() }}</span>

                                            </span>
                                        @endif



                                        <div class="dropdown-divider"></div>
                                    @endif



                                    @if ($settings->disable_wallet == 'on' && auth()->user()->verified_id == 'yes')
                                        <div class="dropdown-divider"></div>
                                    @endif



                                    <a class="dropdown-item dropdown-navbar url-user"
                                        href="{{ url('profile', auth()->User()->username) }}"><i class="feather icon-user mr-2"></i>
                                        {{ auth()->user()->verified_id == 'yes' ? __('general.my_page') : __('users.my_profile') }}</a>

                                    @if (auth()->user()->verified_id == 'yes')
                                        <a class="dropdown-item dropdown-navbar" href="{{ url('dashboard') }}">
                                            <i class="bi-speedometer2 me-2 mr-2"></i>
                                            {{ __('admin.dashboard') }}</a>

                                        <a class="dropdown-item dropdown-navbar" href="{{ url('my/posts') }}"><i
                                                class="feather icon-feather mr-2"></i> {{ __('general.my_posts') }}</a>



                                        @if ($settings->allow_vault)
                                            <a class="dropdown-item dropdown-navbar" href="{{ url('my/vault') }}"><i
                                                    class="feather icon-archive mr-2"></i> {{ __('general.vault') }}</a>
                                        @endif
                                    @endif



                                    <div class="dropdown-divider"></div>

                                    @if (auth()->user()->verified_id == 'yes')
                                        <a class="dropdown-item dropdown-navbar" href="{{ url('my/subscribers') }}"><i
                                                class="feather icon-users mr-2"></i> {{ __('users.my_subscribers') }}</a>
                                    @endif

                                    <a class="dropdown-item dropdown-navbar" href="{{ url('my/subscriptions') }}"><i
                                            class="feather icon-user-check mr-2"></i> {{ __('users.my_subscriptions') }}</a>

                                    <a class="dropdown-item dropdown-navbar" href="{{ url('my/bookmarks') }}"><i
                                            class="feather icon-bookmark mr-2"></i> {{ __('general.bookmarks') }}</a>

                                    <a class="dropdown-item dropdown-navbar" href="{{ url('my/likes') }}"><i
                                            class="feather icon-heart mr-2"></i> {{ __('general.likes') }}</a>

                                    <a class="dropdown-item dropdown-navbar" href="{{ route('user.settings') }}"><i
                                            class="bi-shield-check mr-2"></i> {{ __('general.settings') }}</a>



                                    @if (auth()->user()->verified_id == 'no' &&
                                            auth()->user()->verified_id != 'reject' &&
                                            $settings->requests_verify_account == 'on')
                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item dropdown-navbar"
                                            href="{{ url('settings/verify/account') }}"><i
                                                class="feather icon-star mr-2"></i> {{ __('general.become_creator') }}</a>
                                    @endif

                                    <div class="dropdown-divider dropdown-navbar"></div>

                                    <a class="dropdown-item dropdown-navbar" href="{{ url('logout') }}"><i
                                            class="feather icon-log-out mr-2"></i> {{ __('auth.logout') }}</a>

                                </div>

                            </li>
                            {{-- <li class="nav-item">

					<a class="nav-link btn-arrow btn-arrow-sm btn btn-main btn-primary pr-3 pl-3" href="{{url('settings/page')}}">

					{{ auth()->user()->verified_id == 'yes' ? __('general.edit_my_page') : __('users.edit_profile')}}</a>

					</li> --}}
                        @endguest
                    </ul>

                </div>

        </div>

    </nav>
</header>
