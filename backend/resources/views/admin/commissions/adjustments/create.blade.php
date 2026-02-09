@extends('layouts.admin')

@section('title', 'Create Adjustment')
@section('page-title', 'Create Commission Adjustment')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="bg-[#021c47] px-6 py-5">
            <h2 class="text-xl font-bold text-white">Adjustment for {{ $ledger->partner->partnerProfile->business_name ?? $ledger->partner->name }}</h2>
            <p class="text-white/80 mt-1">Period: {{ $ledger->formatted_period }}</p>
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

            <!-- Info Alert -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-8">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-yellow-800 mb-2">About Adjustments</h4>
                        <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                            <li><strong>Positive Amount:</strong> Increases commission owed (e.g., correction, penalty)</li>
                            <li><strong>Negative Amount:</strong> Decreases commission owed (e.g., refund, bonus)</li>
                            <li>Adjustments automatically update the ledger balance and status</li>
                            <li>Reason is required for audit trail</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Adjustment Form -->
            <form action="{{ route('admin.commissions.adjustments.store', $ledger->id) }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Amount <span class="text-red-500">*</span></label>
                        <input type="number" name="adjustment_amount" step="0.01"
                               value="{{ old('adjustment_amount') }}"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('adjustment_amount') border-red-500 @enderror"
                               placeholder="Enter positive or negative amount"
                               required>
                        @error('adjustment_amount')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Examples: <strong>500</strong> (add Rs 500), <strong>-200</strong> (subtract Rs 200)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Type <span class="text-red-500">*</span></label>
                        <select name="adjustment_type" class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('adjustment_type') border-red-500 @enderror" required>
                            <option value="">Select Type</option>
                            <option value="refund" {{ old('adjustment_type') == 'refund' ? 'selected' : '' }}>Refund (Negative)</option>
                            <option value="correction" {{ old('adjustment_type') == 'correction' ? 'selected' : '' }}>Correction (Positive/Negative)</option>
                            <option value="penalty" {{ old('adjustment_type') == 'penalty' ? 'selected' : '' }}>Penalty (Positive)</option>
                            <option value="bonus" {{ old('adjustment_type') == 'bonus' ? 'selected' : '' }}>Bonus (Negative)</option>
                            <option value="other" {{ old('adjustment_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('adjustment_type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Adjustment Reason <span class="text-red-500">*</span></label>
                    <textarea name="adjustment_reason" rows="4"
                              class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('adjustment_reason') border-red-500 @enderror"
                              placeholder="Explain why this adjustment is needed (required for audit trail)"
                              required>{{ old('adjustment_reason') }}</textarea>
                    @error('adjustment_reason')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Settlement Reference (Optional)</label>
                    <input type="text" name="settlement_reference" value="{{ old('settlement_reference') }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('settlement_reference') border-red-500 @enderror"
                           placeholder="Reference number, ticket ID, etc.">
                    @error('settlement_reference')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-end pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.commissions.partners.show', $ledger->partner_id) }}" class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Create Adjustment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Example Scenarios -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#021c47]">Common Adjustment Scenarios</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <h4 class="font-semibold text-yellow-800 mb-2">Scenario 1: Transaction Refunded</h4>
                    <p class="text-sm text-yellow-700">
                        Partner processed a Rs 10,000 transaction (Rs 300 commission). Customer got refund.<br>
                        <strong>Action:</strong> Amount: <code class="bg-yellow-100 px-1 rounded">-300</code>, Type: <code class="bg-yellow-100 px-1 rounded">Refund</code>
                    </p>
                </div>
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                    <h4 class="font-semibold text-blue-800 mb-2">Scenario 2: Calculation Error</h4>
                    <p class="text-sm text-blue-700">
                        System calculated Rs 5,000 but should be Rs 5,500.<br>
                        <strong>Action:</strong> Amount: <code class="bg-blue-100 px-1 rounded">500</code>, Type: <code class="bg-blue-100 px-1 rounded">Correction</code>
                    </p>
                </div>
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                    <h4 class="font-semibold text-red-800 mb-2">Scenario 3: Late Payment Penalty</h4>
                    <p class="text-sm text-red-700">
                        Partner delayed payment by 60 days. Rs 1,000 late fee.<br>
                        <strong>Action:</strong> Amount: <code class="bg-red-100 px-1 rounded">1000</code>, Type: <code class="bg-red-100 px-1 rounded">Penalty</code>
                    </p>
                </div>
                <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                    <h4 class="font-semibold text-green-800 mb-2">Scenario 4: Early Payment Bonus</h4>
                    <p class="text-sm text-green-700">
                        Partner paid within 7 days. Rs 500 discount.<br>
                        <strong>Action:</strong> Amount: <code class="bg-green-100 px-1 rounded">-500</code>, Type: <code class="bg-green-100 px-1 rounded">Bonus</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
