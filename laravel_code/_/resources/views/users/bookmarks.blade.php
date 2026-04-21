@extends('layouts.app')

@section('title') {{trans('general.bookmarks')}} -@endsection

@section('content')
<section class="section section-sm dashboard-home">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5 dashboard-home-container">
      <div class="row app-main-row dashboard-main-row">

        <div class="col-lg-3 col-md-3 side_bar_box_shadow dashboard-left-col">
          @include('includes.menu-sidebar-home')
        </div>

        <div class="col-lg-6 col-md-6 p-0 second wrap-post dashboard-center-col">

          @if($updates->count() != 0)
          <div class="grid-updates position-relative" id="updatesPaginator">
              @include('includes.updates')
          </div>

        @else
          <div class="grid-updates position-relative" id="updatesPaginator"></div>

        <div class="mb-5 text-center no-updates main-no-updates">
          <div class="sub-no-updates">
            <span class="btn-block mb-3">
              <i class="far fa-bookmark ico-no-result bg_black"></i>
            </span>
            <h4 class="font_weight_400 font_size_18">{{trans('general.no_bookmarks')}}</h4>
            <div class="no-updates-div"></div>
          </div>
        </div>
        <div class="grid-updates position-relative" id="updatesPaginator">
          <div class="p-3 d-lg-none">
            @include('includes.explore_creators')
          </div>
        </div>
        @endif
        </div><!-- end col-md-6 -->

        <div class="col-lg-3 col-md-3 @if ($users->count() != 0) mb-4 @endif d-md-block d-none dashboard-right-col">

          <div class="d-md-block sticky-top">
            @if ($users->count() == 0)
            <div class="panel panel-default panel-transparent mb-4 d-lg-block d-none">
          	  <div class="panel-body">
          	    <div class="media none-overflow">
          			  <div class="d-flex my-2 align-items-center">
          			      <img class="rounded-circle mr-2" src="{{Helper::getFile(config('path.avatar').auth()->user()->avatar)}}" width="60" height="60">

          						<div class="d-block">
          						<strong>{{auth()->user()->name}}</strong>


          							<div class="d-block">
          								<small class="media-heading text-muted btn-block margin-zero">
                            <a href="{{url('settings/page')}}">
                  						{{ auth()->user()->verified_id == 'yes' ? trans('general.edit_my_page') : trans('users.edit_profile')}}
                              <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small>
                            </a>
                          </small>
          							</div>
          						</div>
          			  </div>
          			</div>
          	  </div>
          	</div>
          @endif

            <div class="d-lg-block" id="">

              @if ($users->count() != 0)
                  @include('includes.explore_creators')
              @endif
              {{--
              <div class="d-lg-block d-none">
                @include('includes.footer-tiny')
              </div>
              --}}
           </div><!-- navbarUserHome -->
          </div><!-- sticky-top -->

        </div><!-- col-md -->

      </div>
    </div>
  </section>
@endsection
