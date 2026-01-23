<ul class="list-unstyled d-lg-block d-none menu-left-home sticky-top">
	{{-- USER PROFILE HEADER --}}
	<li class="sidebar-user mb-3">
		<div class="user_avatar">
			<img
				src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}"
				alt="User Avatar">
		</div>

		<div class="user_info">
			<h6 class="mb-0">
				{{ Auth::user()->name }}
			</h6>
			<small>
				{{ Auth::user()->email }}
			</small>
			<a href="{{ url(auth()->user()->username) }}" class="btn_profile">
				My Profile
			</a>
		</div>
	</li>
	@if (auth()->user()->verified_id == 'yes')
	<li class="sidebar-card">
		<div class="card-icon">
			<i class="fas fa-dollar-sign"></i>
		</div>
		<div class="card-info">
			<small>Earnings</small>
			<strong>{{ Helper::amountFormatDecimal(auth()->user()->balance) }}</strong>
		</div>
	</li>
	@endif

	{{-- WALLET BALANCE --}}
	<li class="sidebar-card">
		<div class="card-icon">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M19 12C19 12.2652 18.8946 12.5196 18.7071 12.7071C18.5196 12.8946 18.2652 13 18 13C17.7348 13 17.4804 12.8946 17.2929 12.7071C17.1054 12.5196 17 12.2652 17 12C17 11.7348 17.1054 11.4804 17.2929 11.2929C17.4804 11.1054 17.7348 11 18 11C18.2652 11 18.5196 11.1054 18.7071 11.2929C18.8946 11.4804 19 11.7348 19 12Z" fill="currentcolor"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M9.944 3.25H13.056C14.894 3.25 16.35 3.25 17.489 3.403C18.661 3.561 19.61 3.893 20.359 4.641C21.283 5.566 21.578 6.804 21.685 8.411C22.262 8.664 22.698 9.201 22.745 9.881C22.75 9.942 22.75 10.007 22.75 10.067V13.933C22.75 13.993 22.75 14.058 22.746 14.118C22.698 14.798 22.262 15.336 21.685 15.59C21.578 17.196 21.283 18.434 20.359 19.359C19.61 20.107 18.661 20.439 17.489 20.597C16.349 20.75 14.894 20.75 13.056 20.75H9.944C8.106 20.75 6.65 20.75 5.511 20.597C4.339 20.439 3.39 20.107 2.641 19.359C1.893 18.61 1.561 17.661 1.403 16.489C1.25 15.349 1.25 13.894 1.25 12.056V11.944C1.25 10.106 1.25 8.65 1.403 7.511C1.561 6.339 1.893 5.39 2.641 4.641C3.39 3.893 4.339 3.561 5.511 3.403C6.651 3.25 8.106 3.25 9.944 3.25ZM20.168 15.75H18.23C16.085 15.75 14.249 14.122 14.249 12C14.249 9.878 16.085 8.25 18.229 8.25H20.167C20.053 6.909 19.796 6.2 19.297 5.702C18.874 5.279 18.294 5.025 17.288 4.89C16.261 4.752 14.906 4.75 12.999 4.75H9.999C8.092 4.75 6.738 4.752 5.709 4.89C4.704 5.025 4.124 5.279 3.701 5.702C3.278 6.125 3.025 6.705 2.89 7.71C2.752 8.738 2.75 10.092 2.75 11.999C2.75 13.906 2.752 15.261 2.89 16.289C3.025 17.294 3.279 17.874 3.702 18.297C4.125 18.72 4.705 18.974 5.711 19.109C6.739 19.247 8.093 19.249 10 19.249H13C14.907 19.249 16.262 19.247 17.29 19.109C18.295 18.974 18.875 18.72 19.298 18.297C19.797 17.799 20.054 17.091 20.168 15.749M5.25 8C5.25 7.80109 5.32902 7.61032 5.46967 7.46967C5.61032 7.32902 5.80109 7.25 6 7.25H10C10.1989 7.25 10.3897 7.32902 10.5303 7.46967C10.671 7.61032 10.75 7.80109 10.75 8C10.75 8.19891 10.671 8.38968 10.5303 8.53033C10.3897 8.67098 10.1989 8.75 10 8.75H6C5.80109 8.75 5.61032 8.67098 5.46967 8.53033C5.32902 8.38968 5.25 8.19891 5.25 8ZM20.924 9.75H18.23C16.806 9.75 15.749 10.809 15.749 12C15.749 13.191 16.806 14.25 18.229 14.25H20.947C21.153 14.237 21.242 14.098 21.249 14.014V9.986C21.242 9.902 21.153 9.763 20.947 9.751L20.924 9.75Z" fill="currentcolor"/>
			</svg>

		</div>
		<div class="card-info">
			<small>Wallet Balance</small>
			<strong>{{ Helper::userWallet() }}</strong>
		</div>
	</li>
	<li class="sidebar_li">
		<a href="{{url('/')}}" @if (request()->is('/')) class="active disabled" @endif>
			<i class="bi-house-door"></i>
			<span class="ml-2">{{ __('admin.home') }}</span>
		</a>
	</li>

	@if ($settings->allow_reels && auth()->user()?->getReelsActive() || $settings->allow_reels && auth()->guest())
	<li class="sidebar_li">
		<a href="{{ url('reels') }}" @if (auth()->guest() && $reelsPublic == 0) data-toggle="modal" data-target="#loginFormModal" @endif>
			<svg xmlns="http://www.w3.org/2000/svg" class="align-text-bottom me-2" fill="currentColor" width="19" height="19" viewBox="0 0 50 50">
				<path d="M 15 4 C 8.9365932 4 4 8.9365932 4 15 L 4 35 C 4 41.063407 8.9365932 46 15 46 L 35 46 C 41.063407 46 46 41.063407 46 35 L 46 15 C 46 8.9365932 41.063407 4 35 4 L 15 4 z M 16.740234 6 L 27.425781 6 L 33.259766 16 L 22.574219 16 L 16.740234 6 z M 29.740234 6 L 35 6 C 39.982593 6 44 10.017407 44 15 L 44 16 L 35.574219 16 L 29.740234 6 z M 14.486328 6.1035156 L 20.259766 16 L 6 16 L 6 15 C 6 10.199833 9.7581921 6.3829803 14.486328 6.1035156 z M 6 18 L 44 18 L 44 35 C 44 39.982593 39.982593 44 35 44 L 15 44 C 10.017407 44 6 39.982593 6 35 L 6 18 z M 21.978516 23.013672 C 20.435152 23.049868 19 24.269284 19 25.957031 L 19 35.041016 C 19 37.291345 21.552344 38.713255 23.509766 37.597656 L 31.498047 33.056641 C 33.442844 31.951609 33.442844 29.044485 31.498047 27.939453 L 23.509766 23.398438 L 23.507812 23.398438 C 23.018445 23.120603 22.49297 23.001607 21.978516 23.013672 z M 21.982422 24.986328 C 22.158626 24.988232 22.342399 25.035052 22.521484 25.136719 L 30.511719 29.677734 C 31.220922 30.080703 31.220922 30.915391 30.511719 31.318359 L 22.519531 35.859375 C 21.802953 36.267773 21 35.808686 21 35.041016 L 21 25.957031 C 21 25.573196 21.201402 25.267385 21.492188 25.107422 C 21.63758 25.02744 21.806217 24.984424 21.982422 24.986328 z" stroke="currentColor" stroke-width="3" fill="none"></path>
			</svg>
			<span class="ml-2">{{ __('general.reels') }}</span>
		</a>
	</li>
	@endif

	@auth
	<li class="sidebar_li">
		<a href="{{ url(auth()->user()->username) }}">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M12.0002 12.75C8.83016 12.75 6.25016 10.17 6.25016 7C6.25016 3.83 8.83016 1.25 12.0002 1.25C15.1702 1.25 17.7502 3.83 17.7502 7C17.7502 10.17 15.1702 12.75 12.0002 12.75ZM12.0002 2.75C9.66016 2.75 7.75016 4.66 7.75016 7C7.75016 9.34 9.66016 11.25 12.0002 11.25C14.3402 11.25 16.2502 9.34 16.2502 7C16.2502 4.66 14.3402 2.75 12.0002 2.75ZM20.5902 22.75C20.1802 22.75 19.8402 22.41 19.8402 22C19.8402 18.55 16.3202 15.75 12.0002 15.75C7.68016 15.75 4.16016 18.55 4.16016 22C4.16016 22.41 3.82016 22.75 3.41016 22.75C3.00016 22.75 2.66016 22.41 2.66016 22C2.66016 17.73 6.85016 14.25 12.0002 14.25C17.1502 14.25 21.3402 17.73 21.3402 22C21.3402 22.41 21.0002 22.75 20.5902 22.75Z" fill="currentcolor"/>
			</svg>

			<span class="ml-2">{{ auth()->user()->verified_id == 'yes' ? __('users.my_profile') : __('users.my_profile') }}</span>
		</a>
	</li>
	 {{-- @if (auth()->user()->verified_id == 'yes') --}}
	<!--<li>
		<a href="{{ url('dashboard') }}">
			<i class="bi-speedometer2"></i>
			<span class="ml-2">{{ __('admin.dashboard') }}</span>
		</a>
	</li>-->
	{{-- @endif  --}}

	<li class="sidebar_li">
		<a href="{{ url('my/purchases') }}" @if (request()->is('my/purchases')) class="active disabled" @endif>
			<i class="bi bi-shop"></i>
			<span class="ml-2">{{ __('general.purchased') }}</span>
		</a>
	</li>

	<li class="sidebar_li">
		<a href="{{ url('messages') }}" @if (request()->is('messages')) class="active disabled" @endif>
			<i class="bi bi-chat-square-text"></i>
			<span class="ml-2">{{ __('general.messages') }}</span>
		</a>
	</li>

	@if ($settings->disable_wallet == 'off')
	<li class="sidebar_li">
		<a href="{{url('my/wallet')}}" @if (request()->is('my/wallet')) class="active disabled" @endif>
			<i class="iconmoon icon-Wallet"></i>
			<span class="ml-2">{{__('general.wallet')}}</span>
		</a>	
	</li>
	@endif
	@if (!$settings->disable_explore_section)
	<li class="sidebar_li">
		<a href="{{ url('explore') }}" @if (request()->is('explore')) class="active disabled" @endif>
			<i class="bi-compass"></i>
			<span class="ml-2">{{ __('general.explore') }}</span>
		</a>
	</li>
	@endif

	<li class="sidebar_li">
		<a href="{{ url('my/subscriptions') }}" @if (request()->is('my/subscriptions')) class="active disabled" @endif>
			<i class="bi bi-cart"></i>
			<span class="ml-2">{{ __('admin.subscriptions') }}</span>
		</a>
	</li>
	
	@if (auth()->user()->verified_id == 'yes')
	<li class="sidebar_li">
		<a href="{{ url('settings/subscription') }}" @if (request()->is('settings/subscription')) class="active disabled" @endif>
			<i class="bi bi-cash-stack"></i>
			<span class="ml-2">{{ __('general.subscription_price') }}</span>
		</a>
	</li>
	
	<li class="sidebar_li">
		<a href="{{ url('my/commission') }}" @if (request()->is('my/commission')) class="active disabled" @endif>
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="currentcolor" stroke-width="1.5"/>
				<path d="M14.7102 10.0611C14.6111 9.29844 13.7354 8.06622 12.1608 8.06619C10.3312 8.06616 9.56136 9.07946 9.40515 9.58611C9.16145 10.2638 9.21019 11.6571 11.3547 11.809C14.0354 11.999 15.1093 12.3154 14.9727 13.956C14.836 15.5965 13.3417 15.951 12.1608 15.9129C10.9798 15.875 9.04764 15.3325 8.97266 13.8733M11.9734 6.99805V8.06982M11.9734 15.9031V16.998" stroke="currentcolor" stroke-width="1.5" stroke-linecap="round"/>
			</svg>
			<span class="ml-2">{{ __('admin.commission') }}</span>
		</a>
	</li>
	@endif
	@if (auth()->user()->verified_id == 'yes')
	<li class="sidebar_li">
		<a href="{{ url('my/balance') }}"
			class="@if (request()->is('my/balance')) active @endif">
			<div>
				<i class="bi bi-credit-card mr-2"></i>
				<span>{{ __('general.balance') }}</span>
			</div>
		</a>
	</li>
	@endif
	<li class="sidebar_li">
		<a href="{{ url('my/bookmarks') }}" @if (request()->is('my/bookmarks')) class="active disabled" @endif>
			<i class="bi-bookmark"></i>
			<span class="ml-2">{{ __('general.bookmarks') }}</span>

		</a>
	</li>
	<li class="sidebar_li">
		<a href="{{auth()->user()->dark_mode == 'off' ? url('mode/dark') : url('mode/light')}}">
			<i class="feather icon-{{ auth()->user()->dark_mode == 'off' ? 'moon' : 'sun'  }}"></i>
			<span class="ml-2">{{ auth()->user()->dark_mode == 'off' ? __('general.dark_mode') : __('general.light_mode') }} </span>
		</a>
	</li>
	<li class="sidebar_li">
		<a href="{{route('user.settings')}}" @if (request()->is('settings/user')) class="active disabled" @endif>
			<i class="bi bi-gear"></i>
			<span class="ml-2">{{ __('general.settings') }}</span>
		</a>
	</li>
	<li class="sidebar_li">
		<a href="{{ url('logout') }}">
			<i class="feather icon-log-out"></i>
			<span class="ml-2">{{ __('auth.logout') }}</span>
		</a>
	</li>
	@else

	<li class="sidebar_li">
		<a href="{{ url('creators') }}">
			<i class="bi-compass"></i>
			<span class="ml-2">{{ __('general.explore') }}</span>
		</a>
	</li>
	@if ($settings->shop)
	<li class="sidebar_li">
		<a href="{{ url('shop') }}">
			<i class="feather icon-shopping-bag"></i>
			<span class="ml-2">{{ __('general.shop') }}</span>
		</a>
	</li>
	@endif
	@endauth
</ul>