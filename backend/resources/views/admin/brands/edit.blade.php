@extends('layouts.admin')

@section('title', 'Edit Brand - BixCash Admin')
@section('page-title', 'Edit Brand')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Brand: {{ $brand->name }}</h3>
            <div>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    Back to Brands
                </a>
                <a href="{{ route('admin.brands.show', $brand) }}" class="btn btn-info">
                    View Brand
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.brands.update', $brand) }}" id="brandForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name">Brand Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $brand->name) }}"
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
                                      rows="3">{{ old('description', $brand->description) }}</textarea>
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
                                    <option value="{{ $category->id }}" {{ old('category_id', $brand->category_id) == $category->id ? 'selected' : '' }}>
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

                            <!-- Current Logo Display -->
                            @if($brand->logo_path)
                                <div class="mb-3">
                                    <label class="form-label"><strong>Current Logo</strong></label>
                                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                                        <img src="{{ $brand->logo_path }}" alt="{{ $brand->name }}" style="max-height: 80px; max-width: 150px; object-fit: contain;">
                                    </div>
                                </div>
                            @endif

                            <!-- File Upload Option -->
                            <div class="mb-3">
                                <label for="logo_file" class="form-label"><strong>Upload New Logo File</strong></label>
                                <input type="file"
                                       class="form-control @error('logo_file') is-invalid @enderror"
                                       id="logo_file"
                                       name="logo_file"
                                       accept="image/*">
                                @error('logo_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload PNG, JPG, or SVG file (max 2MB) - will replace current logo</small>
                            </div>

                            <!-- URL Option -->
                            <div class="mb-3">
                                <label for="logo_path" class="form-label"><strong>Or Enter Logo URL</strong></label>
                                <input type="url"
                                       class="form-control @error('logo_path') is-invalid @enderror"
                                       id="logo_path"
                                       name="logo_path"
                                       value="{{ old('logo_path', $brand->logo_path) }}"
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
                                   value="{{ old('website', $brand->website) }}"
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
                                           value="{{ old('commission_rate', $brand->commission_rate) }}"
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
                                           value="{{ old('order', $brand->order) }}"
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
                                               {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
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
                                               {{ old('is_featured', $brand->is_featured) ? 'checked' : '' }}>
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
                                        @if($brand->logo_path)
                                            <img src="{{ $brand->logo_path }}" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.style.display='none'; this.parentNode.innerHTML='Brand Logo'; this.parentNode.style.background='#f0f0f0';">
                                        @else
                                            Brand Logo
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <strong id="previewName">{{ $brand->name }}</strong>
                                    <br>
                                    <small class="text-muted" id="previewCategory">
                                        {{ $brand->category ? $brand->category->name : 'No category' }}
                                    </small>
                                </div>
                                <small class="text-muted mt-2 d-block text-center">Preview updates as you type</small>
                            </div>
                        </div>

                        @if($brand->partner)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Partner Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Partner:</strong> {{ $brand->partner->name }}</p>
                                    <p><strong>Email:</strong> {{ $brand->partner->email }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Update Brand</button>
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
            const categorySelect = document.getElementById('category_id');

            const previewName = document.getElementById('previewName');
            const previewLogo = document.getElementById('previewLogo');
            const previewCategory = document.getElementById('previewCategory');

            function updatePreview() {
                previewName.textContent = nameInput.value || 'Brand Name';

                // Update category
                const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
                previewCategory.textContent = selectedCategory.value ? selectedCategory.text : 'No category';

                // Update logo
                if (logoPathInput.value) {
                    previewLogo.innerHTML = `<img src="${logoPathInput.value}" style="max-width: 100%; max-height: 100%; object-fit: contain;" onerror="this.style.display='none'; this.parentNode.innerHTML='Brand Logo'; this.parentNode.style.background='#f0f0f0';">`;
                } else {
                    previewLogo.innerHTML = 'Brand Logo';
                    previewLogo.style.background = '#f0f0f0';
                }
            }

            nameInput.addEventListener('input', updatePreview);
            logoPathInput.addEventListener('input', updatePreview);
            categorySelect.addEventListener('change', updatePreview);
        });
    </script>
@endsection