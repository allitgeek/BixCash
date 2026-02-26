@extends('layouts.admin')

@section('title', 'Partner Details - BixCash Admin')
@section('page-title', 'Partner Details')

@section('content')
    {{-- Header Card --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">{{ $partner->partnerProfile->business_name ?? $partner->name }}</h3>
                <p class="text-sm text-gray-500 mt-1">Partner ID: #{{ $partner->id }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.partners.edit', $partner) }}" class="px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                    ✏️ Edit
                </a>
                <a href="{{ route('admin.partners.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                    Back
                </a>
            </div>
        </div>
        
        {{-- Status & Actions Bar --}}
        <div class="px-6 py-4 bg-gray-50 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-600">Status:</span>
                @if($partner->partnerProfile)
                    @if($partner->partnerProfile->status === 'approved')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Approved</span>
                    @elseif($partner->partnerProfile->status === 'pending')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending Review</span>
                    @else
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Rejected</span>
                    @endif
                @endif
                <span class="text-sm font-medium text-gray-600 ml-4">Account:</span>
                @if($partner->is_active)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Active</span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Inactive</span>
                @endif
            </div>
            <div class="flex flex-wrap gap-2">
                @if($partner->partnerProfile && in_array($partner->partnerProfile->status, ['pending', 'rejected']))
                    <form method="POST" action="{{ route('admin.partners.approve', $partner) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 shadow-sm transition-all" onclick="return confirm('Approve this partner?')">
                            ✓ {{ $partner->partnerProfile->status === 'rejected' ? 'Re-Approve' : 'Approve' }}
                        </button>
                    </form>
                    @if($partner->partnerProfile->status === 'pending')
                        <button type="button" class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-red-600 transition-all" onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                            Reject
                        </button>
                    @endif
                @endif
                <form method="POST" action="{{ route('admin.partners.update-status', $partner) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="is_active" value="{{ $partner->is_active ? 0 : 1 }}">
                    <button type="submit" class="px-4 py-2 {{ $partner->is_active ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white text-sm font-medium rounded-lg transition-all">
                        {{ $partner->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                @if($partner->pin_hash)
                    <button type="button" class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition-all" onclick="document.getElementById('reset-pin-modal').classList.remove('hidden')">
                        Reset PIN
                    </button>
                @else
                    <button type="button" class="px-4 py-2 bg-[#021c47] text-white text-sm font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all" onclick="document.getElementById('set-pin-modal').classList.remove('hidden')">
                        Set PIN
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] transition-all">
            <p class="text-sm font-medium text-gray-500">Total Transactions</p>
            <p class="text-2xl font-bold text-[#021c47] mt-1">{{ $stats['total_transactions'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] transition-all">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-[#93db4d] mt-1">Rs. {{ number_format($stats['total_revenue'], 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] transition-all">
            <p class="text-sm font-medium text-gray-500">Partner Profit</p>
            <p class="text-2xl font-bold text-[#021c47] mt-1">Rs. {{ number_format($stats['total_profit'], 0) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] transition-all">
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_confirmations'] }}</p>
        </div>
    </div>

    {{-- Business Information --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h4 class="text-base font-bold text-[#021c47]">Business Information</h4>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Business Logo</span>
                        <span class="flex items-center gap-2">
                            @if($partner->partnerProfile && $partner->partnerProfile->logo)
                                <img src="{{ asset('storage/' . $partner->partnerProfile->logo) }}" alt="Logo" class="w-10 h-10 rounded-lg object-cover border">
                                <button type="button" onclick="document.getElementById('logo-modal').classList.remove('hidden')" class="text-xs text-blue-600 hover:underline">Update</button>
                            @else
                                <span class="text-gray-400">No logo</span>
                                <button type="button" onclick="document.getElementById('logo-modal').classList.remove('hidden')" class="text-xs text-blue-600 hover:underline">Upload</button>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Business Name</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->partnerProfile->business_name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Business Type</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->partnerProfile->business_type ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Business Address</span>
                        <span class="text-sm text-[#021c47] text-right">{{ $partner->partnerProfile->business_address ?? '-' }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Contact Name</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->name }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Phone</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->phone }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Email</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->email ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">Registered</span>
                        <span class="text-sm text-[#021c47]">{{ $partner->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                </div>
            </div>
            @if($partner->partnerProfile && $partner->partnerProfile->status === 'rejected')
                <div class="mt-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                    <p class="text-sm font-medium text-red-700">Rejected: {{ $partner->partnerProfile->rejection_notes ?? 'No reason provided' }}</p>
                    <p class="text-xs text-red-500 mt-1">{{ $partner->partnerProfile->rejected_at?->format('M j, Y g:i A') }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h4 class="text-base font-bold text-[#021c47]">Recent Transactions</h4>
            <a href="{{ route('admin.partners.transactions', $partner) }}" class="text-sm text-[#021c47] hover:text-[#93db4d] font-medium">View All →</a>
        </div>
        <div class="p-6">
            @if($recentTransactions->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-4 py-3 text-left font-semibold">Code</th>
                                <th class="px-4 py-3 text-left font-semibold">Customer</th>
                                <th class="px-4 py-3 text-right font-semibold">Amount</th>
                                <th class="px-4 py-3 text-right font-semibold">Profit</th>
                                <th class="px-4 py-3 text-center font-semibold">Status</th>
                                <th class="px-4 py-3 text-left font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($recentTransactions as $transaction)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                    <td class="px-4 py-3 font-medium text-[#021c47]">{{ $transaction->transaction_code }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $transaction->customer->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">Rs. {{ number_format($transaction->invoice_amount, 0) }}</td>
                                    <td class="px-4 py-3 text-right text-[#93db4d] font-medium">Rs. {{ number_format($transaction->partner_profit_share, 0) }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($transaction->status === 'confirmed')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Confirmed</span>
                                        @elseif($transaction->status === 'pending_confirmation')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($transaction->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-gray-500">{{ $transaction->transaction_date->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No transactions yet.</p>
            @endif
        </div>
    </div>

    {{-- Reject Modal --}}
    <div id="reject-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-4">Reject Partner Application</h3>
            <form method="POST" action="{{ route('admin.partners.reject', $partner) }}">
                @csrf
                <div class="mb-4">
                    <label for="rejection_notes" class="block text-sm font-medium text-[#021c47] mb-2">Rejection Reason *</label>
                    <textarea id="rejection_notes" name="rejection_notes" rows="4" required placeholder="Please provide a reason..."
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white font-medium rounded-lg hover:bg-red-600">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Set PIN Modal --}}
    <div id="set-pin-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-2">🔑 Set Partner PIN</h3>
            <p class="text-sm text-gray-500 mb-4">Set a 4-digit PIN for partner login</p>
            <form method="POST" action="{{ route('admin.partners.set-pin', $partner) }}">
                @csrf
                <div class="mb-4">
                    <label for="pin" class="block text-sm font-medium text-[#021c47] mb-2">4-Digit PIN *</label>
                    <input type="text" id="pin" name="pin" maxlength="4" pattern="[0-9]{4}" required placeholder="Enter PIN"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg text-center text-2xl tracking-widest focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="mb-4">
                    <label for="pin_confirmation" class="block text-sm font-medium text-[#021c47] mb-2">Confirm PIN *</label>
                    <input type="text" id="pin_confirmation" name="pin_confirmation" maxlength="4" pattern="[0-9]{4}" required placeholder="Re-enter PIN"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg text-center text-2xl tracking-widest focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('set-pin-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47]">Set PIN</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reset PIN Modal --}}
    <div id="reset-pin-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-2">🔄 Reset Partner PIN</h3>
            <p class="text-sm text-gray-500 mb-4">Enter a new 4-digit PIN</p>
            <form method="POST" action="{{ route('admin.partners.reset-pin', $partner) }}">
                @csrf
                <div class="mb-4">
                    <label for="new_pin" class="block text-sm font-medium text-[#021c47] mb-2">New PIN *</label>
                    <input type="text" id="new_pin" name="new_pin" maxlength="4" pattern="[0-9]{4}" required placeholder="Enter new PIN"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg text-center text-2xl tracking-widest focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="mb-4">
                    <label for="new_pin_confirmation" class="block text-sm font-medium text-[#021c47] mb-2">Confirm New PIN *</label>
                    <input type="text" id="new_pin_confirmation" name="new_pin_confirmation" maxlength="4" pattern="[0-9]{4}" required placeholder="Re-enter new PIN"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg text-center text-2xl tracking-widest focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('reset-pin-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600">Reset PIN</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Logo Modal --}}
    <div id="logo-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-lg font-bold text-[#021c47] mb-2">📷 {{ $partner->partnerProfile && $partner->partnerProfile->logo ? 'Update' : 'Upload' }} Logo</h3>
            <p class="text-sm text-gray-500 mb-4">JPG or PNG, max 2MB</p>
            <form method="POST" action="{{ route('admin.partners.update-logo', $partner) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <input type="file" name="logo" accept="image/jpeg,image/jpg,image/png" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d]">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('logo-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47]">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Close modals when clicking outside
    document.querySelectorAll('[id$="-modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    });
    // PIN input validation
    document.querySelectorAll('input[name="pin"], input[name="pin_confirmation"], input[name="new_pin"], input[name="new_pin_confirmation"]').forEach(input => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 4);
        });
    });
</script>
@endpush
