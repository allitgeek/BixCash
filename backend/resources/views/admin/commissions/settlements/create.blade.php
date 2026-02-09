@extends('layouts.admin')

@section('title', 'Process Settlement')
@section('page-title', 'Process Commission Settlement')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-[#93db4d] px-6 py-5">
            <h2 class="text-xl font-bold text-[#021c47]">Settlement for {{ $ledger->partner->partnerProfile->business_name ?? $ledger->partner->name }}</h2>
            <p class="text-[#021c47]/80 mt-1">Period: {{ $ledger->formatted_period }}</p>
        </div>
        <div class="p-6">
            <!-- Ledger Summary -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Commission Owed</p>
                        <p class="text-xl font-bold text-[#021c47]">Rs {{ number_format($ledger->commission_owed, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Already Paid</p>
                        <p class="text-xl font-bold text-[#93db4d]">Rs {{ number_format($ledger->amount_paid, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Outstanding</p>
                        <p class="text-xl font-bold text-red-600">Rs {{ number_format($ledger->amount_outstanding, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Transactions</p>
                        <p class="text-xl font-bold text-[#021c47]">{{ number_format($ledger->total_transactions) }}</p>
                    </div>
                </div>
            </div>

            <!-- Settlement Form -->
            <form action="{{ route('admin.commissions.settlements.store', $ledger->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount to Settle <span class="text-red-500">*</span></label>
                        <input type="number" name="amount_settled" step="0.01" min="0.01" max="{{ $ledger->amount_outstanding }}"
                               value="{{ old('amount_settled', $ledger->amount_outstanding) }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('amount_settled') border-red-500 @enderror" required>
                        @error('amount_settled')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maximum: Rs {{ number_format($ledger->amount_outstanding, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('payment_method') border-red-500 @enderror" required>
                            <option value="">Select Method</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="wallet_deduction" {{ old('payment_method') == 'wallet_deduction' ? 'selected' : '' }}>Wallet Deduction</option>
                            <option value="adjustment" {{ old('payment_method') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        {{-- Wallet Balance Info --}}
                        @if($ledger->partner->wallet)
                            <div id="wallet-balance-info" class="hidden mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-semibold text-sm text-blue-800">Partner's Wallet Balance</span>
                                </div>
                                <p class="text-lg font-bold text-blue-800">Rs {{ number_format($ledger->partner->wallet->balance, 2) }}</p>
                                @if($ledger->partner->wallet->balance < $ledger->amount_outstanding)
                                    <p class="text-sm text-yellow-700 mt-2">
                                        Insufficient balance for full settlement. Available: Rs {{ number_format($ledger->partner->wallet->balance, 2) }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <div id="wallet-unavailable-info" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-semibold text-sm text-red-800">Partner does not have a wallet</span>
                                </div>
                                <p class="text-sm text-red-600 mt-1">Please use another payment method.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Settlement Reference</label>
                        <input type="text" name="settlement_reference" value="{{ old('settlement_reference') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('settlement_reference') border-red-500 @enderror"
                               placeholder="Transaction ID, Check #, etc.">
                        @error('settlement_reference')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Payment</label>
                        <input type="file" name="proof_of_payment"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('proof_of_payment') border-red-500 @enderror"
                               accept=".jpg,.jpeg,.png,.pdf">
                        @error('proof_of_payment')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">JPG, PNG, PDF (Max 5MB)</p>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes</label>
                    <textarea name="admin_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('admin_notes') border-red-500 @enderror"
                              placeholder="Any notes about this settlement...">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-8 border-gray-200">

                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-3 bg-[#93db4d] text-[#021c47] rounded-lg font-semibold hover:bg-[#7bc62e] transition-colors">
                        Process Settlement
                    </button>
                    <a href="{{ route('admin.commissions.partners.show', $ledger->partner_id) }}" class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
    const walletBalanceInfo = document.getElementById('wallet-balance-info');
    const walletUnavailableInfo = document.getElementById('wallet-unavailable-info');

    function toggleWalletInfo() {
        const isWalletDeduction = paymentMethodSelect.value === 'wallet_deduction';

        if (walletBalanceInfo) {
            walletBalanceInfo.classList.toggle('hidden', !isWalletDeduction);
        }

        if (walletUnavailableInfo) {
            walletUnavailableInfo.classList.toggle('hidden', !isWalletDeduction);
        }
    }

    paymentMethodSelect.addEventListener('change', toggleWalletInfo);
    toggleWalletInfo();
});
</script>
@endpush
