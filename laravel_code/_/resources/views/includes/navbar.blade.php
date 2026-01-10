<header>
    <nav
        class="navbar navbar-expand-lg navbar-inverse fixed-top modern-navbar p-nav @if (auth()->guest() && request()->path() == '/' && $settings->home_style == 0) scroll @else p-3 @if (request()->is('live/*')) d-none @endif  @if (request()->is('messages/*')) d-none d-lg-block shadow-sm @elseif(request()->is('messages')) shadow-sm @else shadow-custom @endif {{ auth()->check() && auth()->user()->dark_mode == 'on' ? 'bg-white' : 'navbar_background_color' }} link-scroll @endif">
        <div class="container-fluid d-flex align-items-center">
            @auth
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
                    <div>
                        <div class="position-absolute d-flex d-lg-none"
                            style="top: 9px; right: 35px;gap:6px;margin-right:20px">
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <i class="bi bi-search icon-navbar"></i>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                    data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                    aria-expanded="false" role="button">
                                    <i class="bi bi-currency-dollar icon-navbar"></i>
                                </a>
                            </div>
                            <div class="d-lg-none">
                                <a href="{{ url('notifications') }}" class="position-relative btn_mobile_nav"
                                    title="{{ trans('general.notifications') }}">
                                    <span
                                        class="noti_notifications notify @if (auth()->user()->unseenNotifications()) d-block @endif">
                                        {{ auth()->user()->unseenNotifications() }}
                                    </span>
                                    <i class="far fa-bell icon-navbar"></i>
                                </a>
                            </div>
                        </div>
                        <div class="buttons-mobile-nav d-lg-none">
                            <a class="btn-mobile-nav navbar-toggler-mobile btn_mobile_nav" href="#"
                                data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse"
                                aria-expanded="false" role="button">
                                <i class="feather icon-menu icon-navbar"></i>
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