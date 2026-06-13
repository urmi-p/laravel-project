@extends('admin.layout')

@section('content')
<style>
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

<h5 class="mb-4 fw-light">
    <a class="text-reset" href="{{ url('panel/admin') }}">{{ __('admin.dashboard') }}</a>
    <i class="bi-chevron-right me-1 fs-6"></i>
    <span class="text-muted">Promo Codes ({{ $codes->total() }})</span>
</h5>

<div class="content">
    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-custom border-0">
        <div class="card-body p-lg-4">
            <div class="table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        @if ($codes->count())
                            <tr>
                                <th class="active">ID</th>
                                <th class="active">Creator</th>
                                <th class="active">Code</th>
                                <th class="active">Discount</th>
                                <th class="active">Usage</th>
                                <th class="active">Charged Revenue</th>
                                <th class="active">Impact</th>
                                <th class="active">Status</th>
                                <th class="active">Actions</th>
                            </tr>

                            @foreach ($codes as $code)
                                @php($codeStats = $stats->get($code->id))
                                @php($codeUsages = $recentUsages->get($code->id, collect()))
                                @php($codeHistories = $recentHistories->get($code->id, collect()))
                                <tr>
                                    <td>{{ $code->id }}</td>
                                    <td>
                                        @if ($code->creator)
                                            <a href="{{ url($code->creator->username) }}" target="_blank">{{ $code->creator->name }}</a>
                                        @else
                                            {{ __('general.no_available') }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $code->code }}</strong>
                                        <small class="text-muted d-block">{{ optional($code->expires_at)->format('Y-m-d H:i') ?: 'No expiry' }}</small>
                                    </td>
                                    <td>
                                        @if ($code->discount_type === 'percentage')
                                            {{ rtrim(rtrim(number_format($code->discount_value, 2), '0'), '.') }}%
                                        @else
                                            {{ Helper::amountFormatDecimal($code->discount_value) }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ (int) ($codeStats->usage_count ?? 0) }}
                                        <small class="text-muted d-block">Subscribers: {{ (int) ($codeStats->subscriber_count ?? 0) }}</small>
                                    </td>
                                    <td>{{ Helper::amountFormatDecimal($codeStats->revenue_generated ?? 0) }}</td>
                                    <td>
                                        <div>Total Discount: {{ Helper::amountFormatDecimal($codeStats->total_discount_amount ?? 0) }}</div>
                                        <small class="text-muted">Creator Earnings Impact: {{ Helper::amountFormatDecimal($codeStats->creator_earnings_impact ?? 0) }}</small>
                                    </td>
                                    <td>
                                        @if ($code->expires_at && $code->expires_at->isPast())
                                            <span class="rounded-pill badge bg-warning">Expired</span>
                                        @elseif ($code->is_active === 'yes')
                                            <span class="rounded-pill badge bg-success">Active</span>
                                        @else
                                            <span class="rounded-pill badge bg-danger">Disabled</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($code->is_active === 'yes')
                                            <form method="POST" action="{{ url('panel/admin/promo-codes/' . $code->id . '/disable') }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Disable</button>
                                            </form>
                                        @else
                                            <span class="text-muted">No action</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="9" class="bg-light-subtle">
                                        <details class="promo-history-panel">
                                            <summary>Recent Usage History</summary>
                                            <div class="table-responsive mt-3">
                                                <table class="table table-sm promo-history-table mb-0">
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
                                                                <td>{{ ucfirst($usage->status) }}</td>
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

                                        <details class="promo-history-panel mt-3">
                                            <summary>Recent Audit History</summary>
                                            <div class="table-responsive mt-3">
                                                <table class="table table-sm promo-history-table mb-0">
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
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">No promo codes found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($codes->lastPage() > 1)
        {{ $codes->onEachSide(0)->links() }}
    @endif
</div>
@endsection
