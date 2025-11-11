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
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('admin.withdrawals.export', request()->query()) }}" style="background: #17a2b8; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">
                    📥 Export CSV
                </a>
                <a href="{{ route('admin.settings.withdrawals') }}" style="background: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 4px; text-decoration: none;">
                    ⚙️ Settings
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($withdrawals->count() > 0)
                <!-- Bulk Actions Bar (hidden by default) -->
                <div id="bulkActionsBar" style="display: none; padding: 1rem; background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span id="selectedCount" style="font-weight: 600; color: #333;">0 selected</span>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button onclick="showBulkApproveModal()" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                                ✅ Bulk Approve
                            </button>
                            <button onclick="showBulkRejectModal()" style="background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">
                                ❌ Bulk Reject
                            </button>
                            <button onclick="clearSelection()" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">
                                Clear
                            </button>
                        </div>
                    </div>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 1rem; text-align: center; border-bottom: 2px solid #dee2e6; width: 50px;">
                                <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)" style="width: 18px; height: 18px; cursor: pointer;">
                            </th>
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
                                <td style="padding: 1rem; text-align: center;">
                                    @if(in_array($withdrawal->status, ['pending', 'processing']))
                                        <input type="checkbox" class="withdrawal-checkbox" value="{{ $withdrawal->id }}" onchange="updateSelection()" style="width: 18px; height: 18px; cursor: pointer;">
                                    @endif
                                </td>
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

    <!-- Bulk Approve Modal -->
    <div id="bulkApproveModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
            <h3 style="margin-top: 0;">Bulk Approve Withdrawals</h3>
            <form method="POST" action="{{ route('admin.withdrawals.bulk-approve') }}" onsubmit="return confirm('Approve selected withdrawals?');">
                @csrf
                <input type="hidden" name="withdrawal_ids" id="bulkApproveIds">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Bank Reference Prefix *</label>
                    <input type="text" name="bank_reference_prefix" required placeholder="e.g., BATCH-2024-001" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                    <small style="color: #666;">Sequential numbers will be appended (e.g., BATCH-2024-001-0001, -0002, etc.)</small>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Payment Date *</label>
                    <input type="date" name="payment_date" required max="{{ date('Y-m-d') }}" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" onclick="hideBulkApproveModal()" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="background: #28a745; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">✅ Approve All</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Reject Modal -->
    <div id="bulkRejectModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: 8px; max-width: 500px; width: 90%;">
            <h3 style="margin-top: 0;">Bulk Reject Withdrawals</h3>
            <form method="POST" action="{{ route('admin.withdrawals.bulk-reject') }}" onsubmit="return confirm('Reject selected withdrawals? Amounts will be refunded.');">
                @csrf
                <input type="hidden" name="withdrawal_ids" id="bulkRejectIds">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Rejection Reason *</label>
                    <textarea name="rejection_reason" required rows="4" placeholder="Explain why these withdrawals are being rejected..." style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px; resize: vertical;"></textarea>
                    <small style="color: #666;">This reason will be shown to all selected customers</small>
                </div>
                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                    <button type="button" onclick="hideBulkRejectModal()" style="background: #6c757d; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="background: #dc3545; color: white; padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;">❌ Reject All</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAllCheckboxes(source) {
            const checkboxes = document.querySelectorAll('.withdrawal-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateSelection();
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.withdrawal-checkbox:checked');
            const count = checkboxes.length;
            const bulkBar = document.getElementById('bulkActionsBar');
            const countSpan = document.getElementById('selectedCount');

            countSpan.textContent = count + ' selected';
            bulkBar.style.display = count > 0 ? 'block' : 'none';

            // Update "Select All" checkbox state
            const allCheckboxes = document.querySelectorAll('.withdrawal-checkbox');
            const selectAll = document.getElementById('selectAll');
            selectAll.checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
        }

        function clearSelection() {
            document.querySelectorAll('.withdrawal-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelection();
        }

        function showBulkApproveModal() {
            const selected = Array.from(document.querySelectorAll('.withdrawal-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Please select at least one withdrawal');
                return;
            }
            document.getElementById('bulkApproveIds').value = JSON.stringify(selected);
            document.getElementById('bulkApproveModal').style.display = 'flex';
        }

        function hideBulkApproveModal() {
            document.getElementById('bulkApproveModal').style.display = 'none';
        }

        function showBulkRejectModal() {
            const selected = Array.from(document.querySelectorAll('.withdrawal-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) {
                alert('Please select at least one withdrawal');
                return;
            }
            document.getElementById('bulkRejectIds').value = JSON.stringify(selected);
            document.getElementById('bulkRejectModal').style.display = 'flex';
        }

        function hideBulkRejectModal() {
            document.getElementById('bulkRejectModal').style.display = 'none';
        }

        // Close modals when clicking outside
        document.getElementById('bulkApproveModal')?.addEventListener('click', function(e) {
            if (e.target === this) hideBulkApproveModal();
        });
        document.getElementById('bulkRejectModal')?.addEventListener('click', function(e) {
            if (e.target === this) hideBulkRejectModal();
        });
    </script>
@endsection
