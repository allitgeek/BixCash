@extends('layouts.admin')

@section('title', 'Withdrawal Request #' . $withdrawal->id . ' - BixCash Admin')
@section('page-title', 'Withdrawal Request #' . $withdrawal->id)

@section('content')
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('admin.withdrawals.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Withdrawals
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Customer Information --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-[#021c47]">👤 Customer Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Name</span>
                            <span class="text-sm font-semibold text-[#021c47]">{{ $withdrawal->user->name }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Phone</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->user->phone }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Email</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Account Age</span>
                            <span class="text-sm text-[#021c47]">{{ $accountAge }} days</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100 col-span-2">
                            <span class="text-sm font-medium text-gray-500">Current Balance</span>
                            <span class="text-sm font-bold text-[#93db4d]">Rs. {{ number_format($withdrawal->user->wallet->balance ?? 0, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank Details --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-[#021c47]">🏦 Bank Details</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Bank Name</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->bank_name }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Account Title</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->account_title }}</span>
                        </div>
                        <div class="flex justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Account Number</span>
                            <span class="text-sm font-bold text-[#021c47] font-mono">{{ $withdrawal->account_number }}</span>
                        </div>
                        @if($withdrawal->iban)
                            <div class="flex justify-between py-3 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">IBAN</span>
                                <span class="text-sm text-[#021c47] font-mono">{{ $withdrawal->iban }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Withdrawal History --}}
            @if($withdrawalHistory->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-bold text-[#021c47]">📜 Previous Withdrawals (Last 10)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-[#021c47] text-white">
                                    <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                                    <th class="px-4 py-3 text-right text-sm font-semibold">Amount</th>
                                    <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($withdrawalHistory as $history)
                                    <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                        <td class="px-4 py-3 text-sm text-[#021c47]">{{ $history->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 text-sm text-right font-medium text-[#021c47]">Rs. {{ number_format($history->amount, 2) }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($history->status === 'completed')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">✅</span>
                                            @elseif($history->status === 'rejected')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">❌</span>
                                            @elseif($history->status === 'cancelled')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">🚫</span>
                                            @elseif($history->status === 'processing')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-600">🔄</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">⏳</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column (1/3) --}}
        <div class="space-y-6">
            {{-- Transaction Details --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-base font-bold text-[#021c47]">💰 Transaction Details</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-4 mb-4 border-b border-gray-200">
                        <p class="text-sm text-gray-500 mb-1">Withdrawal Amount</p>
                        <p class="text-3xl font-bold text-red-500">Rs. {{ number_format($withdrawal->amount, 2) }}</p>
                    </div>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Status</span>
                            @if($withdrawal->status === 'pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">⏳ Pending</span>
                            @elseif($withdrawal->status === 'processing')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">🔄 Processing</span>
                            @elseif($withdrawal->status === 'completed')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">✅ Completed</span>
                            @elseif($withdrawal->status === 'rejected')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">❌ Rejected</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">🚫 Cancelled</span>
                            @endif
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Requested</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($withdrawal->fraud_score > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Fraud Score</span>
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $withdrawal->fraud_score >= 50 ? 'bg-red-500' : 'bg-yellow-500' }} text-white">
                                    {{ $withdrawal->fraud_score }}/100
                                </span>
                            </div>
                        @endif
                    </div>

                    @if($withdrawal->fraud_flags && count(json_decode($withdrawal->fraud_flags, true)) > 0)
                        <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-r-lg">
                            <p class="text-sm font-semibold text-yellow-700 mb-2">🚩 Fraud Flags:</p>
                            <ul class="list-disc pl-4 text-sm text-yellow-600">
                                @foreach(json_decode($withdrawal->fraud_flags, true) as $flag)
                                    <li>{{ ucwords(str_replace('_', ' ', $flag)) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Approval/Rejection Forms --}}
            @if($withdrawal->status === 'pending' || $withdrawal->status === 'processing')
                {{-- Approve Form --}}
                <div class="bg-white rounded-xl border-2 border-[#93db4d] shadow-sm">
                    <div class="px-6 py-3 bg-[#93db4d]">
                        <h3 class="text-base font-bold text-[#021c47]">✅ Approve Withdrawal</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Bank Reference *</label>
                                    <input type="text" name="bank_reference" required placeholder="e.g., TXN123456789"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Payment Date *</label>
                                    <input type="date" name="payment_date" required max="{{ date('Y-m-d') }}"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Proof of Payment</label>
                                    <input type="file" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d]">
                                    <p class="mt-1 text-xs text-gray-400">Optional: JPG, PNG, PDF (max 5MB)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Admin Notes</label>
                                    <textarea name="admin_notes" rows="2" placeholder="Internal notes..."
                                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 resize-none"></textarea>
                                </div>
                                <button type="submit" onclick="return confirm('Approve this withdrawal?')"
                                        class="w-full py-3 bg-[#93db4d] text-[#021c47] font-bold rounded-lg hover:bg-[#7fc93d] transition-all">
                                    ✅ Approve Withdrawal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Reject Form --}}
                <div class="bg-white rounded-xl border-2 border-red-500 shadow-sm">
                    <div class="px-6 py-3 bg-red-500">
                        <h3 class="text-base font-bold text-white">❌ Reject Withdrawal</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Rejection Reason *</label>
                                    <textarea name="rejection_reason" rows="3" required placeholder="Explain why this withdrawal is being rejected..."
                                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 resize-none"></textarea>
                                    <p class="mt-1 text-xs text-gray-400">This will be shown to the customer</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#021c47] mb-2">Admin Notes</label>
                                    <textarea name="admin_notes" rows="2" placeholder="Internal notes..."
                                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-red-300 focus:ring-2 focus:ring-red-100 resize-none"></textarea>
                                </div>
                                <button type="submit" onclick="return confirm('Reject this withdrawal? Amount will be refunded.')"
                                        class="w-full py-3 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition-all">
                                    ❌ Reject & Refund
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                {{-- Processing Info (Completed/Rejected) --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-base font-bold text-[#021c47]">ℹ️ Processing Information</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Processed By</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->processedBy->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Processed At</span>
                            <span class="text-sm text-[#021c47]">{{ $withdrawal->processed_at ? $withdrawal->processed_at->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                        @if($withdrawal->status === 'completed')
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">Bank Reference</span>
                                <span class="text-sm font-bold text-[#021c47] font-mono">{{ $withdrawal->bank_reference }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-500">Payment Date</span>
                                <span class="text-sm text-[#021c47]">{{ $withdrawal->payment_date ? \Carbon\Carbon::parse($withdrawal->payment_date)->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            @if($withdrawal->proof_of_payment)
                                <a href="{{ asset('storage/' . $withdrawal->proof_of_payment) }}" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all">
                                    📎 View Proof of Payment
                                </a>
                            @endif
                        @endif
                        @if($withdrawal->status === 'rejected' && $withdrawal->rejection_reason)
                            <div class="p-4 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
                                <p class="text-sm font-semibold text-red-700">Rejection Reason:</p>
                                <p class="text-sm text-red-600 mt-1">{{ $withdrawal->rejection_reason }}</p>
                            </div>
                        @endif
                        @if($withdrawal->admin_notes)
                            <div class="p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg">
                                <p class="text-sm font-semibold text-blue-700">Admin Notes:</p>
                                <p class="text-sm text-blue-600 mt-1">{{ $withdrawal->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
