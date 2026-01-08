<header>
    <nav
        class="navbar navbar-expand-lg navbar-inverse fixed-top modern-navbar p-nav @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0) scroll @else p-3 @if (request()->is('live/*')) d-none @endif  @if (request()->is('messages/*')) d-none d-lg-block shadow-sm @elseif(request()->is('messages')) shadow-sm @else shadow-custom @endif {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
        <div class="container-fluid d-flex align-items-center">
            @auth
                <div class="d-flex justify-content-between">
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
                    <div>
                        <div class="position-absolute d-flex d-lg-none"
                            style="top: 25px; right: 35px;gap:6px;margin-right:20px">
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <svg class="icon-navbar" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.625 15.75C12.56 15.75 15.75 12.56 15.75 8.625C15.75 4.68997 12.56 1.5 8.625 1.5C4.68997 1.5 1.5 4.68997 1.5 8.625C1.5 12.56 4.68997 15.75 8.625 15.75Z" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M16.5 16.5L15 15" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <svg class="icon-navbar" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.5 9C16.5 13.1421 13.1421 16.5 9 16.5C4.85786 16.5 1.5 13.1421 1.5 9C1.5 4.85786 4.85786 1.5 9 1.5C13.1421 1.5 16.5 4.85786 16.5 9Z" stroke="#A3A3A3" stroke-width="1.125"/>
                                        <path d="M11.0297 7.54582C10.9554 6.97383 10.2986 6.04966 9.11767 6.04964C7.74547 6.04962 7.16809 6.80959 7.05093 7.18958C6.86816 7.69785 6.90471 8.74282 8.51309 8.85675C10.5236 8.99925 11.329 9.23655 11.2266 10.467C11.1241 11.6974 10.0033 11.9632 9.11767 11.9347C8.23192 11.9062 6.7828 11.4994 6.72656 10.405M8.97712 5.24854V6.05236M8.97712 11.9273V12.7485" stroke="#A3A3A3" stroke-width="1.125" stroke-linecap="round"/>
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
                                    <svg class="icon-navbar" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.6005 8.24994C14.0362 12.2812 15.75 13.4999 15.75 13.4999H2.25C2.25 13.4999 4.5 11.9002 4.5 6.29994C4.5 5.02719 4.974 3.80619 5.81775 2.90619C6.66225 2.00619 7.8075 1.49994 9 1.49994C9.25275 1.49994 9.504 1.52244 9.75 1.56744M10.2975 15.7499C10.1658 15.9774 9.97659 16.1663 9.74886 16.2976C9.52112 16.4289 9.26287 16.498 9 16.498C8.73713 16.498 8.47888 16.4289 8.25114 16.2976C8.02341 16.1663 7.83421 15.9774 7.7025 15.7499M14.25 5.99994C14.8467 5.99994 15.419 5.76289 15.841 5.34093C16.2629 4.91897 16.5 4.34668 16.5 3.74994C16.5 3.1532 16.2629 2.58091 15.841 2.15895C15.419 1.73699 14.8467 1.49994 14.25 1.49994C13.6533 1.49994 13.081 1.73699 12.659 2.15895C12.2371 2.58091 12 3.1532 12 3.74994C12 4.34668 12.2371 4.91897 12.659 5.34093C13.081 5.76289 13.6533 5.99994 14.25 5.99994Z" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>

                                </a>
                            </div>
                        </div>
                        <div class="buttons-mobile-nav d-lg-none">
                            <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                aria-expanded="false" role="button">
                            <svg class="icon-navbar" width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.42969 4.28571H17.144" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                            <path d="M3.42969 10.2857H17.144" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                            <path d="M3.42969 16.2857H17.144" stroke="#A3A3A3" stroke-width="1.2" stroke-linecap="round"/>
                            </svg>
                            </a>
                        </div>
                    </div>
                </div>


            @endauth

            @guest
                <button class="navbar-toggler @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0) text-white @endif" type="button"
                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
            @endguest

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
                    <li class="nav-item dropdown d-lg-block d-none">
                        <a class="nav-link px-2 {{ request()->is('dashboard') ? 'font_bold' : 'font_normal' }}"
                            href="{{ url('dashboard') }}" title="{{ __('admin.dashboard') }}">
                            {{ __('admin.dashboard') }}
                        </a>
                    </li>
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

					@guest
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
                    @endguest
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

                                        <i class="bi bi-speedometer2 mr-2"></i>

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

                                        <i class="bi bi-speedometer2 mr-2"></i>

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
                                        <i class="bi bi-credit-card icon-navbar"></i>
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

                                <i class="bi bi-chat-square-text icon-navbar"></i>

                                <span class="d-lg-none align-middle ml-1">{{ __('general.messages') }}</span>

                            </a>

                        </li>
                        <li class="nav-item dropdown d-lg-block d-none">
                            <a href="{{ url('notifications') }}" class="nav-link px-2"
                                title="{{ __('general.notifications') }}">
                                <span class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                    {{ auth()->user()->unseenNotifications() }}
                                </span>
                                <i class="far fa-bell icon-navbar"></i>
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
                                    <a class="dropdown-item dropdown-navbar" href="{{ url('panel/admin') }}"><i
                                            class="bi bi-speedometer2 mr-2"></i> {{ __('admin.admin') }}</a>

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

                                    <a class="dropdown-item dropdown-navbar" href="{{ url('dashboard') }}"><i
                                            class="bi bi-speedometer2 mr-2"></i> {{ __('admin.dashboard') }}</a>

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

        </div>

    </nav>
</header>
