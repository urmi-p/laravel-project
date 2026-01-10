@extends('layouts.app')
@section('css')
  <style type="text/css">
    [data-bs-theme="light"] .main_heading_shop {
      color: #FFFFFF !important;
    }
  </style>
@endsection
@section('title')
    {{ __('general.shop') }} -
@endsection

@section('content')
    <section class="section section-sm">
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2">
            <div class="row mb-sm">
                <div class="col-lg-12 py-5">
                    <h2 class="mb-0 text-break font_weight_700 fs-24 pb-3 main_heading_shop">
                        {{ __('general.shop') }} @if (request('tags'))
                            "{{ request('tags') }}"
                        @endif

                        @if (request('cat'))
                            - {{ __('general.category') }}
                            "{{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}"
                        @endif

                    </h2>
                    <p class="lead m-0 font_weight_400 fs-14">{{ trans('general.explore_products_creators') }}
                        @guest

                            @if ($settings->registration_active == '1')
                                <a href="{{ url('signup') }}" class="link-border">{{ trans('general.join_now') }}</a>
                            @endif

                        @endguest

                        @if (auth()->check() && auth()->user()->verified_id == 'yes')
                            <span class="d-block mt-2 w-100">

                                @if ($settings->digital_product_sale && !$settings->custom_content && !$settings->physical_products)
                                    <a class="btn btn-primary" href="{{ url('add/product') }}">

                                        <i class="bi-plus"></i> {{ __('general.add_product') }}

                                    </a>
                                @elseif (!$settings->digital_product_sale && $settings->custom_content && !$settings->physical_products)
                                    <a class="btn btn-primary" href="{{ url('add/custom/content') }}">

                                        <i class="bi-plus"></i> {{ __('general.add_custom_content') }}

                                    </a>
                                @elseif (!$settings->digital_product_sale && $settings->physical_products && !$settings->custom_content)
                                    <a class="btn btn-primary" href="{{ url('add/physical/product') }}">

                                        <i class="bi-plus"></i> {{ __('general.add_physical_product') }}

                                    </a>
                                @else
                                    <a class="btn btn-primary" href="#" data-toggle="modal"
                                        data-target="#addItemForm">

                                        <i class="bi-plus"></i> {{ __('general.add_new') }}

                                    </a>
                                @endif
                            </span>
                        @endif
                    </p>
                </div>
            </div>
            <div class="row">
                @if ($products->total() != 0)
                    <div class="col-md-12 mb-4">
                        <div class="btn-block mb-3">
                            <span>
                                <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto" id="filter">
                                    <option @if (!request()->get('sort')) selected @endif value="{{ url('shop') }}">
                                        {{ trans('general.latest') }}</option>

                                    <option @if (request()->get('sort') == 'oldest') selected @endif
                                        value="{{ url('shop?sort=oldest') }}">{{ trans('general.oldest') }}</option>

                                    <option @if (request()->get('sort') == 'priceMin') selected @endif
                                        value="{{ url('shop?sort=priceMin') }}">{{ trans('general.lowest_price') }}
                                    </option>
                                    <option @if (request()->get('sort') == 'priceMax') selected @endif
                                        value="{{ url('shop?sort=priceMax') }}">{{ trans('general.highest_price') }}
                                    </option>

                                    @if ($settings->physical_products)
                                        <option @if (request()->get('sort') == 'physical') selected @endif
                                            value="{{ url('shop?sort=physical') }}">
                                            {{ trans('general.physical_products') }}</option>
                                    @endif

                                    <option @if (request()->get('sort') == 'digital') selected @endif
                                        value="{{ url('shop?sort=digital') }}">{{ trans('general.digital_products') }}
                                    </option>

                                    <option @if (request()->get('sort') == 'custom') selected @endif
                                        value="{{ url('shop?sort=custom') }}">{{ trans('general.custom_content') }}
                                    </option>

                                </select>

                                @if ($categories->count())
                                    <select class="ml-2 custom-select mb-2 mb-lg-0 w-auto filter">

                                        <option @if (!request()->get('cat')) selected @endif
                                            value="{{ url('shop') }}">{{ trans('general.all_categories') }}</option>

                                        @foreach ($categories as $category)
                                            <option @if (request()->get('cat') == $category->slug) selected @endif
                                                value="{{ url("shop?cat=$category->slug") }}">

                                                {{ Lang::has('shop-categories.' . $category->slug) ? __('shop-categories.' . $category->slug) : $category->name }}

                                            </option>
                                        @endforeach

                                    </select>
                                @endif

                            </span>

                        </div>

                        <div class="row">
                            @foreach ($products as $product)
                                <div class="col-md-4 mb-4">

                                    @include('shop.listing-products')

                                </div><!-- end col-md-4 -->
                            @endforeach

                            @if ($products->hasPages())
                                <div class="w-100 d-block">

                                    {{ $products->onEachSide(0)->appends(['tags' => request('tags'), 'sort' => request('sort')])->links() }}

                                </div>
                            @endif

                        </div><!-- row -->

                    </div><!-- col-md-9 -->
                @else
                    <div class="col-md-12">

                        <div class="my-5 text-center no-updates">

                            <span class="btn-block mb-3">
                                <i class="feather icon-shopping-bag ico-no-result"></i>
                            </span>
                            <h4 class="font-weight-light">{{ trans('general.no_results_found') }}</h4>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    @includeWhen(auth()->check() && auth()->user()->verified_id == 'yes', 'shop.modal-add-item')

@endsection
