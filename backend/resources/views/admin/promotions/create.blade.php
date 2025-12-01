@extends('layouts.admin')

@section('title', 'Create Promotion - BixCash Admin')
@section('page-title', 'Create Promotion')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Promotion</h3>
            <div>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                    Back to Promotions
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.promotions.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Brand Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="brand_name" class="form-label">Brand Name *</label>
                            <input type="text"
                                   class="form-control @error('brand_name') is-invalid @enderror"
                                   id="brand_name"
                                   name="brand_name"
                                   value="{{ old('brand_name') }}"
                                   required
                                   placeholder="e.g., Nike, KFC, Sapphire">
                            @error('brand_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order" class="form-label">Display Order *</label>
                            <input type="number"
                                   class="form-control @error('order') is-invalid @enderror"
                                   id="order"
                                   name="order"
                                   value="{{ old('order', 1) }}"
                                   min="0"
                                   required>
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Lower numbers appear first</small>
                        </div>
                    </div>
                </div>

                <!-- Logo Upload Section -->
                <div class="form-group">
                    <label class="form-label">Brand Logo</label>

                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; border: 1px solid #dee2e6; margin-bottom: 1rem;">
                        <div style="background: #e3f2fd; padding: 1rem; border-radius: 6px; border-left: 4px solid #2196f3; margin-bottom: 1rem;">
                            <h6 style="margin: 0 0 0.5rem 0; color: #1976d2; font-weight: 600;">📋 Logo Guidelines</h6>
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.9rem; color: #424242;">
                                <li><strong>Dimensions:</strong> 150x80 pixels (recommended)</li>
                                <li><strong>Format:</strong> PNG (recommended), JPG, SVG, WebP</li>
                                <li><strong>File Size:</strong> Maximum 2MB, optimal 10-50KB</li>
                                <li><strong>Background:</strong> Transparent or white background preferred</li>
                                <li><strong>Quality:</strong> High resolution for crisp display</li>
                            </ul>
                        </div>

                        <!-- File Upload -->
                        <div class="row">
                            <div class="col-md-6">
                                <label for="logo_file" class="form-label">Upload Logo File</label>
                                <input type="file"
                                       class="form-control @error('logo_file') is-invalid @enderror"
                                       id="logo_file"
                                       name="logo_file"
                                       accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp"
                                       onchange="previewImage(this, 'file-preview')">
                                @error('logo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="logo_path" class="form-label">Or Enter Logo URL</label>
                                <input type="url"
                                       class="form-control @error('logo_path') is-invalid @enderror"
                                       id="logo_path"
                                       name="logo_path"
                                       value="{{ old('logo_path') }}"
                                       placeholder="https://example.com/logo.png"
                                       onchange="previewImage(this, 'url-preview')">
                                @error('logo_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview Areas -->
                        <div class="row" style="margin-top: 1rem;">
                            <div class="col-md-6">
                                <div id="file-preview" style="text-align: center; min-height: 100px; border: 2px dashed #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #999;">File preview will appear here</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="url-preview" style="text-align: center; min-height: 100px; border: 2px dashed #ddd; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <span style="color: #999;">URL preview will appear here</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Discount Configuration -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label">Discount Type *</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_upto" value="upto" {{ old('discount_type', 'upto') === 'upto' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="discount_upto">
                                        <span style="background: #3498db; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">UPTO</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_flat" value="flat" {{ old('discount_type') === 'flat' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="discount_flat">
                                        <span style="background: #e67e22; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">FLAT</span>
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="discount_type" id="discount_coming_soon" value="coming_soon" {{ old('discount_type') === 'coming_soon' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="discount_coming_soon">
                                        <span style="background: #9b59b6; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">COMING SOON</span>
                                    </label>
                                </div>
                            </div>
                            @error('discount_type')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6" id="discount-value-group">
                        <div class="form-group">
                            <label for="discount_value" class="form-label">Discount Percentage <span id="discount-value-required">*</span></label>
                            <div class="input-group">
                                <input type="number"
                                       class="form-control @error('discount_value') is-invalid @enderror"
                                       id="discount_value"
                                       name="discount_value"
                                       value="{{ old('discount_value') }}"
                                       min="1"
                                       max="100"
                                       placeholder="20">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('discount_value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6" id="discount-text-group">
                        <div class="form-group">
                            <label for="discount_text" class="form-label">Custom Display Text <span id="discount-text-required" style="display: none;">*</span></label>
                            <input type="text"
                                   class="form-control @error('discount_text') is-invalid @enderror"
                                   id="discount_text"
                                   name="discount_text"
                                   value="{{ old('discount_text') }}"
                                   maxlength="40"
                                   placeholder="Leave empty for auto-generation">
                            @error('discount_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" id="discount-text-help">Optional. If empty, will auto-generate (e.g., "Upto 20% Off")</small>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active (promotion will be displayed on website)
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Promotion</button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);

    if (input.type === 'file' && input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 80px; object-fit: contain;" alt="Preview">`;
        };
        reader.readAsDataURL(input.files[0]);

        // Clear the URL field
        document.getElementById('logo_path').value = '';
        document.getElementById('url-preview').innerHTML = '<span style="color: #999;">URL preview will appear here</span>';
    } else if (input.type === 'url' && input.value) {
        const img = new Image();
        img.onload = function() {
            preview.innerHTML = `<img src="${input.value}" style="max-width: 100%; max-height: 80px; object-fit: contain;" alt="Preview">`;
        };
        img.onerror = function() {
            preview.innerHTML = '<span style="color: #e74c3c;">Invalid image URL</span>';
        };
        img.src = input.value;

        // Clear the file field
        document.getElementById('logo_file').value = '';
        document.getElementById('file-preview').innerHTML = '<span style="color: #999;">File preview will appear here</span>';
    }
}

// Auto-generate discount text preview and handle coming_soon type
document.addEventListener('DOMContentLoaded', function() {
    const discountTypeInputs = document.querySelectorAll('input[name="discount_type"]');
    const discountValueInput = document.getElementById('discount_value');
    const discountTextInput = document.getElementById('discount_text');
    const discountValueGroup = document.getElementById('discount-value-group');
    const discountValueRequired = document.getElementById('discount-value-required');
    const discountTextRequired = document.getElementById('discount-text-required');
    const discountTextHelp = document.getElementById('discount-text-help');

    function handleDiscountTypeChange() {
        const type = document.querySelector('input[name="discount_type"]:checked')?.value;

        if (type === 'coming_soon') {
            // Hide discount value field for coming_soon
            discountValueGroup.style.display = 'none';
            discountValueInput.removeAttribute('required');
            discountValueRequired.style.display = 'none';

            // Update text field for coming_soon
            discountTextInput.placeholder = 'e.g., Coming Soon, Coming Soon on 25th Nov';
            discountTextHelp.textContent = 'Enter custom text to display (max 40 characters). Default: "Coming Soon"';
            discountTextRequired.style.display = 'none';
        } else {
            // Show discount value field for upto/flat
            discountValueGroup.style.display = 'block';
            discountValueInput.setAttribute('required', 'required');
            discountValueRequired.style.display = 'inline';

            // Update text field for upto/flat
            discountTextInput.placeholder = 'Leave empty for auto-generation';
            discountTextHelp.textContent = 'Optional. If empty, will auto-generate (e.g., "Upto 20% Off")';
            discountTextRequired.style.display = 'none';

            // Update placeholder with preview
            updateDiscountPreview();
        }
    }

    function updateDiscountPreview() {
        const type = document.querySelector('input[name="discount_type"]:checked')?.value;
        if (type === 'coming_soon') return; // Skip for coming_soon

        if (discountTextInput.value) return; // Don't override custom text

        const value = discountValueInput.value;

        if (type && value) {
            const typeText = type.charAt(0).toUpperCase() + type.slice(1);
            discountTextInput.placeholder = `${typeText} ${value}% Off`;
        }
    }

    discountTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleDiscountTypeChange();
            updateDiscountPreview();
        });
    });

    discountValueInput.addEventListener('input', updateDiscountPreview);

    // Initial setup
    handleDiscountTypeChange();
    updateDiscountPreview();
});
</script>
@endpush