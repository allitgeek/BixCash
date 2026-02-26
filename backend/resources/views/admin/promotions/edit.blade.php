@extends('layouts.admin')

@section('title', 'Edit Promotion - BixCash Admin')
@section('page-title', 'Edit Promotion')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Edit Promotion</h1>
                <p class="text-gray-500 mt-1">{{ $promotion->brand_name }} &mdash; #{{ $promotion->id }}</p>
            </div>
            <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Promotions
            </a>
        </div>

        <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Form (2 cols) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Basic Info Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Basic Information</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-1">Brand Name <span class="text-red-500">*</span></label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('brand_name') border-red-500 @enderror"
                                           id="brand_name" name="brand_name"
                                           value="{{ old('brand_name', $promotion->brand_name) }}"
                                           required placeholder="e.g., Nike, KFC">
                                    @error('brand_name')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">Display Order <span class="text-red-500">*</span></label>
                                    <input type="number"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('order') border-red-500 @enderror"
                                           id="order" name="order"
                                           value="{{ old('order', $promotion->order) }}" min="0" required>
                                    @error('order')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-400">Lower numbers appear first</p>
                                </div>
                            </div>

                            <!-- Status -->
                            <label class="flex items-center gap-3 cursor-pointer pt-1">
                                <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-green-500 focus:ring-green-400"
                                       name="is_active" id="is_active" value="1"
                                       {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-gray-700">Active <span class="text-gray-400 font-normal">(visible on website)</span></span>
                            </label>
                        </div>
                    </div>

                    <!-- Logo Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Brand Logo</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            @if($promotion->logo_path)
                                <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <img src="{{ $promotion->logo_url }}" alt="{{ $promotion->brand_name }}" class="w-20 h-14 object-contain rounded border border-gray-200 bg-white p-1">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700 truncate">{{ basename($promotion->logo_path) }}</p>
                                        <p class="text-xs text-green-600">Current logo &mdash; upload below to replace</p>
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="logo_file" class="block text-sm font-medium text-gray-700 mb-1">Upload File</label>
                                    <input type="file"
                                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#021c47] file:text-white hover:file:bg-[#93db4d] hover:file:text-[#021c47] file:cursor-pointer file:transition-colors border border-gray-200 rounded-lg @error('logo_file') border-red-500 @enderror"
                                           id="logo_file" name="logo_file"
                                           accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp"
                                           onchange="previewImage(this, 'file-preview')">
                                    @error('logo_file')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, SVG, WebP &mdash; Max 2MB</p>
                                </div>
                                <div>
                                    <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-1">Or Enter URL</label>
                                    <input type="url"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('logo_path') border-red-500 @enderror"
                                           id="logo_path" name="logo_path"
                                           value="{{ old('logo_path') }}"
                                           placeholder="https://example.com/logo.png"
                                           onchange="previewImage(this, 'url-preview')">
                                    @error('logo_path')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div id="file-preview" class="hidden items-center justify-center p-3 border-2 border-dashed border-gray-200 rounded-lg min-h-[60px]"></div>
                                <div id="url-preview" class="hidden items-center justify-center p-3 border-2 border-dashed border-gray-200 rounded-lg min-h-[60px]"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Discount Card -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Discount Configuration</h3>
                        </div>
                        <div class="p-5 space-y-4">
                            <!-- Type Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type <span class="text-red-500">*</span></label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_type" value="upto" class="w-4 h-4 text-blue-600 focus:ring-blue-500" {{ old('discount_type', $promotion->discount_type) === 'upto' ? 'checked' : '' }}>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">UPTO</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_type" value="flat" class="w-4 h-4 text-orange-500 focus:ring-orange-400" {{ old('discount_type', $promotion->discount_type) === 'flat' ? 'checked' : '' }}>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">FLAT</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="discount_type" value="coming_soon" class="w-4 h-4 text-purple-600 focus:ring-purple-500" {{ old('discount_type', $promotion->discount_type) === 'coming_soon' ? 'checked' : '' }}>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">COMING SOON</span>
                                    </label>
                                </div>
                                @error('discount_type')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Value + Text -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div id="discount-value-group">
                                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-1">Discount % <span id="discount-value-required" class="text-red-500">*</span></label>
                                    <div class="flex">
                                        <input type="number"
                                               class="flex-1 px-3 py-2 border border-gray-200 rounded-l-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('discount_value') border-red-500 @enderror"
                                               id="discount_value" name="discount_value"
                                               value="{{ old('discount_value', $promotion->discount_value) }}"
                                               min="1" max="100" placeholder="20">
                                        <span class="inline-flex items-center px-3 py-2 bg-gray-100 border border-l-0 border-gray-200 rounded-r-lg text-gray-500 text-sm font-medium">%</span>
                                    </div>
                                    @error('discount_value')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div id="discount-text-group">
                                    <label for="discount_text" class="block text-sm font-medium text-gray-700 mb-1">Custom Text <span id="discount-text-required" class="text-red-500 hidden">*</span></label>
                                    <input type="text"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('discount_text') border-red-500 @enderror"
                                           id="discount_text" name="discount_text"
                                           value="{{ old('discount_text', $promotion->discount_text) }}"
                                           maxlength="40" placeholder="Leave empty for auto-generation">
                                    @error('discount_text')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-400" id="discount-text-help">Auto-generates if empty (e.g., "Upto 20% Off")</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Update Promotion
                        </button>
                        <a href="{{ route('admin.promotions.index') }}" class="px-6 py-2.5 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Right: Sidebar (1 col) -->
                <div class="space-y-6">
                    <!-- Live Preview -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Preview</h3>
                        </div>
                        <div class="p-5">
                            <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                                <div id="preview-logo" class="mb-3 flex items-center justify-center">
                                    @if($promotion->logo_path)
                                        <img src="{{ $promotion->logo_url }}" alt="Preview" class="max-w-[120px] max-h-[60px] object-contain">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <p id="preview-brand" class="font-bold text-[#021c47] text-lg">{{ $promotion->brand_name }}</p>
                                <p id="preview-discount" class="text-sm text-gray-500 mt-1">{{ $promotion->formatted_discount }}</p>
                                <div class="mt-3">
                                    @php
                                        $previewTypeClasses = match($promotion->discount_type) {
                                            'upto' => 'bg-blue-100 text-blue-700',
                                            'flat' => 'bg-orange-100 text-orange-700',
                                            'coming_soon' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-700'
                                        };
                                    @endphp
                                    <span id="preview-type-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $previewTypeClasses }}">
                                        {{ str_replace('_', ' ', $promotion->discount_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-[#021c47] uppercase tracking-wider">Details</h3>
                        </div>
                        <div class="p-5">
                            <dl class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">ID</dt>
                                    <dd class="font-medium text-gray-900">{{ $promotion->id }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Status</dt>
                                    <dd>
                                        @if($promotion->is_active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Created</dt>
                                    <dd class="font-medium text-gray-900">{{ $promotion->created_at->format('M j, Y') }}</dd>
                                </div>
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Updated</dt>
                                    <dd class="font-medium text-gray-900">{{ $promotion->updated_at->format('M j, Y') }}</dd>
                                </div>
                                @if($promotion->creator)
                                <div class="border-t border-gray-100"></div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Created By</dt>
                                    <dd class="font-medium text-gray-900">{{ $promotion->creator->name }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const filePreview = document.getElementById('file-preview');
    const urlPreview = document.getElementById('url-preview');
    const logoPreview = document.getElementById('preview-logo');

    function setPreviewImg(src) {
        if (logoPreview) {
            logoPreview.innerHTML = `<img src="${src}" alt="Preview" class="max-w-[120px] max-h-[60px] object-contain">`;
        }
    }

    if (input.type === 'file' && input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="max-h-[60px] object-contain" alt="Preview">`;
            preview.classList.remove('hidden');
            preview.classList.add('flex');
            setPreviewImg(e.target.result);
        };
        reader.readAsDataURL(input.files[0]);

        document.getElementById('logo_path').value = '';
        urlPreview.innerHTML = '';
        urlPreview.classList.add('hidden');
    } else if (input.type === 'url' && input.value) {
        const img = new Image();
        img.onload = function() {
            preview.innerHTML = `<img src="${input.value}" class="max-h-[60px] object-contain" alt="Preview">`;
            preview.classList.remove('hidden');
            preview.classList.add('flex');
            setPreviewImg(input.value);
        };
        img.onerror = function() {
            preview.innerHTML = '<span class="text-red-500 text-xs">Invalid image URL</span>';
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
    const brandInput = document.getElementById('brand_name');
    const previewBrand = document.getElementById('preview-brand');
    const previewDiscount = document.getElementById('preview-discount');

    // Live preview for brand name
    if (brandInput && previewBrand) {
        brandInput.addEventListener('input', function() {
            previewBrand.textContent = this.value || 'Brand Name';
        });
    }

    function handleDiscountTypeChange() {
        const type = document.querySelector('input[name="discount_type"]:checked')?.value;

        if (type === 'coming_soon') {
            discountValueGroup.style.display = 'none';
            discountValueInput.removeAttribute('required');
            discountValueRequired.classList.add('hidden');
            discountTextInput.placeholder = 'e.g., Coming Soon, Coming Soon on 25th Nov';
            discountTextHelp.textContent = 'Custom text (max 40 chars). Default: "Coming Soon"';
            discountTextRequired.classList.add('hidden');
        } else {
            discountValueGroup.style.display = 'block';
            discountValueInput.setAttribute('required', 'required');
            discountValueRequired.classList.remove('hidden');
            discountTextInput.placeholder = 'Leave empty for auto-generation';
            discountTextHelp.textContent = 'Auto-generates if empty (e.g., "Upto 20% Off")';
            discountTextRequired.classList.add('hidden');
            updateDiscountPreview();
        }
    }

    function updateDiscountPreview() {
        const type = document.querySelector('input[name="discount_type"]:checked')?.value;
        if (type === 'coming_soon') {
            if (previewDiscount) previewDiscount.textContent = discountTextInput.value || 'Coming Soon';
            return;
        }

        const value = discountValueInput.value;
        if (type && value && !discountTextInput.value) {
            const typeText = type.charAt(0).toUpperCase() + type.slice(1);
            const text = `${typeText} ${value}% Off`;
            discountTextInput.placeholder = text;
            if (previewDiscount) previewDiscount.textContent = text;
        } else if (discountTextInput.value) {
            if (previewDiscount) previewDiscount.textContent = discountTextInput.value;
        }
    }

    discountTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleDiscountTypeChange();
            updateDiscountPreview();
        });
    });

    discountValueInput.addEventListener('input', updateDiscountPreview);
    discountTextInput.addEventListener('input', updateDiscountPreview);
    handleDiscountTypeChange();
    updateDiscountPreview();
});
</script>
@endpush
