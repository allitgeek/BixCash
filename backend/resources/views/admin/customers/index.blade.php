@extends('layouts.admin')

@section('title', 'Customer Management - BixCash Admin')
@section('page-title', 'Customer Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Customers</h3>
            <div style="color: #666; font-size: 0.9rem; margin-top: 0.25rem;">
                View and manage customer accounts registered via mobile app
            </div>
        </div>
        <div class="card-body">

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-4">
                <div style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 250px;">
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, email, or phone..."
                               style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                        <select id="status" name="status" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; min-width: 120px;">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="phone_verified" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Phone</label>
                        <select id="phone_verified" name="phone_verified" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px; min-width: 120px;">
                            <option value="">All</option>
                            <option value="1" {{ request('phone_verified') === '1' ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ request('phone_verified') === '0' ? 'selected' : '' }}>Unverified</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request()->hasAny(['search', 'status', 'phone_verified']))
                            <a href="{{ route('admin.customers.index') }}" class="btn" style="background: #6c757d; color: white; margin-left: 0.5rem;">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Statistics -->
            <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #1976d2; font-weight: 500;">Total Customers</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #0d47a1;">{{ $customers->total() }}</div>
                </div>
                <div style="flex: 1; min-width: 200px; padding: 1rem; background: #e8f5e9; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #388e3c; font-weight: 500;">Active Customers</div>
                    <div style="font-size: 1.8rem; font-weight: 600; color: #1b5e20;">
                        {{ $customers->where('is_active', true)->count() }}
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            @if($customers->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Customer</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Phone</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Email</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">PIN</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Registered</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <div>
                                            <strong>{{ $customer->name }}</strong>
                                            @if($customer->customerProfile && $customer->customerProfile->full_name)
                                                <br>
                                                <small style="color: #666;">{{ $customer->customerProfile->full_name }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <div>
                                            {{ $customer->phone }}
                                            @php
                                                $profile = $customer->customerProfile;
                                                $phoneVerified = $customer->hasVerifiedPhone();
                                                $manuallyVerified = $profile && $profile->is_verified;
                                            @endphp

                                            @if($phoneVerified && $manuallyVerified)
                                                {{-- Fully verified (green) --}}
                                                <span style="background: #27ae60; color: white; padding: 0.15rem 0.3rem; border-radius: 3px; font-size: 0.7rem; margin-left: 0.25rem;">
                                                    ✓ Verified
                                                </span>
                                            @elseif($phoneVerified && !$manuallyVerified)
                                                {{-- Ufone bypass - needs manual verification (orange) --}}
                                                <form method="POST" action="{{ route('admin.customers.verify-phone', $customer) }}" style="display: inline;" onsubmit="return confirm('Have you called this customer to confirm their identity?');">
                                                    @csrf
                                                    <button type="submit" style="background: #f39c12; color: white; padding: 0.15rem 0.3rem; border-radius: 3px; font-size: 0.7rem; margin-left: 0.25rem; border: none; cursor: pointer;">
                                                        ⚠ Verify Phone
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Not verified at all (red) --}}
                                                <span style="background: #e74c3c; color: white; padding: 0.15rem 0.3rem; border-radius: 3px; font-size: 0.7rem; margin-left: 0.25rem;">
                                                    ✗ Unverified
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $customer->email ?? '-' }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($customer->is_active)
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
                                        @if($customer->pin_hash)
                                            @if($customer->isPinLocked())
                                                <span style="background: #e67e22; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem;">
                                                    Locked
                                                </span>
                                            @else
                                                <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.75rem;">
                                                    Set
                                                </span>
                                            @endif
                                        @else
                                            <span style="color: #999; font-size: 0.8rem;">Not Set</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $customer->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ route('admin.customers.show', $customer) }}"
                                               class="btn" style="background: #17a2b8; color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                View
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}"
                                               class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <a href="{{ route('admin.customers.transactions', $customer) }}"
                                               class="btn btn-info" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Transactions
                                            </a>
                                            <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn {{ $customer->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                    {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $customers->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No customers found</h4>
                    <p>{{ request()->hasAny(['search', 'status', 'phone_verified']) ? 'Try adjusting your search criteria.' : 'Customers will appear here once they register via the mobile app.' }}</p>
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
