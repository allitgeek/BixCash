@extends('layouts.admin')

@section('title', 'Commission Batches - BixCash Admin')
@section('page-title', 'Commission Batches')

@section('content')
    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.commissions.batches.index') }}" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search Period</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="e.g., 2025-11" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">🔍 Filter</button>
                    <a href="{{ route('admin.commissions.batches.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Batches Table -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">📦 All Commission Batches</h5>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span class="badge bg-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">
                        {{ $batches->total() }} Total
                    </span>
                    <a href="{{ route('admin.commissions.export.batches', request()->query()) }}"
                       class="btn btn-success btn-sm"
                       style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem;"
                       title="Export with current filters: {{ request()->hasAny(['status', 'search']) ? 'Filtered' : 'All data' }}"
                       onclick="return confirm('Export {{ $batches->total() }} batch(es) to Excel?\n\nCurrent filters will be applied to the export.');">
                        📊 Export to Excel
                        @if(request()->hasAny(['status', 'search']))
                            <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Filtered</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Batch ID</th>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Partners</th>
                            <th style="padding: 0.75rem;">Transactions</th>
                            <th style="padding: 0.75rem;">Total Amount</th>
                            <th style="padding: 0.75rem;">Commission</th>
                            <th style="padding: 0.75rem;">Status</th>
                            <th style="padding: 0.75rem;">Completed</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($batches as $batch)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-secondary">#{{ $batch->id }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $batch->formatted_period }}</strong><br>
                                    <small style="color: #6c757d;">
                                        {{ \Carbon\Carbon::parse($batch->period_start)->format('M d') }} - 
                                        {{ \Carbon\Carbon::parse($batch->period_end)->format('M d, Y') }}
                                    </small>
                                </td>
                                <td style="padding: 0.75rem;">{{ number_format($batch->total_partners) }}</td>
                                <td style="padding: 0.75rem;">{{ number_format($batch->total_transactions) }}</td>
                                <td style="padding: 0.75rem;">
                                    Rs {{ number_format($batch->total_transaction_amount, 2) }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: #f5576c;">Rs {{ number_format($batch->total_commission_calculated, 2) }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($batch->status === 'completed')
                                        <span class="badge bg-success">✅ Completed</span>
                                    @elseif($batch->status === 'processing')
                                        <span class="badge bg-warning">🔄 Processing</span>
                                    @elseif($batch->status === 'failed')
                                        <span class="badge bg-danger">❌ Failed</span>
                                    @else
                                        <span class="badge bg-secondary">⏳ {{ ucfirst($batch->status) }}</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($batch->completed_at)
                                        {{ $batch->completed_at->format('M d, Y') }}<br>
                                        <small style="color: #6c757d;">{{ $batch->completed_at->format('h:i A') }}</small>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.batches.show', $batch->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding: 3rem; text-align: center;">
                                    <div style="color: #6c757d; font-size: 1.1rem;">
                                        <p style="margin-bottom: 1rem;">📦 No commission batches found</p>
                                        <a href="{{ route('admin.commissions.index') }}" class="btn btn-primary">
                                            Go to Dashboard
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($batches->hasPages())
            <div class="card-footer" style="background: white; padding: 1rem;">
                {{ $batches->links() }}
            </div>
        @endif
    </div>
@endsection
