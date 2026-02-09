@extends('layouts.admin')

@section('title', 'Edit Promotion - BixCash Admin')
@section('page-title', 'Edit Promotion')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-[#021c47]">Edit Promotion: {{ $promotion->brand_name }}</h3>
            <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Back to Promotions
            </a>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">Brand Name <span class="text-red-500">*</span></label>
                        <input type="text"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('brand_name') border-red-500 @enderror"
                               id="brand_name"
                               name="brand_name"
                               value="{{ old('brand_name', $promotion->brand_name) }}"
                               required
                               placeholder="e.g., Nike, KFC, Sapphire">
                        @error('brand_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Display Order <span class="text-red-500">*</span></label>
                        <input type="number"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('order') border-red-500 @enderror"
                               id="order"
                               name="order"
                               value="{{ old('order', $promotion->order) }}"
                               min="0"
                               required>
                        @error('order')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    </div>
                </div>

                <!-- Logo Upload Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo</label>

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-4">
                            <h6 class="font-semibold text-blue-800 mb-2">Logo Guidelines</h6>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li><strong>Dimensions:</strong> 150x80 pixels (recommended)</li>
                                <li><strong>Format:</strong> PNG (recommended), JPG, SVG, WebP</li>
                                <li><strong>File Size:</strong> Maximum 2MB, optimal 10-50KB</li>
                                <li><strong>Background:</strong> Transparent or white background preferred</li>
                            </ul>
                        </div>

                        @if($promotion->logo_path)
                            <div class="mb-4 p-4 bg-white rounded-xl border border-gray-200 text-center">
                                <p class="text-sm font-semibold text-gray-700 mb-2">Current Logo:</p>
                                <img src="{{ $promotion->logo_url }}" alt="{{ $promotion->brand_name }}" class="max-w-[150px] max-h-[80px] object-contain mx-auto border border-gray-200 rounded">
                                <p class="text-sm text-gray-500 mt-2">{{ basename($promotion->logo_path) }}</p>
                            </div>

                            <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded-r-lg mb-4">
                                <p class="text-green-800"><strong>Current logo will be kept</strong> — Only use the fields below if you want to change the logo</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="logo_file" class="block text-sm font-medium text-gray-600 mb-2">{{ $promotion->logo_path ? 'Upload New Logo (optional)' : 'Upload Logo File' }}</label>
                                <input type="file"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('logo_file') border-red-500 @enderror"
                                       id="logo_file"
                                       name="logo_file"
                                       accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp"
                                       onchange="previewImage(this, 'file-preview')">
                                @error('logo_file')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="logo_path" class="block text-sm font-medium text-gray-600 mb-2">{{ $promotion->logo_path ? 'Or Use New URL (optional)' : 'Or Enter Logo URL' }}</label>
                                <input type="url"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('logo_path') border-red-500 @enderror"
                                       id="logo_path"
                                       name="logo_path"
                                       value="{{ old('logo_path') }}"
                                       placeholder="https://example.com/logo.png"
                                       onchange="previewImage(this, 'url-preview')">
                                @error('logo_path')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div id="file-preview" class="hidden text-center min-h-[80px] border-2 border-dashed border-gray-300 rounded-lg items-center justify-center"></div>
                            <div id="url-preview" class="hidden text-center min-h-[80px] border-2 border-dashed border-gray-300 rounded-lg items-center justify-center"></div>
                        </div>
                    </div>
                </div>

                <!-- Discount Configuration -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">Discount Type <span class="text-red-500">*</span></label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="discount_type" id="discount_upto" value="upto" class="w-4 h-4 text-[#93db4d] focus:ring-[#93db4d]" {{ old('discount_type', $promotion->discount_type) === 'upto' ? 'checked' : '' }}>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">UPTO</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="discount_type" id="discount_flat" value="flat" class="w-4 h-4 text-[#93db4d] focus:ring-[#93db4d]" {{ old('discount_type', $promotion->discount_type) === 'flat' ? 'checked' : '' }}>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">FLAT</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="discount_type" id="discount_coming_soon" value="coming_soon" class="w-4 h-4 text-[#93db4d] focus:ring-[#93db4d]" {{ old('discount_type', $promotion->discount_type) === 'coming_soon' ? 'checked' : '' }}>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">COMING SOON</span>
                        </label>
                    </div>
                    @error('discount_type')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div id="discount-value-group">
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">Discount Percentage <span id="discount-value-required" class="text-red-500">*</span></label>
                        <div class="flex">
                            <input type="number"
                                   class="flex-1 px-4 py-3 border border-gray-200 rounded-l-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('discount_value') border-red-500 @enderror"
                                   id="discount_value"
                                   name="discount_value"
                                   value="{{ old('discount_value', $promotion->discount_value) }}"
                                   min="1"
                                   max="100"
                                   placeholder="20">
                            <span class="inline-flex items-center px-4 py-3 bg-gray-100 border border-l-0 border-gray-200 rounded-r-lg text-gray-600 font-medium">%</span>
                        </div>
                        @error('discount_value')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="discount-text-group">
                        <label for="discount_text" class="block text-sm font-medium text-gray-700 mb-2">Custom Display Text <span id="discount-text-required" class="text-red-500 hidden">*</span></label>
                        <input type="text"
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('discount_text') border-red-500 @enderror"
                               id="discount_text"
                               name="discount_text"
                               value="{{ old('discount_text', $promotion->discount_text) }}"
                               maxlength="40"
                               placeholder="Leave empty for auto-generation">
                        @error('discount_text')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500" id="discount-text-help">Optional. If empty, will auto-generate (e.g., "Upto 20% Off")</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]"
                               name="is_active"
                               id="is_active"
                               value="1"
                               {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-700">Active (promotion will be displayed on website)</span>
                    </label>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="px-6 py-3 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Update Promotion
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Show Details Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#021c47]">Promotion Details</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">ID:</span>
                        <span class="text-gray-900">{{ $promotion->id }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Current Status:</span>
                        @if($promotion->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                        @endif
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="font-medium text-gray-600">Current Discount:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">{{ $promotion->formatted_discount }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Created:</span>
                        <span class="text-gray-900">{{ $promotion->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="font-medium text-gray-600">Last Updated:</span>
                        <span class="text-gray-900">{{ $promotion->updated_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                    @if($promotion->creator)
                    <div class="flex justify-between py-2">
                        <span class="font-medium text-gray-600">Created By:</span>
                        <span class="text-gray-900">{{ $promotion->creator->name }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const filePreview = document.getElementById('file-preview');
    const urlPreview = document.getElementById('url-preview');

    if (input.type === 'file' && input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="max-w-full max-h-[80px] object-contain" alt="Preview">`;
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        };
        reader.readAsDataURL(input.files[0]);

        document.getElementById('logo_path').value = '';
        urlPreview.innerHTML = '';
        urlPreview.classList.add('hidden');
    } else if (input.type === 'url' && input.value) {
        const img = new Image();
        img.onload = function() {
            preview.innerHTML = `<img src="${input.value}" class="max-w-full max-h-[80px] object-contain" alt="Preview">`;
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        };
        img.onerror = function() {
            preview.innerHTML = '<span class="text-red-500">Invalid image URL</span>';
            preview.classList.remove('hidden');
            preview.classList.add('flex');
        };
        img.src = input.value;

        document.getElementById('logo_file').value = '';
        filePreview.innerHTML = '';
        filePreview.classList.add('hidden');
    } else if (input.type === 'url' && !input.value) {
        urlPreview.innerHTML = '';
        urlPreview.classList.add('hidden');
    }
}

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
            discountValueGroup.style.display = 'none';
            discountValueInput.removeAttribute('required');
            discountValueRequired.classList.add('hidden');
            discountTextInput.placeholder = 'e.g., Coming Soon, Coming Soon on 25th Nov';
            discountTextHelp.textContent = 'Enter custom text to display (max 40 characters). Default: "Coming Soon"';
            discountTextRequired.classList.add('hidden');
        } else {
            discountValueGroup.style.display = 'block';
            discountValueInput.setAttribute('required', 'required');
            discountValueRequired.classList.remove('hidden');
            discountTextInput.placeholder = 'Leave empty for auto-generation';
            discountTextHelp.textContent = 'Optional. If empty, will auto-generate (e.g., "Upto 20% Off")';
            discountTextRequired.classList.add('hidden');
            updateDiscountPreview();
        }
    }

    function updateDiscountPreview() {
        const type = document.querySelector('input[name="discount_type"]:checked')?.value;
        if (type === 'coming_soon') return;

        const value = discountValueInput.value;
        if (type && value && !discountTextInput.value) {
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
    handleDiscountTypeChange();
    updateDiscountPreview();
});
</script>
@endpush
