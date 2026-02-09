@extends('layouts.admin')

@section('title', 'Create Brand - BixCash Admin')
@section('page-title', 'Create Brand')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">Create New Brand</h3>
            <a href="{{ route('admin.brands.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Back to Brands
            </a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.brands.store') }}" id="brandForm" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name <span class="text-red-500">*</span></label>
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
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('category_id') border-red-500 @enderror"
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
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo</label>

                            <!-- File Upload Option -->
                            <div class="mb-4">
                                <label for="logo_file" class="block text-sm font-medium text-gray-600 mb-2">Upload Logo File</label>
                                <input type="file"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('logo_file') border-red-500 @enderror"
                                       id="logo_file"
                                       name="logo_file"
                                       accept="image/*">
                                @error('logo_file')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Upload PNG, JPG, or SVG file (max 2MB)</p>
                            </div>

                            <!-- URL Option -->
                            <div>
                                <label for="logo_path" class="block text-sm font-medium text-gray-600 mb-2">Or Enter Logo URL</label>
                                <input type="url"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('logo_path') border-red-500 @enderror"
                                       id="logo_path"
                                       name="logo_path"
                                       value="{{ old('logo_path') }}"
                                       placeholder="https://example.com/logo.png">
                                @error('logo_path')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">Enter the full URL to the brand logo (if not uploading file)</p>
                            </div>
                        </div>

                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website URL</label>
                            <input type="url"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('website') border-red-500 @enderror"
                                   id="website"
                                   name="website"
                                   value="{{ old('website') }}"
                                   placeholder="https://example.com">
                            @error('website')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">Commission Rate (%)</label>
                                <input type="number"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('commission_rate') border-red-500 @enderror"
                                       id="commission_rate"
                                       name="commission_rate"
                                       value="{{ old('commission_rate', 0) }}"
                                       min="0"
                                       max="100"
                                       step="0.01">
                                @error('commission_rate')
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Active (visible on website)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox"
                                       class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]"
                                       id="is_featured"
                                       name="is_featured"
                                       value="1"
                                       {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Featured brand</span>
                            </label>
                        </div>
                    </div>

                    <!-- Preview -->
                    <div>
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-6">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h5 class="font-semibold text-[#021c47]">Preview</h5>
                            </div>
                            <div class="p-4 bg-gray-50">
                                <div id="brandPreview" class="bg-white rounded-xl shadow-sm p-4 w-[200px] h-[120px] flex items-center justify-center mx-auto">
                                    <div id="previewLogo" class="w-[150px] h-[80px] bg-gray-100 rounded flex items-center justify-center text-gray-400 text-sm">
                                        Brand Logo
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <p class="font-semibold text-gray-900" id="previewName">Brand Name</p>
                                    <p class="text-sm text-gray-500" id="previewCategory">No category</p>
                                </div>
                                <p class="text-xs text-gray-400 text-center mt-3">Preview updates as you type</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 mt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Create Brand
                    </button>
                    <a href="{{ route('admin.brands.index') }}" class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
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

                const selectedCategory = categorySelect.options[categorySelect.selectedIndex];
                previewCategory.textContent = selectedCategory.value ? selectedCategory.text : 'No category';

                if (logoFileInput.files && logoFileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewLogo.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-full object-contain">`;
                        previewLogo.classList.remove('bg-gray-100');
                    };
                    reader.readAsDataURL(logoFileInput.files[0]);
                } else if (logoPathInput.value) {
                    previewLogo.innerHTML = `<img src="${logoPathInput.value}" class="max-w-full max-h-full object-contain" onerror="this.style.display='none'; this.parentNode.innerHTML='Brand Logo'; this.parentNode.classList.add('bg-gray-100');">`;
                    previewLogo.classList.remove('bg-gray-100');
                } else {
                    previewLogo.innerHTML = 'Brand Logo';
                    previewLogo.classList.add('bg-gray-100');
                }
            }

            logoFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    logoPathInput.value = '';
                }
                updatePreview();
            });

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
