@extends('layouts.admin')

@section('title', 'Settlement Proof Gallery - BixCash Admin')
@section('page-title', 'Settlement Proof Gallery')

@section('content')
    <!-- Header -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-[#021c47] mb-1">Proof of Payment Gallery</h2>
                    <p class="text-sm text-gray-500">View all uploaded settlement proof documents</p>
                </div>
                <a href="{{ route('admin.commissions.settlements.history') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    ← Back to History
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.commissions.settlements.proof-gallery') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Partner</label>
                    <select name="partner_id" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="">All Partners</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }} - {{ $partner->partnerProfile->business_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#021c47] text-white py-2 px-4 rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Filter
                    </button>
                </div>
            </form>

            @if(request()->hasAny(['partner_id', 'from_date', 'to_date']))
                <div class="mt-4">
                    <a href="{{ route('admin.commissions.settlements.proof-gallery') }}" class="text-sm text-[#021c47] hover:text-[#93db4d] font-medium">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-6">
            @if($settlements->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($settlements as $settlement)
                        @php
                            $proofPath = Storage::url($settlement->proof_of_payment);
                            $extension = strtolower(pathinfo($settlement->proof_of_payment, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            $isPdf = $extension === 'pdf';
                        @endphp

                        <div class="border-2 border-gray-200 rounded-xl overflow-hidden hover:border-[#93db4d] transition-colors">
                            <!-- Preview Area -->
                            <div class="bg-gray-50 h-48 flex items-center justify-center overflow-hidden cursor-pointer" onclick="openProofModal('{{ $proofPath }}', '{{ $isImage ? 'image' : ($isPdf ? 'pdf' : 'other') }}')">
                                @if($isImage)
                                    <img src="{{ $proofPath }}" alt="Proof" class="max-w-full max-h-full object-cover">
                                @elseif($isPdf)
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-red-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="mt-2 text-gray-600 font-medium">PDF Document</p>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="mt-2 text-gray-600 font-medium">{{ strtoupper($extension) }} File</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="p-4">
                                <div class="mb-3">
                                    <p class="font-semibold text-gray-900">{{ $settlement->partner->partnerProfile->business_name ?? $settlement->partner->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $settlement->partner->name }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                                    <div>
                                        <p class="text-gray-500 text-xs">Amount</p>
                                        <p class="font-semibold text-[#93db4d]">Rs {{ number_format($settlement->amount_settled, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-xs">Period</p>
                                        <p class="font-semibold text-gray-900">{{ $settlement->ledger->formatted_period }}</p>
                                    </div>
                                </div>

                                <div class="text-sm mb-3">
                                    <p class="text-gray-500 text-xs">Method</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $settlement->formatted_payment_method }}</span>
                                </div>

                                @if($settlement->settlement_reference)
                                    <div class="text-sm mb-3">
                                        <p class="text-gray-500 text-xs">Reference</p>
                                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $settlement->settlement_reference }}</code>
                                    </div>
                                @endif

                                <div class="text-xs text-gray-500 mb-4">
                                    <p>Processed: {{ $settlement->processed_at->format('M d, Y h:i A') }}</p>
                                    <p>By: {{ $settlement->processedByUser->name ?? 'N/A' }}</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <a href="{{ $proofPath }}" target="_blank" class="flex-1 text-center py-2 px-3 border border-[#021c47] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                        View
                                    </a>
                                    <a href="{{ $proofPath }}" download class="flex-1 text-center py-2 px-3 border border-gray-200 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $settlements->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Proof Documents Found</h3>
                    <p class="text-gray-500">Settlements with uploaded proof of payment will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Proof Modal -->
    <div id="proofModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl w-full max-w-4xl max-h-[90vh] overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-[#021c47]">Proof of Payment</h3>
                <button type="button" onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 max-h-[70vh] overflow-auto bg-gray-50" id="proofContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <a id="downloadProof" href="#" download class="px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                    Download
                </a>
                <button type="button" onclick="closeProofModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function openProofModal(url, type) {
            const content = document.getElementById('proofContent');
            const downloadBtn = document.getElementById('downloadProof');

            downloadBtn.href = url;

            if (type === 'image') {
                content.innerHTML = '<img src="' + url + '" class="max-w-full h-auto rounded-lg mx-auto shadow-lg">';
            } else if (type === 'pdf') {
                content.innerHTML = '<iframe src="' + url + '" class="w-full h-[60vh] border-0 rounded-lg"></iframe>';
            } else {
                content.innerHTML = '<div class="text-center py-8"><p class="text-gray-600">Preview not available. Click download to view the file.</p></div>';
            }

            document.getElementById('proofModal').classList.remove('hidden');
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
        }

        document.getElementById('proofModal').addEventListener('click', function(e) {
            if (e.target === this) closeProofModal();
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('proofModal').classList.contains('hidden')) {
                closeProofModal();
            }
        });
    </script>
@endsection
