@extends('layouts.admin')

@section('title', 'Commission Adjustments - BixCash Admin')
@section('page-title', 'Commission Adjustments History')

@section('content')
    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Partner</label>
                    <select name="partner_id" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="">All Partners</option>
                        @foreach($partners as $p)
                            <option value="{{ $p->id }}" {{ request('partner_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->partnerProfile->business_name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Adjustment Type</label>
                    <select name="adjustment_type" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="">All Types</option>
                        <option value="refund" {{ request('adjustment_type') == 'refund' ? 'selected' : '' }}>💸 Refund</option>
                        <option value="correction" {{ request('adjustment_type') == 'correction' ? 'selected' : '' }}>✏️ Correction</option>
                        <option value="penalty" {{ request('adjustment_type') == 'penalty' ? 'selected' : '' }}>⚠️ Penalty</option>
                        <option value="bonus" {{ request('adjustment_type') == 'bonus' ? 'selected' : '' }}>🎁 Bonus</option>
                        <option value="other" {{ request('adjustment_type') == 'other' ? 'selected' : '' }}>📝 Other</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                           style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">🔍 Filter</button>
                    <a href="{{ route('admin.commissions.adjustments.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Adjustments Table -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">✏️ All Commission Adjustments</h5>
                <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                    {{ $adjustments->total() }} Total
                </span>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">ID</th>
                            <th style="padding: 0.75rem;">Date</th>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Type</th>
                            <th style="padding: 0.75rem;">Amount</th>
                            <th style="padding: 0.75rem;">Reason</th>
                            <th style="padding: 0.75rem;">Processed By</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($adjustments as $adjustment)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-secondary">#{{ $adjustment->id }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $adjustment->processed_at->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ $adjustment->processed_at->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.partners.show', $adjustment->partner_id) }}" class="text-decoration-none">
                                        <strong>{{ $adjustment->partner->partnerProfile->business_name ?? $adjustment->partner->name }}</strong><br>
                                        <small style="color: #6c757d;">{{ $adjustment->partner->name }}</small>
                                    </a>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.batches.show', $adjustment->ledger->batch_id) }}" class="text-decoration-none">
                                        {{ $adjustment->ledger->formatted_period }}
                                    </a>
                                </td>
                                <td style="padding: 0.75rem;">
                                    @php
                                        $typeConfig = [
                                            'refund' => ['icon' => '💸', 'color' => 'danger', 'label' => 'Refund'],
                                            'correction' => ['icon' => '✏️', 'color' => 'warning', 'label' => 'Correction'],
                                            'penalty' => ['icon' => '⚠️', 'color' => 'dark', 'label' => 'Penalty'],
                                            'bonus' => ['icon' => '🎁', 'color' => 'success', 'label' => 'Bonus'],
                                            'other' => ['icon' => '📝', 'color' => 'secondary', 'label' => 'Other'],
                                        ];
                                        $config = $typeConfig[$adjustment->adjustment_type] ?? $typeConfig['other'];
                                    @endphp
                                    <span class="badge bg-{{ $config['color'] }}">
                                        {{ $config['icon'] }} {{ $config['label'] }}
                                    </span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: {{ $adjustment->amount_settled >= 0 ? '#00f2fe' : '#f5576c' }};">
                                        Rs {{ number_format($adjustment->amount_settled, 2) }}
                                    </strong>
                                </td>
                                <td style="padding: 0.75rem; max-width: 300px;">
                                    <small>{{ Str::limit($adjustment->adjustment_reason, 60) }}</small>
                                    @if(strlen($adjustment->adjustment_reason) > 60)
                                        <button type="button"
                                                class="btn btn-sm btn-link p-0"
                                                style="font-size: 0.75rem; vertical-align: baseline;"
                                                onclick="alert('{{ addslashes($adjustment->adjustment_reason) }}')">
                                            Read more
                                        </button>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $adjustment->processedByUser->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.partners.show', $adjustment->partner_id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        View Ledger
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 3rem; text-align: center; color: #6c757d;">
                                    No adjustments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($adjustments->hasPages())
            <div class="card-footer" style="background: white; padding: 1rem;">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>
@endsection
