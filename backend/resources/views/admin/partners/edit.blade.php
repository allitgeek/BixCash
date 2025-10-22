@extends('layouts.admin')

@section('title', 'Edit Partner - BixCash Admin')
@section('page-title', 'Edit Partner Profile')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Partner: {{ $partner->partnerProfile->business_name ?? $partner->name }}</h3>
            <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-secondary">Cancel</a>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #ef4444;">
                <strong>Please fix the following errors:</strong>
                <ul style="list-style: disc; padding-left: 1.25rem; margin-top: 0.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.partners.update', $partner) }}" id="editPartnerForm">
                @csrf
                @method('PUT')

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Business Name -->
                    <div class="form-group">
                        <label for="business_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name" class="form-control" required
                               value="{{ old('business_name', $partner->partnerProfile->business_name ?? '') }}" placeholder="e.g., KFC Lahore">
                    </div>

                    <!-- Contact Person Name -->
                    <div class="form-group">
                        <label for="contact_person_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Contact Person Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" required
                               value="{{ old('contact_person_name', $partner->partnerProfile->contact_person_name ?? $partner->name) }}" placeholder="Full name">
                    </div>

                    <!-- Phone (Read-only) -->
                    <div class="form-group">
                        <label for="phone_display" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Mobile Number
                        </label>
                        <input type="text" id="phone_display" class="form-control" readonly
                               value="{{ $partner->phone }}" style="background: #f7fafc; cursor: not-allowed;">
                        <small style="color: #718096; font-size: 0.75rem;">Phone number cannot be changed</small>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Email (Optional)
                        </label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{ old('email', $partner->email) }}" placeholder="partner@email.com">
                    </div>

                    <!-- Business Type -->
                    <div class="form-group">
                        <label for="business_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Type <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="business_type" name="business_type" class="form-control" required>
                            <option value="">Select business type</option>
                            <option value="Restaurant" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                            <option value="Retail" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Retail' ? 'selected' : '' }}>Retail Store</option>
                            <option value="Cafe" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Cafe' ? 'selected' : '' }}>Cafe</option>
                            <option value="Grocery" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Grocery' ? 'selected' : '' }}>Grocery Store</option>
                            <option value="Fashion" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                            <option value="Electronics" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Salon" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Salon' ? 'selected' : '' }}>Salon & Spa</option>
                            <option value="Pharmacy" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="Services" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Services' ? 'selected' : '' }}>Services</option>
                            <option value="Other" {{ old('business_type', $partner->partnerProfile->business_type ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label for="business_city" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            City (Optional)
                        </label>
                        <input type="text" id="business_city" name="business_city" class="form-control"
                               value="{{ old('business_city', $partner->partnerProfile->business_city ?? '') }}" placeholder="e.g., Lahore">
                    </div>
                </div>

                <!-- Business Address (Full Width) -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="business_address" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        Business Address (Optional)
                    </label>
                    <input type="text" id="business_address" name="business_address" class="form-control"
                           value="{{ old('business_address', $partner->partnerProfile->business_address ?? '') }}" placeholder="Complete business address">
                </div>

                <!-- Logo Information -->
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        @if($partner->partnerProfile && $partner->partnerProfile->logo)
                            <img src="{{ asset('storage/' . $partner->partnerProfile->logo) }}" alt="Current Logo"
                                 style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0; background: white;">
                            <div>
                                <p style="margin: 0; font-weight: 600; color: #2d3748;">Current Logo</p>
                                <p style="margin: 0; font-size: 0.875rem; color: #718096;">
                                    To change or remove the logo, please go back to the partner details page.
                                </p>
                            </div>
                        @else
                            <div style="width: 64px; height: 64px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <svg style="width: 32px; height: 32px; color: #a0aec0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <p style="margin: 0; font-weight: 600; color: #2d3748;">No Logo Uploaded</p>
                                <p style="margin: 0; font-size: 0.875rem; color: #718096;">
                                    To upload a logo, please go back to the partner details page.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('admin.partners.show', $partner) }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Partner Profile</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Form submission handler
    const form = document.getElementById('editPartnerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
        });
    }
</script>
@endpush
