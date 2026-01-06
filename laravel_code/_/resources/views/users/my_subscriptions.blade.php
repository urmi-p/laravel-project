@extends('layouts.app')

@section('title')
    {{ __('users.my_subscriptions') }} -
@endsection

@section('content')
    <section class="section section-sm">
        @include('includes.header-mobile')
        <div class="container-fluid pt-lg-5 pt-2">

            <div class="row">
                <div class="col-lg-3 col-md-2" style="box-shadow: 0px 4px 25px 0px #2A864214;">
                    @include('includes.menu-sidebar-home')
                </div>
                {{-- @include('includes.cards-settings') --}}

                <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">
                    <div class="row mb-sm">
                        <div class="col-lg-8 py-5">
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24">{{ __('users.my_subscriptions') }}</h2>
                            <p class="lead mt-0 font_weight_400 fs-14">{{ __('users.my_subscriptions_subtitle') }}</p>
                        </div>
                    </div>

                    <!-- dummy sub -->
                    <!-- <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-striped m-0">
                  <thead>
                    <tr>
                      <th scope="col">Subscribed</th>
                      <th scope="col">Date</th>
                      <th scope="col">Interval</th>
                      <th scope="col">Ends At</th>
                      <th scope="col">Status</th>
                    </tr>
                  </thead>

                  <tbody>
                    
                    <tr>
                      <td>
                        <a href="#">
                          <img src="https://via.placeholder.com/40" width="40" height="40" class="rounded-circle mr-2">
                          John Doe
                        </a>
                      </td>
                      <td>2026-01-03</td>
                      <td>Monthly</td>
                      <td>2026-02-03</td>
                      <td>
                        <span class="badge badge-pill badge-success text-uppercase">Active</span>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>
                        <a href="#">
                          <img src="https://via.placeholder.com/40" width="40" height="40" class="rounded-circle mr-2">
                          Jane Smith
                        </a>
                      </td>
                      <td>2026-01-01</td>
                      <td>Yearly</td>
                      <td>2027-01-01</td>
                      <td>
                        <span class="badge badge-pill badge-success text-uppercase">Active</span>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>No Available</td>
                      <td>2026-01-02</td>
                      <td>Not Applicable</td>
                      <td>Free Subscription</td>
                      <td>
                        <span class="badge badge-pill badge-success text-uppercase">Active</span>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>
                        <a href="#">
                          <img src="https://via.placeholder.com/40" width="40" height="40" class="rounded-circle mr-2">
                          Alex Johnson
                        </a>
                      </td>
                      <td>2025-12-28</td>
                      <td>Monthly</td>
                      <td>2025-12-30</td>
                      <td>
                        <span class="badge badge-pill badge-danger text-uppercase">Cancelled</span>
                      </td>
                    </tr>

                    
                    <tr>
                      <td>
                        <a href="#">
                          <img src="https://via.placeholder.com/40" width="40" height="40" class="rounded-circle mr-2">
                          Emily Brown
                        </a>
                      </td>
                      <td>2026-01-03</td>
                      <td>Monthly</td>
                      <td>—</td>
                      <td>
                        <span class="badge badge-pill badge-warning text-uppercase">Incomplete</span>
                        <br>
                        <a class="badge badge-pill badge-success text-uppercase" href="#">Confirm Payment</a>
                      </td>
                    </tr>

                  </tbody>
                </table>
              </div>
            </div> -->
                    <div style="max-width: 600px; margin: 20px auto; font-family: sans-serif;">

                        <!-- Dummy Card 1 -->
                        <div
                            style="background-color: #1e1e1e; color: #fff; border: 1px solid #333; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <strong>Subscriptions Detail</strong>
                                <span style="color: #aaa; cursor: pointer;">Close Only</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; row-gap: 10px;">
                                <span>Subscribed</span>
                                <span>Close Only</span>

                                <span>Date</span>
                                <span>13 Nov, 2025</span>

                                <span>Interval</span>
                                <span>Not applicable</span>

                                <span>Ends at</span>
                                <span>Free Subscription</span>

                                <span>Status:</span>
                                <span style="display: flex; align-items: center; gap: 5px; color: #2ecc71;">
                                    <span
                                        style="width: 10px; height: 10px; border-radius: 50%; background-color: #2ecc71; display: inline-block;"></span>
                                    Active
                                </span>
                            </div>
                        </div>

                        <!-- Dummy Card 2 -->
                        <div
                            style="background-color: #1e1e1e; color: #fff; border: 1px solid #333; border-radius: 8px; padding: 20px; margin-bottom: 20px;">
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                <strong>Subscriptions Detail</strong>
                                <span style="color: #aaa; cursor: pointer;">Close Only</span>
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; row-gap: 10px;">
                                <span>Subscribed</span>
                                <span>Close Only</span>

                                <span>Date</span>
                                <span>13 Nov, 2025</span>

                                <span>Interval</span>
                                <span>Not applicable</span>

                                <span>Ends at</span>
                                <span>Free Subscription</span>

                                <span>Status:</span>
                                <span style="display: flex; align-items: center; gap: 5px; color: #f1c40f;">
                                    <span
                                        style="width: 10px; height: 10px; border-radius: 50%; background-color: #f1c40f; display: inline-block;"></span>
                                    Under Review
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- dummy sub end -->
                    @if ($subscriptions->count() != 0)
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

                        <!-- <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-striped m-0">
                  <thead>
                    <tr>
                      <th scope="col">{{ __('users.subscribed') }}</th>
                      <th scope="col">{{ __('admin.date') }}</th>
                      <th scope="col">{{ __('general.interval') }}</th>
                      <th scope="col">{{ __('admin.ends_at') }}</th>
                      <th scope="col">{{ __('admin.status') }}</th>
                    </tr>
                  </thead>

                  <tbody>
                    @foreach ($subscriptions as $subscription)
    <tr>
                      <td>
                        @if (!isset($subscription->creator->username))
    {{ __('general.no_available') }}
