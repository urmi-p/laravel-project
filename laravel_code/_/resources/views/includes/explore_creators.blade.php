<div class="mb-2">
  <h6 class="mb-3 filter-explorer explore_title">
    <span class="explore_title_text">{{__('general.explore_our_creators')}}</span>

    <span class="explore_actions">
      @auth
      @if ($users->count() > 2)
      <a href="javascript:void(0);" class="h5 text-decoration-none refresh_creators toggleFindFreeCreators btn-tooltip" data-toggle="tooltip" data-placement="top" title="{{ __('general.show_only_free') }}">
        <i class="feather icon-tag"></i>
      </a>
      <a href="javascript:void(0);" class="h5 text-decoration-none refresh_creators refresh-btn">
        <i class="feather icon-refresh-cw"></i>
      </a>
      @endif
      @else
      @if (!$settings->disable_creators_section)
      <a href="{{url('creators')}}">{{ __('general.view_all') }} <small class="pl-1"><i class="fa fa-long-arrow-alt-right"></i></small></a>
      @endif
      @endauth
    </span>
  </h6>



  <ul class="list-group">

    <div class="containerRefreshCreators">

      @include('includes.listing-explore-creators')

    </div><!-- containerRefreshCreators -->

  </ul>

</div><!-- d-lg-none -->
