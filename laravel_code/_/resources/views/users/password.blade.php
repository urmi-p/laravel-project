@extends('layouts.app')

@section('title') {{__('general.password')}} -@endsection

@section('content')
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
    <div class="row">
      @include('includes.cards-settings')
      <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
        <div class="row mb-sm">
          <div class="col-lg-8">
            <h2 class="mb-0 font-montserrat font_weight_700 pb-3 fs-24">{{__('general.password')}}</h2>
            <p class="lead mt-0 font_weight_400 fs-14">{{__('auth.update_your_password')}}</p>
          </div>
        </div>
        @if (session('status'))
        <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>

          {{ session('status') }}
        </div>
        @endif

        @if (session('incorrect_pass'))
        <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ session('incorrect_pass') }}
        </div>
        @endif

        @include('errors.errors-forms')

        <form method="POST" action="{{ url('settings/password') }}">

          @csrf

          @if (auth()->user()->password != '')
          <div class="form-group">
            <label class="font_weight_500 fs-16">{{ __('general.old_password') }}</label>
            <div class="input-group input-group-sub mb-4">
              <!-- <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-unlock"></i></span>
              </div> -->
              <input class="form-control brd-12" name="old_password" placeholder="{{__('general.enter_your_old_password')}}" type="password" required>
            </div>
          </div>
          @endif

          <div class="form-group">
            <label class="font_weight_500 fs-16">{{ __('general.new_password') }}</label>
            <div class="input-group input-group-sub mb-4" id="showHidePassword">
              
              <input class="form-control brd-12" name="new_password" placeholder="{{__('general.enter_your_new_password')}}" type="password" required oninput="checkStrength(this.value)">
              <div class="input-group-append">
                <span class="input-group-text c-pointer"><i class="feather icon-eye-off"></i></span>
              </div>
            </div>
            
                  <div class="strength-bar">
                    <div id="strengthFill" class="strength-fill"></div>
                  </div>

                  <div id="strengthText" class="strength-text">Very Weak</div>

                  <div id="strengthHint" class="strength-hint">
                    Please enter strong password include Capital Letters, Numbers and signs
                  </div>
                
          </div>
          <div class="form-group">
            <label class="font_weight_500 fs-16">{{ __('auth.confirm_password') }}</label>
            <div class="input-group input-group-sub mb-4">
              <input
                class="form-control brd-12"
                name="confirm_password"
                placeholder="{{ __('general.enter_your_new_password') }}"
                type="password"
                required
              >
            </div>

            @error('confirm_password')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>

          <button class="btn btn-1 btn-success btn-block buttonActionSubmit" type="submit">{{__('general.save_changes')}}</button>

        </form>
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection