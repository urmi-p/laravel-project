@extends('layouts.app')

@section('title') {{trans('general.sales')}} -@endsection

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
              <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3">{{trans('general.sales')}}</h2>
              <p class="lead mt-0 font_weight_400 fs-14">{{trans('general.sales_your_products')}}</p>
            </div>
          </div>
          @if ($sales->count() != 0)

              <div class="btn-block mb-3 text-right">
                <span>
                  {{trans('general.filter_by')}}

                  <select class="ml-2 custom-select w-auto" id="filter">
                      <option @if (! request()->get('sort')) selected @endif value="{{url('my/sales')}}">{{trans('general.latest')}}</option>
                        <option @if (request()->get('sort') == 'oldest') selected @endif value="{{url('my/sales')}}?sort=oldest">{{trans('general.oldest')}}</option>
                      <option @if (request()->get('sort') == 'pending') selected @endif value="{{url('my/sales')}}?sort=pending">{{trans('general.pending')}}</option>
                    </select>
                </span>
              </div>

            @if (session('message'))
            <div class="alert alert-success mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('message') }}
            </div>
            @endif

            @if (session('error_message'))
            <div class="alert alert-danger mb-3">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
              </button>
              <i class="fa fa-check mr-1"></i> {{ session('error_message') }}
            </div>
            @endif

          <div class="card shadow-sm mb-2">
          <div class="table-responsive">
            <table class="table table-striped m-0">
              <thead>
                <tr>
                  <th scope="col">{{trans('general.item')}}</th>
                  <th scope="col">{{trans('general.buyer')}}</th>
                  <th scope="col">{{trans('general.delivery_status')}}</th>
                  <th scope="col">{{trans('general.price')}}</th>
                  <th scope="col">{{trans('admin.date')}}</th>
                  <th scope="col">{{trans('admin.actions')}}</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($sales as $sale)
                  <tr>
                    <td>
                      <a href="{{url('shop/product', $sale->products()->id)}}">
                        {{ Str::limit($sale->products()->name, 25, '...') }}
                      </a>
                      </td>
                      <td>
                        @if (! isset($sale->user()->username))
                          {{ trans('general.no_available') }}
                        @else
                        <a href="{{ url($sale->user()->username) }}">{{ '@'.$sale->user()->username }}</a>
                      @endif
                      </td>
                      <td>
                        @if ($sale->delivery_status == 'delivered')
                          <span class="badge badge-pill badge-success text-uppercase">{{ __('general.delivered') }}</span>

                        @else
                          <span class="badge badge-pill badge-warning text-uppercase">{{ __('general.pending') }}</span>
                        @endif
                      </td>
                    <td>{{ Helper::amountFormatDecimal($sale->transactions()->amount) }}</td>
                    <td>{{Helper::formatDate($sale->created_at)}}</td>

                    <td>
                      @if ($sale->products()->type == 'custom' || $sale->products()->type == 'physical')
                      <div class="d-flex">

                        <a title="{{ __('general.see_details') }}" class="d-inline-block mr-2 btn btn-primary btn-sm-custom" data-toggle="modal" data-target="#customContentForm{{$sale->id}}" href="#">
                        <i class="bi-eye"></i>
                        </a>

                        @if ($sale->delivery_status == 'pending')
                          <form class="d-inline-block" method="post" action="{{url('delivered/product', $sale->id)}}">
                            @csrf
                            <button title="{{ __('general.mark_as_delivered') }}" class="mr-2 btn btn-success btn-sm-custom actionAcceptReject acceptOrder" type="button">
                              <i class="bi-check2"></i>
                            </button>
                          </form>

                          <form class="d-inline-block" method="post" action="{{ url('reject/order', $sale->id) }}">
                            @csrf
                            <button title="{{ __('general.reject') }}" class="btn btn-danger btn-sm-custom actionAcceptReject rejectOrder" type="button">
                              <i class="bi-x-lg"></i>
                            </button>
                          </form>

                          </div>
                        @endif

                      @else
                        {{ __('general.not_applicable') }}
                      @endif
                    </td>
                  </tr>

                  @include('includes.modal-custom-content')

                @endforeach

              </tbody>
            </table>
          </div>
          </div><!-- card -->

            @if ($sales->hasPages())
              <div class="mt-2">
    			    	{{ $sales->onEachSide(0)->appends(['sort' => request('sort')])->links() }}
                </div>
    			    	@endif

        @else
          <div class="text-center no-updates main-no-updates">
            <div class="sub-no-updates">
              <span class="btn-block mb-3">
                <i class="bi-cart-x ico-no-result bg_black"></i>
              </span>
              <h4 class="font_weight_400 font_size_18">{{trans('general.no_results_found')}}</h4>
            </div>
          </div>
        @endif

        </div><!-- end col-md-6 -->
      </div>
    </div>
  </section>
@endsection
