@extends('layouts.app')

@section('title') {{trans('users.settings')}} -@endsection

@section('css')
<style>
  .user-settings-menu-center > .col-md-3.col-lg-3 {
    flex: 0 0 100%;
    max-width: 100%;
  }

  .user-settings-menu-center .btn-menu-expand {
    display: none !important;
  }

  .user-settings-menu-center .left-settings-sidebar.navbar-collapse {
    display: block !important;
  }
</style>
@endsection

@section('content')
<section class="section section-sm">
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
      <div class="row mb-sm justify-content-center">
        <div class="col-12 col-lg-10">
          <h2 class="mb-3 font-montserrat text-center">{{trans('general.settings')}}</h2>
          <div class="row user-settings-menu-center">
            @include('includes.cards-settings')
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
