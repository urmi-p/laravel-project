@extends('layouts.app')

@section('title') {{trans('users.settings')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="bi-shield-check mr-2"></i> {{trans('general.settings')}}</h2>
        </div>
      </div>
      <div class="row">
        @include('includes.cards-settings')
      </div>
    </div>
  </section>
@endsection
