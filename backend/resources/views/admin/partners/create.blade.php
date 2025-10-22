@extends('layouts.admin')

@section('title', 'Create New Partner - BixCash Admin')
@section('page-title', 'Create New Partner')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add New Partner</h3>
            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Back to Partners</a>
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

            <form method="POST" action="{{ route('admin.partners.store') }}" id="createPartnerForm" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Business Name -->
                    <div class="form-group">
                        <label for="business_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="business_name" name="business_name" class="form-control" required
                               value="{{ old('business_name') }}" placeholder="e.g., KFC Lahore">
                    </div>

                    <!-- Mobile Number -->
                    <div class="form-group">
                        <label for="phone" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Mobile Number <span style="color: #ef4444;">*</span>
                        </label>
                        <div style="display: flex; gap: 0.5rem;">
                            <div style="padding: 0.5rem 1rem; background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 5px; font-weight: 600;">+92</div>
                            <input type="text" id="phone" name="phone" class="form-control" required maxlength="10" pattern="[0-9]{10}"
                                   value="{{ old('phone') }}" placeholder="3001234567" style="flex: 1;">
                        </div>
                        <small style="color: #718096; font-size: 0.75rem;">Enter 10-digit mobile number without +92</small>
                    </div>

                    <!-- Contact Person Name -->
                    <div class="form-group">
                        <label for="contact_person_name" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Contact Person Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="contact_person_name" name="contact_person_name" class="form-control" required
                               value="{{ old('contact_person_name') }}" placeholder="Full name">
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Email (Optional)
                        </label>
                        <input type="email" id="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="partner@email.com">
                    </div>

                    <!-- Business Type -->
                    <div class="form-group">
                        <label for="business_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Type <span style="color: #ef4444;">*</span>
                        </label>
                        <select id="business_type" name="business_type" class="form-control" required>
                            <option value="">Select business type</option>
                            <option value="Restaurant" {{ old('business_type') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                            <option value="Retail" {{ old('business_type') == 'Retail' ? 'selected' : '' }}>Retail Store</option>
                            <option value="Cafe" {{ old('business_type') == 'Cafe' ? 'selected' : '' }}>Cafe</option>
                            <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery Store</option>
                            <option value="Fashion" {{ old('business_type') == 'Fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                            <option value="Electronics" {{ old('business_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="Salon" {{ old('business_type') == 'Salon' ? 'selected' : '' }}>Salon & Spa</option>
                            <option value="Pharmacy" {{ old('business_type') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                            <option value="Services" {{ old('business_type') == 'Services' ? 'selected' : '' }}>Services</option>
                            <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- City -->
                    <div class="form-group">
                        <label for="city" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            City (Optional)
                        </label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="{{ old('city') }}" placeholder="e.g., Lahore">
                    </div>

                    <!-- Logo -->
                    <div class="form-group">
                        <label for="logo" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                            Business Logo (Optional)
                        </label>
                        <input type="file" id="logo" name="logo" class="form-control" accept="image/jpeg,image/jpg,image/png"
                               onchange="previewLogo(event)">
                        <small style="color: #718096; font-size: 0.75rem;">JPG or PNG, max 2MB</small>
                        <div id="logoPreview" style="margin-top: 0.75rem; display: none;">
                            <img id="logoPreviewImg" src="" alt="Logo Preview" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 2px solid #e2e8f0;">
                        </div>
                    </div>
                </div>

                <!-- Business Address (Full Width) -->
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="business_address" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">
                        Business Address (Optional)
                    </label>
                    <input type="text" id="business_address" name="business_address" class="form-control"
                           value="{{ old('business_address') }}" placeholder="Complete business address">
                </div>

                <!-- Auto-Approve Option -->
                <div class="form-group" style="margin-bottom: 1.5rem; padding: 1rem; background: #e3f2fd; border-radius: 8px;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                        <input type="checkbox" name="auto_approve" value="1" {{ old('auto_approve', '1') ? 'checked' : '' }}
                               style="width: 18px; height: 18px;">
                        <span style="font-weight: 600; color: #1976d2;">
                            Auto-approve this partner (Partner will be immediately active)
                        </span>
                    </label>
                    <small style="color: #718096; margin-left: 2rem; display: block; margin-top: 0.25rem;">
                        If unchecked, partner will be created with "Pending" status and require manual approval.
                    </small>
                </div>

                <!-- Submit Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Partner</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Phone number validation
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
        });
    }

    // Logo preview function
    function previewLogo(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            event.target.value = '';
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Only JPG and PNG images are allowed');
            event.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewDiv = document.getElementById('logoPreview');
            const previewImg = document.getElementById('logoPreviewImg');
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Form submission handler
    const form = document.getElementById('createPartnerForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.textContent = 'Creating...';
            submitBtn.disabled = true;
        });
    }
</script>
@endpush
