@extends('layouts.app')

@section('title')
    {{ trans('users.edit_profile') }} -
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/select2/select2.min.css') }}?v={{ $settings->version }}" rel="stylesheet" type="text/css" />
    <style type="text/css">
    [data-bs-theme="light"] .light_mode_form {
      border: 1px solid #1e1e1e2e;
    }
    [data-bs-theme="light"] .sub_desc_edit_page {
      color: #5f5f5f;
    }
    </style>
@endsection

@section('content')
    <section class="section section-sm">
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2">

            <div class="row">
                
                    @include('includes.cards-settings')
                
                <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-lg-8 py-3">
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">{{ trans('users.edit_profile') }}
                            </h2>
                            <p class="lead mt-0 font_weight_400 fs-14 sub_desc_edit_page">{{ trans('users.settings_page_desc') }}</p>
                        </div>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>

                            {{ trans('admin.success_update') }}
                        </div>
                    @endif

                    @include('errors.errors-forms')

                    @include('includes.alert-payment-disabled')

                    <form method="POST" action="{{ url('settings/page') }}" id="formEditPage" accept-charset="UTF-8"
                        enctype="multipart/form-data">

                        @csrf

                        <input type="hidden" id="featured_content" name="featured_content"
                            value="{{ auth()->user()->featured_content }}">

                        <div class="form-group">
                            <label>{{ trans('auth.full_name1') }} *</label>
                            <div class="input-group mb-4">
                                {{-- <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-user"></i></span>
                                </div> --}}
                                <input class="form-control light_mode_form" name="full_name" placeholder="{{ trans('auth.full_name') }}"
                                    value="{{ auth()->user()->name }}" type="text">
                            </div>
                        </div><!-- End form-group -->

                        <div class="form-group">
                            <label>{{ trans('auth.username') }} *</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text pr-0">{{ Helper::removeHTPP(url('/')) }}/</span>
                                </div>
                                <input class="form-control light_mode_form" name="username" maxlength="25"
                                    placeholder="{{ trans('auth.username') }}" value="{{ auth()->user()->username }}"
                                    type="text">
                            </div>
                            <div class="text-muted btn-block">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="hide_name" value="yes"
                                        @if (auth()->user()->hide_name == 'yes') checked @endif id="customSwitch1">
                                    <label class="custom-control-label switch"
                                        for="customSwitch1">{{ trans('general.hide_name') }}</label>
                                </div>
                            </div>
                        </div><!-- End form-group -->

                        <div class="form-group">
                            <label>{{ trans('auth.email') }} *</label>
                            <input class="form-control " placeholder="{{ trans('auth.email') }} *" {!! auth()->user()->isSuperAdmin() ? 'name="email"' : 'disabled' !!}
                                value="{{ auth()->user()->email }}" type="text">
                        </div><!-- End form-group -->

                        <div class="form-group">

                            {{-- <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-user-tie"></i></span>
                          </div> --}}
                            <label>{{ trans('users.profession_ocupation') }} *</label>
                            <input class="form-control light_mode_form" name="profession"
                                placeholder="{{ trans('users.profession_ocupation') }}"
                                value="{{ auth()->user()->profession }}" type="text">

                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-6 form-group mb-0">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-language"></i></span>
                                </div> --}}
                                    <label>{{ trans('general.language') }}</label>
                                    <select name="language" class="form-control custom-select light_mode_form">
                                        <option @if (auth()->user()->language == '') selected="selected" @endif value="">
                                            ({{ trans('general.language') }}) {{ __('general.not_specified') }}</option>
                                        @foreach (Languages::orderBy('name')->get() as $languages)
                                            <option @if (auth()->user()->language == $languages->abbreviation) selected="selected" @endif
                                                value="{{ $languages->abbreviation }}">{{ $languages->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- End Form Group -->

                            <div class="col-lg-4 col-md-6 form-group mb-0">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-calendar-alt"></i></span>
                                </div> --}}
                                    <label>{{ trans('general.birthdate') }} *</label>
                                    <input class="form-control datepicker light_mode_form" @if (auth()->user()->birthdate_changed == 'yes') disabled @endif
                                        name="birthdate" placeholder="{{ trans('general.birthdate') }} *"
                                        value="{{ auth()->user()->birthdate ?? date(Helper::formatDatepicker(), strtotime(auth()->user()->birthdate)) }}"
                                        autocomplete="off" type="text">
                                </div>
                                <small class="form-text text-muted mb-4">{{ trans('general.valid_formats') }}
                                    <strong>{{ now()->subYears(18)->format(Helper::formatDatepicker()) }}</strong> --
                                    <strong>({{ trans('general.birthdate_changed_info') }})</strong>
                                </small>
                            </div>
                            <div class="col-lg-4 col-md-6 form-group mb-0">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-venus-mars"></i></span>
                                </div> --}}
                                    <label>{{ trans('general.gender') }}</label>
                                    <select name="gender" class="form-control custom-select light_mode_form">
                                        <option @if (auth()->user()->gender == '') selected="selected" @endif value="">
                                            ({{ trans('general.gender') }})
                                            {{ __('general.not_specified') }}</option>
                                        @foreach ($genders as $gender)
                                            <option @if (auth()->user()->gender == $gender) selected="selected" @endif
                                                value="{{ $gender }}">{{ __('general.' . $gender) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div><!-- End Form Group -->
                        </div>
                        <div class="row form-group mb-0">

                            @if (auth()->user()->verified_id == 'yes')
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        {{-- <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fa fa-link"></i></span>
                                        </div> --}}
                                        <label>{{ trans('general.website_misc') }}</label>
                                        <input class="form-control light_mode_form" name="website"
                                            placeholder="{{ trans('users.website') }}"
                                            value="{{ auth()->user()->website }}" type="text">
                                    </div>
                                </div><!-- ./col-md-12 -->

                                <div class="col-md-12" id="billing">
                                    <div class="mb-4" style="width: 100%; overflow: hidden;">
                                        {{-- <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-lightbulb"></i></span>
                                        </div> --}}
                                        <label>{{ trans('general.category') }}</label>
                                        <select name="categories_id[]" multiple class="form-control categoriesMultiple light_mode_form">
                                            @foreach (Categories::where('mode', 'on')->orderBy('name')->get() as $category)
                                                <option @if (in_array($category->id, $categories)) selected="selected" @endif
                                                    value="{{ $category->id }}">
                                                    {{ Lang::has('categories.' . $category->slug) ? __('categories.' . $category->slug) : $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- ./col-md-12 -->
                            @endif

                            <div class="col-lg-12 py-2">
                                <h6 class="font_weight_700 fs-24"> {{ trans('general.billing_information') }}</h6>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                                    </div> --}}
                                    <label>{{ trans('general.company') }} *</label>
                                    <input class="form-control light_mode_form" name="company"
                                        placeholder="{{ trans('general.company') }}"
                                        value="{{ auth()->user()->company }}" type="text">
                                </div>
                            </div><!-- ./col-md-6 -->

                            <div class="col-md-6">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                    </div> --}}
                                    <label>{{ trans('general.country') }}</label>
                                    <select name="countries_id" class="form-control custom-select light_mode_form">
                                        <option value="">{{ trans('general.select_your_country') }} *</option>
                                        @foreach (Countries::orderBy('country_name')->get() as $country)
                                            <option @if (auth()->user()->countries_id == $country->id) selected="selected" @endif
                                                value="{{ $country->id }}">{{ $country->country_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><!-- ./col-md-6 -->

                            <div class="col-md-6">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                                    </div> --}}
                                    <label>{{ trans('general.city') }}</label>
                                    <input class="form-control light_mode_form" name="city" placeholder="{{ trans('general.city') }}"
                                        value="{{ auth()->user()->city }}" type="text">
                                </div>
                            </div><!-- ./col-md-6 -->

                            <div class="col-md-6 @if (auth()->user()->verified_id == 'no') scrollError @endif">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
                                    </div> --}}
                                    <label>{{ trans('general.address') }}</label>
                                    <input class="form-control light_mode_form" name="address"
                                        placeholder="{{ trans('general.address') }}"
                                        value="{{ auth()->user()->address }}" type="text">
                                </div>
                            </div><!-- ./col-md-6 -->

                            <div class="col-md-6">
                                <div class="mb-4">
                                    {{-- <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                                    </div> --}}
                                    <label>{{ trans('general.zip') }}</label>
                                    <input class="form-control light_mode_form" name="zip" placeholder="{{ trans('general.zip') }}"
                                        value="{{ auth()->user()->zip }}" type="text">
                                </div>
                            </div><!-- ./col-md-6 -->

                        </div><!-- End Row Form Group -->

                        @if (auth()->user()->verified_id == 'yes')
                            <div class="row form-group mb-0">
                                <div class="col-lg-12 py-2">
                                    <h6 class="font_weight_700 fs-24">
                                        {{ trans('admin.profiles_social') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="facebook"
                                            placeholder="https://facebook.com/username"
                                            value="{{ auth()->user()->facebook }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-twitter-x"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="twitter"
                                            placeholder="https://twitter.com/username"
                                            value="{{ auth()->user()->twitter }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="instagram"
                                            placeholder="https://instagram.com/username"
                                            value="{{ auth()->user()->instagram }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="youtube"
                                            placeholder="https://youtube.com/username"
                                            value="{{ auth()->user()->youtube }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-pinterest-p"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="pinterest"
                                            placeholder="https://pinterest.com/username"
                                            value="{{ auth()->user()->pinterest }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-github"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="github"
                                            placeholder="https://github.com/username"
                                            value="{{ auth()->user()->github }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-snapchat"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="snapchat"
                                            placeholder="https://www.snapchat.com/add/username"
                                            value="{{ auth()->user()->snapchat }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-tiktok"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="tiktok"
                                            placeholder="https://www.tiktok.com/@username"
                                            value="{{ auth()->user()->tiktok }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-telegram"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="telegram" placeholder="https://t.me/username"
                                            value="{{ auth()->user()->telegram }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-twitch"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="twitch"
                                            placeholder="https://www.twitch.tv/username"
                                            value="{{ auth()->user()->twitch }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-discord"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="discord"
                                            placeholder="https://discord.gg/username"
                                            value="{{ auth()->user()->discord }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-vk"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="vk" placeholder="https://vk.com/username"
                                            value="{{ auth()->user()->vk }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->

                            <div class="row form-group mb-0">
                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-reddit"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="reddit"
                                            placeholder="https://reddit.com/user/username"
                                            value="{{ auth()->user()->reddit }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-spotify"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="spotify"
                                            placeholder="https://spotify.com/username"
                                            value="{{ auth()->user()->spotify }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="bi-threads"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="threads"
                                            placeholder="https://threads.net/username"
                                            value="{{ auth()->user()->threads }}" type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fab fa-kickstarter"></i></span>
                                        </div>
                                        <input class="form-control light_mode_form" name="kick"
                                            placeholder="https://kick.com/username" value="{{ auth()->user()->kick }}"
                                            type="text">
                                    </div>
                                </div><!-- ./col-md-6 -->
                            </div><!-- End Row Form Group -->



                            <div class="form-group">
                                <label class="w-100"><i class="fa fa-bullhorn text-muted"></i>
                                    {{ trans('users.your_story') }} *
                                    <span id="the-count" class="float-right d-inline">
                                        <span id="current"></span>
                                        <span id="maximum">/ {{ $settings->story_length }}</span>
                                    </span>
                                </label>
                                <textarea name="story" id="story" rows="5" cols="40"
                                    class="light_mode_form form-control textareaAutoSize scrollError">{{ auth()->user()->story ? auth()->user()->story : old('story') }}</textarea>

                            </div><!-- End Form Group -->
                        @endif

                        <!-- Alert -->
                        <div class="alert alert-danger my-3 display-none" id="errorUdpateEditPage">
                            <ul class="list-unstyled m-0" id="showErrorsUdpatePage">
                                <li></li>
                            </ul>
                        </div><!-- Alert -->

                        <button class="btn btn-1 btn-success btn-block"
                            data-msg-success="{{ trans('admin.success_update') }}" id="saveChangesEditPage"
                            type="submit"><i></i> {{ trans('general.save_changes') }}</button>
                    </form>
                </div><!-- end col-md-6 -->
            </div>
        </div>
    </section>
@endsection

@section('javascript')
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    @if (config('app.locale') != 'en')
        <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.' . config('app.locale') . '.js') }}"></script>
    @endif

    <script src="{{ asset('plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/select2/i18n/' . config('app.locale') . '.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        @if (auth()->user()->verified_id == 'yes')
            $('#current').html($('#story').val().length);
        @endif

        $('.categoriesMultiple').select2({
            width: '100%',
            tags: false,
            tokenSeparators: [','],
            maximumSelectionLength: {{ $settings->limit_categories }},
            placeholder: '{{ trans('admin.categories') }}',
            language: {
                maximumSelected: function() {
                    return "{{ trans('general.maximum_selected_categories', ['limit' => $settings->limit_categories]) }}";
                },
                searching: function() {
                    return "{{ trans('general.searching') }}";
                },
                noResults: function() {
                    return '{{ trans('general.no_results') }}';
                }
            }
        });

        $('.datepicker').datepicker({
            format: '{{ Helper::formatDatepicker(true) }}',
            startDate: '01/01/1920',
            endDate: '{{ now()->subYears(18)->format(Helper::formatDatepicker()) }}',
            language: '{{ config('app.locale') }}'
        });
    </script>
@endsection
