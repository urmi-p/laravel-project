@extends('layouts.app')

@section('css')
<style type="text/css">
    /* COMMISSION PAGE */
    .commission-wrapper {
        color: #fff;
    }

    .commission-card {
        background: #111;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
    }

    .commission-card .label {
        font-size: 12px;
        color: #9aa0a6;
        text-transform: uppercase;
        letter-spacing: .5px;
        display: inline;
    }

    .commission-card h2,
    .commission-card h4 {
        margin: 10px 0;
        font-weight: 700;
    }

    .commission-card p {
        font-size: 13px;
        color: #8b8b8b;
        margin-bottom: 0;
    }

    .commission-box {
        background: #0c0c0c;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .4);
    }

    /* PROGRESS BAR */
    .commission-progress {
        height: 10px;
        background: #222;
        border-radius: 20px;
        overflow: hidden;
    }

    .commission-progress .creator {
        background: linear-gradient(90deg, #ff4d5a, #ff7a7a);
    }

    .commission-progress .platform {
        background: #5865f2;
    }

    .commission-progress .tax {
        background: #9b59b6;
    }

    /* INPUT */
    .dark-input {
        background: #111;
        border: 1px solid #222;
        color: #fff;
    }

    .dark-input:focus {
        background: #111;
        border-color: #ff4d5a;
        color: #fff;
        box-shadow: none;
    }

    .calc-icon {
        width: 22px;
        height: 22px;
        border-radius: 6px;
        background: #1c1c1c;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* TITLE */
    .calc-title {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .6px;
        color: #9aa0a6;
    }

    /* DESCRIPTION */
    .calc-desc {
        font-size: 13px;
        color: #8b8b8b;
        margin-bottom: 16px;
    }

    /* INPUT */
    .calc-label {
        font-size: 12px;
        color: #9aa0a6;
    }

    .calc-input {
        background: #0f0f0f;
        border: 1px solid #1f1f1f;
        color: #fff;
        height: 44px;
    }

    .calc-input:focus {
        background: #0f0f0f;
        border-color: #ff4d5a;
        box-shadow: none;
        color: #fff;
    }

    .calc-help {
        font-size: 11px;
        color: #6f6f6f;
    }

    /* BUTTON */
    .calc-btn {
        padding: 6px 18px;
        font-size: 13px;
        border-radius: 8px;
    }

    /* CARDS */
    .rev-card {
        background: #101010;
        border-radius: 12px;
        padding: 16px;
        height: 100%;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .4);
    }

    .rev-card p {
        font-size: 12px;
        color: #7c7c7c;
        margin-top: 8px;
    }

    .rev-label {
        font-size: 13px;
        font-weight: 500;
    }

    .rev-percent {
        font-size: 13px;
        font-weight: 600;
    }

    /* LABEL COLORS */
    .creator {
        color: #ff5a68;
    }

    .platform {
        color: #6c7bff;
    }

    .tax {
        color: #c77dff;
    }

    /* PROGRESS BAR */
    .rev-progress-wrapper {
        margin-top: 10px;
    }

    .rev-progress {
        width: 100%;
        height: 8px;
        background: #1e1e1e;
        border-radius: 20px;
        overflow: hidden;
        display: flex;
    }

    .rev-progress .bar {
        height: 100%;
        transition: width .4s ease;
    }

    .creator-bar {
        background: linear-gradient(90deg, #ff4d5a, #ff7a7a);
    }

    .platform-bar {
        background: #5865f2;
    }

    .tax-bar {
        background: #9b59b6;
    }

    /* HEADINGS & TEXT */
    [data-bs-theme="light"] .commission-wrapper h4,
    [data-bs-theme="light"] .commission-wrapper h2 {
        color: #111;
    }

    [data-bs-theme="light"] .commission-wrapper p {
        color: #555;
    }

    /* TOP STAT CARDS */
    [data-bs-theme="light"] .commission-card {
        background: #ffffff;
        border: 1px solid #e6e8ec;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    [data-bs-theme="light"] .commission-card .label {
        color: #555;
    }

    [data-bs-theme="light"] .commission-card h2 {
        color: #111;
    }

    /* REVENUE SPLIT CARDS */
    [data-bs-theme="light"] .rev-card {
        background: #ffffff;
        border: 1px solid #e6e8ec;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    [data-bs-theme="light"] .rev-card p {
        color: #666;
    }

    [data-bs-theme="light"] .rev-label {
        color: #333;
    }

    [data-bs-theme="light"] .rev-percent {
        color: #111;
    }

    /* PROGRESS BAR */
    [data-bs-theme="light"] .rev-progress {
        background: #eaecef;
    }

    [data-bs-theme="light"] .creator-bar {
        background: linear-gradient(90deg, #ff4d5a, #ff8a8a);
    }

    [data-bs-theme="light"] .platform-bar {
        background: #5865f2;
    }

    [data-bs-theme="light"] .tax-bar {
        background: #c084fc;
    }

    /* CALCULATOR INPUT */
    [data-bs-theme="light"] .calc-input {
        background: #ffffff;
        border: 1px solid #dcdfe4;
        color: #111;
    }

    [data-bs-theme="light"] .calc-input::placeholder {
        color: #888;
    }

    [data-bs-theme="light"] .calc-help {
        color: #777;
    }

    /* BUTTON */
    [data-bs-theme="light"] .calc-btn {
        border: none;
        color: #fff;
    }
    
    .calc-btn, .calc-btn:hover, .calc-btn:active{
        background: #e2394c;
    }

    /* SVG ICONS */
    [data-bs-theme="light"] .commission-card svg path,
    [data-bs-theme="light"] .rev-card svg path {
        opacity: 1;
    }
</style>
@endsection

@section('title') {{ __('general.platform_commission') }} - @endsection

@section('content')
<section class="section section-sm">
    @include('includes.header-mobile')
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">

        <div class="row app-main-row">
            <div class="col-lg-3 col-md-3 side_bar_box_shadow">
                @include('includes.menu-sidebar-home')
            </div>
            {{-- @include('includes.cards-settings') --}}

            <div class="col-md-9 col-lg-9 mb-3">

                <div class="commission-wrapper">
                    <h4 class="fw-bold font_weight_700 fs-24">{{ __('general.subscription_fees') }}</h4>
                    <p class="mb-4 font_weight_400 fs-14">
                        {{ __('general.subscription_fees_desc') }}
                    </p>

                    {{-- TOP STATS --}}
                    <div class="row g-3 mb-4 mobile_div">
                        <div class="col-lg-4 col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#FF6900" fill-opacity="0.1" />
                                    <g clip-path="url(#clip0_6275_2605)">
                                        <path d="M13.332 17.3334C15.5412 17.3334 17.332 15.5425 17.332 13.3334C17.332 11.1242 15.5412 9.33337 13.332 9.33337C11.1229 9.33337 9.33203 11.1242 9.33203 13.3334C9.33203 15.5425 11.1229 17.3334 13.332 17.3334Z" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.0612 14.9133C20.6914 15.1483 21.2522 15.5384 21.6917 16.0475C22.1312 16.5566 22.4352 17.1683 22.5757 17.826C22.7161 18.4838 22.6885 19.1663 22.4952 19.8106C22.302 20.4548 21.9494 21.0399 21.4702 21.5117C20.9909 21.9836 20.4003 22.327 19.7532 22.5101C19.106 22.6933 18.4231 22.7103 17.7677 22.5596C17.1122 22.4089 16.5053 22.0954 16.0031 21.648C15.5009 21.2006 15.1196 20.6338 14.8945 20" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.668 12H13.3346V14.6667" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19.1399 17.2533L19.6066 17.7267L17.7266 19.6067" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2605">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span class="label">{{ __('general.commission_creator_take_home') }}</span>
                                <h2>{{ number_format(100 - ($commission + $tax), 1) }}%</h2>
                                <p>{{ __('general.commission_creator_take_home_desc', ['percent' => number_format(100 - ($commission + $tax), 1)]) }}</p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#615FFF" fill-opacity="0.1" />
                                    <g clip-path="url(#clip0_6275_2619)">
                                        <path d="M21.3385 16.6666C21.3385 20 19.0052 21.6666 16.2319 22.6333C16.0867 22.6825 15.9289 22.6802 15.7852 22.6266C13.0052 21.6666 10.6719 20 10.6719 16.6666V12C10.6719 11.8232 10.7421 11.6536 10.8671 11.5286C10.9922 11.4035 11.1617 11.3333 11.3385 11.3333C12.6719 11.3333 14.3385 10.5333 15.4985 9.51997C15.6398 9.39931 15.8194 9.33301 16.0052 9.33301C16.191 9.33301 16.3706 9.39931 16.5119 9.51997C17.6785 10.54 19.3385 11.3333 20.6719 11.3333C20.8487 11.3333 21.0183 11.4035 21.1433 11.5286C21.2683 11.6536 21.3385 11.8232 21.3385 12V16.6666Z" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2619">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span class="label">{{ __('general.commission_platform_maintenance') }}</span>
                                <h2>{{ $commission }}%</h2>
                                <p>{{ __('general.commission_platform_maintenance_desc_top') }}</p>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#F6339A" fill-opacity="0.1" />
                                    <path d="M21.3281 11.3334H10.6615C9.92508 11.3334 9.32812 11.9303 9.32812 12.6667V19.3334C9.32812 20.0698 9.92508 20.6667 10.6615 20.6667H21.3281C22.0645 20.6667 22.6615 20.0698 22.6615 19.3334V12.6667C22.6615 11.9303 22.0645 11.3334 21.3281 11.3334Z" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.32812 14.6666H22.6615" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="label">{{ __('general.commission_payment_processing_tax') }}</span>
                                <h2>{{ $tax }}%</h2>
                                <p>{{ __('general.commission_payment_processing_tax_desc_top') }}</p>
                            </div>
                        </div>

                    </div>

                    {{-- REVENUE SPLIT --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-2 gap_10px">
                            <div class="calc-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#615FFF" fill-opacity="0.1" />
                                    <g clip-path="url(#clip0_6275_2664)">
                                        <path d="M13.332 17.3334C15.5412 17.3334 17.332 15.5425 17.332 13.3334C17.332 11.1242 15.5412 9.33337 13.332 9.33337C11.1229 9.33337 9.33203 11.1242 9.33203 13.3334C9.33203 15.5425 11.1229 17.3334 13.332 17.3334Z" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.0612 14.9133C20.6914 15.1483 21.2522 15.5384 21.6917 16.0475C22.1312 16.5566 22.4352 17.1683 22.5757 17.826C22.7161 18.4838 22.6885 19.1663 22.4952 19.8106C22.302 20.4548 21.9494 21.0399 21.4702 21.5117C20.9909 21.9836 20.4003 22.327 19.7532 22.5101C19.106 22.6933 18.4231 22.7103 17.7677 22.5596C17.1122 22.4089 16.5053 22.0954 16.0031 21.648C15.5009 21.2006 15.1196 20.6338 14.8945 20" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.668 12H13.3346V14.6667" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19.1399 17.2533L19.6066 17.7267L17.7266 19.6067" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2664">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <span class="calc-title">{{ __('general.commission_revenue_split') }}</span>
                        </div>
                        <!-- DESCRIPTION -->
                        <p class="calc-desc">
                            {{ __('general.commission_revenue_split_desc') }}
                        </p>

                        <!-- CARDS -->
                        <div class="row g-3 mb-3 mobile_div">
                            <div class="col-md-4">
                                <div class="rev-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap_10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <g clip-path="url(#clip0_6275_2677)">
                                                    <path d="M5.33203 9.33337C7.54117 9.33337 9.33203 7.54251 9.33203 5.33337C9.33203 3.12424 7.54117 1.33337 5.33203 1.33337C3.12289 1.33337 1.33203 3.12424 1.33203 5.33337C1.33203 7.54251 3.12289 9.33337 5.33203 9.33337Z" stroke="#2B7FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M12.0612 6.91333C12.6914 7.14828 13.2522 7.53835 13.6917 8.04746C14.1312 8.55657 14.4352 9.16829 14.5757 9.82604C14.7161 10.4838 14.6885 11.1663 14.4952 11.8106C14.302 12.4548 13.9494 13.0399 13.4702 13.5117C12.9909 13.9836 12.4003 14.327 11.7532 14.5101C11.106 14.6933 10.4231 14.7103 9.76765 14.5596C9.11217 14.4089 8.50528 14.0954 8.00308 13.648C7.50089 13.2006 7.11962 12.6338 6.89453 12" stroke="#2B7FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M4.66797 4H5.33464V6.66667" stroke="#2B7FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M11.1399 9.25334L11.6066 9.72667L9.72656 11.6067" stroke="#2B7FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_6275_2677">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <span class="rev-label">{{ __('general.commission_creator_take_home') }}</span>
                                        </div>
                                        <span class="rev-percent" data-value="{{ 100 - ($commission + $tax) }}">{{ number_format(100 - ($commission + $tax), 1) }}%</span>
                                    </div>
                                    <p>{{ __('general.commission_creator_take_home_card_desc') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="rev-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap_10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <g clip-path="url(#clip0_6275_2690)">
                                                    <path d="M13.3385 8.66664C13.3385 12 11.0052 13.6666 8.23188 14.6333C8.08665 14.6825 7.9289 14.6802 7.78521 14.6266C5.00521 13.6666 2.67188 12 2.67188 8.66664V3.99997C2.67188 3.82316 2.74211 3.65359 2.86714 3.52857C2.99216 3.40355 3.16173 3.33331 3.33854 3.33331C4.67188 3.33331 6.33854 2.53331 7.49854 1.51997C7.63978 1.39931 7.81944 1.33301 8.00521 1.33301C8.19097 1.33301 8.37064 1.39931 8.51188 1.51997C9.67854 2.53997 11.3385 3.33331 12.6719 3.33331C12.8487 3.33331 13.0183 3.40355 13.1433 3.52857C13.2683 3.65359 13.3385 3.82316 13.3385 3.99997V8.66664Z" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_6275_2690">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            <span class="rev-label">{{ __('general.commission_platform_maintenance') }}</span>
                                        </div>
                                        <span class="rev-percent" data-value="{{ $commission }}">{{ $commission }}%</span>
                                    </div>
                                    <p>{{ __('general.commission_platform_maintenance_card_desc') }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="rev-card">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap_10px">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M13.3359 3.33337H2.66927C1.93289 3.33337 1.33594 3.93033 1.33594 4.66671V11.3334C1.33594 12.0698 1.93289 12.6667 2.66927 12.6667H13.3359C14.0723 12.6667 14.6693 12.0698 14.6693 11.3334V4.66671C14.6693 3.93033 14.0723 3.33337 13.3359 3.33337Z" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1.33594 6.66663H14.6693" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span class="rev-label">{{ __('general.commission_payment_processing_tax') }}</span>
                                        </div>
                                        <span class="rev-percent" data-value="{{ $tax }}">{{ $tax }}%</span>
                                    </div>
                                    <p>{{ __('general.commission_payment_processing_tax_card_desc') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rev-progress-wrapper">
                            <div class="rev-progress">
                                <span class="bar creator-bar"></span>
                                <span class="bar platform-bar"></span>
                                <span class="bar tax-bar"></span>
                            </div>
                        </div>

                        <p class="calc-desc pt-2">{{ __('general.commission_adjust_payout_settings') }}</p>
                    </div>

                    {{-- EARNINGS CALCULATOR --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap_10px mb-2">
                            <div class="calc-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#364153" />
                                    <g clip-path="url(#clip0_6275_2771)">
                                        <path d="M20.0013 9.33337H12.0013C11.2649 9.33337 10.668 9.93033 10.668 10.6667V21.3334C10.668 22.0698 11.2649 22.6667 12.0013 22.6667H20.0013C20.7377 22.6667 21.3346 22.0698 21.3346 21.3334V10.6667C21.3346 9.93033 20.7377 9.33337 20.0013 9.33337Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.332 12H18.6654" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.668 17.3334V20" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M18.668 14.6666H18.6746" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 14.6667H16.0067" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.332 14.6666H13.3387" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 17.3333H16.0067" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.332 17.3334H13.3387" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 20H16.0067" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M13.332 20H13.3387" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2771">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <span class="calc-title">{{ __('general.commission_earnings_calculator') }}</span>
                        </div>
                        <!-- DESCRIPTION -->
                        <p class="calc-desc">
                            {{ __('general.commission_earnings_calculator_desc') }}
                        </p>
                        <div class="mb-2 input-group-sub">
                            <label class="form-label calc-label">{{ __('general.commission_plan_price') }}</label>
                            <input type="number" class="form-control calc-input" value="10.00">
                            <small class="calc-help">
                                {{ __('general.commission_plan_price_help') }}
                            </small>
                        </div>
                        <button class="btn btn-danger calc-btn mt-3">
                            {{ __('general.commission_recalculate') }}
                        </button>
                    </div>

                    {{-- BOTTOM RESULT --}}
                    <div class="row g-3 mobile_div">

                        <div class="col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#FF6900" fill-opacity="0.1" />
                                    <g clip-path="url(#clip0_6275_2605)">
                                        <path d="M13.332 17.3334C15.5412 17.3334 17.332 15.5425 17.332 13.3334C17.332 11.1242 15.5412 9.33337 13.332 9.33337C11.1229 9.33337 9.33203 11.1242 9.33203 13.3334C9.33203 15.5425 11.1229 17.3334 13.332 17.3334Z" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20.0612 14.9133C20.6914 15.1483 21.2522 15.5384 21.6917 16.0475C22.1312 16.5566 22.4352 17.1683 22.5757 17.826C22.7161 18.4838 22.6885 19.1663 22.4952 19.8106C22.302 20.4548 21.9494 21.0399 21.4702 21.5117C20.9909 21.9836 20.4003 22.327 19.7532 22.5101C19.106 22.6933 18.4231 22.7103 17.7677 22.5596C17.1122 22.4089 16.5053 22.0954 16.0031 21.648C15.5009 21.2006 15.1196 20.6338 14.8945 20" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M12.668 12H13.3346V14.6667" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M19.1399 17.2533L19.6066 17.7267L17.7266 19.6067" stroke="#FF6900" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2605">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span class="label">{{ __('general.commission_creator_keeps') }}</span>
                                <h4 id="calc-creator-keeps">€9.29</h4>
                                <p>{{ number_format(100 - ($commission + $tax), 1) }}%</p>
                                <p>{{ __('general.commission_creator_keeps_desc') }}</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#615FFF" fill-opacity="0.1" />
                                    <g clip-path="url(#clip0_6275_2619)">
                                        <path d="M21.3385 16.6666C21.3385 20 19.0052 21.6666 16.2319 22.6333C16.0867 22.6825 15.9289 22.6802 15.7852 22.6266C13.0052 21.6666 10.6719 20 10.6719 16.6666V12C10.6719 11.8232 10.7421 11.6536 10.8671 11.5286C10.9922 11.4035 11.1617 11.3333 11.3385 11.3333C12.6719 11.3333 14.3385 10.5333 15.4985 9.51997C15.6398 9.39931 15.8194 9.33301 16.0052 9.33301C16.191 9.33301 16.3706 9.39931 16.5119 9.51997C17.6785 10.54 19.3385 11.3333 20.6719 11.3333C20.8487 11.3333 21.0183 11.4035 21.1433 11.5286C21.2683 11.6536 21.3385 11.8232 21.3385 12V16.6666Z" stroke="#615FFF" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_6275_2619">
                                            <rect width="16" height="16" fill="white" transform="translate(8 8)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                <span class="label">{{ __('general.commission_platform_keeps') }}</span>
                                <h4 id="calc-platform-keeps">€0.50</h4>
                                <p>{{ $commission }}%</p>
                                <p>{{ __('general.commission_platform_keeps_desc') }}</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="commission-card">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <path d="M0 10C0 4.47715 4.47715 0 10 0H22C27.5228 0 32 4.47715 32 10V22C32 27.5228 27.5228 32 22 32H10C4.47715 32 0 27.5228 0 22V10Z" fill="#F6339A" fill-opacity="0.1" />
                                    <path d="M21.3281 11.3334H10.6615C9.92508 11.3334 9.32812 11.9303 9.32812 12.6667V19.3334C9.32812 20.0698 9.92508 20.6667 10.6615 20.6667H21.3281C22.0645 20.6667 22.6615 20.0698 22.6615 19.3334V12.6667C22.6615 11.9303 22.0645 11.3334 21.3281 11.3334Z" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9.32812 14.6666H22.6615" stroke="#F6339A" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="label">{{ __('general.commission_processing_tax') }}</span>
                                <h4 id="calc-processing-tax">€0.20</h4>
                                <p>{{ $tax }}% + {{ $tax_cents }}</p>
                                <p>{{ __('general.commission_processing_tax_desc') }}</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", () => {

        // Set Progress Bar Widths
        document.querySelector('.creator-bar').style.width = "{{ 100 - ($commission + $tax) }}%";
        document.querySelector('.platform-bar').style.width = "{{ $commission }}%";
        document.querySelector('.tax-bar').style.width = "{{ $tax }}%";

        // Calculator Logic
        const calcInput = document.querySelector('.calc-input');
        const calcBtn = document.querySelector('.calc-btn');
        const creatorKeepsEl = document.getElementById('calc-creator-keeps');
        const platformKeepsEl = document.getElementById('calc-platform-keeps');
        const processingTaxEl = document.getElementById('calc-processing-tax');

        const commissionPercent = parseFloat("{{ $commission }}");
        const taxPercent = parseFloat("{{ $tax }}");
        const taxCents = parseFloat("{{ $tax_cents }}");

        function formatEuro(amount) {
            return new Intl.NumberFormat('en-IE', {
                style: 'currency',
                currency: 'EUR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        }

        function calculateEarnings() {
            let price = parseFloat(calcInput.value);
            if (isNaN(price) || price < 0) price = 0;

            // Processor Fees (Tax)
            // Logic: Amount - (Amount * Tax% + Cents)
            let processorFeeAmount = (price * taxPercent / 100) + taxCents;
            let amountAfterProcessor = price - processorFeeAmount;

            // Platform Fee (Commission)
            // Logic: AmountAfterProcessor * Commission%
            let platformFeeAmount = amountAfterProcessor * commissionPercent / 100;

            // Creator Net
            let creatorNet = amountAfterProcessor - platformFeeAmount;

            // Update UI
            creatorKeepsEl.innerText = formatEuro(creatorNet);
            platformKeepsEl.innerText = formatEuro(platformFeeAmount);
            processingTaxEl.innerText = formatEuro(processorFeeAmount);
        }

        calcBtn.addEventListener('click', (e) => {
            e.preventDefault();
            calculateEarnings();
        });

        // Initial Calculation
        calculateEarnings();

    });
</script>
@endsection
