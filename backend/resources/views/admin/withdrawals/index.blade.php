@extends('layouts.admin')

@section('title', 'Withdrawal Requests - BixCash Admin')
@section('page-title', 'Withdrawal Requests')

@section('content')
    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">⏳ Pending</h5>
                <h2 style="margin-bottom: 0.25rem;">{{ $stats['pending_count'] }}</h2>
                <p style="margin: 0; font-size: 1.25rem; font-weight: 500;">Rs. {{ number_format($stats['pending_amount'], 2) }}</p>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">🔄 Processing</h5>
                <h2 style="margin-bottom: 0.25rem;">{{ $stats['processing_count'] }}</h2>
                <p style="margin: 0; font-size: 1.25rem; font-weight: 500;">Rs. {{ number_format($stats['processing_amount'], 2) }}</p>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">✅ Completed Today</h5>
                <h2 style="margin-bottom: 0.25rem;">{{ $stats['completed_today_count'] }}</h2>
                <p style="margin: 0; font-size: 1.25rem; font-weight: 500;">Rs. {{ number_format($stats['completed_today_amount'], 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.withdrawals.index') }}" style="display: grid; grid-template-columns: repeat(4, 1fr) auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                    <select name="status" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">From Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">To Date</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or phone..." style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" style="background: #007bff; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; white-space: nowrap;">
                        🔍 Filter
                    </button>
                    <a href="{{ route('admin.withdrawals.index') }}" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; white-space: nowrap;">
                        ✖️ Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawal Requests Table -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">Withdrawal Requests ({{ $withdrawals->total() }})</h3>
            <a href="{{ route('admin.settings.withdrawals') }}" style="background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">
                ⚙️ Settings
            </a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($withdrawals->count() > 0)
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">ID</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Customer</th>
                            <th style="padding: 1rem; text-align: right; border-bottom: 2px solid #dee2e6;">Amount</th>
                            <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6;">Status</th>
                            <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6;">Flags</th>
                            <th style="padding: 1rem; text-align: left; border-bottom: 2px solid #dee2e6;">Requested</th>
                            <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($withdrawals as $withdrawal)
                            <tr style="border-bottom: 1px solid #dee2e6;">
                                <td style="padding: 1rem;">
                                    <strong>#{{ $withdrawal->id }}</strong>
                                </td>
                                <td style="padding: 1rem;">
                                    <div><strong>{{ $withdrawal->user->name }}</strong></div>
                                    <div style="color: #666; font-size: 0.875rem;">{{ $withdrawal->user->phone }}</div>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <strong style="font-size: 1.1rem;">Rs. {{ number_format($withdrawal->amount, 2) }}</strong>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    @if($withdrawal->status === 'pending')
                                        <span style="background: #ffc107; color: #000; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">⏳ Pending</span>
                                    @elseif($withdrawal->status === 'processing')
                                        <span style="background: #17a2b8; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">🔄 Processing</span>
                                    @elseif($withdrawal->status === 'completed')
                                        <span style="background: #28a745; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">✅ Completed</span>
                                    @elseif($withdrawal->status === 'rejected')
                                        <span style="background: #dc3545; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">❌ Rejected</span>
                                    @else
                                        <span style="background: #6c757d; color: #fff; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; font-weight: 500;">🚫 Cancelled</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    @if($withdrawal->isFlagged())
                                        <span style="background: #ff6b6b; color: white; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 600;" title="Fraud Score: {{ $withdrawal->fraud_score }}">
                                            🚩 FLAGGED
                                        </span>
                                    @else
                                        <span style="color: #999;">—</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem;">
                                    <div>{{ $withdrawal->created_at->format('M d, Y') }}</div>
                                    <div style="color: #666; font-size: 0.875rem;">{{ $withdrawal->created_at->format('h:i A') }}</div>
                                </td>
                                <td style="padding: 1rem; text-align: center;">
                                    <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" style="background: #007bff; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none; font-size: 0.875rem;">
                                        👁️ View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div style="padding: 1.5rem;">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <div style="padding: 3rem; text-align: center; color: #999;">
                    <p style="font-size: 1.25rem; margin-bottom: 0.5rem;">📭 No withdrawal requests found</p>
                    <p>Try adjusting your filters</p>
                </div>
            @endif
        </div>
    </div>
@endsection
