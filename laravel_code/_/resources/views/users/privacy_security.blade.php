@extends('layouts.app')

@section('css')
<style type="text/css">
  .privacy_card{
    background: #303030;
    border-radius: 15px;
    padding: 20px;
    gap: 32px;
  }
  [data-bs-theme="light"] .privacy_card{
    background: #ffffff;
  }
  .border_bottom{
    border-bottom: 1px solid #FFFFFF;
  }

  [data-bs-theme="light"] .border_bottom{
    border-bottom: 1px solid #5f5f5f;
  }
  .main_session_div{
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .session_left {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .badge-active-now {
    background: transparent;
    color: #00FF43;
    font-weight: 400;
    font-size: 14px;
    padding: 0;
  }
  .close_all_btn{
    border-radius: 100px;
    background-color: #E2394C;
  }

  .close_all_btn:hover, .delete_account_btn:hover{
    background-color: #E2394C;
  }
</style>
@endsection
@section('title') {{trans('general.privacy_security')}} -@endsection

@section('content')
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">

    <div class="row">

      @include('includes.cards-settings')

      <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
        <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-2">{{trans('general.privacy_security')}}</h2>
        <p class="lead font_weight_400 fs-14 mt-0">{{trans('general.desc_privacy')}}</p>
        @if (session('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ session('status') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <!-- <h5>{{ __('general.login_sessions') }}</h5> -->
        <div class="card mb-4 privacy_card mt-2">
          <div class="card-body">

            @if ($agents->count() || $currentSession)
            <small class="w-100 d-block font_weight_700 fs-24 pb-3 txt_mob_18"><strong>{{ __('general.last_login_record') }}</strong></small>

            @if ($currentSession)
            <div class="card-text mb-4 border_bottom pb-3 font_weight_400 fs-14">
              <div class="main_session_div">
                <div class="session_left">
                  <i class="bi-{{ $currentSession->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i>
                  {{ $currentSession->getNameBrowser() }} {{ __('general.on') }} {{ $currentSession->getNamePlatform() }}{{ $currentSession->device_type == 'phone' ? ', '.$currentSession->device : null }}
                  
                </div>
                <span class="badge badge-active-now">{{ __('general.active_now') }}</span>
              </div>
                
              <small class="w-100 d-block mt-2 mb-0 font_weight_400 fs-14">
                {{ $currentSession->ip }} - {{ $currentSession->country ? $currentSession->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($currentSession->updated_at))}}"></span>
              </small>
            </div>
            @endif

            @foreach ($agents as $agent)
            <p class="card-text mb-1 font_weight_600 fs-18 pt-3">
              <i class="bi-{{ $agent->device_type == 'phone' ? 'phone' : 'display' }} mr-1"></i>
              <strong>{{ $agent->getNameBrowser() }} {{ __('general.on') }} {{ $agent->getNamePlatform() }} {{ $agent->device_type == 'phone' ? ', '.$agent->device : null }}</strong>
            </p>
            <small class="w-100 d-block mb-2 font_weight_400 fs-14">
              {{ $agent->ip }} - {{ $agent->country ? $agent->country.' - ' : null }} <span class="timeAgo" data="{{date('c', strtotime($agent->updated_at))}}"></span>
            </small>
            @endforeach

            <small class="w-100 d-block my-3 font_weight_400 fs-12"> <i class="bi-exclamation-triangle mr-1"></i> {{ __('general.login_session_alert') }}</small>

            @if ($agents->count() != 0)
            <a href="#" class="btn btn-sm btn-danger mt-2 close_all_btn" data-toggle="modal" data-target="#logoutDevices">
              {{ __('general.close_all_sessions') }}
            </a>

            @include('includes.modal-logout-devices')

            @endif

            @else
            {{ __('general.no_results_found') }}
            @endif
          </div>
        </div>

        @if (auth()->user()->verified_id == 'yes')
        <p class="font_weight_700 fs-24">{{ __('general.privacy') }}</p>

        <form method="POST" action="{{ url('privacy/security') }}">

          @csrf

          <div class="form-group font_weight_500">
            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="hide_profile" value="yes" @if (auth()->user()->hide_profile == 'yes') checked @endif id="customSwitch1">
                <label class="custom-control-label switch fs-16" for="customSwitch1">{{ __('general.hide_profile') }} {{ __('general.info_hide_profile') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="hide_last_seen" value="yes" @if (auth()->user()->hide_last_seen == 'yes') checked @endif id="customSwitch2">
                <label class="custom-control-label switch fs-16" for="customSwitch2">{{ __('general.hide_last_seen') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="active_status_online" value="yes" @if (auth()->user()->active_status_online == 'yes') checked @endif id="customSwitch6">
                <label class="custom-control-label switch fs-16" for="customSwitch6">{{ __('general.active_status_online') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="hide_count_subscribers" value="yes" @if (auth()->user()->hide_count_subscribers == 'yes') checked @endif id="customSwitch3">
                <label class="custom-control-label switch fs-16" for="customSwitch3">{{ __('general.hide_count_subscribers') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="hide_my_country" value="yes" @if (auth()->user()->hide_my_country == 'yes') checked @endif id="customSwitch4">
                <label class="custom-control-label switch fs-16" for="customSwitch4">{{ __('general.hide_my_country') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="show_my_birthdate" value="yes" @if (auth()->user()->show_my_birthdate == 'yes') checked @endif id="customSwitch5">
                <label class="custom-control-label switch fs-16" for="customSwitch5">{{ __('general.show_my_birthdate') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="posts_privacy" value="1" @if (auth()->user()->posts_privacy) checked @endif id="posts_privacy">
                <label class="custom-control-label switch fs-16" for="posts_privacy">{{ __('general.posts_privacy') }}</label>
              </div>
            </div>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="allow_comments" value="1" @checked(auth()->user()->allow_comments) id="allow_comments">
                <label class="custom-control-label switch fs-16" for="allow_comments">{{ __('general.allow_comments') }}</label>
              </div>
            </div>

            <p class="mt-5 font_weight_700 fs-24">{{ __('general.security') }}</p>

            <div class="btn-block mb-4">
              <div class="custom-control custom-switch custom-switch-lg">
                <input type="checkbox" class="custom-control-input" name="two_factor_auth" value="yes" @if (auth()->user()->two_factor_auth == 'yes') checked @endif id="customSwitch7">
                <label class="custom-control-label switch fs-16 font_weight_500" for="customSwitch7">
                  {{ __('general.two_step_auth') }}
                  <i class="bi bi-info-circle text-muted" data-toggle="tooltip" data-placement="top" title="{{trans('general.two_step_auth_info')}}"></i>
                </label>
              </div>
            </div>
          </div><!-- End form-group -->

          <button class="btn btn-1 btn-success btn-block" onClick="this.form.submit(); this.disabled=true; this.innerText='{{ __('general.please_wait')}}';" type="submit">{{ __('general.save_changes')}}</button>

        </form>
        @endif

        @if (! auth()->user()->isSuperAdmin())
        <p class="mt-5 font_weight_700 fs-24">{{ __('general.delete_account') }}</p>
        <small class="w-100 font_weight_400 fs-14">{{ __('general.delete_account_alert') }}</small>

        <div class="w-100 d-block mt-3 mb-5 ">
          <a class="btn btn-main pr-3 pl-3 delete_account_btn mobi_full_btn bg_prime_e3 brd-12" href="{{ url('account/delete') }}">
            {{ __('general.delete_account') }}</small>
          </a>
        </div>

        @if (auth()->user()->verified_id == 'yes' && auth()->user()->free_subscription == 'yes' && $settings->allow_creators_deactivate_profile)
        <h5 class="mt-5">{{ __('general.deactivate_your_account') }}</h5>
        <small class="w-100">{{ __('general.deactivate_your_account_alert') }}</small>

        <div class="w-100 d-block mt-2 mb-5">
          <form action="{{ route('deactivate.account') }}" method="POST">
            @csrf
            <button class="btn btn-main btn-warning pr-3 pl-3 brd-12 delete_account_btn" id="actionDeactivate">
              <i class="bi-person-slash mr-1"></i> {{ __('general.deactivate_your_account') }}</small>
            </button>
          </form>

        </div>
        @endif
        @endif

      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection

@section('javascript')
<script type="text/javascript">
  $("#actionDeactivate").on('click', function(e) {
    e.preventDefault();

    var element = $(this);
    var form = $(element).parents('form');

    element.blur();

    swal({
        title: delete_confirm,
        type: "warning",
        showLoaderOnConfirm: true,
        showCancelButton: true,
        confirmButtonColor: "#ffc107",
        confirmButtonText: "{{ __('general.yes_confirm_deactivate') }}",
        cancelButtonText: cancel_confirm,
        closeOnConfirm: false,
      },
      function(isConfirm) {
        if (isConfirm) {
          form.submit();
        }
      });
  });
</script>
@endsection