@else
    <a href="{{ url($subscription->creator->username) }}">
                          <img src="{{ Helper::getFile(config('path.avatar') . $subscription->creator->avatar) }}" width="40" height="40" class="rounded-circle mr-2">
                          {{ $subscription->creator->hide_name == 'yes' ? $subscription->creator->username : $subscription->creator->name }}
                        </a>
    @endif
                      </td>
                      <td>{{ Helper::formatDate($subscription->created_at) }}</td>
                      <td>{{ $subscription->free == 'yes' ? __('general.not_applicable') : __('general.' . $subscription->interval) }}</td>
                      <td>
                        @if ($subscription->ends_at)
    {{ Helper::formatDate($subscription->ends_at) }}
@elseif ($subscription->free == 'yes')
    {{ __('general.free_subscription') }}
@elseif ($subscription->stripe_id != '' && !$subscription->ends_at && $subscription->stripe_status != 'incomplete')
    {{ Helper::formatDate(auth()->user()->subscription('main', $subscription->stripe_price)->asStripeSubscription()->current_period_end, true) }}
@else
    {{ __('general.no_available') }}
    @endif
                      </td>
                      <td>
                        @if (
                            ($subscription->stripe_id == '' &&
                                strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s')) &&
                                $subscription->cancelled == 'no') ||
                                ($subscription->stripe_id != '' && $subscription->stripe_status == 'active') ||
                                ($subscription->stripe_id == '' && $subscription->free == 'yes'))
    <span class="badge badge-pill badge-success text-uppercase">{{ __('general.active') }}</span> <br>
@elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
    <span class="badge badge-pill badge-warning text-uppercase">{{ __('general.incomplete') }}</span> <br>

                        <a class="badge badge-pill badge-success text-uppercase" href="{{ route('cashier.payment', $subscription->last_payment) }}">
                          {{ __('general.confirm_payment') }}
                        </a>
