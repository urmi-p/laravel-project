@extends('layouts.app')

@section('title')
    {{ __('general.promo_codes') }} -
@endsection

@section('css')
<style type="text/css">
    .promo-page-header {
        margin-bottom: 1.5rem;
    }

    .promo-page-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.65rem;
    }

    .promo-page-title i {
        color: #ff4d6d;
    }

    .promo-page-subtitle {
        max-width: 680px;
        margin-bottom: 0;
    }

    .promo-section-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1.1rem;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.025), rgba(255, 255, 255, 0.01));
        overflow: hidden;
    }

    .promo-section-card .card-body {
        padding: 1.35rem;
    }

    .promo-section-title {
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    [data-bs-theme="dark"] .light_mode_form {
        background-color: #111 !important;
        border-color: rgba(255, 255, 255, 0.08);
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

    .promo-history-panel {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin-top: 1rem;
        padding-top: 1rem;
    }

    .promo-code-card {
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 1rem;
        padding: 1.25rem;
    }

    .promo-code-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .promo-code-status {
        flex-shrink: 0;
    }

    .promo-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1rem;
    }

    .promo-form-grid .form-group {
        margin-bottom: 0;
    }

    .promo-form-grid label {
        display: block;
        margin-bottom: 0.45rem;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .promo-form-grid .form-control,
    .promo-form-grid .custom-select {
        min-height: 3rem;
        border-radius: 0.8rem;
    }

    .promo-form-grid input[readonly],
    .promo-form-grid select[disabled] {
        opacity: 0.78;
    }

    .promo-code-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .promo-code-stat {
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 0.85rem;
        padding: 0.85rem 0.95rem;
        min-height: 100%;
        background: rgba(255, 255, 255, 0.025);
    }

    .promo-code-stat-label {
        display: block;
        margin-bottom: 0.35rem;
        font-size: 0.8rem;
        line-height: 1.25;
        color: #9ca3af;
    }

    .promo-code-stat-value {
        display: block;
        font-weight: 700;
        line-height: 1.3;
        word-break: break-word;
    }

    .promo-code-stat-wide {
        grid-column: span 2;
    }

    .promo-code-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        align-items: center;
        margin-top: 1rem;
    }

    .promo-code-actions .btn {
        margin: 0;
        min-width: 120px;
    }

    .promo-create-actions {
        display: flex;
        justify-content: flex-start;
        margin-top: 1rem;
    }

    .promo-create-actions .btn {
        min-width: 220px;
    }

    .promo-history-panel summary {
        cursor: pointer;
        font-weight: 600;
        outline: none;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .promo-history-panel summary::-webkit-details-marker {
        display: none;
    }

    .promo-history-panel summary::before {
        content: '+';
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        color: #cfcfcf;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .promo-history-panel[open] summary::before {
        content: '-';
    }

    .promo-history-table {
        min-width: 760px;
    }

    .promo-history-table th,
    .promo-history-table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    @media (max-width: 576px) {
        .promo-page-header {
            margin-bottom: 1.2rem;
        }

        .promo-page-title {
            gap: 0.55rem;
            margin-bottom: 0.5rem;
        }

        .promo-section-card .card-body {
            padding: 1rem;
        }

        select.promo-code-select {
            font-size: 16px;
        }

        .promo-code-card {
            padding: 1rem;
        }

        .promo-code-stats {
            grid-template-columns: 1fr;
        }

        .promo-code-stat-wide {
            grid-column: span 1;
        }

        .promo-create-actions .btn {
            width: 100%;
            min-width: 0;
        }

        .promo-code-actions .btn {
            flex: 1 1 calc(50% - 0.375rem);
            min-width: 0;
        }
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
                <div class="row mb-sm promo-page-header">
                    <div class="col-lg-8 py-5">
                        <div class="promo-page-title">
                            <i class="bi bi-ticket-perforated"></i>
                            <h2 class="mb-0 font-montserrat font_weight_700 fs-24">{{ __('general.promo_codes') }}</h2>
                        </div>
                        <p class="lead fs-14 font_weight_400 theme-subtitle promo-page-subtitle">{{ __('general.promo_codes_subtitle') }}</p>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="far fa-times-circle mr-2"></i> {{ __('general.promo_codes_form_error') }}
                    </div>
                @endif

                <div class="card shadow-sm mb-4 promo-section-card">
                    <div class="card-body">
                        <h5 class="promo-section-title">{{ __('general.create_promo_code') }}</h5>
                        <form method="POST" action="{{ url('settings/promo-codes') }}">
                            @csrf
                            <div class="promo-form-grid">
                                <div class="form-group">
                                    <label>{{ __('general.code') }}</label>
                                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" maxlength="100" required>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.promo_discount_type') }}</label>
                                    <select name="discount_type" class="form-control custom-select light_mode_form promo-code-select" required>
                                        <option value="fixed" @if (old('discount_type') === 'fixed') selected @endif>{{ __('general.promo_discount_type_fixed') }}</option>
                                        <option value="percentage" @if (old('discount_type') === 'percentage') selected @endif>{{ __('general.percentage') }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.promo_discount_value') }}</label>
                                    <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.promo_expiry_date_time') }}</label>
                                    <div class="promo-code-datetime-wrap">
                                        <input type="datetime-local" name="expires_at" class="form-control light_mode_form promo-code-datetime" value="{{ old('expires_at') }}">
                                        <span class="promo-code-datetime-icon"><i class="bi bi-calendar3"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.promo_total_usage_limit') }}</label>
                                    <input type="number" min="1" name="usage_limit_total" class="form-control" value="{{ old('usage_limit_total') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.promo_per_user_limit') }}</label>
                                    <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('general.status') }}</label>
                                    <select name="is_active" class="form-control custom-select light_mode_form promo-code-select">
                                        <option value="yes" @if (old('is_active', 'yes') === 'yes') selected @endif>{{ __('general.active') }}</option>
                                        <option value="no" @if (old('is_active') === 'no') selected @endif>{{ __('general.disabled') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="promo-create-actions">
                                <button class="btn btn-1 btn-success" type="submit">{{ __('general.create_promo_code') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm promo-section-card">
                    <div class="card-body">
                        <h5 class="promo-section-title">{{ __('general.your_promo_codes') }}</h5>

                        @if ($codes->count())
                            @foreach ($codes as $code)
                                @php($codeStats = $stats->get($code->id))
                                @php($codeUsages = $recentUsages->get($code->id, collect()))
                                @php($codeHistories = $recentHistories->get($code->id, collect()))
                                <div class="promo-code-card mb-4">
                                    <div class="promo-code-header">
                                        <div>
                                            <h5 class="mb-1">{{ $code->code }}</h5>
                                            <small class="text-muted">
                                                {{ __('general.promo_discount_label') }}:
                                                @if ($code->discount_type === 'percentage')
                                                    {{ rtrim(rtrim(number_format($code->discount_value, 2), '0'), '.') }}% {{ __('general.discount') }}
                                                @else
                                                    {{ Helper::amountFormatDecimal($code->discount_value) }} {{ __('general.discount') }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="promo-code-status">
                                            @if ($code->expires_at && $code->expires_at->isPast())
                                                <span class="badge badge-warning">{{ __('general.expired') }}</span>
                                            @elseif ($code->is_active === 'yes')
                                                <span class="badge badge-success">{{ __('general.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('general.disabled') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="promo-code-stats">
                                        <div class="promo-code-stat">
                                            <span class="promo-code-stat-label">{{ __('general.promo_uses') }}</span>
                                            <span class="promo-code-stat-value">{{ (int) ($codeStats->usage_count ?? 0) }}</span>
                                        </div>
                                        <div class="promo-code-stat">
                                            <span class="promo-code-stat-label">{{ __('general.promo_charged_revenue') }}</span>
                                            <span class="promo-code-stat-value">{{ Helper::amountFormatDecimal($codeStats->revenue_generated ?? 0) }}</span>
                                        </div>
                                        <div class="promo-code-stat">
                                            <span class="promo-code-stat-label">{{ __('general.promo_total_discount') }}</span>
                                            <span class="promo-code-stat-value">{{ Helper::amountFormatDecimal($codeStats->total_discount_amount ?? 0) }}</span>
                                        </div>
                                        <div class="promo-code-stat">
                                            <span class="promo-code-stat-label">{{ __('general.promo_subscribers') }}</span>
                                            <span class="promo-code-stat-value">{{ (int) ($codeStats->subscriber_count ?? 0) }}</span>
                                        </div>
                                        <div class="promo-code-stat promo-code-stat-wide">
                                            <span class="promo-code-stat-label">{{ __('general.promo_creator_earnings_impact') }}</span>
                                            <span class="promo-code-stat-value">{{ Helper::amountFormatDecimal($codeStats->creator_earnings_impact ?? 0) }}</span>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ url('settings/promo-codes/' . $code->id) }}">
                                        @csrf
                                        <div class="promo-form-grid">
                                            <div class="form-group">
                                                <label>{{ __('general.code') }}</label>
                                                <input type="text" name="code" class="form-control" value="{{ old('code', $code->code) }}" @if (! is_null($code->first_used_at)) readonly @endif>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.type') }}</label>
                                                <select name="discount_type" class="form-control custom-select light_mode_form promo-code-select" @if (! is_null($code->first_used_at)) disabled @endif>
                                                    <option value="fixed" @if ($code->discount_type === 'fixed') selected @endif>{{ __('general.promo_discount_type_fixed') }}</option>
                                                    <option value="percentage" @if ($code->discount_type === 'percentage') selected @endif>{{ __('general.percentage') }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.value') }}</label>
                                                <input type="number" step="0.01" min="0.01" name="discount_value" class="form-control" value="{{ old('discount_value', $code->discount_value) }}" @if (! is_null($code->first_used_at)) readonly @endif>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.promo_expires_at') }}</label>
                                                <div class="promo-code-datetime-wrap">
                                                    <input type="datetime-local" name="expires_at" class="form-control light_mode_form promo-code-datetime" value="{{ old('expires_at', optional($code->expires_at)->format('Y-m-d\TH:i')) }}">
                                                    <span class="promo-code-datetime-icon"><i class="bi bi-calendar3"></i></span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.promo_total_usage_limit') }}</label>
                                                <input type="number" min="1" name="usage_limit_total" class="form-control" value="{{ old('usage_limit_total', $code->usage_limit_total) }}">
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.promo_per_user_limit') }}</label>
                                                <input type="number" min="1" name="usage_limit_per_user" class="form-control" value="{{ old('usage_limit_per_user', $code->usage_limit_per_user) }}">
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('general.status') }}</label>
                                                <select name="is_active" class="form-control custom-select light_mode_form promo-code-select">
                                                    <option value="yes" @if ($code->is_active === 'yes') selected @endif>{{ __('general.active') }}</option>
                                                    <option value="no" @if ($code->is_active === 'no') selected @endif>{{ __('general.disabled') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="promo-code-actions">
                                            <button class="btn btn-1 btn-success" type="submit">{{ __('admin.save') }}</button>
                                            @if ($code->is_active === 'yes')
                                                <button class="btn btn-outline-danger" type="submit" formaction="{{ url('settings/promo-codes/' . $code->id . '/disable') }}">{{ __('general.promo_disable_action') }}</button>
                                            @endif
                                        </div>
                                        @if (! is_null($code->first_used_at))
                                            <small class="text-muted d-block mt-2">{{ __('general.promo_discount_locked_after_first_use') }}</small>
                                        @endif
                                    </form>

                                    <details class="promo-history-panel">
                                        <summary>{{ __('general.promo_recent_usage_history') }}</summary>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm promo-history-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('admin.date') }}</th>
                                                        <th>{{ __('general.subscriber') }}</th>
                                                        <th>{{ __('general.status') }}</th>
                                                        <th>{{ __('general.promo_plan') }}</th>
                                                        <th>{{ __('general.gateway') }}</th>
                                                        <th>{{ __('general.promo_original') }}</th>
                                                        <th>{{ __('general.promo_discount_label') }}</th>
                                                        <th>{{ __('general.promo_charged') }}</th>
                                                        <th>{{ __('general.tax') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($codeUsages as $usage)
                                                        <tr>
                                                            <td>{{ optional($usage->used_at ?: $usage->created_at)->format('Y-m-d H:i') ?: __('general.no_available') }}</td>
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
                                                            <td>{{ $usage->plan->name ?? ucfirst($usage->plan_interval ?? __('general.no_available')) }}</td>
                                                            <td>{{ $usage->gateway_name ?? __('general.no_available') }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->original_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->discount_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->charged_amount) }}</td>
                                                            <td>{{ Helper::amountFormatDecimal($usage->tax_amount) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="9" class="text-center text-muted">{{ __('general.promo_no_usage_yet') }}</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </details>

                                    <details class="promo-history-panel">
                                        <summary>{{ __('general.promo_recent_audit_history') }}</summary>
                                        <div class="table-responsive mt-3">
                                            <table class="table table-sm promo-history-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('admin.date') }}</th>
                                                        <th>{{ __('general.promo_actor') }}</th>
                                                        <th>{{ __('admin.role') }}</th>
                                                        <th>{{ __('general.promo_event') }}</th>
                                                        <th>{{ __('general.promo_notes') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($codeHistories as $history)
                                                        <tr>
                                                            <td>{{ optional($history->created_at)->format('Y-m-d H:i') ?: __('general.no_available') }}</td>
                                                            <td>
                                                                @if ($history->actor)
                                                                    <a href="{{ url($history->actor->username) }}" target="_blank">
                                                                        {{ $history->actor->hide_name === 'yes' ? $history->actor->username : $history->actor->name }}
                                                                    </a>
                                                                @else
                                                                    {{ __('general.promo_system') }}
                                                                @endif
                                                            </td>
                                                            <td>{{ ucfirst($history->actor_role) }}</td>
                                                            <td>{{ ucwords(str_replace('_', ' ', $history->event_type)) }}</td>
                                                            <td>{{ $history->notes ?: '-' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">{{ __('general.promo_no_activity_yet') }}</td>
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
                            <p class="text-muted mb-0">{{ __('general.promo_no_codes_yet') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
