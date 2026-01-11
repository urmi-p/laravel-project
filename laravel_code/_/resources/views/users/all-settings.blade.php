@extends('layouts.app')

@section('title') {{trans('users.settings')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container-fluid pt-lg-5 pt-2">
      <div class="row mb-sm">
        @include('includes.cards-settings')
        <div class="col-lg-9">
          <h2 class="mb-0 font-montserrat">{{trans('general.settings')}}</h2>
        </div>
      </div>
    </div>
  </section>
@endsection