@else
    <span class="badge badge-pill badge-danger text-uppercase">{{ __('general.cancelled') }}</span>
    @endif
                      </td>
                    </tr>
    @endforeach

                  </tbody>
                </table>
              </div>
            </div> -->
                        {{-- Subscriptions Cards --}}
                        <div class="subscriptions-cards">
                            @foreach ($subscriptions as $subscription)
                                <div class="card mb-3 bg-dark text-white shadow-sm"
                                    style="border-radius: 8px; border: 1px solid #333;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <strong>Subscriptions Detail</strong>
                                        <span class="text-muted" style="cursor: pointer;">Close Only</span>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">Subscribed</div>
                                        <div class="col-6">
                                            @if (!isset($subscription->creator->username))
                                                {{ __('general.no_available') }}
                                            @else
                                                <a href="{{ url($subscription->creator->username) }}" class="text-white">
                                                    <img src="{{ Helper::getFile(config('path.avatar') . $subscription->creator->avatar) }}"
                                                        width="40" height="40" class="rounded-circle mr-2">
                                                    {{ $subscription->creator->hide_name == 'yes' ? $subscription->creator->username : $subscription->creator->name }}
                                                </a>
                                            @endif
                                        </div>

                                        <div class="col-6">Date</div>
                                        <div class="col-6">{{ Helper::formatDate($subscription->created_at) }}</div>

                                        <div class="col-6">Interval</div>
                                        <div class="col-6">
                                            {{ $subscription->free == 'yes' ? __('general.not_applicable') : __('general.' . $subscription->interval) }}
                                        </div>

                                        <div class="col-6">Ends at</div>
                                        <div class="col-6">
                                            @if ($subscription->ends_at)
                                                {{ Helper::formatDate($subscription->ends_at) }}
                                            @elseif ($subscription->free == 'yes')
                                                {{ __('general.free_subscription') }}
                                            @elseif ($subscription->stripe_id != '' && !$subscription->ends_at && $subscription->stripe_status != 'incomplete')
                                                {{ Helper::formatDate(auth()->user()->subscription('main', $subscription->stripe_price)->asStripeSubscription()->current_period_end, true) }}
                                            @else
                                                {{ __('general.no_available') }}
                                            @endif
                                        </div>

                                        <div class="col-6">Status:</div>
                                        <div class="col-6 d-flex align-items-center">
                                            @if (
                                                ($subscription->stripe_id == '' &&
                                                    strtotime($subscription->ends_at) > strtotime(now()->format('Y-m-d H:i:s')) &&
                                                    $subscription->cancelled == 'no') ||
                                                    ($subscription->stripe_id != '' && $subscription->stripe_status == 'active') ||
                                                    ($subscription->stripe_id == '' && $subscription->free == 'yes'))
                                                <span class="status-dot bg-success"></span>
                                                <span class="ml-2 text-success">{{ __('general.active') }}</span>
                                            @elseif ($subscription->stripe_id != '' && $subscription->stripe_status == 'incomplete')
                                                <span class="status-dot bg-warning"></span>
                                                <span class="ml-2 text-warning">{{ __('general.incomplete') }}</span>
                                                <br>
                                                <a class="badge badge-pill badge-success text-uppercase mt-1"
                                                    href="{{ route('cashier.payment', $subscription->last_payment) }}">
                                                    {{ __('general.confirm_payment') }}
                                                </a>
                                            @else
                                                <span class="status-dot bg-danger"></span>
                                                <span class="ml-2 text-danger">{{ __('general.cancelled') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- card -->

                        @if ($subscriptions->hasPages())
                            {{ $subscriptions->links() }}
                        @endif
                    @else
                        <div class="my-5 text-center main-no-updates">
                            <div class="sub-no-updates">
                                <span class="btn-block mb-3">
                                    <i class="feather icon-user-check ico-no-result bg_black"></i>
                                </span>
                                <h4 class="font_weight_400 font_size_18 text_color_white">{{ __('users.not_subscribed') }} <a
                                        href="{{ url('creators') }}"
                                       class="text_color_white text_decor_underline" >{{ __('general.explore_creators') }}</a>
                                </h4>

                            </div>
                        </div>
                    @endif

                </div><!-- end col-md-6 -->

            </div>
        </div>
    </section>
@endsection
