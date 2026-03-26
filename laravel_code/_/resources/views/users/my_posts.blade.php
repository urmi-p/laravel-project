@extends('layouts.app')

@section('title') {{__('general.my_posts')}} -@endsection

@section('css')
<style>
  @media (max-width: 767.98px) {
    .my-posts-table thead {
      display: none;
    }
    .my-posts-table,
    .my-posts-table tbody,
    .my-posts-table tr,
    .my-posts-table td {
      display: block;
      width: 100%;
    }
    .my-posts-table tr {
      border-bottom: 1px solid rgba(255,255,255,0.08);
      padding: 10px 12px;
    }
    .my-posts-table td {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      padding: 6px 0;
      border: 0;
    }
    .my-posts-table td::before {
      content: attr(data-label);
      font-weight: 600;
      opacity: 0.8;
      flex: 0 0 auto;
      margin-right: 8px;
    }
    .my-posts-table td > * {
      text-align: right;
    }
  }
</style>
@endsection

@section('content')
<section class="section section-sm">
    @include('includes.header-mobile')
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
      <div class="row mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-2"><i class="feather icon-feather mr-2"></i> {{__('general.my_posts')}}</h2>
          <p class="lead mt-0 font_weight_400 fs-14">{{__('general.all_post_created')}}</p>
        </div>
      </div>
      <div class="row">

        <div class="col-md-12 mb-5">

          @if (session('notify'))
          <div class="alert alert-primary">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>

            <i class="bi-info-circle mr-1"></i> {{ session('notify') }}
          </div>
          @endif

          <div class="d-lg-flex d-block justify-content-between align-items-center mb-3 text-word-break">
            <form class="position-relative mr-3 w-100 mb-lg-0 mb-2" role="search" autocomplete="off" action="{{ url('my/posts') }}" method="get">
              <i class="bi bi-search btn-search bar-search"></i>
             <input type="text" minlength="3" required="" name="q" class="form-control pl-5" value="{{ request('q') }}" placeholder="{{ __('general.search') }}" aria-label="Search">
          </form>

            <div class="w-lg-100 d-flex align-items-center gap-2">
              <select class="form-control custom-select w-100 pr-4 filter dark_all_dropdown">
                <option @selected(!request('sort')) value="{{ url('my/posts') }}">{{ __('general.all') }}</option>

                @if ($settings->allow_scheduled_posts)
                <option @selected(request('sort') == 'scheduled') value="{{ url('my/posts?sort=scheduled') }}">
                  {{ __('general.scheduled') }}
                </option> 
                @endif
                
                <option @selected(request('sort') == 'pending') value="{{ url('my/posts?sort=pending') }}">
                  {{ __('admin.pending') }}
                </option> 


                <option @selected(request('sort') == 'ppv') value="{{ url('my/posts?sort=ppv') }}">
                  {{ __('general.ppv') }}
                </option>
              </select>
            </div>
          </div>

          @if ($posts->isNotEmpty())
          <div class="card shadow-sm mb-2">
          <div class="table-responsive">
            <table class="table table-striped m-0 my-posts-table">
              <thead>
                <tr>
                  <th scope="col">{{ __('general.id') }}</th>
                  <th scope="col">{{__('admin.content')}}</th>
                  <th scope="col">{{__('admin.description')}}</th>
                  <th scope="col">{{__('admin.type')}}</th>
                  <th scope="col">{{__('general.price')}}</th>
                  <th scope="col">{{__('general.interactions')}}</th>
                  <th scope="col">{{__('admin.date')}}</th>
                  <th scope="col">{{__('admin.status')}}</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($posts as $post)
                  <tr>
                    <td data-label="{{ __('general.id') }}">{{ $post->id }}</td>
                    <td data-label="{{ __('admin.content') }}">
                      @if ($post->media_count)
                      {{ $post->media_count }} {{trans_choice('general.files', $post->media_count )}}
                      @else
                      {{ __('general.text') }}
                      @endif
                    </td>

                    <td data-label="{{ __('admin.description') }}">
                    <a href="{{ url($post->creator->username, 'post').'/'.$post->id }}" target="_blank">
                      {{ str_limit($post->description, 20, '...') }} <i class="bi bi-box-arrow-up-right ml-1"></i>
                    </a>
                    </td>
                    <td data-label="{{ __('admin.type') }}">
                      @if ($post->locked == 'yes')
                        <i class="feather icon-lock mr-1" title="{{__('users.content_locked')}}"></i>
                      @else
                        <i class="iconmoon icon-WorldWide mr-1" title="{{__('general.public')}}"></i>
                      @endif
                    </td>
                    <td data-label="{{ __('general.price') }}">{{ Helper::amountFormatDecimal($post->price) }}</td>
                    <td data-label="{{ __('general.interactions') }}">
                      @if (!$settings->hide_total_likes)
                      <i class="far fa-heart"></i> {{ $post->likes_count }} 
                      @endif
                      <i class="far fa-comment ml-1"></i> {{ ($post->comments_count + $post->replies_count) }}
                      <i class="feather icon-bookmark ml-1"></i> {{ $post->bookmarks_count }}
                    </td>
                    <td data-label="{{ __('admin.date') }}">{{Helper::formatDate($post->date)}}</td>
                    <td data-label="{{ __('admin.status') }}">
                      @if ($post->status == 'active')
                        <span class="badge badge-pill badge-success text-uppercase">{{__('general.active')}}</span>
                      @elseif($post->status == 'schedule')
                      <span class="badge badge-pill badge-info text-uppercase">{{__('general.scheduled')}}</span>
                        <a tabindex="0" role="button" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="top" data-content="{{ __('general.date_schedule') }} {{ Helper::formatDateSchedule($post->scheduled_date) }}">
                          <i class="far fa-question-circle"></i>
                        </a>
                        @else
                        <span class="badge badge-pill badge-warning text-uppercase">{{__('general.pending')}}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          </div><!-- card -->

          @if ($posts->hasPages())
  			    	{{ $posts->onEachSide(0)->links() }}
  			    	@endif

        @else
          <div class="text-center no-updates main-no-updates">
            <div class="sub-no-updates">
              <span class="btn-block mb-3">
                <i class="feather icon-feather ico-no-result bg_black"></i>
              </span>

              @if (request('q'))
                <h4 class="font_weight_400 font_size_18">{{__('general.no_results_found')}}</h4>
                
                <div class="no-updates-div">
                  <a href="{{ url('my/posts') }}" class="btn btn-primary btn-sm mt-3">
                    <i class="bi-arrow-left mr-1"></i> {{ __('general.go_back') }}
                  </a>
                </div>
              @else
                <h4 class="font_weight_400 font_size_18">{{__('general.not_post_created')}}</h4>
              @endif
            </div>
          </div>
        @endif
        </div><!-- end col-md-6 -->

      </div>
    </div>
  </section>
@endsection
