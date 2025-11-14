@extends('layouts.admin')

@section('title', 'Commissions Dashboard - BixCash Admin')
@section('page-title', 'Commissions Dashboard')

@section('content')
    <!-- Quick Stats -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">💰 Total Outstanding</h5>
                <h2 style="margin-bottom: 0;">Rs {{ number_format($totalOutstanding, 2) }}</h2>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">📊 This Month</h5>
                <h2 style="margin-bottom: 0;">Rs {{ number_format($thisMonthCommission, 2) }}</h2>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">⏳ Pending Settlements</h5>
                <h2 style="margin-bottom: 0;">{{ number_format($pendingCount) }}</h2>
            </div>
        </div>

        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
            <div class="card-body">
                <h5 style="margin-bottom: 0.5rem; opacity: 0.9;">✅ Total Settled</h5>
                <h2 style="margin-bottom: 0;">Rs {{ number_format($totalSettled, 2) }}</h2>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Recent Batches -->
        <div class="card">
            <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h5 style="margin: 0;">📦 Recent Batches</h5>
                    <a href="{{ route('admin.commissions.batches.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table" style="margin: 0;">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th style="padding: 0.75rem;">Period</th>
                                <th style="padding: 0.75rem;">Partners</th>
                                <th style="padding: 0.75rem;">Transactions</th>
                                <th style="padding: 0.75rem;">Commission</th>
                                <th style="padding: 0.75rem;">Status</th>
                                <th style="padding: 0.75rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBatches as $batch)
                                <tr>
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $batch->formatted_period }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">{{ $batch->total_partners }}</td>
                                    <td style="padding: 0.75rem;">{{ number_format($batch->total_transactions) }}</td>
                                    <td style="padding: 0.75rem;">
                                        <strong>Rs {{ number_format($batch->total_commission_calculated, 2) }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($batch->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($batch->status === 'processing')
                                            <span class="badge bg-warning">Processing</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($batch->status) }}</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <a href="{{ route('admin.commissions.batches.show', $batch->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 2rem; text-align: center; color: #6c757d;">
                                        No batches calculated yet
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
                <h5 style="margin: 0;">⚡ Quick Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.commissions.calculate') }}" method="POST" style="margin-bottom: 1rem;">
                    @csrf
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Calculate Commissions</label>
                    <input type="month" name="period" value="{{ date('Y-m', strtotime('-1 month')) }}" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 0.5rem;" required>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        📊 Calculate Now
                    </button>
                </form>

                <hr style="margin: 1.5rem 0;">

                <a href="{{ route('admin.commissions.partners.index') }}" class="btn btn-outline-secondary" style="width: 100%; margin-bottom: 0.5rem;">
                    👥 View All Partners
                </a>
                <a href="{{ route('admin.commissions.settlements.history') }}" class="btn btn-outline-secondary" style="width: 100%;">
                    📜 Settlement History
                </a>
            </div>
        </div>
    </div>

    <!-- Top Outstanding Partners -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <h5 style="margin: 0;">🔝 Top Outstanding Partners</h5>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Business Name</th>
                            <th style="padding: 0.75rem;">Outstanding Amount</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topOutstandingPartners as $item)
                            @php
                                $partner = $item->partner;
                                $profile = $partner->partnerProfile;
                            @endphp
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $partner->phone }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $profile->business_name ?? 'N/A' }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: #f5576c;">Rs {{ number_format($item->total_outstanding, 2) }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.partners.show', $partner->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 2rem; text-align: center; color: #6c757d;">
                                    No outstanding commissions
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Settlements -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">💵 Recent Settlements</h5>
                <a href="{{ route('admin.commissions.settlements.history') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Date</th>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Period</th>
                            <th style="padding: 0.75rem;">Amount</th>
                            <th style="padding: 0.75rem;">Method</th>
                            <th style="padding: 0.75rem;">Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSettlements as $settlement)
                            <tr>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processed_at->format('M d, Y') }}<br>
                                    <small style="color: #6c757d;">{{ $settlement->processed_at->format('h:i A') }}</small>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $settlement->partner->name }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->ledger->formatted_period }}
                                </td>
                                <td style="padding: 0.75rem;">
                                    <strong style="color: #00f2fe;">Rs {{ number_format($settlement->amount_settled, 2) }}</strong>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-info">{{ $settlement->formatted_payment_method }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    {{ $settlement->processedByUser->name ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2rem; text-align: center; color: #6c757d;">
                                    No settlements yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
