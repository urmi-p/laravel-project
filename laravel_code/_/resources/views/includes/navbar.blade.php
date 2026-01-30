<header class=" @if ( request()->is('password/reset')) forgotpwd @endif">
    <nav
        class="navbar navbar-expand-lg navbar-inverse fixed-top modern-navbar p-nav @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0) scroll @else p-3 @if (request()->is('live/*')) d-none @endif  @if (request()->is('messages/*')) shadow-sm @elseif(request()->is('messages')) shadow-sm @else shadow-custom @endif {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
        <div class="container-fluid d-flex align-items-center">
            
                <div class="d-flex justify-content-between">
                    <div class="navbar-left d-flex align-items-center">
                        <a class="navbar-brand" href="{{ url('/') }}">

                            @if (auth()->check() && auth()->user()->dark_mode == 'on')
                                <img src="{{ url('img', $settings->logo) }}" data-logo="{{ $settings->logo }}"
                                    data-logo-2="{{ $settings->logo_2 }}" alt="{{ $settings->title }}"
                                    class="logo align-bottom max-w-100" />
                            @else
                                <img src="{{ url('img', auth()->guest() && request()->path() == '/' && $settings->home_style == 0 ? $settings->logo : $settings->logo_2) }}"
                                    data-logo="{{ $settings->logo }}" data-logo-2="{{ $settings->logo_2 }}"
                                    alt="{{ $settings->title }}" class="logo align-bottom max-w-100" />
                            @endif
                        </a>
                    </div>
                    @auth
                    <div>
                        <div class="position-absolute d-flex d-lg-none main_head_search" style="top: 25px; right: 35px;gap:6px;margin-right:20px">
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.625 15.75C12.56 15.75 15.75 12.56 15.75 8.625C15.75 4.68997 12.56 1.5 8.625 1.5C4.68997 1.5 1.5 4.68997 1.5 8.625C1.5 12.56 4.68997 15.75 8.625 15.75Z" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16.5 16.5L15 15" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z" stroke="#A3A3A3" stroke-width="1.125"/>
                                        <path d="M11.0336 7.54582C10.9593 6.97383 10.3025 6.04966 9.12157 6.04964C7.74937 6.04962 7.17199 6.80959 7.05484 7.18958C6.87206 7.69785 6.90862 8.74282 8.517 8.85675C10.5275 8.99925 11.3329 9.23655 11.2305 10.467C11.128 11.6974 10.0072 11.9632 9.12157 11.9347C8.23582 11.9062 6.7867 11.4994 6.73047 10.405M8.98102 5.24854V6.05236M8.98102 11.9273V12.7485" stroke="#A3A3A3" stroke-width="1.125" stroke-linecap="round"/>
                                    </svg>

                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ url('notifications') }}" class="position-relative btn_mobile_nav"
                                    title="{{ trans('general.notifications') }}">
                                    <span
                                        class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                        {{ auth()->user()->unseenNotifications() }}
                                    </span>
                                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6044 8.24994C14.0402 12.2812 15.7539 13.4999 15.7539 13.4999H2.25391C2.25391 13.4999 4.50391 11.9002 4.50391 6.29994C4.50391 5.02719 4.97791 3.80619 5.82166 2.90619C6.66616 2.00619 7.81141 1.49994 9.00391 1.49994C9.25666 1.49994 9.50791 1.52244 9.75391 1.56744M10.3014 15.7499C10.1697 15.9774 9.98049 16.1663 9.75276 16.2976C9.52503 16.4289 9.26678 16.498 9.00391 16.498C8.74103 16.498 8.48278 16.4289 8.25505 16.2976C8.02732 16.1663 7.83811 15.9774 7.70641 15.7499M14.2539 5.99994C14.8506 5.99994 15.4229 5.76289 15.8449 5.34093C16.2669 4.91897 16.5039 4.34668 16.5039 3.74994C16.5039 3.1532 16.2669 2.58091 15.8449 2.15895C15.4229 1.73699 14.8506 1.49994 14.2539 1.49994C13.6572 1.49994 13.0849 1.73699 12.6629 2.15895C12.241 2.58091 12.0039 3.1532 12.0039 3.74994C12.0039 4.34668 12.241 4.91897 12.6629 5.34093C13.0849 5.76289 13.6572 5.99994 14.2539 5.99994Z" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                </a>
                            </div>
                        </div>
                        <div class="buttons-mobile-nav d-lg-none">
                            <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                aria-expanded="false" role="button">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.42578 4.28571H17.1401" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                                    <path d="M3.42578 10.2857H17.1401" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                                    <path d="M3.42578 16.2857H17.1401" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                                </svg>

                            </a>
                        </div>
                    </div>
                    @endauth
                </div>


            

            @guest
                <button class="333 navbar-toggler @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0) text-white @endif" type="button"
                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
            @endguest
            @auth
            <div class="justify-content-between collapse navbar-collapse navbar-mobile" id="navbarCollapse">
                <div class="d-lg-none text-right pr-2 mb-2">

                    <button type="button" class="navbar-toggler close-menu-mobile" data-toggle="collapse"
                        data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                @if ((auth()->guest() && $settings->who_can_see_content == 'all') || auth()->check())

                    <ul class="navbar-nav">
                        @if (!$settings->disable_creators_section)

                            @if (!$settings->disable_search_creators)

                                <form class="form-inline my-lg-0 position-relative" method="get"
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

                <ul class="navbar-nav">
                    
                    @auth
                        @if (auth()->user()->verified_id == 'yes')
                        <li class="nav-item dropdown d-lg-block d-none">
                            <a class="nav-link navbar_mid_link px-2 {{ request()->is('dashboard') ? 'font_bold' : 'font_normal' }}"
                                href="{{ url('dashboard') }}" title="{{ __('admin.dashboard') }}">
                                {{ __('admin.dashboard') }}
                            </a>
                        </li>
                        @else
                            <li class="nav-item dropdown d-lg-block d-none">
                                <a class="nav-link navbar_mid_link px-2 {{ request()->is('/') ? 'font_bold' : 'font_normal' }}"
                                    href="{{ url('/') }}" title="{{ __('admin.dashboard') }}">
                                    {{ __('admin.dashboard') }}
                                </a>
                            </li>
                        @endif
                    @endauth
                    @if (!$settings->disable_creators_section)

                        <li class="nav-item dropdown d-lg-block d-none">
                            <a class="nav-link navbar_mid_link px-2 {{ request()->is('creators*') ? 'font_bold' : 'font_normal' }}"
                                href="{{ url('creators') }}" title="{{ __('general.explore_creators') }}">
                                {{ __('general.explore_creators') }}
                            </a>
                        </li>

                    @endif

                    @if ($settings->shop)

                        <li class="nav-item dropdown d-lg-block d-none">
                            <a class="nav-link navbar_mid_link px-2 {{ request()->is('shop*') ? 'font_bold' : 'font_normal' }}"
                                href="{{ url('shop') }}" title="{{ __('general.explore_shop') }}">
                                {{ __('general.explore_shop') }}
                            </a>
                        </li>
                    @endif

					<!-- @guest
						@if (!$settings->disable_creators_section)

							<li class="nav-item dropdown d-lg-block d-none">
								<a class="nav-link px-2 {{ request()->is('creators*') ? 'font_bold' : 'font_normal' }}"
									href="{{ url('creators') }}" title="{{ __('general.explore_creators') }}">
									{{ __('general.explore_creators') }}
								</a>
							</li>

						@endif

						@if ($settings->shop)

							<li class="nav-item dropdown d-lg-block d-none">
								<a class="nav-link px-2 {{ request()->is('shop*') ? 'font_bold' : 'font_normal' }}"
									href="{{ url('shop') }}" title="{{ __('general.explore_shop') }}">
									{{ __('general.explore_shop') }}
								</a>
							</li>
						@endif
                    @endguest -->
                </ul>

                <ul class="navbar-nav">

                    @guest

                        <li class="nav-item mr-1">

                            <a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif
                                class="nav-link login-btn @if ($settings->registration_active == '0') btn btn-main btn-primary pr-3 pl-3 @endif"
                                href="{{ in_array(config('settings.home_style'), [0, 2]) ? url('login') : url('/') }}">

                                {{ __('auth.login') }}

                            </a>

                        </li>
                        @if ($settings->registration_active == '1')
                            <li class="nav-item">

                                <a @if (Helper::showLoginFormModal()) data-toggle="modal" data-target="#loginFormModal" @endif
                                    class="toggleRegister nav-link btn btn-main @if (request()->path() == '/' && $settings->home_style == 0) btn-light @else btn-primary @endif btn-register-menu pr-3 pl-3 btn-arrow btn-arrow-sm"
                                    href="{{ in_array(config('settings.home_style'), [0, 2]) ? url('signup') : url('/') }}">

                                    {{ __('general.getting_started') }}

                                </a>
                            </li>
                        @endif
                    @else
                        <!-- ============ Menu Mobile ============-->
                        @if (auth()->user()->role == 'admin')
                            <li class="nav-item dropdown d-lg-none mt-2 border-bottom">
                                <a href="{{ url('panel/admin') }}" class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <i class="bi-speedometer2 me-2 mr-2"></i>
                                        <span class="d-lg-none">{{ __('admin.admin') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown d-lg-none @if (auth()->user()->role != 'admin') mt-2 @endif">
                            <a href="{{ url(auth()->user()->username) }}"
                                class="nav-link px-2 link-menu-mobile py-1 url-user">
                                <div>
                                    <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                        alt="User" class="rounded-circle avatarUser mr-1" width="20"
                                        height="20">
                                    <span
                                        class="d-lg-none">{{ auth()->user()->verified_id == 'yes' ? __('general.my_page') : __('users.my_profile') }}</span>
                                </div>
                            </a>

                        </li>
                        @if (auth()->user()->verified_id == 'yes')
                            <li class="nav-item dropdown d-lg-none">
                                <a href="{{ url('dashboard') }}" class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 9V3H21V9H13ZM3 13V3H11V13H3ZM13 21V11H21V21H13ZM3 21V15H11V21H3ZM5 11H9V5H5V11ZM15 19H19V13H15V19ZM15 7H19V5H15V7ZM5 19H9V17H5V19Z" fill="white"/>
                                        </svg>
                                        <span class="d-lg-none">{{ __('admin.dashboard') }}</span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item dropdown d-lg-none">
                                <a href="{{ url('my/posts') }}" class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <i class="feather icon-feather mr-2"></i>
                                        <span class="d-lg-none">{{ __('general.my_posts') }}</span>
                                    </div>
                                </a>
                            </li>

                            @if ($settings->allow_vault)
                                <li class="nav-item dropdown d-lg-none">
                                    <a href="{{ url('my/vault') }}" class="nav-link px-2 link-menu-mobile py-1">
                                        <div>
                                            <i class="feather icon-archive mr-2"></i>
                                            <span class="d-lg-none">{{ __('general.vault') }}</span>
                                        </div>
                                    </a>
                                </li>
                            @endif

                        @endif

                        <li class="nav-item dropdown d-lg-none">
                            <a href="{{ url('my/bookmarks') }}" class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i class="feather icon-bookmark mr-2"></i>
                                    <span class="d-lg-none">{{ __('general.bookmarks') }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown d-lg-none border-bottom">
                            <a href="{{ url('my/likes') }}" class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i class="feather icon-heart mr-2"></i>
                                    <span class="d-lg-none">{{ __('general.likes') }}</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item dropdown d-lg-none border-bottom">

                            <a href="{{ route('user.settings') }}" class="nav-link px-2 link-menu-mobile py-1">

                                <div>

                                    <i class="bi-shield-check mr-2"></i>

                                    <span class="d-lg-none">{{ __('general.settings') }}</span>

                                </div>

                            </a>

                        </li>

                        @if (auth()->user()->verified_id == 'yes')
                            <li class="nav-item dropdown d-lg-none">
                                <a class="nav-link px-2 link-menu-mobile py-1 balance">
                                    <div>
                                        <i class="iconmoon icon-Dollar mr-2"></i>
                                        <span class="d-lg-none balance">{{ __('general.balance') }}:
                                            {{ Helper::amountFormatDecimal(auth()->user()->balance) }}</span>

                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.0) || $settings->disable_wallet == 'off')
                            <li class="nav-item dropdown d-lg-none border-bottom">
                                <a @if ($settings->disable_wallet == 'off') href="{{ url('my/wallet') }}" @endif
                                    class="nav-link px-2 link-menu-mobile py-1">

                                    <div>
                                        <i class="iconmoon icon-Wallet mr-2"></i>

                                        {{ __('general.wallet') }}: <span
                                            class="balanceWallet">{{ Helper::userWallet() }}</span>

                                    </div>
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->verified_id == 'yes')
                            <li class="nav-item dropdown d-lg-none">
                                <a href="{{ url('my/subscribers') }}" class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <i class="feather icon-users mr-2"></i>
                                        <span class="d-lg-none">{{ __('users.my_subscribers') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown d-lg-none">
                            <a href="{{ url('my/subscriptions') }}" class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i class="feather icon-user-check mr-2"></i>
                                    <span class="d-lg-none">{{ __('users.my_subscriptions') }}</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item dropdown d-lg-none border-bottom">
                            <a href="{{ url('my/purchases') }}" class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i class="bi bi-bag-check mr-2"></i>
                                    <span class="d-lg-none">{{ __('general.purchased') }}</span>
                                </div>
                            </a>
                        </li>

                        @if (auth()->user()->verified_id == 'no' && auth()->user()->verified_id != 'reject')
                            <li class="nav-item dropdown d-lg-none">
                                <a href="{{ url('settings/verify/account') }}"
                                    class="nav-link px-2 link-menu-mobile py-1">
                                    <div>
                                        <i class="feather icon-star mr-2"></i>
                                        <span class="d-lg-none">{{ __('general.become_creator') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endif

                        {{-- for mobile menu --}}
                        <li class="nav-item dropdown d-lg-none">

                            <a href="{{ auth()->user()->dark_mode == 'off' ? url('mode/dark') : url('mode/light') }}"
                                class="nav-link px-2 link-menu-mobile py-1">
                                <div>
                                    <i
                                        class="feather icon-{{ auth()->user()->dark_mode == 'off' ? 'moon' : 'sun' }} mr-2"></i>
                                    <span
                                        class="d-lg-none">{{ auth()->user()->dark_mode == 'off' ? __('general.dark_mode') : __('general.light_mode') }}
                                    </span>

                                </div>
                            </a>
                        </li>

                        <li class="nav-item dropdown d-lg-none mb-2">

                            <a href="{{ url('logout') }}" class="nav-link px-2 link-menu-mobile py-1">

                                <div>

                                    <i class="feather icon-log-out mr-2"></i>

                                    <span class="d-lg-none">{{ __('auth.logout') }}</span>

                                </div>

                            </a>

                        </li>

                        <!-- =========== End Menu Mobile ============-->

                        <li class="nav-item dropdown d-lg-block d-none">
                            <div class="theme-toggle-group">
                                <a href="{{ url('mode/light') }}"
                                    class="theme-toggle-btn {{ auth()->user()->dark_mode == 'off' ? 'active' : '' }}"
                                    title="Light mode">
                                    <i class="feather icon-sun icon-navbar"></i>
                                </a>

                                <a href="{{ url('mode/dark') }}"
                                    class="theme-toggle-btn {{ auth()->user()->dark_mode == 'on' ? 'active' : '' }}"
                                    title="Dark mode">
                                    <i class="feather icon-moon icon-navbar"></i>
                                </a>

                            </div>
                        </li>
                        <li class="nav-item dropdown d-lg-block d-none">
                            @if (($settings->disable_wallet == 'on' && auth()->user()->wallet != 0.0) || $settings->disable_wallet == 'off')
                                @if ($settings->disable_wallet == 'off')
                                    <a class="nav-link px-2" href="{{ url('my/wallet') }}">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 3.5H14C14.93 3.5 15.395 3.5 15.7765 3.60222C16.8117 3.87962 17.6204 4.68827 17.8978 5.72354C18 6.10504 18 6.57003 18 7.5H5C3.89543 7.5 3 6.60457 3 5.5C3 4.39543 3.89543 3.5 5 3.5H8" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M3 5.5V15.5C3 18.3284 3 19.7426 3.87868 20.6213C4.75736 21.5 6.17157 21.5 9 21.5H15C17.8284 21.5 19.2426 21.5 20.1213 20.6213C21 19.7426 21 18.3284 21 15.5V13.5C21 10.6716 21 9.25736 20.1213 8.37868C19.2426 7.5 17.8284 7.5 15 7.5H7" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21 12.5H19C18.535 12.5 18.3025 12.5 18.1118 12.5511C17.5941 12.6898 17.1898 13.0941 17.0511 13.6118C17 13.8025 17 14.035 17 14.5C17 14.965 17 15.1975 17.0511 15.3882C17.1898 15.9059 17.5941 16.3102 18.1118 16.4489C18.3025 16.5 18.535 16.5 19 16.5H21" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M10.5 2.5C12.433 2.5 14 4.067 14 6C14 6.5368 13.8792 7.04537 13.6632 7.5H7.33682C7.12085 7.04537 7 6.5368 7 6C7 4.067 8.567 2.5 10.5 2.5Z" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="dropdown-item dropdown-navbar balance">
                                        <i class="bi bi-credit-card icon-navbar"></i>
                                    </span>
                                @endif
                            @endif
                        </li>
                        <li class="nav-item dropdown d-lg-block d-none">
                            <a href="{{ url('messages') }}" class="nav-link px-2" title="{{ __('general.messages') }}">

                                <span class="noti_msg notify @if (auth()->user()->messagesInbox() != 0) d-block @endif">

                                    {{ auth()->user()->messagesInbox() }}

                                </span>

                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z" stroke="currentcolor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 8H17" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 13H13" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <span class="d-lg-none align-middle ml-1">{{ __('general.messages') }}</span>

                            </a>

                        </li>
                        <li class="nav-item dropdown d-lg-block d-none">
                            <a href="{{ url('notifications') }}" class="nav-link px-2"
                                title="{{ __('general.notifications') }}">
                                <span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                    {{ auth()->user()->unseenNotifications() }}
                                </span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.134 11C18.715 16.375 21 18 21 18H3C3 18 6 15.867 6 8.4C6 6.703 6.632 5.075 7.757 3.875C8.883 2.675 10.41 2 12 2C12.337 2 12.672 2.03 13 2.09M13.73 21C13.5544 21.3033 13.3021 21.5552 12.9985 21.7302C12.6948 21.9053 12.3505 21.9974 12 21.9974C11.6495 21.9974 11.3052 21.9053 11.0015 21.7302C10.6979 21.5552 10.4456 21.3033 10.27 21M19 8C19.7956 8 20.5587 7.68393 21.1213 7.12132C21.6839 6.55871 22 5.79565 22 5C22 4.20435 21.6839 3.44129 21.1213 2.87868C20.5587 2.31607 19.7956 2 19 2C18.2044 2 17.4413 2.31607 16.8787 2.87868C16.3161 3.44129 16 4.20435 16 5C16 5.79565 16.3161 6.55871 16.8787 7.12132C17.4413 7.68393 18.2044 8 19 8Z" stroke="currentcolor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>

                                <span class="d-lg-none align-middle ml-1">{{ __('general.notifications') }}</span>
                            </a>
                        </li>
                        <li class="nav-item dropdown d-lg-block d-none">

                            <a class="nav-link" href="#" id="nav-inner-success_dropdown_1" role="button"
                                data-toggle="dropdown">

                                <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                    alt="User" class="rounded-circle avatarUser mr-1" width="28" height="28">

                                <span class="d-lg-none">{{ auth()->user()->first_name }}</span>

                                <i class="feather icon-chevron-down m-0 align-middle"></i>

                            </a>

                            <div class="dropdown-menu mb-1 dropdown-menu-right dd-menu-user"
                                aria-labelledby="nav-inner-success_dropdown_1">

                                @if (auth()->user()->role == 'admin')
                                    <a class="dropdown-item dropdown-navbar" href="{{ url('panel/admin') }}">
                                        <svg width="13" height="13" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" class="lay-dash-icon mr-2">
                                            <path d="M10 6V0H18V6H10ZM0 10V0H8V10H0ZM10 18V8H18V18H10ZM0 18V12H8V18H0ZM2 8H6V2H2V8ZM12 16H16V10H12V16ZM12 4H16V2H12V4ZM2 16H6V14H2V16Z" fill="currentcolor"/>
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
                                    href="{{ url(auth()->User()->username) }}"><i class="feather icon-user mr-2"></i>
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



                                <div class="dropdown-divider"></div>



                                @if (auth()->user()->dark_mode == 'off')
                                    <a class="dropdown-item dropdown-navbar" href="{{ url('mode/dark') }}"><i
                                            class="feather icon-moon mr-2"></i> {{ __('general.dark_mode') }}</a>
                                @else
                                    <a class="dropdown-item dropdown-navbar" href="{{ url('mode/light') }}"><i
                                            class="feather icon-sun mr-2"></i> {{ __('general.light_mode') }}</a>
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
            @endauth

        </div>

    </nav>
</header>