@extends('layouts.admin')

@section('title', 'Edit Category - BixCash Admin')
@section('page-title', 'Edit Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Category: {{ $category->name }}</h3>
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Back to Categories
                </a>
                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info">
                    View Category
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.update', $category) }}" id="categoryForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name">Category Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $category->name) }}"
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
                                      rows="3">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Category Icon</label>

                            <!-- Current Icon Display -->
                            @if($category->icon_path)
                                <div class="mb-3">
                                    <label class="form-label"><strong>Current Icon</strong></label>
                                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 8px; text-align: center;">
                                        <img src="{{ $category->icon_path }}" alt="{{ $category->name }}" style="max-height: 60px; max-width: 60px; object-fit: contain;">
                                    </div>
                                </div>
                            @endif

                            <!-- File Upload Option -->
                            <div class="mb-3">
                                <label for="icon_file" class="form-label"><strong>Upload New Icon File</strong></label>
                                <input type="file"
                                       class="form-control @error('icon_file') is-invalid @enderror"
                                       id="icon_file"
                                       name="icon_file"
                                       accept="image/*">
                                @error('icon_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upload PNG, JPG, or SVG file (max 2MB) - will replace current icon</small>
                            </div>

                            <!-- URL Option -->
                            <div class="mb-3">
                                <label for="icon_path" class="form-label"><strong>Or Enter Icon URL</strong></label>
                                <input type="url"
                                       class="form-control @error('icon_path') is-invalid @enderror"
                                       id="icon_path"
                                       name="icon_path"
                                       value="{{ old('icon_path', $category->icon_path) }}"
                                       placeholder="https://example.com/icon.png">
                                @error('icon_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the full URL to the category icon (if not uploading file)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="color">Category Color</label>
                                    <input type="color"
                                           class="form-control @error('color') is-invalid @enderror"
                                           id="color"
                                           name="color"
                                           value="{{ old('color', $category->color ?: '#3498db') }}">
                                    @error('color')
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
                                           value="{{ old('order', $category->order) }}"
                                           min="0"
                                           required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Information -->
                        <h5 style="margin-top: 2rem; margin-bottom: 1rem; border-bottom: 1px solid #dee2e6; padding-bottom: 0.5rem;">SEO Information</h5>

                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title', $category->meta_title) }}"
                                   maxlength="255">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended: 50-60 characters</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      maxlength="500">{{ old('meta_description', $category->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Recommended: 150-160 characters</small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (visible on website)
                                </label>
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
                                <div id="categoryPreview" style="
                                    background: white;
                                    border: 2px solid {{ $category->color ?: '#021c47' }};
                                    border-radius: 8px;
                                    padding: 1rem;
                                    width: 120px;
                                    height: 160px;
                                    text-align: center;
                                    margin: 0 auto;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    align-items: center;
                                    transition: all 0.3s ease;
                                ">
                                    <div id="previewIcon" style="width: 60px; height: 60px; margin-bottom: 0.5rem; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                        @if($category->icon_path)
                                            <img src="{{ $category->icon_path }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" onerror="this.style.display='none';">
                                        @else
                                            Icon
                                        @endif
                                    </div>
                                    <span id="previewName" style="color: #021c47; font-weight: bold; font-size: 0.9rem;">
                                        {{ $category->name }}
                                    </span>
                                </div>
                                <small class="text-muted mt-2 d-block text-center">Preview updates as you type</small>
                            </div>
                        </div>

                        @if($category->brands_count > 0)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Associated Brands</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        This category has {{ $category->brands_count }} brand(s) associated with it.
                                    </p>
                                    <a href="{{ route('admin.brands.index', ['category_id' => $category->id]) }}" class="btn btn-sm btn-outline-primary">
                                        View Brands
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Update Category</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const iconPathInput = document.getElementById('icon_path');
            const iconFileInput = document.getElementById('icon_file');
            const colorInput = document.getElementById('color');

            const previewName = document.getElementById('previewName');
            const previewIcon = document.getElementById('previewIcon');
            const categoryPreview = document.getElementById('categoryPreview');

            function updatePreview() {
                previewName.textContent = nameInput.value || 'Category Name';
                categoryPreview.style.borderColor = colorInput.value || '#021c47';

                // Update icon - prioritize file upload over URL
                if (iconFileInput.files && iconFileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewIcon.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">`;
                    };
                    reader.readAsDataURL(iconFileInput.files[0]);
                } else if (iconPathInput.value) {
                    previewIcon.innerHTML = `<img src="${iconPathInput.value}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;" onerror="this.style.display='none'; this.parentNode.innerHTML='Icon'; this.parentNode.style.background='#f0f0f0'; this.parentNode.style.color='#999';">`;
                } else {
                    previewIcon.innerHTML = 'Icon';
                    previewIcon.style.background = '#f0f0f0';
                    previewIcon.style.color = '#999';
                }
            }

            // Clear URL when file is selected
            iconFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    iconPathInput.value = '';
                }
                updatePreview();
            });

            // Clear file when URL is entered
            iconPathInput.addEventListener('input', function() {
                if (this.value) {
                    iconFileInput.value = '';
                }
                updatePreview();
            });

            nameInput.addEventListener('input', updatePreview);
            colorInput.addEventListener('input', updatePreview);
        });
    </script>
@endsection