@extends('layouts.app')

@section('title')
    Promo Codes -
@endsection

@section('css')
<style type="text/css">
    [data-bs-theme="dark"] .light_mode_form {
        background-color: #111 !important;
    }

    select.promo-code-select {
        width: 100%;
        max-width: 100%;
    }

    .promo-code-datetime-wrap {
        position: relative;
    }

    .promo-code-datetime-icon {
        position: absolute;
        top: 50%;
        right: 0.95rem;
        transform: translateY(-50%);
        color: #cfcfcf;
        pointer-events: none;
        line-height: 1;
    }

    input.promo-code-datetime {
        color-scheme: dark;
        padding-right: 2.75rem;
    }

    input.promo-code-datetime::-webkit-calendar-picker-indicator {
        opacity: 0;
        background: transparent;
        border-radius: 0;
        cursor: pointer;
        margin: 0;
        padding: 0;
        position: absolute;
        right: 0.75rem;
        width: 1.25rem;
        height: 1.25rem;
    }

    input.promo-code-datetime::-webkit-datetime-edit,
    input.promo-code-datetime::-webkit-datetime-edit-fields-wrapper,
    input.promo-code-datetime::-webkit-datetime-edit-text,
    input.promo-code-datetime::-webkit-datetime-edit-month-field,
    input.promo-code-datetime::-webkit-datetime-edit-day-field,
    input.promo-code-datetime::-webkit-datetime-edit-year-field,
    input.promo-code-datetime::-webkit-datetime-edit-hour-field,
    input.promo-code-datetime::-webkit-datetime-edit-minute-field,
    input.promo-code-datetime::-webkit-datetime-edit-ampm-field {
        color: inherit;
        background: transparent;
    }

    @media (max-width: 576px) {
        select.promo-code-select {
            font-size: 16px;
        }
    }

    .promo-history-panel {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .promo-history-panel summary {
        cursor: pointer;
        font-weight: 600;
        outline: none;
    }

    .promo-history-table th,
    .promo-history-table td {
        white-space: nowrap;
        vertical-align: middle;
    }
</style>
@endsection

@section('content')
<section class="section section-sm">
    @include('includes.header-mobile')
    <div class="container-fluid pt-lg-5 pt-2 px-lg-5">
        <div class="row">
            @include('includes.cards-settings')

            <div class="col-md-12 col-lg-9 mb-5 mb-lg-0">
                <div class="row mb-sm">
                    <div class="col-lg-8 py-5">
                        <h2 class="mb-0 font-montserrat font_weight_700 fs-24 pb-3"><i class="bi bi-ticket-perforated mr-2"></i>Promo Codes</h2>
                        <p class="lead mt-0 fs-14 font_weight_400 theme-subtitle">Create, manage, and review promo code performance for your subscription plans.</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="far fa-times-circle mr-2"></i> Please review the promo code form and try again.
                    </div>
                @endif

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Create Promo Code</h5>
                        <form method="POST" action="{{ url('settings/promo-codes') }}">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Code</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" maxlength="100" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Discount Type</label>
                                    <select name="discount_type" class="form-control custom-select light_mode_form promo-code-select" required>
                                        <option value="fixed" @if (old('discount_type') === 'fixed') selected @endif>Fixed</option>
                                        <option value="percentage" @if (old('discount_type') === 'percentage') selected @endif>Percentage</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Discount Value</label>
                                    <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>Expiry Date & Time</label>
                                    <div class="promo-code-datetime-wrap">
                                        <input type="datetime-local" name="expires_at" class="form-control light_mode_form promo-code-datetime" value="{{ old('expires_at') }}">
                                        <span class="promo-code-datetime-icon"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Total Usage Limit</label>
                                    <input type="number" min="1" name="usage_limit_total" class="form-control" value="{{ old('usage_limit_total') }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Per User Limit</label>
                                    <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Status</label>
                                    <select name="is_active" class="form-control custom-select light_mode_form promo-code-select">
                                        <option value="yes" @if (old('is_active', 'yes') === 'yes') selected @endif>Active</option>
                                        <option value="no" @if (old('is_active') === 'no') selected @endif>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-1 btn-success" type="submit">Create Promo Code</button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Your Promo Codes</h5>

                        @if ($codes->count())
                            @foreach ($codes as $code)
                                @php($codeStats = $stats->get($code->id))
                                @php($codeUsages = $recentUsages->get($code->id, collect()))
                                @php($codeHistories = $recentHistories->get($code->id, collect()))
                                <div class="border rounded p-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                        <div>
                                            <h5 class="mb-1">{{ $code->code }}</h5>
                                            <small class="text-muted">
                                                Discount:
                                                @if ($code->discount_type === 'percentage')
                                                    {{ rtrim(rtrim(number_format($code->discount_value, 2), '0'), '.') }}% off
                                                @else
                                                    {{ Helper::amountFormatDecimal($code->discount_value) }} off
                                                @endif
                                            </small>
                                        </div>
                                        <div>
                                            @if ($code->expires_at && $code->expires_at->isPast())
                                                <span class="badge badge-warning">Expired</span>
                                            @elseif ($code->is_active === 'yes')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Disabled</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-2 col-6"><strong>Uses:</strong> {{ (int) ($codeStats->usage_count ?? 0) }}</div>
                                        <div class="col-md-2 col-6"><strong>Charged Revenue:</strong> {{ Helper::amountFormatDecimal($codeStats->revenue_generated ?? 0) }}</div>
                                        <div class="col-md-3 col-6"><strong>Total Discount:</strong> {{ Helper::amountFormatDecimal($codeStats->total_discount_amount ?? 0) }}</div>
                                        <div class="col-md-2 col-6"><strong>Subscribers:</strong> {{ (int) ($codeStats->subscriber_count ?? 0) }}</div>
                                        <div class="col-md-3 col-12"><strong>Creator Earnings Impact:</strong> {{ Helper::amountFormatDecimal($codeStats->creator_earnings_impact ?? 0) }}</div>
                                    </div>

                                    <form method="POST" action="{{ url('settings/promo-codes/' . $code->id) }}">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-3">
                                                <label>Code</label>
                                                <input type="text" name="code" class="form-control" value="{{ old('code', $code->code) }}" @if (! is_null($code->first_used_at)) readonly @endif>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Type</label>
                                                <select name="discount_type" class="form-control custom-select light_mode_form promo-code-select" @if (! is_null($code->first_used_at)) disabled @endif>
                                                    <option value="fixed" @if ($code->discount_type === 'fixed') selected @endif>Fixed</option>
                                                    <option value="percentage" @if ($code->discount_type === 'percentage') selected @endif>Percentage</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Value</label>
                                                <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $code->discount_value) }}" @if (! is_null($code->first_used_at)) readonly @endif>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Expires At</label>
                                                <div class="promo-code-datetime-wrap">
                                                    <input type="datetime-local" name="expires_at" class="form-control light_mode_form promo-code-datetime" value="{{ old('expires_at', optional($code->expires_at)->format('Y-m-d\TH:i')) }}">
                                                    <span class="promo-code-datetime-icon"><i class="bi bi-calendar3"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label>Total Usage Limit</label>
                                                <input type="number" min="1" name="usage_limit_total" class="form-control" value="{{ old('usage_limit_total', $code->usage_limit_total) }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label>Per User Limit</label>
                                                <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $code->usage_limit_per_user) }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <label>Status</label>
                                                <select name="is_active" class="form-control custom-select light_mode_form promo-code-select">
                                                    <option value="yes" @if ($code->is_active === 'yes') selected @endif>Active</option>
                                                    <option value="no" @if ($code->is_active === 'no') selected @endif>Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            <button class="btn btn-1 btn-success mr-2 mb-2" type="submit">Save</button>
                                            @if ($code->is_active === 'yes')
                                                <button class="btn btn-outline-danger mb-2" type="submit" formaction="{{ url('settings/promo-codes/' . $code->id . '/disable') }}">Disable</button>
                                            @endif
                                        </div>
                                        @if (! is_null($code->first_used_at))
                                            <small class="text-muted d-block mt-2">Discount type and value are locked after first use.</small>
                                        @endif
                                    </form>

                                    <details class="promo-history-panel">
                                        <summary>Recent Usage History</summary>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm promo-history-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Subscriber</th>
                                                        <th>Status</th>
                                                        <th>Plan</th>
                                                        <th>Gateway</th>
                                                        <th>Original</th>
                                                        <th>Discount</th>
                                                        <th>Charged</th>
                                                        <th>Tax</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($codeUsages as $usage)
                                                        <tr>
                                                            <td>{{ optional($usage->used_at ?: $usage->created_at)->format('Y-m-d H:i') ?: 'N/A' }}</td>
                                                            <td>
                                                                @if ($usage->user)
                                                                    <a href="{{ url($usage->user->username) }}" target="_blank">
                                                                        {{ $usage->user->hide_name === 'yes' ? $usage->user->username : $usage->user->name }}
                                                                    </a>
                                                                @else
                                                                    {{ __('general.no_available') }}
                                                                @endif
                                                            </td>
                                                            <td><span class="badge badge-secondary">{{ ucfirst($usage->status) }}</span></td>
                                                            <td>{{ $usage->plan->name ?? ucfirst($usage->plan_interval ?? 'N/A') }}</td>
                                                            <td>{{ $usage->gateway_name ?? 'N/A' }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->original_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->discount_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->charged_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->tax_amount) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted">No promo code usage has been recorded yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </details>

                                    <details class="promo-history-panel">
                                        <summary>Recent Audit History</summary>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm promo-history-table">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Actor</th>
                                                        <th>Role</th>
                                                        <th>Event</th>
                                                        <th>Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($codeHistories as $history)
                                                        <tr>
                                                            <td>{{ optional($history->created_at)->format('Y-m-d H:i') ?: 'N/A' }}</td>
                                                            <td>
                                                                @if ($history->actor)
                                                                    <a href="{{ url($history->actor->username) }}" target="_blank">
                                                                        {{ $history->actor->hide_name === 'yes' ? $history->actor->username : $history->actor->name }}
                                                                    </a>
                                                                @else
                                                                    System
                                                                @endif
                                                            </td>
                                                            <td>{{ ucfirst($history->actor_role) }}</td>
                                                            <td>{{ ucwords(str_replace('_', ' ', $history->event_type)) }}</td>
                                                            <td>{{ $history->notes ?: '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">No promo code activity has been recorded yet.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </details>
                                </div>
                            @endforeach

                            {{ $codes->onEachSide(0)->links() }}
                        @else
                            <p class="text-muted mb-0">No promo codes created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
