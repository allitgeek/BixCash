@extends('layouts.admin')

@section('title', 'Customer Details - BixCash Admin')
@section('page-title', 'Customer Details')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.index') }}" class="btn" style="background: #6c757d; color: white;">
            ← Back to Customers
        </a>
    </div>

    <!-- Customer Basic Info Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Customer Information</h3>
            <div>
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                    Edit Customer
                </a>
                <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Customer</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <!-- Left Column -->
                <div>
                    <div class="info-row">
                        <strong>Customer ID:</strong>
                        <span>#{{ $customer->id }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Name:</strong>
                        <span>{{ $customer->name }}</span>
                    </div>
                    @if($customer->customerProfile && $customer->customerProfile->full_name)
                        <div class="info-row">
                            <strong>Full Name:</strong>
                            <span>{{ $customer->customerProfile->full_name }}</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <strong>Email:</strong>
                        <span>{{ $customer->email ?? 'Not provided' }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Phone:</strong>
                        <span>
                            {{ $customer->phone }}
                            @if($customer->hasVerifiedPhone())
                                <span style="background: #27ae60; color: white; padding: 0.15rem 0.4rem; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">
                                    Verified
                                </span>
                            @else
                                <span style="background: #e74c3c; color: white; padding: 0.15rem 0.4rem; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">
                                    Unverified
                                </span>
                            @endif
                        </span>
                    </div>
                    @if($customer->phone_verified_at)
                        <div class="info-row">
                            <strong>Phone Verified At:</strong>
                            <span>{{ $customer->phone_verified_at->format('M j, Y g:i A') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div>
                    <div class="info-row">
                        <strong>Status:</strong>
                        <span>
                            @if($customer->is_active)
                                <span style="background: #27ae60; color: white; padding: 0.25rem 0.75rem; border-radius: 5px;">
                                    Active
                                </span>
                            @else
                                <span style="background: #e74c3c; color: white; padding: 0.25rem 0.75rem; border-radius: 5px;">
                                    Inactive
                                </span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <strong>PIN Status:</strong>
                        <span>
                            @if($customer->pin_hash)
                                @if($customer->isPinLocked())
                                    <span style="background: #e67e22; color: white; padding: 0.25rem 0.75rem; border-radius: 5px;">
                                        Locked ({{ $customer->getPinLockoutTimeRemaining() }} min remaining)
                                    </span>
                                @else
                                    <span style="background: #27ae60; color: white; padding: 0.25rem 0.75rem; border-radius: 5px;">
                                        Set
                                    </span>
                                @endif
                            @else
                                <span style="color: #999;">Not Set</span>
                            @endif
                        </span>
                    </div>
                    @if($customer->pin_hash)
                        <div class="info-row">
                            <strong>PIN Attempts:</strong>
                            <span>{{ $customer->pin_attempts }}/5</span>
                        </div>
                    @endif
                    <div class="info-row">
                        <strong>Registered:</strong>
                        <span>{{ $customer->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div class="info-row">
                        <strong>Last Login:</strong>
                        <span>{{ $customer->last_login_at ? $customer->last_login_at->format('M j, Y g:i A') : 'Never' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($customer->pin_hash)
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #dee2e6;">
                    <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">PIN Management</h4>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        @if($customer->isPinLocked())
                            <form method="POST" action="{{ route('admin.customers.unlock-pin', $customer) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Unlock PIN for this customer?')">
                                    Unlock PIN
                                </button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.customers.reset-pin', $customer) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn" style="background: #e74c3c; color: white;" onclick="return confirm('Reset PIN? Customer will need to set a new PIN.')">
                                Reset PIN
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Status Toggle -->
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #dee2e6;">
                <h4 style="margin-bottom: 1rem; font-size: 1.1rem;">Account Status</h4>
                <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn {{ $customer->is_active ? 'btn-warning' : 'btn-success' }}" onclick="return confirm('{{ $customer->is_active ? 'Deactivate' : 'Activate' }} this customer account?')">
                        {{ $customer->is_active ? 'Deactivate Account' : 'Activate Account' }}
                    </button>
                </form>
                <p style="margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                    {{ $customer->is_active ? 'Deactivating will prevent the customer from logging in.' : 'Activating will allow the customer to log in again.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Customer Profile Card -->
    @if($customer->customerProfile)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profile Details</h3>
            </div>
            <div class="card-body">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                    <div>
                        @if($customer->customerProfile->date_of_birth)
                            <div class="info-row">
                                <strong>Date of Birth:</strong>
                                <span>{{ \Carbon\Carbon::parse($customer->customerProfile->date_of_birth)->format('M j, Y') }}</span>
                            </div>
                        @endif
                        @if($customer->customerProfile->gender)
                            <div class="info-row">
                                <strong>Gender:</strong>
                                <span>{{ ucfirst($customer->customerProfile->gender) }}</span>
                            </div>
                        @endif
                        @if($customer->customerProfile->cnic)
                            <div class="info-row">
                                <strong>CNIC:</strong>
                                <span>
                                    {{ $customer->customerProfile->cnic }}
                                    @if($customer->customerProfile->cnic_verified)
                                        <span style="background: #27ae60; color: white; padding: 0.15rem 0.4rem; border-radius: 3px; font-size: 0.75rem; margin-left: 0.5rem;">
                                            Verified
                                        </span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>
                        @if($customer->customerProfile->address)
                            <div class="info-row">
                                <strong>Address:</strong>
                                <span>{{ $customer->customerProfile->address }}</span>
                            </div>
                        @endif
                        @if($customer->customerProfile->city)
                            <div class="info-row">
                                <strong>City:</strong>
                                <span>{{ $customer->customerProfile->city }}</span>
                            </div>
                        @endif
                        @if($customer->customerProfile->postal_code)
                            <div class="info-row">
                                <strong>Postal Code:</strong>
                                <span>{{ $customer->customerProfile->postal_code }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!$customer->customerProfile->date_of_birth && !$customer->customerProfile->gender && !$customer->customerProfile->address && !$customer->customerProfile->city && !$customer->customerProfile->cnic)
                    <p style="color: #999; text-align: center; padding: 2rem;">
                        Customer has not completed their profile yet.
                    </p>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee;
    }
    .info-row strong {
        color: #555;
        min-width: 140px;
    }
    .info-row span {
        color: #333;
        text-align: right;
        flex: 1;
    }
</style>
@endpush
