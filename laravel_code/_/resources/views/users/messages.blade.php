@extends('layouts.app')

@section('title'){{trans('general.messages')}} -@endsection

@section('css')
<style>
  @media (max-width: 767.98px) {
    .messages-mobile-action-card {
      margin: 0 0 1rem;
      border-radius: 1.5rem;
      background: #303030;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .messages-mobile-action-card .card-body {
      padding: 0.95rem;
    }

    .messages-mobile-action-card .btn {
      width: 100%;
      min-height: 3.25rem;
      border-radius: 0.75rem;
      font-weight: 600;
    }

    .messages-landing-page #messagesContainer .messages-empty-state {
      width: 100% !important;
      margin: 0 !important;
      display: flex;
      align-items: center;
      justify-content: center;
      align-self: stretch;
    }

    .messages-landing-page #messagesContainer .messages-empty-state .card-body {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding-left: 1rem;
      padding-right: 1rem;
    }

  }
</style>
@endsection

@section('content')
<section class="section section-sm pb-0 section-msg messages-landing-page">
  <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
    <div class="row justify-content-center h-100 mx-0">

      @if (auth()->check() && auth()->user()->role === 'admin')
        <div class="col-lg-3 col-md-3 side_bar_box_shadow">
          @include('includes.menu-sidebar-home')
        </div>
      @else
        <div class="col-lg-3">
          @include('includes.menu-sidebar-home')
        </div>
      @endif
      <div class="col-md-6 col-sm-6 p-0 messages-middle-col">
        <div class="card border-0 d-lg-block d-md-block d-none messageDiv">
          <div class="content px-1 py-3 d-scrollbars container-msg">

            <div class="flex-column d-flex justify-content-center text-center h-100">

              <div class="w-100">
                <h2 class="mb-0 font-montserrat"><i class="feather icon-send mr-2"></i> {{trans('general.messages')}}</h2>
                <p class="lead text-muted mt-0">{{trans('general.messages_subtitle')}}</p>
                <button class="btn btn-primary btn-sm w-small-100" data-toggle="modal" data-target="#newMessageForm">
                  <i class="bi bi-plus-lg mr-1"></i> {{trans('general.new_message')}}
                </button>
              </div>

            </div>
          </div><!-- container-msg -->

        </div><!-- card -->
      </div><!-- end col-md-6 -->
      <div class="col-sm-3 col-md-6 col-lg-3 p-0">
        @if (auth()->user()->verified_id == 'yes' && request()->is('messages') && auth()->user()->totalSubscriptionsActive() > 1)
          <div class="card border-0 d-md-none messages-mobile-action-card">
            <div class="card-body">
              <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newMessageForm">
                <i class="bi bi-plus-lg mr-1"></i> {{ trans('general.new_message') }}
              </button>
            </div>
          </div>
        @endif

        <div class="wrapper-msg-inbox" id="messagesContainer">
        @include('includes.sidebar-messages-inbox')
        </div>
      </div>
    </div><!-- end row -->
  </div><!-- end container -->
</section>
@include('includes.modal-new-message')
@endsection

@section('javascript')
<script src="{{ asset('js/messages.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/fileuploader/fileuploader-msg.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/paginator-messages.js') }}"></script>
@endsection
