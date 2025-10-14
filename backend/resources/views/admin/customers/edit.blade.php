@extends('layouts.admin')

@section('title', 'Edit Customer - BixCash Admin')
@section('page-title', 'Edit Customer')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.customers.show', $customer) }}" class="btn" style="background: #6c757d; color: white;">
            ← Back to Customer Details
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Customer Information</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #3498db; color: #3498db;">
                        Basic Information
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Name <span style="color: red;">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}"
                                   required class="form-control @error('name') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('name')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('email')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group">
                            <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Phone Number <span style="color: red;">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}"
                                   required class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="+923001234567"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            <small style="color: #666; font-size: 0.875rem;">Format: +92XXXXXXXXXX</small>
                            @error('phone')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="is_active" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Account Status <span style="color: red;">*</span>
                            </label>
                            <select id="is_active" name="is_active" required
                                    class="form-control @error('is_active') is-invalid @enderror"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="1" {{ old('is_active', $customer->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $customer->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('is_active')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Profile Information Section -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #3498db; color: #3498db;">
                        Profile Information
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Full Name -->
                        <div class="form-group">
                            <label for="full_name" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Full Name
                            </label>
                            <input type="text" id="full_name" name="full_name"
                                   value="{{ old('full_name', $customer->customerProfile->full_name ?? '') }}"
                                   class="form-control @error('full_name') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('full_name')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-group">
                            <label for="date_of_birth" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Date of Birth
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth"
                                   value="{{ old('date_of_birth', $customer->customerProfile->date_of_birth ?? '') }}"
                                   class="form-control @error('date_of_birth') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('date_of_birth')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="form-group">
                            <label for="gender" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Gender
                            </label>
                            <select id="gender" name="gender"
                                    class="form-control @error('gender') is-invalid @enderror"
                                    style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $customer->customerProfile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $customer->customerProfile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $customer->customerProfile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- CNIC -->
                        <div class="form-group">
                            <label for="cnic" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                CNIC
                            </label>
                            <input type="text" id="cnic" name="cnic"
                                   value="{{ old('cnic', $customer->customerProfile->cnic ?? '') }}"
                                   class="form-control @error('cnic') is-invalid @enderror"
                                   placeholder="12345-1234567-1"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            <small style="color: #666; font-size: 0.875rem;">Format: XXXXX-XXXXXXX-X</small>
                            @error('cnic')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div style="margin-bottom: 2rem;">
                    <h4 style="margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #3498db; color: #3498db;">
                        Address Information
                    </h4>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                        <!-- Address -->
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label for="address" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                      class="form-control @error('address') is-invalid @enderror"
                                      style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">{{ old('address', $customer->customerProfile->address ?? '') }}</textarea>
                            @error('address')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="form-group">
                            <label for="city" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                City
                            </label>
                            <input type="text" id="city" name="city"
                                   value="{{ old('city', $customer->customerProfile->city ?? '') }}"
                                   class="form-control @error('city') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('city')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Postal Code -->
                        <div class="form-group">
                            <label for="postal_code" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                                Postal Code
                            </label>
                            <input type="text" id="postal_code" name="postal_code"
                                   value="{{ old('postal_code', $customer->customerProfile->postal_code ?? '') }}"
                                   class="form-control @error('postal_code') is-invalid @enderror"
                                   style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            @error('postal_code')
                                <div style="color: #e74c3c; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid #dee2e6;">
                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn" style="background: #6c757d; color: white;">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
