@extends('layouts.app')
@section('css')
    <style>
        .fileuploader-items {
            white-space: unset !important;
        }

        .fileuploader-item:nth-child(1) {
            margin-left: 16px !important;
        }

        #formUpdateCreate .fileuploader {
            width: 100%;
            margin: 0 0 24px !important;
            padding: 0 !important;
            background: transparent !important;
            border-radius: 0 !important;
            min-height: 0 !important;
        }

        #formUpdateCreate .rounded-large {
            position: relative;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input {
            min-height: 674px !important;
            border: 0 !important;
            border-radius: 20px !important;
            background: #333438 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-inner {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            width: 100%;
            max-width: 320px;
            padding: 0;
            margin: 0 auto;
            text-align: center;
            background: transparent !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input p {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main {
            width: 80px;
            height: 80px;
            border-radius: 999px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            background: #191919 !important;
            font-size: 0 !important;
            position: relative;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main::before {
            content: "" !important;
            width: 32px !important;
            height: 32px !important;
            display: inline-block;
            background: none !important;
            border: 2px solid #fff;
            border-radius: 999px;
            box-sizing: border-box;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-icon-main::after {
            content: "!";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -53%);
            color: #fff;
            font-size: 19px;
            font-weight: 500;
            line-height: 1;
            font-family: Poppins, sans-serif;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3 {
            width: 283px;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 0 !important;
            line-height: 0 !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3 span {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
            content: "Drag and drop an image or click\Ato upload";
            white-space: pre-line;
            display: block;
            color: #fff;
            font-family: Poppins, sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 28px;
            letter-spacing: -0.439453px;
            margin: 0;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button {
            width: 157px !important;
            height: 48px !important;
            border: 0 !important;
            border-radius: 8px !important;
            background: #191919 !important;
            color: #fff !important;
            box-shadow: none !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 12px;
            padding: 12px 8px !important;
            margin: 0 !important;
            position: static !important;
            transform: none !important;
            overflow: hidden;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button span {
            display: none !important;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button::before {
            content: "";
            width: 24px;
            height: 24px;
            flex: 0 0 24px;
            display: inline-block;
            background: url("data:image/svg+xml;utf8,<svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M12 4V14' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/><path d='M9 7L12 4L15 7' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>") no-repeat center / contain;
        }

        #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input-button::after {
            content: "Choose File";
            color: #fff;
            font-family: Poppins, sans-serif;
            font-weight: 600;
            font-size: 18px;
            line-height: 27px;
        }

        @media (max-width: 768px) {
            #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input {
                min-height: 560px !important;
                border-radius: 16px !important;
            }

            #formUpdateCreate .fileuploader-theme-dragdrop .fileuploader-input h3::after {
                font-size: 16px;
                line-height: 24px;
            }
        }

        #formUpdateCreate .fileuploader-items {
            display: none !important;
        }

        #formUpdateCreate.step-preview .fileuploader,
        #formUpdateCreate.step-details .fileuploader {
            display: none !important;
        }

        #formUpdateCreate.step-upload #postPreviewStep,
        #formUpdateCreate.step-upload #postDetailsStep {
            display: none !important;
        }

        .post-preview-step {
            display: none;
            background: #333438;
            border-radius: 20px;
            padding: 24px;
            min-height: 674px;
            position: relative;
            overflow: hidden;
        }

        .post-preview-step.active {
            display: block;
        }

        .post-preview-back {
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            background: rgba(25, 25, 25, 0.5);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .post-details-step {
            display: none;
        }

        .post-details-step.active {
            display: block;
        }

        .post-details-back-wrap {
            margin-bottom: 12px;
        }

        .post-details-back {
            width: 36px;
            height: 36px;
            border: 0;
            border-radius: 10px;
            background: rgba(25, 25, 25, 0.5);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .post-preview-media {
            margin-top: 12px;
            border-radius: 16px;
            overflow: hidden;
            height: 560px;
            background: #2d2f36;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .post-preview-media img {
            max-width: 100%;
            max-height: 100%;
            transform-origin: center center;
        }

        .post-preview-controls {
            position: absolute;
            left: 24px;
            right: 24px;
            bottom: 20px;
        }

        .upload-processing-overlay {
            display: none;
            position: absolute;
            inset: 0;
            border-radius: 20px;
            background: rgba(25, 25, 25, 0.86);
            z-index: 5;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #fff;
            gap: 10px;
        }

        .upload-processing-overlay.active {
            display: flex;
        }

        .upload-processing-spinner {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: #fff;
            animation: post-upload-spin 0.9s linear infinite;
        }

        .upload-processing-text {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        @keyframes post-upload-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .post-preview-range {
            width: 100%;
            margin-bottom: 16px;
            -webkit-appearance: none;
            appearance: none;
            height: 14px;
            border-radius: 999px;
            background: linear-gradient(to right, #030213 var(--zoom-value, 100%), #d9d9df var(--zoom-value, 100%));
            outline: none;
        }

        .post-preview-range::-webkit-slider-runnable-track {
            height: 14px;
            border-radius: 999px;
            background: transparent;
        }

        .post-preview-range::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 28px;
            height: 28px;
            margin-top: -7px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid #030213;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }

        .post-preview-range::-moz-range-track {
            height: 14px;
            border-radius: 999px;
            background: #d9d9df;
        }

        .post-preview-range::-moz-range-progress {
            height: 14px;
            border-radius: 999px;
            background: #030213;
        }

        .post-preview-range::-moz-range-thumb {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fff;
            border: 6px solid #030213;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.25);
            cursor: pointer;
        }

        .post-preview-zoom-level {
            display: block;
            color: #fff;
            font-size: 13px;
            margin-bottom: 10px;
            text-align: right;
        }

        .post-preview-continue {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: #e53b54;
            color: #fff;
            font-weight: 700;
            padding: 12px 14px;
        }

        /* .advanced-settings {
            color: #fff;
        } */

        .setting-label {
            display: block;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .visibility-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .visibility-btn {
            background: #2a2a2a;
            border: none;
            color: #ddd;
            padding: 8px 14px;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.2s;
            font-size: 14px;
        }

        .visibility-btn:hover {
            background: #3a3a3a;
        }

        .visibility-btn.active {
            background: #444;
            color: #fff;
        }

        .visibility-btn.is-disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .setting-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 5px;
        }

        /* Toggle Switch */
        .switch_update {
            position: relative;
            display: inline-block;
            width: 46px;
            height: 24px;
        }

        .switch_update input {
            display: none;
        }

        .slider_update {
            position: absolute;
            cursor: pointer;
            background-color: #555;
            border-radius: 24px;
            inset: 0;
            transition: .3s;
        }

        .slider_update:before {
            content: "";
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: white;
            border-radius: 50%;
            transition: .3s;
        }

        .switch_update input:checked + .slider_update {
            background-color: #ff4d6d;
        }

        .switch_update input:checked + .slider_update:before {
            transform: translateX(22px);
        }

        .setting-help {
            display: block;
            margin-top: 8px;
            color: #9ca3af;
            font-size: 12px;
            line-height: 1.4;
        }

        .description-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

    </style>
@endsection

@section('content')
    <section class="section section-sm">
        {{-- for mobile header --}}
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
            <div class="row">
                <div class="col-lg-3 col-md-4 side_bar_box_shadow">
                    @include('includes.menu-sidebar-home')
                </div>
                <div class="col-lg-6 col-md-8 p-0">
                        @include('includes.alert-payment-disabled')
                        <div class="progress-wrapper px-3 px-lg-0 display-none mb-3" id="progress">
                            <div class="progress-info">
                                <div class="progress-percentage">
                                    <span class="percent">0%</span>
                                </div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="row mb-sm">
                        <div class="col-lg-8">
                            <h4 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">
                                <a href="javascript:history.back();" class="text-decoration-none mr-2"
                                    title="{{ __('general.go_back') }}">
                                    <i class="fas fa-arrow-left"></i>
                                </a> <span style="text-align: center">New Post</span>
                            </h4>
                        </div>
                    </div><!-- row -->

                    <div class="pb-3 px-3">
                        <form method="POST" action="{{ url('update/create') }}" enctype="multipart/form-data"
                            id="formUpdateCreate">
                            @csrf
                            <div class="post-composer-dark">
                                <div class="blocked display-none"></div>
                                <div class="pb-0">
                                    <div class="media">
                                    </div><!-- media -->
                                    <input class="custom-control-input d-none" id="customCheckLocked" type="checkbox"
                                        {{ auth()->user()->post_locked == 'yes' ? 'checked' : '' }} name="locked"
                                        value="yes">

                                    <!-- Alert -->

                                    <div class="alert alert-danger my-3 display-none" id="errorUdpate">

                                        <ul class="list-unstyled m-0" id="showErrorsUdpate"></ul>

                                    </div><!-- Alert -->

                                </div>

                                <div class="rounded-large">

                                    <div class="justify-content-between align-items-center">
                                        
                                        <div class="w-100 mb-2">

                                            <small id="previewImage"></small>

                                            <a href="javascript:void(0)" id="removePhoto"
                                                class="text-danger p-1 small display-none btn-tooltip-form"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>

                                        </div>

                                        <div class="w-100 mb-2">

                                            <small id="previewEpub"></small>

                                            <a href="javascript:void(0)" id="removeEpub"
                                                class="text-danger p-1 small display-none btn-tooltip-form"
                                                data-toggle="tooltip" data-placement="top"
                                                title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>

                                        </div>
                                        
                                        <input type="file" name="photo[]" id="filePhoto"
                                            accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" multiple
                                            class="visibility-hidden filepond">
                                        <div id="uploadProcessingOverlay" class="upload-processing-overlay">
                                            <span class="upload-processing-spinner"></span>
                                            <span class="upload-processing-text">Uploading... <strong id="uploadProcessingPercent">0%</strong></span>
                                        </div>

                                        <div id="postPreviewStep" class="post-preview-step">
                                            <button type="button" id="postPreviewBack" class="post-preview-back">
                                                <i class="fas fa-arrow-left"></i>
                                            </button>
                                            <div class="post-preview-media">
                                                <img id="postPreviewImage" src="" alt="Preview">
                                            </div>
                                            <div class="post-preview-controls">
                                                <small id="postPreviewZoomLevel" class="post-preview-zoom-level">100%</small>
                                                <input id="postPreviewZoom" class="post-preview-range" type="range" min="1" max="100" step="1" value="100">
                                                <button type="button" id="postPreviewContinue" class="post-preview-continue">Continue</button>
                                            </div>
                                        </div>
                                        
                                            
                                        {{-- for hide on first load start  --}}
                                        <div id="postDetailsStep" class="post-details-step">
                                            <div class="post-details-back-wrap">
                                                <button type="button" id="postDetailsBack" class="post-details-back">
                                                    <i class="fas fa-arrow-left"></i>
                                                </button>
                                            </div>
                                            <div class="form-group" id="titlePost">
                                                <label>{{__('general.title')}}</label>
                                                <div class="input-group mb-2">
                                                    <input class="form-control" autocomplete="off" name="title"
                                                    maxlength="100" placeholder="{{ __('admin.title') }}" type="text">
                                                </div>
                                                <small class="form-text text-muted mb-4">
                                                    {{ __('general.title_post_info', ['numbers' => 100]) }}
                                                </small>

                                            </div><!-- End form-group -->
                                            <div class="description-row">
                                                <label class="mb-0">{{__('general.description')}}</label>
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                                    class="btn btn-post p-bottom-8 btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill"
                                                    title="Emoji">
                                                    <i class="bi-emoji-smile f-size-20 align-bottom"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar">
                                                    @include('includes.emojis')
                                                </div>
                                            </div>
                                            <textarea name="description" id="updateDescription" data-post-length="{{ $settings->update_length }}" rows="5"
                                                cols="40" placeholder="{{ __('general.write_something') }}"
                                                class="form-control textareaAutoSize updateDescription emojiArea"></textarea>
                                            <div class="form-group display-none mt-3" id="price">
                                                <label>{{ __('general.price') }}</label>
                                                <div class="input-group mb-2">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{{ $settings->currency_symbol }}</span>
                                                    </div>

                                                    <input class="form-control isNumber" autocomplete="off" name="price"
                                                        placeholder="{{ __('general.price') }}" type="text">
                                                </div>
                                            </div><!-- End form-group -->
                                            <input type="hidden" name="scheduled_date" id="inputScheduled" value="">
                                            <input type="hidden" id="visibilityMode" value="everyone">
                                            <div class="w-100 mb-3 display-none" id="dateScheduleContainer">
                                                <small class="font-weight-bold">
                                                    <i class="bi-calendar-event mr-1"></i> {{ __('general.date_schedule') }} <span id="dateSchedule"></span>
                                                </small>
                                                <a href="javascript:void(0)" id="deleteSchedule" class="text-danger p-1 px-2 btn-tooltip-form"
                                                    data-toggle="tooltip" data-placement="top" title="{{ __('general.delete') }}"><i class="fa fa-times-circle"></i></a>
                                            </div>

                                            <hr class="my-4">
                                            <div class="advanced-settings">

                                                <h4 class="mb-3 fw-bold">{{__('general.advanced_settings')}}</h4>

                                                <!-- Who can see this post -->
                                                <div class="mb-4">
                                                    <label class="setting-label">Who can see this post</label>

                                                    <div class="visibility-options">
                                                        <button type="button" class="visibility-btn active" data-visibility="everyone">
                                                            Everyone
                                                        </button>

                                                        <button type="button" class="visibility-btn is-disabled" data-visibility="followers" disabled>
                                                            Followers Only
                                                        </button>

                                                        <button type="button" class="visibility-btn" data-visibility="subscribers">
                                                            Subscribers Only
                                                        </button>

                                                        <button type="button" class="visibility-btn" data-visibility="premium">
                                                            Premium Post ($)
                                                        </button>
                                                    </div>
                                                    <small class="setting-help">Followers-only is not available in current backend; Subscribers and Premium are supported.</small>
                                                </div>

                                                <!-- Hide likes -->
                                                <div class="setting-row">
                                                    <div>
                                                        <h6>Hide like and counts on this post</h6>
                                                        <small class="text-muted">
                                                            Only you will see the total number of likes on this post.
                                                        </small>
                                                    </div>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="hideLikesCountToggle" name="hide_likes_count" value="1">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Turn off commenting -->
                                                <div class="setting-row">
                                                    <div>
                                                        <h6>Turn off commenting</h6>
                                                        <small class="text-muted">
                                                            You can change this later from post menu.
                                                        </small>
                                                    </div>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="turnOffCommentsToggle" name="turn_off_comments" value="1">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Schedule -->
                                                <div class="setting-row">
                                                    <h6>{{__('general.schedule')}}</h6>
                                                    <label class="switch_update">
                                                        <input type="checkbox" id="advScheduleToggle" @if (!$settings->allow_scheduled_posts) disabled @endif>
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>
                                                @if (!$settings->allow_scheduled_posts)
                                                    <small class="setting-help">Scheduling is disabled by admin settings.</small>
                                                @endif

                                                <!-- Price -->
                                                <div class="setting-row">
                                                    <h6>{{__('general.price')}}</h6>
                                                    <strong id="advancedPriceValue">{{ $settings->currency_symbol }}0</strong>
                                                </div>

                                            </div>
                                        {{-- for hide on first load end  --}}
                                        
                                        @php
                                            $creatorLive = Helper::isCreatorLive(
                                                $getCurrentLiveCreators,
                                                auth()->user()->id,
                                            );
                                        @endphp
                                        {{-- added new div start --}}
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="action-left">
                                                <div class="action_avatar">
                                                    <span class="rounded-circle position-relative">
                                                        <a
                                                            href="{{ $creatorLive ? url('live', auth()->user()->username) : url(auth()->user()->username) }}">
                                                            @if (auth()->check() && $creatorLive)
                                                                <span
                                                                    class="live-span">{{ __('general.live') }}</span>
                                                            @endif
                                                            <img src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                                                alt="{{ auth()->user()->hide_name == 'yes' ? auth()->user()->username : auth()->user()->name }}"
                                                                class="rounded-circle avatarUser" width="60"
                                                                height="60">
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="action_user_info">
                                                    <strong>
                                                        <a href="{{ url(auth()->user()->username) }}">
                                                            {{ auth()->user()->hide_name == 'yes' ? auth()->user()->username : auth()->user()->name }}
                                                        </a>
                                                    </strong>

                                                    @if (auth()->user()->verified_id == 'yes')
                                                        <small class="verified mt-2"
                                                            title="{{ __('general.verified_account') }}"
                                                            data-toggle="tooltip" data-placement="top">
                                                            <i class="bi bi-patch-check-fill"></i>
                                                        </small>
                                                    @endif
                                                    <span>
                                                        <small class="text-muted font-14 mt-2">{{ '@' . auth()->user()->username }}</small>
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                @if ($settings->live_streaming_status == 'on')
                                                    <button type="button"
                                                        class="btnCreateLive btn e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill btn-upload btn-tooltip"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ __('general.stream_live') }}">
                                                        <i class="bi-camera-video f-size-20 align-bottom"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btnMultipleUpload btn e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill btn-upload btn-tooltip" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media')}} ({{ __('general.media_type_upload') }})">
                                                    <i class="feather icon-image f-size-20 align-bottom"></i>
                                                </button>
                                                @if (
                                                (!$settings->ppv_only_free_accounts) ||
                                                (auth()->user()->free_subscription == 'yes' && $settings->ppv_only_free_accounts))
                                                        <button type="button" id="setPrice"
                                                            class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill"
                                                            data-toggle="tooltip" data-placement="top"
                                                            title="{{ __('general.price_post_ppv') }}">
                                                            <i class="feather icon-tag f-size-20 align-bottom"></i>
                                                        </button>
                                                @endif
                                                <button type="button" id="setTitle"
                                                    class="btn btn-tooltip-form e-none btn-post @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ __('general.title_post_block') }}">

                                                    <i class="bi-type f-size-20 align-bottom"></i>

                                                </button>
                                                
                                                <button type="button" id="setSubscribersOnly"
                                                    class="btn e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill btn-upload btn-tooltip-form"
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="{{ __('users.locked_content') }}">
                                                    <i class="feather icon-lock f-size-20 align-bottom"></i>
                                                </button>
                                                
                                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-post p-bottom-8 btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                                                    <i class="bi-emoji-smile f-size-20 align-bottom"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownEmoji">
                                                    @include('includes.emojis')
                                                </div>
                                                @if ($settings->allow_zip_files)
                                                    <input type="file" name="zip" id="fileZip" accept="application/x-zip-compressed" class="visibility-hidden">
                                                @endif
                                                @if ($settings->allow_reels)
                                                    <button onclick="window.location.href='{{ url('create/reel') }}'"
                                                        type="button" data-toggle="tooltip" data-placement="top"
                                                        title="{{ __('general.create_reel') }}"
                                                        class="btn btn-post p-bottom-8 btn-tooltip-form e-none @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">

                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" x="0px" y="0px"
                                                            width="22" height="22" viewBox="0 0 50 50">

                                                            <path
                                                                d="M 15 4 C 8.9365932 4 4 8.9365932 4 15 L 4 35 C 4 41.063407 8.9365932 46 15 46 L 35 46 C 41.063407 46 46 41.063407 46 35 L 46 15 C 46 8.9365932 41.063407 4 35 4 L 15 4 z M 16.740234 6 L 27.425781 6 L 33.259766 16 L 22.574219 16 L 16.740234 6 z M 29.740234 6 L 35 6 C 39.982593 6 44 10.017407 44 15 L 44 16 L 35.574219 16 L 29.740234 6 z M 14.486328 6.1035156 L 20.259766 16 L 6 16 L 6 15 C 6 10.199833 9.7581921 6.3829803 14.486328 6.1035156 z M 6 18 L 44 18 L 44 35 C 44 39.982593 39.982593 44 35 44 L 15 44 C 10.017407 44 6 39.982593 6 35 L 6 18 z M 21.978516 23.013672 C 20.435152 23.049868 19 24.269284 19 25.957031 L 19 35.041016 C 19 37.291345 21.552344 38.713255 23.509766 37.597656 L 31.498047 33.056641 C 33.442844 31.951609 33.442844 29.044485 31.498047 27.939453 L 23.509766 23.398438 L 23.507812 23.398438 C 23.018445 23.120603 22.49297 23.001607 21.978516 23.013672 z M 21.982422 24.986328 C 22.158626 24.988232 22.342399 25.035052 22.521484 25.136719 L 30.511719 29.677734 C 31.220922 30.080703 31.220922 30.915391 30.511719 31.318359 L 22.519531 35.859375 C 21.802953 36.267773 21 35.808686 21 35.041016 L 21 25.957031 C 21 25.573196 21.201402 25.267385 21.492188 25.107422 C 21.63758 25.02744 21.806217 24.984424 21.982422 24.986328 z"
                                                                stroke="currentColor" stroke-width="2" fill="none"></path>

                                                        </svg>

                                                    </button>
                                                @endif

                                                @if ($settings->allow_epub_files)
                                                    <input type="file" name="epub" id="ePubFile"
                                                        accept="application/epub+zip" class="visibility-hidden">
                                                @endif
                                            </div>
                                        </div>
                                        {{-- end here --}}
                                        <div class="d-inline-block mt-3 position-relative w-100-mobile">

                                            <span class="d-inline-block position-relative rounded-pill w-100-mobile">

                                                <span class="btn-blocked display-none"></span>

                                                <button type="button" 
                                                    class="btn btn-sm btn-primary rounded-large float-right e-none w-100-mobile"
                                                    data-empty="{{ __('general.empty_post') }}"
                                                    data-error="{{ __('general.error') }}"
                                                    data-msg-error="{{ __('general.error_internet_disconnected') }}"
                                                    id="btnCreateUpdate">

                                                    <i></i> <span
                                                        id="textPostPublish">Confirm & Publish</span>

                                                </button>

                                            </span>

                                        </div>

                                        </div>

                                    </div>

                                </div><!-- card footer -->

                            </div><!-- card -->

                        </form>

                        <!-- Post Pending -->

                        <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostPending">

                            <button type="button" class="close mt-1" id="btnAlertPostPending">

                                <span aria-hidden="true">

                                    <i class="bi bi-x-lg"></i>

                                </span>

                            </button>

                            <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_pending_review') }}

                            <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>

                        </div>

                        <!-- Post Schedule -->

                        <div class="alert alert-primary display-none card-border-0" role="alert" id="alertPostSchedule">

                            <button type="button" class="close mt-1" id="btnAlertPostSchedule">

                                <span aria-hidden="true">

                                    <i class="bi bi-x-lg"></i>

                                </span>

                            </button>



                            <i class="bi-info-circle mr-1"></i> {{ __('general.alert_post_schedule') }}

                            <a href="{{ url('my/posts') }}" class="link-border text-white">{{ __('general.my_posts') }}</a>

                        </div>
                    </div>                </div><!-- end col-md-6 -->

                <div class="col-lg-3 col-md-4 mb-4 d-lg-block d-none">
                    @if ($users->count() == 0)
                        <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
                            <div class="panel-body">
                                <div class="media none-overflow">
                                    <div class="d-flex my-2 align-items-center">
                                        <img class="rounded-circle mr-2"
                                            src="{{ Helper::getFile(config('path.avatar') . auth()->user()->avatar) }}"
                                            width="60" height="60">

                                        <div class="d-block">
                                            <strong>{{ auth()->user()->name }}</strong>


                                            <div class="d-block">
                                                <small class="media-heading text-muted btn-block margin-zero">
                                                    <a href="{{ url('settings/page') }}">
                                                        {{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile') }}
                                                        <small class="pl-1"><i
                                                                class="fa fa-long-arrow-alt-right"></i></small>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-lg-block sticky-top" id="">

                        @if ($users->count() != 0)
                            @include('includes.explore_creators')
                        @endif
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- container -->
    </section>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.fileuploader').addClass('d-block');

            var $uploadStep = $('#formUpdateCreate .fileuploader');
            var $previewStep = $('#postPreviewStep');
            var $detailsStep = $('#postDetailsStep');
            var $previewImage = $('#postPreviewImage');
            var $zoom = $('#postPreviewZoom');
            var $zoomLevel = $('#postPreviewZoomLevel');
            var $form = $('#formUpdateCreate');
            var $visibilityButtons = $('.visibility-btn[data-visibility]');
            var $visibilityMode = $('#visibilityMode');
            var $priceInput = $('input[name="price"]');
            var $advancedPriceValue = $('#advancedPriceValue');
            var $scheduleToggle = $('#advScheduleToggle');
            var $uploadOverlay = $('#uploadProcessingOverlay');
            var $uploadOverlayPercent = $('#uploadProcessingPercent');
            var previewUrl = null;
            var zoomBase = 100;
            var currencySymbol = @json($settings->currency_symbol);
            function syncZoomSliderFill() {
                var min = parseFloat($zoom.attr('min')) || 1;
                var max = parseFloat($zoom.attr('max')) || 100;
                var val = parseFloat($zoom.val()) || min;
                var pct = ((val - min) / Math.max(1, (max - min))) * 100;
                $zoom.css('--zoom-value', pct + '%');
            }

            function setupAutoZoom() {
                var imgEl = $previewImage.get(0);
                var containerEl = $('.post-preview-media').get(0);
                if (!imgEl || !containerEl || !imgEl.naturalWidth || !imgEl.naturalHeight) {
                    zoomBase = 100;
                    $zoom.attr({ min: 1, max: 100, step: 1 }).val(100).prop('disabled', true);
                    syncZoomSliderFill();
                    $zoomLevel.text('100%');
                    $previewImage.css('transform', 'scale(1)');
                    return;
                }

                var fitScale = Math.min(
                    containerEl.clientWidth / imgEl.naturalWidth,
                    containerEl.clientHeight / imgEl.naturalHeight
                );

                // Keep behavior similar to previous popup zoom: start at fitted scale.
                zoomBase = Math.max(25, Math.min(100, Math.round(fitScale * 100)));
                var zoomMax = 100;
                $zoom.attr({ min: 1, max: zoomMax, step: 1 }).val(zoomBase).prop('disabled', zoomBase >= zoomMax);
                syncZoomSliderFill();
                $zoomLevel.text(zoomBase + '%');
                $previewImage.css('transform', 'scale(1)');
            }

            function showUploadStep() {
                $form.removeClass('step-preview step-details').addClass('step-upload');
                $uploadStep.show();
                $previewStep.removeClass('active');
                $detailsStep.removeClass('active');
                $zoom.val(zoomBase);
                syncZoomSliderFill();
                $zoomLevel.text(zoomBase + '%');
                $previewImage.css('transform', 'scale(1)');
            }

            function showPreviewStep(src) {
                $form.removeClass('step-upload step-details').addClass('step-preview');
                $uploadStep.hide();
                $detailsStep.removeClass('active');
                $previewImage.attr('src', src || '');
                $previewStep.addClass('active');

                $previewImage.off('load.stepzoom').on('load.stepzoom', function() {
                    setupAutoZoom();
                });

                if ($previewImage.get(0).complete) {
                    setTimeout(function() {
                        setupAutoZoom();
                    }, 30);
                }
            }

            function showDetailsStep() {
                $form.removeClass('step-upload step-preview').addClass('step-details');
                $uploadStep.hide();
                $previewStep.removeClass('active');
                $detailsStep.addClass('active');
            }

            function updatePriceLabel() {
                var value = ($priceInput.val() || '').trim();
                if (!value) {
                    $advancedPriceValue.text(currencySymbol + '0');
                    return;
                }
                $advancedPriceValue.text(currencySymbol + value);
            }

            function ensureLockedState(shouldLock) {
                var isLocked = $('#customCheckLocked').is(':checked');
                if (isLocked !== shouldLock) {
                    $('#customCheckLocked').prop('checked', shouldLock);
                }
            }

            function ensurePriceInputState(shouldShow) {
                var isShown = $priceInput.hasClass('active');
                if (isShown !== shouldShow) {
                    if ($('#setPrice').length) {
                        $('#setPrice').trigger('click');
                    } else {
                        $priceInput.toggleClass('active', shouldShow);
                        if (shouldShow) {
                            $('#price').stop(true, true).slideDown(100);
                        } else {
                            $('#price').stop(true, true).slideUp(100);
                            $priceInput.val('');
                        }
                    }
                }
            }

            function applyVisibilityMode(mode) {
                $visibilityMode.val(mode);
                $visibilityButtons.removeClass('active');
                $visibilityButtons.filter('[data-visibility="' + mode + '"]').addClass('active');
                $('#setSubscribersOnly').toggleClass('btn-active-hover', mode === 'subscribers');

                if (mode === 'premium') {
                    ensureLockedState(true);
                    ensurePriceInputState(true);
                } else if (mode === 'subscribers') {
                    ensureLockedState(true);
                    ensurePriceInputState(false);
                    $priceInput.val('');
                } else {
                    ensureLockedState(false);
                    ensurePriceInputState(false);
                    $priceInput.val('');
                }

                updatePriceLabel();
            }

            showUploadStep();
            if (($priceInput.val() || '').trim() !== '') {
                applyVisibilityMode('premium');
            } else if ($('#customCheckLocked').is(':checked')) {
                applyVisibilityMode('subscribers');
            } else {
                applyVisibilityMode('everyone');
            }
            updatePriceLabel();

            $(document).on('post-media-uploaded', function(e, payload) {
                if (!payload || payload.format !== 'image') {
                    return;
                }

                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('100%');

                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }

                if (payload.file) {
                    previewUrl = URL.createObjectURL(payload.file);
                }

                if (!previewUrl) {
                    var inputFile = ($('#filePhoto').get(0) && $('#filePhoto').get(0).files && $('#filePhoto').get(0).files[0]) ? $('#filePhoto').get(0).files[0] : null;
                    if (inputFile) {
                        previewUrl = URL.createObjectURL(inputFile);
                    }
                }

                if (!previewUrl) {
                    var fallbackSrc = $('#formUpdateCreate .fileuploader-item-image img').first().attr('src') || '';
                    if (!fallbackSrc) {
                        var fallbackCanvas = $('#formUpdateCreate .fileuploader-item-image canvas').first();
                        if (fallbackCanvas.length) {
                            fallbackSrc = fallbackCanvas.get(0).toDataURL('image/png');
                        }
                    }
                    showPreviewStep(fallbackSrc || '');
                    return;
                }

                showPreviewStep(previewUrl);
            });

            $(document).on('post-media-removed', function() {
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }
                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('0%');
                showUploadStep();
            });

            $(document).on('post-media-upload-start', function() {
                $uploadOverlayPercent.text('0%');
                $uploadOverlay.addClass('active');
            });

            $(document).on('post-media-upload-progress', function(e, payload) {
                var percent = payload && typeof payload.percentage !== 'undefined' ? Math.max(0, Math.min(100, parseInt(payload.percentage, 10) || 0)) : 0;
                $uploadOverlayPercent.text(percent + '%');
            });

            $(document).on('post-media-upload-failed', function() {
                $uploadOverlay.removeClass('active');
                $uploadOverlayPercent.text('0%');
            });

            $zoom.on('input change', function() {
                var rawValue = parseFloat($(this).val() || zoomBase);
                var value = Math.max(zoomBase, rawValue);
                if (rawValue < zoomBase) {
                    $(this).val(zoomBase);
                }
                syncZoomSliderFill();
                var scale = value / zoomBase;
                $previewImage.css('transform', 'scale(' + scale + ')');
                $zoomLevel.text(Math.round(value) + '%');
            });

            $(window).on('resize', function() {
                if ($previewStep.hasClass('active')) {
                    setupAutoZoom();
                }
            });

            $('#postPreviewContinue').on('click', function() {
                showDetailsStep();
            });

            $('#postDetailsBack').on('click', function() {
                var currentSrc = $previewImage.attr('src') || '';
                showPreviewStep(currentSrc);
            });

            $('#postPreviewBack').on('click', function() {
                var api = $.fileuploader.getInstance($('input[name="photo[]"]'));
                if (api) {
                    api.reset();
                }
                if (previewUrl) {
                    URL.revokeObjectURL(previewUrl);
                    previewUrl = null;
                }
                showUploadStep();
            });

            $visibilityButtons.on('click', function() {
                if ($(this).is(':disabled')) {
                    return;
                }
                applyVisibilityMode($(this).data('visibility'));
            });

            $('#setSubscribersOnly').on('click', function() {
                if ($visibilityMode.val() === 'subscribers') {
                    applyVisibilityMode('everyone');
                } else {
                    applyVisibilityMode('subscribers');
                }
            });


            $priceInput.on('input change', function() {
                updatePriceLabel();
            });

            $scheduleToggle.on('change', function() {
                if (!$(this).is(':checked')) {
                    $('#inputScheduled').val('');
                    $('#dateScheduleContainer').hide();
                    $('#textPostPublish').html(publish);
                    return;
                }
                $('#modalSchedulePost').modal('show');
            });

            $(document).on('click', '#btnSubmitSchedule', function() {
                setTimeout(function() {
                    $scheduleToggle.prop('checked', !!$('#inputScheduled').val());
                }, 120);
            });

            $('#modalSchedulePost').on('hidden.bs.modal', function() {
                $scheduleToggle.prop('checked', !!$('#inputScheduled').val());
            });

            $(document).on('click', '#deleteSchedule', function() {
                $scheduleToggle.prop('checked', false);
            });
        });
    </script>
@endsection
