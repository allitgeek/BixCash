@extends('layouts.admin')

@section('title', 'Withdrawal Requests - BixCash Admin')
@section('page-title', 'Withdrawal Requests')

@section('content')
    {{-- Quick Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-500"></div>
            <div class="pl-3">
                <p class="text-sm font-medium text-gray-500">⏳ Pending</p>
                <p class="text-3xl font-bold text-[#021c47] mt-1">{{ $stats['pending_count'] }}</p>
                <p class="text-sm font-semibold text-yellow-600 mt-1">Rs. {{ number_format($stats['pending_amount'], 2) }}</p>
            </div>
        </div>
        <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
            <div class="pl-3">
                <p class="text-sm font-medium text-gray-500">🔄 Processing</p>
                <p class="text-3xl font-bold text-[#021c47] mt-1">{{ $stats['processing_count'] }}</p>
                <p class="text-sm font-semibold text-blue-600 mt-1">Rs. {{ number_format($stats['processing_amount'], 2) }}</p>
            </div>
        </div>
        <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#93db4d]"></div>
            <div class="pl-3">
                <p class="text-sm font-medium text-gray-500">✅ Completed Today</p>
                <p class="text-3xl font-bold text-[#93db4d] mt-1">{{ $stats['completed_today_count'] }}</p>
                <p class="text-sm font-semibold text-[#65a030] mt-1">Rs. {{ number_format($stats['completed_today_amount'], 2) }}</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-[#021c47] mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#021c47] mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#021c47] mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-[#021c47] mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or phone..."
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                    Filter
                </button>
                <a href="{{ route('admin.withdrawals.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                    Clear
                </a>
            </div>
        </form>
    </div>

    {{-- Withdrawal Requests Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-[#021c47]">Withdrawal Requests ({{ $withdrawals->total() }})</h3>
            <div class="flex gap-2">
                <a href="{{ route('admin.withdrawals.export', request()->query()) }}" class="px-4 py-2 bg-blue-500 text-white font-medium rounded-lg hover:bg-blue-600 transition-all duration-200">
                    📥 Export CSV
                </a>
                <a href="{{ route('admin.settings.withdrawals') }}" class="px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-all duration-200">
                    ⚙️ Settings
                </a>
            </div>
        </div>

        @if($withdrawals->count() > 0)
            {{-- Bulk Actions Bar --}}
            <div id="bulkActionsBar" class="hidden px-6 py-3 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <span id="selectedCount" class="font-semibold text-[#021c47]">0 selected</span>
                    <div class="flex gap-2">
                        <button onclick="showBulkApproveModal()" class="px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-all">
                            ✅ Bulk Approve
                        </button>
                        <button onclick="showBulkRejectModal()" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600 transition-all">
                            ❌ Bulk Reject
                        </button>
                        <button onclick="clearSelection()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-all">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-[#021c47] text-white">
                            <th class="px-4 py-3 text-center w-12">
                                <input type="checkbox" id="selectAll" onclick="toggleAllCheckboxes(this)" class="w-4 h-4 cursor-pointer">
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">ID</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Customer</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Amount</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Flags</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Requested</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($withdrawals as $withdrawal)
                            <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                <td class="px-4 py-3 text-center">
                                    @if(in_array($withdrawal->status, ['pending', 'processing']))
                                        <input type="checkbox" class="withdrawal-checkbox w-4 h-4 cursor-pointer" value="{{ $withdrawal->id }}" onchange="updateSelection()">
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-bold text-[#021c47]">#{{ $withdrawal->id }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-[#021c47]">{{ $withdrawal->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $withdrawal->user->phone }}</div>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="font-bold text-lg text-[#021c47]">Rs. {{ number_format($withdrawal->amount, 2) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($withdrawal->status === 'pending')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">⏳ Pending</span>
                                    @elseif($withdrawal->status === 'processing')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">🔄 Processing</span>
                                    @elseif($withdrawal->status === 'completed')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">✅ Completed</span>
                                    @elseif($withdrawal->status === 'rejected')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">❌ Rejected</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">🚫 Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($withdrawal->isFlagged())
                                        <span class="px-2 py-1 text-xs font-bold rounded bg-red-500 text-white" title="Fraud Score: {{ $withdrawal->fraud_score }}">🚩 FLAGGED</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-[#021c47]">{{ $withdrawal->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $withdrawal->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.withdrawals.show', $withdrawal->id) }}" class="px-3 py-1.5 bg-[#021c47] text-white text-xs font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($withdrawals->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $withdrawals->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <p class="text-lg font-semibold text-[#021c47] mb-2">No withdrawal requests found</p>
                <p class="text-gray-500">Try adjusting your filters</p>
            </div>
        @endif
    </div>

    {{-- Bulk Approve Modal --}}
    <div id="bulkApproveModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-4">Bulk Approve Withdrawals</h3>
            <form method="POST" action="{{ route('admin.withdrawals.bulk-approve') }}" onsubmit="return confirm('Approve selected withdrawals?');">
                @csrf
                <input type="hidden" name="withdrawal_ids" id="bulkApproveIds">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#021c47] mb-2">Bank Reference Prefix *</label>
                    <input type="text" name="bank_reference_prefix" required placeholder="e.g., BATCH-2024-001"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <p class="mt-1 text-xs text-gray-400">Sequential numbers will be appended</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#021c47] mb-2">Payment Date *</label>
                    <input type="date" name="payment_date" required max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideBulkApproveModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d]">✅ Approve All</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Bulk Reject Modal --}}
    <div id="bulkRejectModal" class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-4">Bulk Reject Withdrawals</h3>
            <form method="POST" action="{{ route('admin.withdrawals.bulk-reject') }}" onsubmit="return confirm('Reject selected withdrawals? Amounts will be refunded.');">
                @csrf
                <input type="hidden" name="withdrawal_ids" id="bulkRejectIds">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#021c47] mb-2">Rejection Reason *</label>
                    <textarea name="rejection_reason" required rows="4" placeholder="Explain why these withdrawals are being rejected..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 resize-none"></textarea>
                    <p class="mt-1 text-xs text-gray-400">This reason will be shown to all selected customers</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="hideBulkRejectModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600">❌ Reject All</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleAllCheckboxes(source) {
            document.querySelectorAll('.withdrawal-checkbox').forEach(cb => cb.checked = source.checked);
            updateSelection();
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.withdrawal-checkbox:checked');
            const count = checkboxes.length;
            document.getElementById('selectedCount').textContent = count + ' selected';
            document.getElementById('bulkActionsBar').classList.toggle('hidden', count === 0);
            const allCheckboxes = document.querySelectorAll('.withdrawal-checkbox');
            document.getElementById('selectAll').checked = allCheckboxes.length > 0 && count === allCheckboxes.length;
        }

        function clearSelection() {
            document.querySelectorAll('.withdrawal-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelection();
        }

        function showBulkApproveModal() {
            const selected = Array.from(document.querySelectorAll('.withdrawal-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) { alert('Please select at least one withdrawal'); return; }
            document.getElementById('bulkApproveIds').value = JSON.stringify(selected);
            document.getElementById('bulkApproveModal').style.display = 'flex';
        }

        function hideBulkApproveModal() { document.getElementById('bulkApproveModal').style.display = 'none'; }

        function showBulkRejectModal() {
            const selected = Array.from(document.querySelectorAll('.withdrawal-checkbox:checked')).map(cb => cb.value);
            if (selected.length === 0) { alert('Please select at least one withdrawal'); return; }
            document.getElementById('bulkRejectIds').value = JSON.stringify(selected);
            document.getElementById('bulkRejectModal').style.display = 'flex';
        }

        function hideBulkRejectModal() { document.getElementById('bulkRejectModal').style.display = 'none'; }

        document.getElementById('bulkApproveModal')?.addEventListener('click', function(e) { if (e.target === this) hideBulkApproveModal(); });
        document.getElementById('bulkRejectModal')?.addEventListener('click', function(e) { if (e.target === this) hideBulkRejectModal(); });
    </script>
@endsection
