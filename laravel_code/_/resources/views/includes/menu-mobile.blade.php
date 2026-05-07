@php
	$isCreatorMobileMenu = auth()->user()->verified_id == 'yes';
	$homeActive = request()->is('/');
	$profileActive = request()->is('profile/' . auth()->user()->username . '*') || request()->is(auth()->user()->username . '*');
	$messageActive = request()->is('messages*');
	$lastActive = !$settings->disable_creators_section ? request()->is('creators*') : ($settings->shop ? request()->is('shop*') : false);
	$publishActive = request()->is('new/update')
		|| request()->is('create/reel')
		|| request()->is('create/story')
		|| request()->is('create/story/text');
@endphp

<div class="menuMobile d-md-none {{ $isCreatorMobileMenu ? 'menuMobile--creator' : '' }} {{ $isCreatorMobileMenu && $messageActive ? 'is-message-active' : '' }} {{ $isCreatorMobileMenu && $lastActive ? 'is-last-active' : '' }} {{ $isCreatorMobileMenu && $profileActive ? 'is-profile-active' : '' }} {{ $isCreatorMobileMenu && $publishActive ? 'is-publish-active' : '' }} {{ $isCreatorMobileMenu && $homeActive ? 'is-home-active' : '' }}">
	<ul class="list-inline d-flex bd-highlight m-0 text-center {{ $isCreatorMobileMenu ? 'menuMobile-nav--creator' : '' }}">
		<li class="flex-fill bd-highlight menu-item-home">
			<a class="btn-mobile @if ($homeActive) active disabled @endif" href="{{ url('/') }}" title="{{ trans('admin.home') }}">
				<i class="feather icon-home icon-navbar"></i>
				@if ($homeActive) <span class="font_weight_400 fs-14">{{ __('admin.home') }}</span> @endif
			</a>
		</li>
		<li class="flex-fill bd-highlight menu-item-profile">
			<a href="{{ url('profile', auth()->user()->username) }}" class="btn-mobile position-relative @if ($profileActive) active disabled @endif">
				<i class="far fa-user icon-navbar"></i>
				@if ($profileActive) <span class="font_weight_400 fs-14">{{ __('users.my_profile') }}</span> @endif
			</a>
		</li>

		@if ($isCreatorMobileMenu)
			<li class="flex-fill bd-highlight menu-mobile-publish-placeholder" aria-hidden="true"></li>
		@endif

		<li class="flex-fill bd-highlight menu-item-message">
			<a href="{{ url('messages') }}" class="btn-mobile position-relative @if ($messageActive) active disabled @endif" title="{{ trans('general.messages') }}">
				<span class="mobile-nav-icon-wrap">
					<i class="feather icon-send icon-navbar"></i>
					<span class="noti_msg notify @if (auth()->user()->messagesInbox() != 0) d-block @endif">
						{{ auth()->user()->messagesInbox() }}
					</span>
				</span>
				@if ($messageActive) <span class="font_weight_400 fs-14">{{ __('general.message') }}</span> @endif
			</a>
		</li>

		@if ($isCreatorMobileMenu)
			@if (!$settings->disable_creators_section)
				<li class="flex-fill bd-highlight menu-item-last">
					<a class="btn-mobile @if (request()->is('creators*')) active disabled @endif" href="{{ url('creators') }}" title="{{ trans('general.explore') }}">
						<i class="far fa-compass icon-navbar"></i>
						@if (request()->is('creators*')) <span class="font_weight_400 fs-14">{{ __('general.explore') }}</span> @endif
					</a>
				</li>
			@elseif ($settings->shop)
				<li class="flex-fill bd-highlight menu-item-last">
					<a class="btn-mobile @if (request()->is('shop*')) active disabled @endif" href="{{ url('shop') }}" title="{{ trans('general.shop') }}">
						<i class="feather icon-shopping-bag icon-navbar"></i>
						@if (request()->is('shop*')) <span class="font_weight_400 fs-14">{{ __('general.shop') }}</span> @endif
					</a>
				</li>
			@endif
		@else
			@if (!$settings->disable_creators_section)
				<li class="flex-fill bd-highlight">
					<a class="btn-mobile @if (request()->is('creators*')) active disabled @endif" href="{{ url('creators') }}" title="{{ trans('general.explore') }}">
						<i class="far fa-compass icon-navbar"></i>
						@if (request()->is('creators*')) <span class="font_weight_400 fs-14">{{ __('general.explore') }}</span> @endif
					</a>
				</li>
			@endif

			@if ($settings->shop)
				<li class="flex-fill bd-highlight">
					<a class="btn-mobile @if (request()->is('shop*')) active disabled @endif" href="{{ url('shop') }}" title="{{ trans('general.shop') }}">
						<i class="feather icon-shopping-bag icon-navbar"></i>
						@if (request()->is('shop*')) <span class="font_weight_400 fs-14">{{ __('general.shop') }}</span> @endif
					</a>
				</li>
			@endif
		@endif

	</ul>

	@if ($isCreatorMobileMenu)
		<div class="menu-mobile-publish-slot">
			<button type="button" class="btn-mobile btn-mobile-publish {{ $publishActive ? 'is-active is-disabled' : '' }}" @unless($publishActive) data-toggle="modal" data-target="#creatorPublishMenu" @endunless title="{{ __('general.publish') }}" @if($publishActive) aria-disabled="true" tabindex="-1" @endif>
				<i class="bi bi-plus-lg"></i>
				<span class="sr-only">{{ __('general.publish') }}</span>
			</button>
		</div>
	@endif
</div>
