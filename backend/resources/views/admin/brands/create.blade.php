@extends('layouts.admin')

@section('title', 'Create Brand - BixCash Admin')
@section('page-title', 'Create Brand')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Brand</h3>
            <div>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    Back to Brands
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.store') }}" id="brandForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name">Brand Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select class="form-control @error('category_id') is-invalid @enderror"
                                    id="category_id"
                                    name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Brand Logo</label>

                            <!-- File Upload Option -->
                            <div class="mb-3">
                                <label for="logo_file" class="form-label"><strong>Upload Logo File</strong></label>
                                <input type="file"
                                       class="form-control @error('logo_file') is-invalid @enderror"
                                       id="logo_file"
                                       name="logo_file"
                                       accept="image/*">
                                @error('logo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload PNG, JPG, or SVG file (max 2MB)</small>
                            </div>

                            <!-- URL Option -->
                            <div class="mb-3">
                                <label for="logo_path" class="form-label"><strong>Or Enter Logo URL</strong></label>
                                <input type="url"
                                       class="form-control @error('logo_path') is-invalid @enderror"
                                       id="logo_path"
                                       name="logo_path"
                                       value="{{ old('logo_path') }}"
                                       placeholder="https://example.com/logo.png">
                                @error('logo_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the full URL to the brand logo (if not uploading file)</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="website">Website URL</label>
                            <input type="url"
                                   class="form-control @error('website') is-invalid @enderror"
                                   id="website"
                                   name="website"
                                   value="{{ old('website') }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="commission_rate">Commission Rate (%)</label>
                                    <input type="number"
                                           class="form-control @error('commission_rate') is-invalid @enderror"
                                           id="commission_rate"
                                           name="commission_rate"
                                           value="{{ old('commission_rate', 0) }}"
                                           min="0"
                                           max="100"
                                           step="0.01">
                                    @error('commission_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Display Order *</label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', 0) }}"
                                           min="0"
                                           required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (visible on website)
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="is_featured"
                                               name="is_featured"
                                               value="1"
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured brand
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Preview</h5>
                            </div>
                            <div class="card-body" style="background: #f8f9fa;">
                                <div id="brandPreview" style="
                                    background: white;
                                    border-radius: 10px;
                                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                    padding: 1rem;
                                    width: 200px;
                                    height: 120px;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin: 0 auto;
                                    transition: all 0.3s ease;
                                ">
                                    <div id="previewLogo" style="
                                        max-width: 150px;
                                        max-height: 80px;
                                        background: #f0f0f0;
                                        border-radius: 4px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        color: #999;
                                        font-size: 0.9rem;
                                        width: 150px;
                                        height: 80px;
                                    ">
                                        Brand Logo
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <strong id="previewName">Brand Name</strong>
                                    <br>
                                    <small class="text-muted" id="previewCategory">No category</small>
                                </div>
                                <small class="text-muted mt-2 d-block text-center">Preview updates as you type</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Create Brand</button>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const logoPathInput = document.getElementById('logo_path');
            const logoFileInput = document.getElementById('logo_file');
            const categorySelect = document.getElementById('category_id');

            const previewName = document.getElementById('previewName');
            const previewLogo = document.getElementById('previewLogo');
            const previewCategory = document.getElementById('previewCategory');

            function updatePreview() {
                previewName.textContent = nameInput.value || 'Brand Name';

                // Update category
                const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
                previewCategory.textContent = selectedCategory.value ? selectedCategory.text : 'No category';

                // Update logo - prioritize file upload over URL
                if (logoFileInput.files && logoFileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewLogo.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 100%; object-fit: contain;">`;
                    };
                    reader.readAsDataURL(logoFileInput.files[0]);
                } else if (logoPathInput.value) {
                    previewLogo.innerHTML = `<img src="${logoPathInput.value}" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.style.display='none'; this.parentNode.innerHTML='Brand Logo'; this.parentNode.style.background='#f0f0f0';">`;
                } else {
                    previewLogo.innerHTML = 'Brand Logo';
                    previewLogo.style.background = '#f0f0f0';
                }
            }

            // Clear URL when file is selected
            logoFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    logoPathInput.value = '';
                }
                updatePreview();
            });

            // Clear file when URL is entered
            logoPathInput.addEventListener('input', function() {
                if (this.value) {
                    logoFileInput.value = '';
                }
                updatePreview();
            });

            nameInput.addEventListener('input', updatePreview);
            categorySelect.addEventListener('change', updatePreview);
        });
    </script>
@endsection