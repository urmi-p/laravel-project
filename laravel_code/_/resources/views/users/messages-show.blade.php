@extends('layouts.app')

@section('title'){{__('general.messages')}} -@endsection
@section('body_class', 'messages-detail-page chat-detail-route-body')

@section('css')
  <script type="text/javascript">
      var subscribed_active = {{ $subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin() ? 'true' : 'false' }};
      var user_id_chat = {{ $user->id }};
      var msg_count_chat = {{ $messages->count() }};
      var callingToFan = "{{ __('general.calling') }} {{ '@' . $user->username }}";
  </script>

  <style>
    @media (min-width: 991px) {
      .fileuploader-theme-thumbnails .fileuploader-thumbnails-input,
      .fileuploader-theme-thumbnails .fileuploader-items-list .fileuploader-item {
        width: calc(14% - 16px);
        padding-top: 12%;
      }
    }

    .profile-card {
      text-align: center;
    }

    .profile-desc {
      font-weight: 400;
      font-size: 14px;
      max-width: 320px;
      margin: 10px auto 18px;
      line-height: 1.5;
    }

    [data-bs-theme="dark"] .profile-desc {
      color: #FFFFFF;
    }

    [data-bs-theme="light"] .profile-desc {
      color: #222;
    }

    .visit-profile-btn {
      display: block;
      width: fit-content;
      background: #E2394C;
      color: #fff;
      padding: 8px 14px;
      border-radius: 12px;
      text-decoration: none;
      transition: 0.25s;
      margin: 0 auto;
    }

    .desc-break {
      display: block;
    }

    .message-composer-toolbar {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 8px;
    }

    .message-send-wrap {
      margin-left: auto;
    }

    @media (max-width: 767.98px) {
      html,
      body.messages-detail-page.chat-detail-route-body {
        background: #1f1f1f !important;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell main[role="main"] .section.section-sm.section-msg.chat-detail-route.messages-chat-page {
        width: 100vw !important;
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
        background: #1f1f1f !important;
      }

      .chat-detail-route.messages-chat-page .messages-middle-col {
        flex: 0 0 100% !important;
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
        flex-direction: column !important;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell {
        overflow: hidden !important;
        background: #1f1f1f;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell .modern-navbar.site-header,
      body.messages-detail-page.chat-detail-route-body.app-auth-shell .app-mobile-top-tabs,
      body.messages-detail-page.chat-detail-route-body.app-auth-shell .menuMobile,
      body.messages-detail-page.chat-detail-route-body.app-auth-shell .app-footer-shell {
        display: none !important;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell main[role="main"] {
        height: 100dvh;
        min-height: 100dvh;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: none !important;
        background: #1f1f1f !important;
        overflow: hidden !important;
      }

      .chat-detail-route.messages-chat-page {
        height: 100dvh !important;
        min-height: 100dvh !important;
        width: 100vw !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #1f1f1f;
        overflow: hidden !important;
      }

      .chat-detail-route.messages-chat-page > .container-fluid,
      .chat-detail-route.messages-chat-page > .container-fluid > .row.justify-content-center.h-100,
      .chat-detail-route.messages-chat-page .messages-middle-col {
        height: 100% !important;
        min-height: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
      }

      .chat-detail-route.messages-chat-page > .container-fluid.pt-lg-5.pt-2.px-lg-5 {
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
      }

      .chat-detail-route.messages-chat-page #messagesContainer.wrapper-msg-inbox {
        display: none !important;
      }

      .chat-detail-route .profile-desc,
      .chat-detail-route .visit-profile-btn {
        display: none !important;
      }

      .chat-detail-route .messageDiv {
        margin: 0 !important;
        padding: 0 !important;
        gap: 0 !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        display: flex !important;
        flex-direction: column;
        height: 100dvh !important;
        min-height: 100dvh !important;
        max-height: 100dvh !important;
        box-shadow: none !important;
        isolation: auto !important;
        overflow: hidden !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header {
        position: sticky;
        top: 0;
        z-index: 20;
        padding: 1rem 1.1rem 1rem !important;
        background: none;
        border-bottom: 0.0625rem solid #ffffff !important;
        overflow: visible !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-header-row {
        width: 100%;
        align-items: center;
        gap: 0;
        flex-wrap: nowrap;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-back-link {
        margin-right: 0 !important;
        flex: 0 0 auto;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-back-link i {
        font-size: 1.75rem;
        color: #fff;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-profile {
        display: grid;
        grid-template-columns: 3.55rem minmax(0, 1fr);
        align-items: center;
        gap: 0.95rem;
        flex: 1 1 auto;
        width: auto;
        min-width: 0;
        margin-left: 0 !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-avatar-link {
        margin-right: 0 !important;
        flex: 0 0 auto;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-avatar {
        width: 3.55rem !important;
        height: 3.55rem !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .user-status::before,
      .chat-detail-route .card-header.chat-detail-page-header .user-status::after {
        display: none !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-copy {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
        text-align: left;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell main[role="main"] .chat-detail-route .card-header.chat-detail-page-header .chat-detail-name {
        display: flex;
        align-items: center;
        gap: 0.32rem;
        margin: 0 0 0.12rem !important;
        min-width: 0;
        color: #fff;
        font-size: 1.22rem !important;
        line-height: 1.08 !important;
        font-weight: 700 !important;
        white-space: nowrap;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-name a,
      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-name .verified {
        color: #fff !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-name a {
        display: block;
        min-width: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-name .verified {
        flex: 0 0 auto;
        font-size: 0.86rem;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-username {
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.96rem;
        line-height: 1.18;
        color: rgba(255, 255, 255, 0.84);
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-status {
        display: none !important;
        align-items: center;
        gap: 0.3rem;
        margin-top: 0.2rem;
        font-size: 0.8rem;
        line-height: 1.1;
        color: rgba(164, 173, 194, 0.78);
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-action-link {
        width: 2rem;
        height: 2rem;
        min-width: 2rem;
        min-height: 2rem;
        padding: 0 !important;
        border-radius: 999rem;
        background: transparent;
        box-shadow: none;
        color: rgba(96, 105, 130, 0.95);
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-action-link.float-right {
        flex: 0 0 auto;
        margin-right: 0 !important;
        margin-left: 0.15rem;
      }

      .chat-detail-route .container-msg {
        flex: 1 1 auto;
        padding: 1rem 1.1rem 1rem !important;
        min-height: 0 !important;
        height: auto !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch;
        background: none;
        border-bottom: 0.0625rem solid #ffffff !important;
      }

      .chat-detail-route .chatlist {
        padding-top: 1.1rem !important;
        padding-bottom: 1.1rem !important;
      }

      .chat-detail-route .chatlist > a.align-self-end.mr-3 {
        margin-right: 0.625rem !important;
      }

      .chat-detail-route .chatlist .avatar-chat {
        width: 2rem !important;
        height: 2rem !important;
      }

      .chat-detail-route .chatlist .wrapper-msg-left,
      .chat-detail-route .chatlist .wrapper-msg-right {
        max-width: min(76vw, 21rem) !important;
      }

      .chat-detail-route .chatlist .message.media-container {
        padding: 0 !important;
        background: transparent !important;
        border-radius: 0 !important;
        gap: 0 !important;
        box-shadow: none !important;
      }

      .chat-detail-route .chatlist .message.media-container .media-wrapper,
      .chat-detail-route .chatlist .message.media-container .container-media-msg {
        border-radius: 1rem !important;
        overflow: hidden;
      }

      .chat-detail-route .chatlist .message:not(.media-container) {
        font-size: 1rem;
        line-height: 1.45;
        padding: 0.9rem 1.05rem !important;
        border-radius: 1.2rem !important;
      }

      .chat-detail-route .chatlist .media-body > .small,
      .chat-detail-route .chatlist .timeAgo,
      .chat-detail-route .chatlist .text-muted {
        color: rgba(159, 169, 189, 0.95) !important;
      }

      .chat-detail-route .chatlist .small {
        font-size: 0.78rem;
      }

      .chat-detail-route .chatlist .chat-gift-card {
        padding: 0 !important;
        background: transparent !important;
        text-align: right;
      }

      .chat-detail-route .chatlist .chat-gift-figure {
        display: block;
        width: 7.25rem;
        margin: 0 0 0.75rem auto;
      }

      .chat-detail-route .chatlist .chat-gift-figure img {
        width: 100% !important;
        max-width: 100% !important;
      }

      .chat-detail-route .chatlist .chat-gift-price {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
        color: #fff !important;
        font-size: 0.95rem !important;
        font-weight: 700;
      }

      .chat-detail-route .chatlist .chat-gift-price small,
      .chat-detail-route .chatlist .chat-gift-price strong {
        color: inherit !important;
        font-size: inherit !important;
      }

      .chat-detail-route .card-footer {
        position: sticky;
        bottom: 0;
        z-index: 25;
        margin-top: auto !important;
        padding: 1.05rem 1.1rem calc(1.15rem + env(safe-area-inset-bottom)) !important;
        background: transparent !important;
        border-top: 0.0625rem solid #ffffff !important;
      }

      .chat-detail-route .chat-detail-input-wrap {
        margin-right: 0 !important;
      }

      .chat-detail-route .chat-detail-input-wrap .triggerEmoji {
        position: absolute;
        top: 50%;
        right: 1rem;
        z-index: 2;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.5rem;
      }

      .chat-detail-route #messageChat {
        min-height: 4.45rem;
        max-height: 7rem;
        padding: 1.05rem 3.35rem 1.05rem 1.15rem;
        border: 0 !important;
        border-radius: 1.2rem !important;
        background: rgba(255, 255, 255, 0.07) !important;
        color: #fff !important;
        font-size: 1.02rem !important;
        line-height: 1.35;
        box-shadow: none !important;
      }

      .chat-detail-route #messageChat::placeholder {
        color: rgba(255, 255, 255, 0.45);
      }

      .chat-detail-route .message-composer-toolbar {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 5.15rem;
        align-items: end;
        gap: 0.75rem;
        margin-top: 1.05rem !important;
      }

      .chat-detail-route .chatlist .iconmoon.icon-Delete,
      .chat-detail-route .chatlist .fa-trash,
      .chat-detail-route .chatlist .actionDeleteMsg,
      .chat-detail-route .chatlist .deleteMsg {
        opacity: 0.45;
      }

      .chat-detail-route .chat-detail-action-icons {
        display: flex;
        width: 100%;
        flex-wrap: nowrap;
        align-items: center;
        gap: 0.6rem;
        min-width: 0;
        overflow: visible;
        padding-bottom: 0.125rem;
        justify-content: flex-start;
      }

      .chat-detail-route .chat-detail-action-icons::-webkit-scrollbar {
        display: none;
      }

      .chat-detail-route .card-footer .btn-upload {
        width: 2.6rem;
        height: 2.6rem;
        min-width: 2.6rem;
        padding: 0 !important;
        border-radius: 999rem !important;
        color: rgba(255, 255, 255, 0.92) !important;
      }

      .chat-detail-route .message-send-wrap {
        margin: 0 !important;
        width: 5.15rem;
        min-width: 5.15rem;
        max-width: 5.15rem;
        display: flex;
        justify-content: flex-end;
        align-self: flex-end;
        overflow: visible;
        padding-left: 0.2rem;
      }

      .chat-detail-route .message-send-wrap #buttonReplyMsgChat {
        width: 5.15rem;
        min-width: 5.15rem;
        max-width: 5.15rem;
        height: 3.05rem;
        padding: 0;
        border-radius: 999rem !important;
        float: none !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        box-shadow: none;
      }

      .chat-detail-route .message-send-wrap #buttonReplyMsgChat i {
        margin: 0;
      }

      .chat-detail-route .card-header,
      .chat-detail-route .card-footer {
        flex: 0 0 auto;
      }

      body.messages-detail-page.chat-detail-route-body.app-auth-shell main .container-fluid,
      body.messages-detail-page.chat-detail-route-body.app-auth-shell main .row {
        max-width: none !important;
      }

      .chat-detail-route.messages-chat-page .card,
      .chat-detail-route.messages-chat-page .messageDiv {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: 0 !important;
        margin-right: 0 !important;
        margin-bottom: 0 !important;
        padding: 0 !important;
        gap: 0 !important;
        background: transparent !important;
      }

      .chat-detail-route.messages-chat-page > .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
        max-width: none !important;
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
      }

      .chat-detail-route.messages-chat-page .messages-middle-col .messageDiv {
        height: 100dvh !important;
        min-height: 100dvh !important;
        max-height: 100dvh !important;
        gap: 0 !important;
      }

      .chat-detail-route.messages-chat-page > .container-fluid > .row.justify-content-center.h-100 {
        --bs-gutter-x: 0 !important;
        --bs-gutter-y: 0 !important;
        width: 100% !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-header-row {
        display: grid;
        grid-template-columns: 1.8rem minmax(0, 1fr) auto;
        column-gap: 0.6rem;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-back-link {
        width: 1.8rem;
        min-width: 1.8rem;
        margin: 0 !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-status .timeAgo {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.42rem;
        margin-left: 0.35rem;
        padding-top: 0;
        flex: 0 0 auto;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-more {
        position: relative;
        flex: 0 0 auto;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-actions .float-right,
      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-actions #dropdown_options {
        margin: 0 !important;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-action-link {
        width: 2rem;
        height: 2rem;
        min-width: 2rem;
        min-height: 2rem;
        align-self: center;
        font-size: 1.15rem;
      }

      .chat-detail-route .card-header.chat-detail-page-header .chat-detail-action-link i {
        font-size: 1.15rem;
      }

      .chat-detail-route .card-header.chat-detail-page-header .dropdown-menu {
        top: calc(100% + 0.15rem) !important;
        right: 0 !important;
        left: auto !important;
        min-width: 12rem;
        z-index: 40;
        margin: 0 !important;
        transform: none !important;
      }

      .chat-detail-route .container-msg {
        padding: 0.35rem 1.25rem 1.25rem !important;
      }

      .chat-detail-route .chatlist {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
      }

      .chat-detail-route .chatlist > a.align-self-end.mr-3 {
        display: none !important;
      }

      .chat-detail-route .chatlist .media-body {
        width: 100%;
      }

      .chat-detail-route .chatlist .wrapper-msg-left,
      .chat-detail-route .chatlist .wrapper-msg-right,
      .chat-detail-route .chatlist .float-left,
      .chat-detail-route .chatlist .float-right {
        max-width: min(74vw, 21rem) !important;
      }

      .chat-detail-route .chatlist .message.bg-primary {
        border-radius: 1.35rem !important;
      }

      .chat-detail-route .chatlist .message.rounded-top-right-0,
      .chat-detail-route .chatlist .message.rounded-bottom-right-0 {
        border-top-right-radius: 0.5rem !important;
        border-bottom-right-radius: 0.5rem !important;
      }

      .chat-detail-route .chatlist .message.rounded-top-left-0,
      .chat-detail-route .chatlist .message.rounded-bottom-left-0 {
        border-top-left-radius: 0.5rem !important;
        border-bottom-left-radius: 0.5rem !important;
      }

      .chat-detail-route .chatlist .chat-gift-card {
        margin-left: auto;
        width: min(74vw, 14rem);
      }

      .chat-detail-route .chatlist .chat-gift-figure {
        width: 8.75rem;
        margin-bottom: 1rem;
      }

      .chat-detail-route .chatlist .chat-gift-price {
        font-size: 1rem !important;
      }

      .chat-detail-route .chatlist .chat-gift-card .card-body {
        padding-right: 0 !important;
        padding-left: 0 !important;
      }

      .chat-detail-route .card-footer {
        padding: 1rem 1.25rem calc(1.1rem + env(safe-area-inset-bottom)) !important;
      }

      .chat-detail-route #messageChat {
        min-height: 4.5rem;
        border-radius: 1.1rem !important;
      }

      .chat-detail-route .message-composer-toolbar {
        grid-template-columns: minmax(0, 1fr) 5.6rem;
        gap: 0.6rem;
      }

      .chat-detail-route .chat-detail-action-icons {
        justify-content: flex-start;
        gap: 0.55rem;
      }

      .chat-detail-route .card-footer .btn-upload {
        width: 2.7rem;
        height: 2.7rem;
        min-width: 2.7rem;
      }

      .chat-detail-route .message-send-wrap #buttonReplyMsgChat {
        width: 5.6rem;
        min-width: 5.6rem;
        max-width: 5.6rem;
        height: 3.05rem;
      }

      .chat-detail-route .message-send-wrap {
        width: 5.6rem;
        min-width: 5.6rem;
        max-width: 5.6rem;
        padding-left: 0.15rem;
      }

      @media (max-width: 420px) {
        .chat-detail-route .message-composer-toolbar {
          grid-template-columns: minmax(0, 1fr) 4.85rem;
          gap: 0.35rem;
        }

        .chat-detail-route .chat-detail-action-icons {
          gap: 0.3rem;
        }

        .chat-detail-route .card-footer .btn-upload {
          width: 2.35rem;
          height: 2.35rem;
          min-width: 2.35rem;
        }

        .chat-detail-route .card-footer .btn-upload i,
        .chat-detail-route .card-footer .btn-upload svg {
          transform: scale(0.9);
          transform-origin: center;
        }

        .chat-detail-route .message-send-wrap,
        .chat-detail-route .message-send-wrap #buttonReplyMsgChat {
          width: 4.85rem;
          min-width: 4.85rem;
          max-width: 4.85rem;
        }

        .chat-detail-route .message-send-wrap #buttonReplyMsgChat {
          height: 2.9rem;
        }
      }
    }
  </style>
@endsection

@section('content')
<section class="section section-sm pb-0 section-msg messages-chat-page chat-detail-route">
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
      <div class="row justify-content-center h-100 mx-0">
        @if (auth()->check() && auth()->user()->role === 'admin')
          <div class="col-lg-3 col-md-3 side_bar_box_shadow">
            @include('includes.menu-sidebar-home')
          </div>
        @else
          <div class="col-md-3 d-lg-block d-none side_bar_box_shadow h-100">
            @include('includes.menu-sidebar-home')
          </div>
        @endif     

          <div class="col-md-6 col-sm-6 h-100 p-0 first messages-middle-col">

          <div class="card  border-0  messageDiv">
            <div class="card-header chat-detail-page-header border-0 p-0">
              <div class="media chat-detail-header-row">
                <a href="{{url()->previous()}}" class="mr-3 chat-detail-back-link"><i class="fa fa-arrow-left"></i></a>
                <div class="media-message-profile-center chat-detail-profile">
                  <a href="{{url('profile', $user->username)}}" class="mr-3 chat-detail-avatar-link">
                    <span class="position-relative user-status @if ($user->active_status_online == 'yes') @if (Helper::isOnline($user->id)) user-online @else user-offline @endif @endif d-block">
                      <img src="{{Helper::getFile(config('path.avatar').$user->avatar)}}" class="rounded-circle chat-detail-avatar" width="95" height="95">
                    </span>
                  </a>

                  <div class="media-body profile-card chat-detail-copy">
                    <h6 class="m-0 fs-24 font_weight_500 chat-detail-name">
                      <a href="{{url('profile', $user->username)}}">
                        {{$user->hide_name == 'yes' ? $user->username : $user->name}}
                      </a>

                      @if ($user->verified_id == 'yes')
                        <small class="verified">
                            <i class="bi bi-patch-check-fill"></i>
                          </small>
                      @endif
                    </h6>
                    <div class="chat-detail-mobile-handle chat-detail-username d-md-none">
                      {{ '@' . $user->username }}
                    </div>
                    @if ($user->active_status_online == 'yes' && $user->hide_last_seen == 'no')
                      <div class="chat-detail-mobile-status chat-detail-status d-md-none">
                        <span>{{ __('general.active') }}</span>
                        <small class="timeAgo @if (Helper::isOnline($user->id)) display-none @endif" data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
                      </div>
                    @endif
                    <!-- Description line -->
                    <p class="profile-desc d-none d-md-block">
                      {{ __('general.chat_with') }}
                      {{ $user->hide_name == 'yes' ? $user->username : $user->name }},
                      <span class="desc-break">
                        {{ __('general.mutual_follow') }}
                      </span>
                    </p>

                    <!-- Visit Profile Button -->
                    <a href="{{ url('profile',$user->username) }}" class="btn visit-profile-btn d-none d-md-block">
                      {{__('general.visit_profile')}}
                    </a>

                    <div class="d-none d-md-block">
                    @if ($user->active_status_online == 'yes')

                        @if ($user->hide_last_seen == 'no')
                          <small>{{ __('general.active') }}</small>
                          <span id="timeAgo">
                            <small class="timeAgo @if (Helper::isOnline($user->id)) display-none @endif" id="lastSeen" data="{{ date('c', strtotime($user->last_seen ?? $user->date)) }}"></small>
                          </span>
                        @else
                          {{'@'.$user->username}}
                        @endif
                    @else
                      {{'@'.$user->username}}
                    @endif
                    </div>
                    
                  </div>
                </div>
                <div class="chat-detail-mobile-actions chat-detail-actions">
                  @if (auth()->user()->verified_id == 'yes' 
                      && $settings->audio_call_status
                      && auth()->user()->price_audio_call
                      && !auth()->user()->isRestricted($user->id)
                      )
                  <a href="javascript:void(0);" class="float-right vertical-ellipsis chat-detail-action-link mr-1 text-decoration-none @if (Helper::isOnline($user->id)) startAudioCall @else buttonDisabled @endif" @if (Helper::isOnline($user->id)) data-toggle="tooltip" data-placement="bottom" title="{{ __('general.new_audio_call') }}" @endif role="button">
                    <i class="feather icon-phone"></i>
                  </a>
                  @endif

                  @if (auth()->user()->verified_id == 'yes' 
                      && $settings->video_call_status
                      && auth()->user()->price_video_call
                      && !auth()->user()->isRestricted($user->id)
                      )
                  <a href="javascript:void(0);" class="float-right vertical-ellipsis chat-detail-action-link mr-1 text-decoration-none @if (Helper::isOnline($user->id)) startVideoCall @else buttonDisabled @endif" @if (Helper::isOnline($user->id)) data-toggle="tooltip" data-placement="bottom" title="{{ __('general.new_video_call') }}" @endif role="button">
                    <i class="feather icon-video"></i>
                  </a>
                  @endif

                  <div class="dropdown chat-detail-more">
                    <a href="javascript:void(0);" class="float-right vertical-ellipsis chat-detail-action-link" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fa fa-ellipsis-v"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">

                    @if ($user->verified_id == 'yes' 
                      && $settings->live_streaming_private == 'on' 
                      && $user->allow_live_streaming_private == 'on' 
                      && !auth()->user()->isRestricted($user->id)
                      && Helper::isOnline($user->id)
                      )
                    <button type="button" class="dropdown-item requestLivePrivateModal" data-toggle="tooltip" data-placement="bottom">
                          <i class="feather icon-video mr-2"></i> {{ __('general.request_private_live_stream') }}
                      </button>
                      @endif

                  @if ($messages->count() != 0 && $settings->users_can_delete_messages)
                    <form method="POST" action="{{ url('conversation/delete', $user->id) }}" class="d-inline">
                      @csrf
                      <button type="submit" class="dropdown-item actionDelete">
                          <i class="feather icon-trash-2 mr-2"></i> {{ __('general.delete') }}
                      </button>
                  </form>

                    @endif

                    @if (auth()->user()->isRestricted($user->id))
                      <button type="button" class="dropdown-item removeRestriction" data-user="{{$user->id}}" id="restrictUser">
                        <i class="fas fa-ban mr-2"></i> {{__('general.remove_restriction')}}
                      </button>

                    @else
                      <button type="button" class="dropdown-item" data-user="{{$user->id}}" id="restrictUser">
                        <i class="fas fa-ban mr-2"></i> {{__('general.restrict')}}
                      </button>
                    @endif
                  </div>
                </div>
                </div>

              </div>

            </div>

            <div class="content py-3 custom-scrollbar container-msg" id="contentDIV" data="{{$user->id}}">

              @if ($messages->count() != 0)
              <div class="flex-column d-flex justify-content-center text-center h-100">
                <div class="w-100" id="loadAjaxChat">
                  <div class="spinner-border text-primary" role="status"></div>
                </div>
              </div>
            @endif
              </div><!-- contentDIV -->

              @if (!auth()->user()->checkRestriction($user->id) && $user->allow_dm || auth()->user()->isSuperAdmin())
                  <div class="card-footer position-relative">

                  @if ($subscribedToYourContent || $subscribedToMyContent || auth()->user()->isSuperAdmin() || $user->isSuperAdmin())

                    <div class="w-100 display-none" id="previewFileChat">
                      <div class="previewFile d-inline"></div>
                      <a href="javascript:;" class="text-danger" id="removeFileChat"><i class="fa fa-times-circle"></i></a>
                    </div>

                    <div class="progress-upload-cover" style="width: 0%; top:0;"></div>

                    <div class="blocked display-none"></div>

                    <!-- Alert -->
                    <div class="alert alert-danger my-3" id="errorMsgChat" style="display: none;">
                    <ul class="list-unstyled m-0" id="showErrorMsgChat"></ul>
                  </div><!-- Alert -->

                    <form action="{{url('message/send')}}" class="w-100 chat-composer-form" method="post" accept-charset="UTF-8" id="formSendMsgChat" enctype="multipart/form-data">
                      <input type="hidden" name="id_user" id="id_user" value="{{$user->id}}">
                      <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <input type="file" name="zip" id="zipFileChat" accept="application/x-zip-compressed" class="visibility-hidden">

                      <div class="w-100 mr-2 position-relative chat-detail-input-wrap">
                        <div>
                        <span class="triggerEmoji" data-toggle="dropdown">
                          <i class="bi-emoji-smile"></i>
                        </span>

                        <div class="dropdown-menu dropdown-menu-right dropdown-emoji custom-scrollbar" aria-labelledby="dropdownMenuButton">
                          @include('includes.emojis')
                        </div>
                      </div>
                        <textarea class="form-control textareaAutoSize emojiArea" data-post-length="{{$settings->update_length}}" rows="1" placeholder="{{__('general.write_something')}}" id="messageChat" name="message"></textarea>
                      </div>

                      <div class="form-group display-none mt-2" id="priceChat">
                        <div class="input-group mb-2">
                        <div class="input-group-prepend">
                          <span class="input-group-text">{{$settings->currency_symbol}}</span>
                        </div>
                            <input class="form-control isNumber" autocomplete="off" name="price" placeholder="{{__('general.price')}}" type="text">
                        </div>
                      </div><!-- End form-group -->

                      <div class="w-100 mb-2">
                        <small id="previewImageChat"></small>
                        <a href="javascript:void(0)" id="removePhotoChat" class="text-danger p-1 small display-none btn-tooltip" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
                      </div>

                      <div class="w-100 mb-2">
                        <small id="previewEpubChat"></small>
                        <a href="javascript:void(0)" id="removeEpubChat" class="text-danger p-1 small display-none btn-tooltip-form" data-toggle="tooltip" data-placement="top" title="{{__('general.delete')}}"><i class="fa fa-times-circle"></i></a>
                      </div>

                      <input type="file" name="media[]" id="fileChat" accept="image/*,video/mp4,video/x-m4v,video/quicktime,audio/mp3" multiple class="visibility-hidden filepond input-fileuploader">

                      <div class="message-composer-toolbar justify-content-between mt-3 align-items-center">
                        <div class="chat-detail-action-icons">

                            <button type="button" class="btnChatMultipleUpload btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_media')}} ({{ $settings->disable_audio ? __('general.photo_video') : __('general.media_type_upload') }})">
                              <i class="feather icon-image align-middle f-size-25"></i>
                            </button>

                            @if ($settings->allow_zip_files)
                            <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_file_zip')}}" onclick="$('#zipFileChat').trigger('click')">
                              <i class="bi bi-file-earmark-zip align-middle f-size-25"></i>
                            </button>
                          @endif

                          @if (auth()->user()->verified_id == 'yes' && $settings->allow_epub_files)
                          <input type="file" name="epub" id="ePubFileChat" accept="application/epub+zip" class="visibility-hidden">

                          <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.upload_epub_file')}}" onclick="$('#ePubFileChat').trigger('click')">
                            <i class="bi-book f-size-25 align-middle"></i>
                          </button>
                        @endif

                        @if (auth()->user()->verified_id == 'yes' && $settings->allow_vault)
                          <button type="button" class="btn btn-upload btn-tooltip btnShowVault e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.add_media_from_vault')}}">
                            <i class="feather icon-archive align-middle f-size-25"></i>
                          </button>
                        @endif

                          @if (auth()->user()->verified_id == 'yes' && auth()->user()->free_subscription == 'yes' && $settings->ppv_only_free_accounts || !$settings->ppv_only_free_accounts && auth()->user()->verified_id == 'yes')
                          <button type="button" id="setPriceChat" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="tooltip" data-placement="top" title="{{__('general.set_price_for_msg')}}">
                            <i class="bi bi-tag align-middle" style="font-size: 27px;"></i>
                          </button>
                        @endif

                        @if ($user->verified_id == 'yes' && $settings->disable_tips == 'off')
                          <button type="button" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill" data-toggle="modal" title="{{__('general.tip')}}" data-target="#tipForm" data-cover="{{Helper::getFile(config('path.cover').$user->cover)}}" data-avatar="{{Helper::getFile(config('path.avatar').$user->avatar)}}" data-name="{{$user->hide_name == 'yes' ? $user->username : $user->name}}" data-userid="{{$user->id}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
                              <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                              <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path fill-rule="evenodd" d="M8 13.5a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                            </svg>
                          </button>
                        @endif

                        @if ($user->verified_id == 'yes' && $settings->gifts)
                        <button type="button" data-toggle="modal" title="{{__('general.gifts')}}" data-target="#giftsForm" class="btn btn-upload btn-tooltip e-none align-bottom @if (auth()->user()->dark_mode == 'off') text-primary @else text-white @endif rounded-pill">
                          <i class="bi-gift f-size-25 align-middle"></i>
                        </button>
                        @endif
                        </div>

                  <div class="d-inline-block message-send-wrap rounded-pill mt-1 position-relative">
                    <div class="btn-blocked display-none"></div>
                    <button type="submit" id="buttonReplyMsgChat" disabled data-send="{{ __('auth.send') }}" data-wait="{{ __('general.send_wait') }}" class="btn btn-sm btn-primary rounded-pill float-right e-none">
                      <i class="far fa-paper-plane"></i>
                    </button>
                    </div>

                  </div><!-- media -->
                </form>
              @else
                <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
                  <i class="fa fa-info-circle mr-2"></i>
                  @php
                    $nameUser = $user->hide_name == 'yes' ? $user->username : $user->first_name;
                  @endphp
                {!! __('general.show_form_msg_error_subscription_', ['user' => '<a href="'.url('profile',$user->username).'" class="link-border text-white">'.$nameUser.'</a>']) !!}
              </div>
                @endif

              </div><!-- card footer -->

              @else

              <div class="card-footer bg-white position-relative">
                <div class="alert alert-primary m-0 alert-dismissible fade show" role="alert">
                  <i class="fa fa-info-circle mr-2"></i>
                  {{ __('general.chat_unavailable') }}
                </div>
              </div>
            @endif

            </div><!-- card -->
          </div><!-- end col-md-8 -->
        <div class="col-sm-3 col-md-6 col-lg-3 wrapper-msg-inbox" id="messagesContainer">
                  @include('includes.sidebar-messages-inbox')
                </div>
          </div><!-- end row -->
        </div><!-- end container -->
</section>
@include('includes.modal-new-message')

  @if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
    @include('includes.modal-live-private-request')
  @endif

  @if ($settings->video_call_status)
    @include('includes.modal-video-call')
  @endif

  @if ($settings->audio_call_status)
    @include('includes.modal-audio-call')
  @endif

@endsection

@section('javascript')
<script src="{{ asset('js/messages.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/fileuploader/fileuploader-msg.js') }}?v={{$settings->version}}"></script>
<script src="{{ asset('js/paginator-messages.js') }}"></script>

@if ($user->verified_id == 'yes' 
            && $settings->live_streaming_private == 'on' 
            && $user->allow_live_streaming_private == 'on' 
            && !auth()->user()->isRestricted($user->id)
            )
<script src="{{ asset('js/live-private-request.js') }}"></script>
@endif

@endsection
