@extends('layouts.app')

@section('title')
{{ $title }} -
@endsection

@section('css')
<style>
@media (min-width: 992px) {
    .section-sm.creator .creators-page-container {
        padding-left: calc(var(--bs-gutter-x, 2.0625rem) * 0.5) !important;
    }
}

@media (max-width: 991px) {
    .section-sm.creator .menu-left-home {
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        border: 0 !important;
        margin-bottom: 10px !important;
    }

    .section-sm.creator .menu-left-home .btn-menu-expand {
        margin-bottom: 8px !important;
    }

    html[data-bs-theme="light"] .app-auth-shell .section-sm.creator .menu-left-home.side_bar_box_shadow {
        background: transparent !important;
        border: 0 !important;
        box-shadow: none !important;
    }

    .section-sm.creator .col-md-9 > .row,
    .section-sm.creator #containerWrapCreators {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .section-sm.creator .menu-left-home .btn-category {
        width: 100%;
        white-space: normal;
        word-break: break-word;
        text-align: left;
    }

    .section-sm.creator {
        overflow-x: hidden;
    }

    .section-sm.creator .card-user-profile {
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }

    .section-sm.creator .card-user-profile .card-title,
    .section-sm.creator .card-user-profile .card-text,
    .section-sm.creator .card-user-profile .list-inline,
    .section-sm.creator .card-user-profile .list-inline-item {
        max-width: 100%;
        overflow-wrap: anywhere;
        word-break: break-word;
    }
}
</style>
@endsection

@section('content')
<section class="section section-sm creator">

    @include('includes.header-mobile')

    <div class="container-fluid pt-lg-5 pt-2 px-lg-5 creators-page-container">
        <div class="row app-main-row">
            <div class="col-md-3 mb-4 menu-left-home side_bar_box_shadow">
                @if (!$settings->disable_creators_section)
                @include('includes.menu-filters-creators')
                @endif

                @include('includes.listing-categories')

            </div><!-- end col-md-3 -->

            @if ($users->count() != 0)
            <div class="col-md-9 mb-4">
                <div class="row mb-sm">

                    <div class="col-lg-12">

                        <h2 class="mb-0 text-break font_weight_700 fs-24">{{ $title }}</h2>

                        <p class="mt-2 font_weight_400 fs-14">
                            {{ __('users.the_best_creators_is_here') }}

                            @guest

                            @if ($settings->registration_active == '1')
                            <a href="{{ url('signup') }}" class="link-border">{{ __('general.join_now') }}</a>
                            @endif

                            @endguest

                            @auth

                            @if (!$settings->disable_explore_section)
                            <a href="{{ url('explore') }}"
                                class="link-border">{{ __('general.explore_posts') }}</a>
                            @endif

                            @endauth
                        </p>
                    </div>
                </div>
                <div class="row" id="containerWrapCreators">
                    @foreach ($users as $response)
                    <div class="col-md-6 mb-4">

                        @include('includes.listing-creators')

                    </div><!-- end col-md-4 -->
                    @endforeach

                    @include('includes.paginator-creators')

                </div><!-- row -->

            </div><!-- col-md-9 -->
            @else
            <div class="col-md-9">
                <div class="text-center no-updates main-no-updates">
                  <div class="sub-no-updates">
                    <span class="btn-block mb-3">
                        <i class="fa fa-user-slash ico-no-result bg_black"></i>
                    </span>
                    <h4 class="font_weight_400 font_size_18">{{ __('general.no_results_found') }}</h4>
                  </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script src="{{ url('public/js/paginator-creators.js') }}?v={{ $settings->version }}"></script>
@endsection
