@extends('layouts.admin')

@section('title', 'Create Category - BixCash Admin')
@section('page-title', 'Create Category')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">Create New Category</h3>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Back to Categories
            </a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.categories.store') }}" id="categoryForm" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name <span class="text-red-500">*</span></label>
                            <input type="text"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('name') border-red-500 @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('description') border-red-500 @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category Icon</label>

                            <div class="mb-4">
                                <label for="icon_file" class="block text-sm font-medium text-gray-600 mb-2">Upload Icon File</label>
                                <input type="file"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('icon_file') border-red-500 @enderror"
                                       id="icon_file"
                                       name="icon_file"
                                       accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp">
                                @error('icon_file')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <div class="mt-2 bg-blue-50 border-l-4 border-blue-500 p-3 rounded-r-lg">
                                    <p class="text-sm font-semibold text-blue-800 mb-1">Recommended Image Specifications:</p>
                                    <ul class="text-sm text-blue-700 space-y-0.5">
                                        <li><strong>Dimensions:</strong> 60x60 pixels (square format)</li>
                                        <li><strong>File Types:</strong> PNG (recommended), JPG, SVG, WebP</li>
                                        <li><strong>File Size:</strong> Maximum 2MB, recommended 10-50KB</li>
                                        <li><strong>Background:</strong> Transparent PNG preferred</li>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <label for="icon_path" class="block text-sm font-medium text-gray-600 mb-2">Or Enter Icon URL</label>
                                <input type="url"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('icon_path') border-red-500 @enderror"
                                       id="icon_path"
                                       name="icon_path"
                                       value="{{ old('icon_path') }}"
                                       placeholder="https://example.com/icon.png">
                                @error('icon_path')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Enter the full URL to the category icon (if not uploading file)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="color" class="block text-sm font-medium text-gray-700 mb-2">Category Color</label>
                                <input type="color"
                                       class="w-full h-12 px-2 py-1 border border-gray-200 rounded-lg cursor-pointer @error('color') border-red-500 @enderror"
                                       id="color"
                                       name="color"
                                       value="{{ old('color', '#021c47') }}">
                                @error('color')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order <span class="text-red-500">*</span></label>
                                <input type="number"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('order') border-red-500 @enderror"
                                       id="order"
                                       name="order"
                                       value="{{ old('order', 0) }}"
                                       min="0"
                                       required>
                                @error('order')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                            </div>
                        </div>

                        <!-- SEO Information -->
                        <div class="pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-[#021c47] mb-4">SEO Information</h4>

                            <div class="space-y-6">
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                    <input type="text"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('meta_title') border-red-500 @enderror"
                                           id="meta_title"
                                           name="meta_title"
                                           value="{{ old('meta_title') }}"
                                           maxlength="255">
                                    @error('meta_title')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Recommended: 50-60 characters</p>
                                </div>

                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <textarea class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('meta_description') border-red-500 @enderror"
                                              id="meta_description"
                                              name="meta_description"
                                              rows="3"
                                              maxlength="500">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500">Recommended: 150-160 characters</p>
                                </div>
                            </div>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]"
                                   id="is_active"
                                   name="is_active"
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="text-sm font-medium text-gray-700">Active (visible on website)</span>
                        </label>
                    </div>

                    <!-- Preview -->
                    <div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h5 class="font-semibold text-[#021c47]">Preview</h5>
                            </div>
                            <div class="p-4 bg-gray-50">
                                <div id="categoryPreview" class="bg-white rounded-xl p-4 w-[120px] h-[160px] flex flex-col items-center justify-center mx-auto transition-all" style="border: 2px solid #021c47;">
                                    <div id="previewIcon" class="w-[60px] h-[60px] bg-gray-100 rounded flex items-center justify-center text-gray-400 text-sm mb-2">
                                        Icon
                                    </div>
                                    <span id="previewName" class="font-bold text-sm text-center" style="color: #021c47;">Category Name</span>
                                </div>
                                <p class="text-xs text-gray-400 text-center mt-3">Preview updates as you type</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 mt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Create Category
                    </button>
                    <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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
                previewName.style.color = colorInput.value || '#021c47';
                categoryPreview.style.borderColor = colorInput.value || '#021c47';

                if (iconFileInput.files && iconFileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewIcon.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover rounded">`;
                    };
                    reader.readAsDataURL(iconFileInput.files[0]);
                } else if (iconPathInput.value) {
                    previewIcon.innerHTML = `<img src="${iconPathInput.value}" class="w-full h-full object-cover rounded" onerror="this.style.display='none'; this.parentNode.innerHTML='Icon'; this.parentNode.classList.add('bg-gray-100');">`;
                } else {
                    previewIcon.innerHTML = 'Icon';
                    previewIcon.classList.add('bg-gray-100');
                }
            }

            iconFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) iconPathInput.value = '';
                updatePreview();
            });

            iconPathInput.addEventListener('input', function() {
                if (this.value) iconFileInput.value = '';
                updatePreview();
            });

            nameInput.addEventListener('input', updatePreview);
            colorInput.addEventListener('input', updatePreview);
        });
    </script>
@endsection
