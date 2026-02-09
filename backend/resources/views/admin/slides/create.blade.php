@extends('layouts.admin')

@section('title', 'Create Hero Slide - BixCash Admin')
@section('page-title', 'Create New Hero Slide')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.slides.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Create New Hero Slide</h1>
                <p class="text-gray-500 mt-1">Add a new slide to the homepage carousel</p>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Basic Information</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors @error('title') border-red-300 @enderror"
                                       placeholder="Enter slide title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea id="description" name="description" rows="3"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors resize-y @error('description') border-red-300 @enderror"
                                          placeholder="Enter slide description">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Media Settings -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Media Settings</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Media Type -->
                            <div>
                                <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Media Type <span class="text-red-500">*</span>
                                </label>
                                <select id="media_type" name="media_type" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <option value="image" {{ old('media_type', 'image') === 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                            </div>

                            <!-- Media Source Toggle -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Media Source <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="media_source" value="file" checked
                                               class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]"
                                               onchange="toggleMediaInput('file')">
                                        <span class="text-sm text-gray-700">Upload File</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="media_source" value="url"
                                               class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]"
                                               onchange="toggleMediaInput('url')">
                                        <span class="text-sm text-gray-700">External URL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div id="file-upload-section">
                                <label for="media_file" class="block text-sm font-medium text-gray-700 mb-1">
                                    Upload Media File <span class="text-red-500">*</span>
                                </label>
                                <input type="file" id="media_file" name="media_file" accept="image/*,video/*"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#021c47] file:text-white file:text-sm file:font-medium hover:file:bg-[#93db4d] hover:file:text-[#021c47]">
                                <p class="mt-1 text-xs text-gray-500">
                                    <strong>Supported:</strong> JPG, PNG, GIF, WebP, MP4, AVI, MOV (Max: 200MB)<br>
                                    <strong>Best for Images:</strong> 1920×1080px • <strong>Best for Videos:</strong> MP4 H.264
                                </p>
                                <div id="file-size-warning" class="hidden mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                                    <strong>⚠️ Large file:</strong> <span id="file-size-message"></span>
                                </div>
                            </div>

                            <!-- URL Input (Hidden by default) -->
                            <div id="url-input-section" class="hidden">
                                <label for="media_path" class="block text-sm font-medium text-gray-700 mb-1">
                                    Media URL <span class="text-red-500">*</span>
                                </label>
                                <input type="url" id="media_path" name="media_path" value="{{ old('media_path') }}"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                       placeholder="https://example.com/image.jpg">
                                <p class="mt-1 text-xs text-gray-500">Enter the full URL to your image or video file</p>
                            </div>
                        </div>
                    </div>

                    <!-- Button & Link Settings -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Button & Link Settings</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <!-- Target URL -->
                            <div>
                                <label for="target_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Target URL
                                </label>
                                <input type="url" id="target_url" name="target_url" value="{{ old('target_url') }}"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                       placeholder="https://example.com">
                                <p class="mt-1 text-xs text-gray-500">Where should users go when they click the button?</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Button Text -->
                                <div>
                                    <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">
                                        Button Text
                                    </label>
                                    <input type="text" id="button_text" name="button_text" value="{{ old('button_text') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                           placeholder="Learn More">
                                </div>

                                <!-- Button Color -->
                                <div>
                                    <label for="button_color" class="block text-sm font-medium text-gray-700 mb-1">
                                        Button Color
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="color" id="button_color" name="button_color" value="{{ old('button_color', '#93db4d') }}"
                                               class="w-14 h-10 border border-gray-200 rounded-lg cursor-pointer">
                                        <input type="text" id="button_color_text" value="{{ old('button_color', '#93db4d') }}"
                                               class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors font-mono text-sm"
                                               placeholder="#93db4d">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule & Order -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Schedule & Order</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Display Order -->
                                <div>
                                    <label for="order" class="block text-sm font-medium text-gray-700 mb-1">
                                        Display Order <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" id="order" name="order" value="{{ old('order', 0) }}" required min="0"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Lower = first</p>
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Start Date
                                    </label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        End Date
                                    </label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded border-gray-300 text-[#93db4d] focus:ring-[#93db4d]">
                                    <span class="text-sm font-medium text-gray-700">Active (Slide will be visible on website)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Preview -->
                <div class="space-y-6">
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden sticky top-4">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Live Preview</h2>
                        </div>
                        <div class="p-4">
                            <div id="slide-preview" class="rounded-xl overflow-hidden min-h-[200px] flex flex-col items-center justify-center text-white text-center p-6"
                                 style="background: linear-gradient(135deg, #76d37a 0%, #021c47 100%);">
                                <div class="bg-black/50 p-4 rounded-lg">
                                    <h3 id="preview-title" class="text-lg font-bold mb-2">Your slide title</h3>
                                    <p id="preview-description" class="text-sm opacity-90 mb-3">Description goes here</p>
                                    <button id="preview-button" class="px-4 py-1.5 rounded-full text-sm font-medium"
                                            style="background: #93db4d; color: #021c47;">
                                        Button Text
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2 text-center">Preview updates as you type</p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="p-4 space-y-3">
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Create Slide
                            </button>
                            <a href="{{ route('admin.slides.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function toggleMediaInput(type) {
    const fileSection = document.getElementById('file-upload-section');
    const urlSection = document.getElementById('url-input-section');
    const fileInput = document.getElementById('media_file');
    const urlInput = document.getElementById('media_path');

    if (type === 'file') {
        fileSection.classList.remove('hidden');
        urlSection.classList.add('hidden');
        fileInput.required = true;
        urlInput.required = false;
        urlInput.value = '';
    } else {
        fileSection.classList.add('hidden');
        urlSection.classList.remove('hidden');
        fileInput.required = false;
        urlInput.required = true;
        fileInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Live preview
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const buttonTextInput = document.getElementById('button_text');
    const buttonColorInput = document.getElementById('button_color');
    const buttonColorText = document.getElementById('button_color_text');

    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewButton = document.getElementById('preview-button');

    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Your slide title';
        previewDescription.textContent = descriptionInput.value || 'Description goes here';
        previewButton.textContent = buttonTextInput.value || 'Button Text';
        previewButton.style.backgroundColor = buttonColorInput.value;
    }

    titleInput.addEventListener('input', updatePreview);
    descriptionInput.addEventListener('input', updatePreview);
    buttonTextInput.addEventListener('input', updatePreview);
    buttonColorInput.addEventListener('input', function() {
        buttonColorText.value = this.value;
        updatePreview();
    });
    buttonColorText.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            buttonColorInput.value = this.value;
            updatePreview();
        }
    });

    // File size warning
    const fileInput = document.getElementById('media_file');
    const fileSizeWarning = document.getElementById('file-size-warning');
    const fileSizeMessage = document.getElementById('file-size-message');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        fileSizeWarning.classList.add('hidden');

        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            if (fileSize > 200) {
                alert(`File too large (${fileSize}MB). Maximum: 200MB`);
                this.value = '';
                return;
            }
            if (fileSize > 50) {
                fileSizeWarning.classList.remove('hidden');
                fileSizeMessage.textContent = `${fileSize}MB - Consider optimizing for better performance.`;
            }
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const mediaSource = document.querySelector('input[name="media_source"]:checked').value;
        if (mediaSource === 'file' && !document.getElementById('media_file').files.length) {
            e.preventDefault();
            alert('Please select a media file to upload.');
            return;
        }
        if (mediaSource === 'url' && !document.getElementById('media_path').value.trim()) {
            e.preventDefault();
            alert('Please enter a media URL.');
            return;
        }
    });
});
</script>
@endpush
