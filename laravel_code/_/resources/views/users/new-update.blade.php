@extends('layouts.app')
@section('css')
    <style>
        .fileuploader-items {
            white-space: unset !important;
        }

        .fileuploader-item:nth-child(1) {
            margin-left: 16px !important;
        }

        .advanced-settings {
            color: #fff;
        }

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

        .switch_update input:checked + .slider {
            background-color: #ff4d6d;
        }

        .switch_update input:checked + .slider:before {
            transform: translateX(22px);
        }
    </style>
@endsection

@section('content')
    <section class="section section-sm">
        {{-- for mobile header --}}
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2">
            <div class="row">
                <div class="col-lg-3 col-md-2 side_bar_box_shadow">
                    @include('includes.menu-sidebar-home')
                </div>
                <div class="col-lg-6 col-md-6 p-0">
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
                                        
                                            
                                        {{-- for hide on first load start  --}}
                                        <div>
                                            <div class="form-group display-none" id="price">

                                                <div class="input-group mb-2">

                                                    <div class="input-group-prepend">

                                                        <span class="input-group-text">{{ $settings->currency_symbol }}</span>

                                                    </div>

                                                    <input class="form-control isNumber" autocomplete="off" name="price"
                                                        placeholder="{{ __('general.price') }}" type="text">

                                                </div>

                                            </div><!-- End form-group -->

                                            <div class="form-group" id="titlePost">
                                                <label>Title</label>
                                                <div class="input-group mb-2">
                                                    <input class="form-control" autocomplete="off" name="title"
                                                    maxlength="100" placeholder="{{ __('admin.title') }}" type="text">
                                                </div>
                                                <small class="form-text text-muted mb-4">
                                                    {{ __('general.title_post_info', ['numbers' => 100]) }}
                                                </small>

                                            </div><!-- End form-group -->
                                            <label>Description</label>
                                            <textarea name="description" id="updateDescription" data-post-length="{{ $settings->update_length }}" rows="5"
                                                cols="40" placeholder="{{ __('general.write_something') }}"
                                                class="form-control textareaAutoSize updateDescription emojiArea">
                                            </textarea>

                                            <hr class="my-4">
                                            <div class="advanced-settings">

                                                <h4 class="mb-3 fw-bold">Advanced Settings</h4>

                                                <!-- Who can see this post -->
                                                <div class="mb-4">
                                                    <label class="setting-label">Who can see this post</label>

                                                    <div class="visibility-options">
                                                        <button type="button" class="visibility-btn active">
                                                            ♡ Everyone
                                                        </button>

                                                        <button type="button" class="visibility-btn">
                                                            ♡ Followers Only
                                                        </button>

                                                        <button type="button" class="visibility-btn">
                                                            ♡ Subscribers Only
                                                        </button>

                                                        <button type="button" class="visibility-btn">
                                                            ♡ Premium Post ($)
                                                        </button>
                                                    </div>
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
                                                        <input type="checkbox">
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
                                                        <input type="checkbox">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Schedule -->
                                                <div class="setting-row">
                                                    <h6>Schedule</h6>
                                                    <label class="switch_update">
                                                        <input type="checkbox">
                                                        <span class="slider_update"></span>
                                                    </label>
                                                </div>

                                                <!-- Price -->
                                                <div class="setting-row">
                                                    <h6>Price</h6>
                                                    <strong>$280</strong>
                                                </div>

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
                                                
                                                @if (!$settings->disable_free_post)
                                                    <button type="button" id="contentLocked"
                                                        class="btn e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill btn-upload btn-tooltip"
                                                        data-toggle="tooltip" data-placement="top"
                                                        title="{{ __('users.locked_content') }}">
                                                        <i
                                                            class="feather icon-lock f-size-20 align-bottom"></i>
                                                    </button>
                                                @endif
                                                
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

                                                <button type="submit" 
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
        });
    </script>
@endsection
