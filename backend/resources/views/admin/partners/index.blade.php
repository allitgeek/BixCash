@extends('layouts.admin')

@section('title', 'Partner Management - BixCash Admin')
@section('page-title', 'Partner Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Partners</h3>
            <div style="display: flex; gap: 1rem; margin-top: 0.5rem; flex-wrap: wrap;">
                <a href="{{ route('admin.partners.create') }}" class="btn btn-success" style="padding: 0.5rem 1rem;">
                    + Create New Partner
                </a>
                <a href="{{ route('admin.partners.pending') }}" class="btn btn-warning" style="padding: 0.5rem 1rem;">
                    View Pending Applications
                    @php
                        $pendingCount = \App\Models\User::whereHas('role', function($q) {
                            $q->where('name', 'partner');
                        })->whereHas('partnerProfile', function($q) {
                            $q->where('status', 'pending');
                        })->count();
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge-notification">{{ $pendingCount }}</span>
                    @endif
                </a>
            </div>
        </div>
        <div class="card-body">

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.partners.index') }}" class="mb-4">
                <div style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, phone, or business name..."
                               style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                        <select id="status" name="status" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; min-width: 150px;">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.partners.index') }}" class="btn" style="background: #6c757d; color: white; margin-left: 0.5rem;">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Statistics -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #1976d2; font-weight: 500;">Total Partners</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #0d47a1;">{{ $partners->total() }}</div>
                </div>
                <div style="flex: 1; min-width: 200px; padding: 1rem; background: #e8f5e9; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #388e3c; font-weight: 500;">Approved Partners</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #1b5e20;">
                        {{ $partners->filter(fn($p) => $p->partnerProfile && $p->partnerProfile->status === 'approved')->count() }}
                    </div>
                </div>
            </div>

            <!-- Partners Table -->
            @if($partners->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Partner</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Business Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Phone</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Active</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Registered</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($partners as $partner)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $partner->name }}</strong>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $partner->partnerProfile->business_name ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $partner->phone }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($partner->partnerProfile)
                                            @if($partner->partnerProfile->status === 'approved')
                                                <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                    Approved
                                                </span>
                                            @elseif($partner->partnerProfile->status === 'pending')
                                                <span style="background: #f39c12; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                    Pending
                                                </span>
                                            @else
                                                <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                    Rejected
                                                </span>
                                            @endif
                                        @else
                                            <span style="color: #999;">-</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($partner->is_active)
                                            <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Active
                                            </span>
                                        @else
                                            <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $partner->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.partners.show', $partner) }}"
                                               class="btn" style="background: #17a2b8; color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                View
                                            </a>
                                            <a href="{{ route('admin.partners.transactions', $partner) }}"
                                               class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Transactions
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $partners->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No partners found</h4>
                    <p>{{ request()->hasAny(['search', 'status']) ? 'Try adjusting your search criteria.' : 'Partners will appear here once they register.' }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Simple pagination styling
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination a, .pagination span');
        paginationLinks.forEach(link => {
            link.style.cssText = 'padding: 0.5rem 0.75rem; margin: 0 0.25rem; border: 1px solid #dee2e6; border-radius: 3px; text-decoration: none; color: #495057;';
            if (link.classList.contains('active')) {
                link.style.cssText += 'background: #3498db; color: white; border-color: #3498db;';
            }
        });
    });
</script>
@endpush
