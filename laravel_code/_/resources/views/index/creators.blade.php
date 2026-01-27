@extends('layouts.app')

@section('title')
{{ $title }} -
@endsection

@section('content')
<section class="section section-sm creator">

    @include('includes.header-mobile')

    <div class="container-fluid pt-lg-5 pt-2">
        <div class="row ">
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

                        <p class="mt-0 font_weight_400 fs-14">
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
                <div class="my-5 text-center no-updates main-no-updates">
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