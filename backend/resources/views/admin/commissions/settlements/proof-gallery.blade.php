@extends('layouts.admin')

@section('title', 'Settlement Proof Gallery - BixCash Admin')
@section('page-title', 'Settlement Proof Gallery')

@section('content')
    <!-- Header -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <div>
                    <h5 style="margin: 0 0 0.25rem 0;">🖼️ Proof of Payment Gallery</h5>
                    <p style="margin: 0; color: #6c757d; font-size: 0.875rem;">
                        View all uploaded settlement proof documents
                    </p>
                </div>
                <a href="{{ route('admin.commissions.settlements.history') }}" class="btn btn-outline-secondary btn-sm">
                    ← Back to History
                </a>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('admin.commissions.settlements.proof-gallery') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Partner</label>
                    <select name="partner_id" class="form-select form-select-sm">
                        <option value="">All Partners</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ request('partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }} - {{ $partner->partnerProfile->business_name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                </div>

                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">🔍 Filter</button>
                </div>
            </form>

            @if(request()->hasAny(['partner_id', 'from_date', 'to_date']))
                <div style="margin-top: 1rem;">
                    <a href="{{ route('admin.commissions.settlements.proof-gallery') }}" class="btn btn-link btn-sm">
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="card">
        <div class="card-body">
            @if($settlements->count() > 0)
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                    @foreach($settlements as $settlement)
                        @php
                            $proofPath = Storage::url($settlement->proof_of_payment);
                            $extension = strtolower(pathinfo($settlement->proof_of_payment, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                            $isPdf = $extension === 'pdf';
                        @endphp

                        <div class="card" style="border: 2px solid #e9ecef; overflow: hidden;">
                            <!-- Preview Area -->
                            <div style="background: #f8f9fa; height: 200px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden;">
                                @if($isImage)
                                    <img src="{{ $proofPath }}" alt="Proof" style="max-width: 100%; max-height: 100%; object-fit: cover; cursor: pointer;" onclick="openProofModal('{{ $proofPath }}', 'image')">
                                @elseif($isPdf)
                                    <div style="text-align: center; cursor: pointer;" onclick="openProofModal('{{ $proofPath }}', 'pdf')">
                                        <div style="font-size: 4rem; color: #dc3545;">📄</div>
                                        <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-weight: 500;">PDF Document</p>
                                    </div>
                                @else
                                    <div style="text-align: center;">
                                        <div style="font-size: 4rem; color: #6c757d;">📎</div>
                                        <p style="margin: 0.5rem 0 0 0; color: #6c757d; font-weight: 500;">{{ strtoupper($extension) }} File</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="card-body" style="padding: 1rem;">
                                <div style="margin-bottom: 0.75rem;">
                                    <strong>{{ $settlement->partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $settlement->partner->partnerProfile->business_name ?? 'N/A' }}</small>
                                </div>

                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <div>
                                        <small style="color: #6c757d; display: block;">Amount</small>
                                        <strong style="color: #00f2fe;">Rs {{ number_format($settlement->amount_settled, 2) }}</strong>
                                    </div>
                                    <div>
                                        <small style="color: #6c757d; display: block;">Period</small>
                                        <strong>{{ $settlement->ledger->formatted_period }}</strong>
                                    </div>
                                </div>

                                <div style="font-size: 0.875rem; margin-bottom: 0.75rem;">
                                    <small style="color: #6c757d; display: block;">Method</small>
                                    <span class="badge bg-info">{{ $settlement->formatted_payment_method }}</span>
                                </div>

                                @if($settlement->settlement_reference)
                                    <div style="font-size: 0.875rem; margin-bottom: 0.75rem;">
                                        <small style="color: #6c757d; display: block;">Reference</small>
                                        <code style="font-size: 0.75rem;">{{ $settlement->settlement_reference }}</code>
                                    </div>
                                @endif

                                <div style="font-size: 0.75rem; color: #6c757d; margin-bottom: 0.75rem;">
                                    <div>Processed: {{ $settlement->processed_at->format('M d, Y h:i A') }}</div>
                                    <div>By: {{ $settlement->processedByUser->name ?? 'N/A' }}</div>
                                </div>

                                <!-- Actions -->
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ $proofPath }}" target="_blank" class="btn btn-sm btn-outline-primary" style="flex: 1;">
                                        👁️ View
                                    </a>
                                    <a href="{{ $proofPath }}" download class="btn btn-sm btn-outline-secondary" style="flex: 1;">
                                        ⬇️ Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div style="margin-top: 2rem;">
                    {{ $settlements->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #6c757d;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📭</div>
                    <h5>No Proof Documents Found</h5>
                    <p>Settlements with uploaded proof of payment will appear here.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Proof Modal -->
    <div class="modal fade" id="proofModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proof of Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="max-height: 80vh; overflow: auto; text-align: center; background: #f8f9fa;">
                    <div id="proofContent"></div>
                </div>
                <div class="modal-footer">
                    <a id="downloadProof" href="#" download class="btn btn-primary">⬇️ Download</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openProofModal(url, type) {
            const modal = new bootstrap.Modal(document.getElementById('proofModal'));
            const content = document.getElementById('proofContent');
            const downloadBtn = document.getElementById('downloadProof');

            downloadBtn.href = url;

            if (type === 'image') {
                content.innerHTML = '<img src="' + url + '" style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">';
            } else if (type === 'pdf') {
                content.innerHTML = '<iframe src="' + url + '" style="width: 100%; height: 70vh; border: none; border-radius: 8px;"></iframe>';
            }

            modal.show();
        }
    </script>
@endsection
