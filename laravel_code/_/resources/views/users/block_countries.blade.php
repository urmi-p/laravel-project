@extends('layouts.app')

@section('title') {{trans('general.block_countries')}} -@endsection

@section('css')
<link href="{{ asset('plugins/select2/select2.min.css') }}?v={{$settings->version}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<section class="section section-sm">
  {{-- for mobile header --}}
  @include('includes.header-mobile')
  <div class="container-fluid pt-lg-5 pt-2">

    <div class="row">
      @include('includes.cards-settings')
      <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
        <div class="row mb-sm">
          <div class="col-lg-12">
            <h2 class="mb-0 font-montserrat pb-3 font_weight_700 fs-24">{{trans('general.block_countries')}}</h2>
            <p class="lead mt-0 font_weight_400 fs-14">{{trans('general.block_countries_info')}}</p>
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

        <form method="POST" action="{{ url('block/countries') }}" class="my-3">
          @csrf

          <div class="mb-4" style="width: 100%; overflow: hidden;">
            <!-- <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-globe"></i></span>
              </div> -->
            <label>{{trans('general.block_countries')}}</label>
            <select name="countries[]" multiple class="form-control light_mode_form" id="select2Countries">
              @foreach (Countries::orderBy('country_name', 'asc')->get() as $country)
              <option @if (in_array($country->country_code, auth()->user()->blockedCountries())) selected="selected" @endif value="{{$country->country_code}}">
                {{ $country->country_name }}
              </option>
              @endforeach
            </select>
          </div>

          <button class="btn btn-1 btn-success btn-block" onClick="this.form.submit(); this.disabled=true; this.innerText='{{trans('general.please_wait')}}';" type="submit">{{trans('general.save_changes')}}</button>
        </form>
      </div><!-- end col-md-6 -->
    </div>
  </div>
</section>
@endsection

@section('javascript')
<script src="{{ asset('plugins/select2/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/select2/i18n/'.config('app.locale').'.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  $('#select2Countries').select2({
    tags: false,
    tokenSeparators: [','],
    placeholder: '{{trans('
    general.block_countries ')}}',
    language: {
      searching: function() {
        return "{{trans('general.searching')}}";
      },
      noResults: function() {
        return '{{trans('
        general.no_results ')}}';
      }
    }
  });
</script>
@endsection