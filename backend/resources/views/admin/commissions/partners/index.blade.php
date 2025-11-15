@extends('layouts.admin')

@section('title', 'Partner Commissions - BixCash Admin')
@section('page-title', 'Partner Commissions')

@section('content')
    <!-- Filters -->
    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="card-body">
            <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Partner name or business..." 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Filter</label>
                    <select name="outstanding_only" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="">All Partners</option>
                        <option value="1" {{ request('outstanding_only') ? 'selected' : '' }}>With Outstanding Only</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Sort By</label>
                    <select name="sort" style="width: 100%; padding: 0.5rem; border: 1px solid #dee2e6; border-radius: 4px;">
                        <option value="total_outstanding" {{ request('sort') == 'total_outstanding' ? 'selected' : '' }}>Outstanding (High to Low)</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">🔍 Filter</button>
                    <a href="{{ route('admin.commissions.partners.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Partners Table -->
    <div class="card">
        <div class="card-header" style="background: white; border-bottom: 2px solid #f8f9fa; padding: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0;">👥 All Partners with Commissions</h5>
                <a href="{{ route('admin.commissions.export.ledgers', request()->query()) }}"
                   class="btn btn-success btn-sm"
                   style="padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem;"
                   title="Export with current filters: {{ request()->hasAny(['search', 'outstanding_only', 'sort']) ? 'Filtered' : 'All partners' }}"
                   onclick="return confirm('Export commission ledgers to Excel?\n\nCurrent filters and sorting will be applied to the export.');">
                    📊 Export Ledgers
                    @if(request()->hasAny(['search', 'outstanding_only']))
                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">Filtered</span>
                    @endif
                </a>
            </div>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th style="padding: 0.75rem;">Partner</th>
                            <th style="padding: 0.75rem;">Business Name</th>
                            <th style="padding: 0.75rem;">Commission Rate</th>
                            <th style="padding: 0.75rem;">Total Ledgers</th>
                            <th style="padding: 0.75rem;">Pending Ledgers</th>
                            <th style="padding: 0.75rem;">Outstanding Amount</th>
                            <th style="padding: 0.75rem;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $partner)
                            @php
                                $profile = $partner->partnerProfile;
                            @endphp
                            <tr>
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $profile->business_name ?? $partner->name }}</strong><br>
                                    <small style="color: #6c757d;">{{ $partner->name }}</small>
                                </td>
                                <td style="padding: 0.75rem;">{{ $partner->phone }}</td>
                                <td style="padding: 0.75rem;">
                                    <span class="badge bg-info">{{ number_format($profile->commission_rate ?? 0, 2) }}%</span>
                                </td>
                                <td style="padding: 0.75rem;">{{ $partner->total_ledgers }}</td>
                                <td style="padding: 0.75rem;">
                                    @if($partner->pending_ledgers > 0)
                                        <span class="badge bg-danger">{{ $partner->pending_ledgers }}</span>
                                    @else
                                        <span style="color: #6c757d;">0</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if($partner->total_outstanding > 0)
                                        <strong style="color: #f5576c;">Rs {{ number_format($partner->total_outstanding, 2) }}</strong>
                                    @else
                                        <span style="color: #6c757d;">-</span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem;">
                                    <a href="{{ route('admin.commissions.partners.show', $partner->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 3rem; text-align: center; color: #6c757d;">
                                    No partners found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($partners->hasPages())
            <div class="card-footer" style="background: white; padding: 1rem;">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
@endsection
