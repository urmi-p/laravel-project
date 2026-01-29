@auth

    <div class="d-lg-none  d-flex justify-content-center">
        <a class="nav-link {{ request()->is('dashboard') ? 'font_mobile_bold' : 'font_mobile_normal' }}" href="{{ url('dashboard') }}"
            title="{{ __('admin.dashboard') }}">
            {{ __('admin.dashboard') }}
        </a>
        @if (!$settings->disable_creators_section)
            <a class="nav-link {{ request()->is('creators*') ? 'font_mobile_bold' : 'font_mobile_normal' }}" href="{{ url('creators') }}"
                title="{{ __('general.explore_creators') }}">
                {{ __('general.explore_creators') }}
            </a>
        @endif

        @if ($settings->shop)
            <a class="nav-link {{ request()->is('shop*') ? 'font_mobile_bold' : 'font_mobile_normal' }}" href="{{ url('shop') }}"
                title="{{ __('general.explore_shop') }}">
                {{ __('general.explore_shop') }}
            </a>
        @endif
    </div>
@endauth

@guest
    <div class="d-lg-none d-flex justify-content-center">
        @if (!$settings->disable_creators_section)
            <a class="nav-link {{ request()->is('creators*') ? 'font_mobile_bold' : 'font_mobile_normal' }}"
                href="{{ url('creators') }}" title="{{ __('general.explore_creators') }}">
                {{ __('general.explore_creators') }}
            </a>
        @endif
        @if ($settings->shop)
            <a class="nav-link {{ request()->is('shop*') ? 'font_mobile_bold' : 'font_mobile_normal' }}" href="{{ url('shop') }}"
                title="{{ __('general.explore_shop') }}">
                {{ __('general.explore_shop') }}
            </a>
        @endif
    </div>
@endguest
