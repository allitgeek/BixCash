@extends('layouts.admin')

@section('title', 'Edit Hero Slide - BixCash Admin')
@section('page-title', 'Edit Hero Slide')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.slides.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-[#021c47]">Edit Slide</h1>
                    <p class="text-gray-500 mt-1">{{ $slide->title }}</p>
                </div>
            </div>
            <a href="{{ route('admin.slides.show', $slide) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                View Slide
            </a>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.slides.update', $slide) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

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
                                <input type="text" id="title" name="title" value="{{ old('title', $slide->title) }}" required
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
                                          placeholder="Enter slide description">{{ old('description', $slide->description) }}</textarea>
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
                            <!-- Current Media Display -->
                            <div id="current-media-section" class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Media</p>
                                <div class="flex items-start gap-4">
                                    @if($slide->media_type === 'image')
                                        <img src="{{ $slide->media_path }}" alt="Current slide" class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <video class="w-32 h-20 object-cover rounded-lg border border-gray-200" muted>
                                            <source src="{{ $slide->media_path }}" type="video/mp4">
                                        </video>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-600">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $slide->media_type === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                                {{ ucfirst($slide->media_type) }}
                                            </span>
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 truncate" title="{{ $slide->media_path }}">
                                            {{ filter_var($slide->media_path, FILTER_VALIDATE_URL) ? 'External URL' : 'Uploaded File' }}: {{ Str::limit(basename($slide->media_path), 30) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Media Type -->
                            <div>
                                <label for="media_type" class="block text-sm font-medium text-gray-700 mb-1">
                                    Media Type <span class="text-red-500">*</span>
                                </label>
                                <select id="media_type" name="media_type" required
                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <option value="image" {{ old('media_type', $slide->media_type) === 'image' ? 'selected' : '' }}>Image</option>
                                    <option value="video" {{ old('media_type', $slide->media_type) === 'video' ? 'selected' : '' }}>Video</option>
                                </select>
                            </div>

                            <!-- Media Source Toggle -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Media Source
                                </label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="media_source" value="keep"
                                               {{ !old('media_source') ? 'checked' : (old('media_source') === 'keep' ? 'checked' : '') }}
                                               class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]"
                                               onchange="toggleMediaInputEdit('keep')">
                                        <span class="text-sm text-gray-700">Keep Current</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="media_source" value="file"
                                               {{ old('media_source') === 'file' ? 'checked' : '' }}
                                               class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]"
                                               onchange="toggleMediaInputEdit('file')">
                                        <span class="text-sm text-gray-700">Upload New File</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="media_source" value="url"
                                               {{ old('media_source') === 'url' ? 'checked' : '' }}
                                               class="w-4 h-4 text-[#93db4d] border-gray-300 focus:ring-[#93db4d]"
                                               onchange="toggleMediaInputEdit('url')">
                                        <span class="text-sm text-gray-700">External URL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- File Upload (Hidden by default) -->
                            <div id="file-upload-section-edit" class="hidden">
                                <label for="media_file" class="block text-sm font-medium text-gray-700 mb-1">
                                    Upload New Media File
                                </label>
                                <input type="file" id="media_file" name="media_file" accept="image/*,video/*"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-[#021c47] file:text-white file:text-sm file:font-medium hover:file:bg-[#93db4d] hover:file:text-[#021c47]">
                                <p class="mt-1 text-xs text-gray-500">
                                    <strong>Supported:</strong> JPG, PNG, GIF, WebP, MP4, AVI, MOV (Max: 200MB)
                                </p>
                                <div id="file-size-warning-edit" class="hidden mt-2 p-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                                    <strong>⚠️ Large file:</strong> <span id="file-size-message-edit"></span>
                                </div>
                            </div>

                            <!-- URL Input (Hidden by default) -->
                            <div id="url-input-section-edit" class="hidden">
                                <label for="media_path" class="block text-sm font-medium text-gray-700 mb-1">
                                    Media URL
                                </label>
                                <input type="url" id="media_path" name="media_path" value="{{ old('media_path', $slide->media_path) }}"
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
                                <input type="url" id="target_url" name="target_url" value="{{ old('target_url', $slide->target_url) }}"
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
                                    <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $slide->button_text) }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                           placeholder="Learn More">
                                </div>

                                <!-- Button Color -->
                                <div>
                                    <label for="button_color" class="block text-sm font-medium text-gray-700 mb-1">
                                        Button Color
                                    </label>
                                    <div class="flex gap-2">
                                        <input type="color" id="button_color" name="button_color" value="{{ old('button_color', $slide->button_color ?: '#93db4d') }}"
                                               class="w-14 h-10 border border-gray-200 rounded-lg cursor-pointer">
                                        <input type="text" id="button_color_text" value="{{ old('button_color', $slide->button_color ?: '#93db4d') }}"
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
                                    <input type="number" id="order" name="order" value="{{ old('order', $slide->order) }}" required min="0"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                    <p class="mt-1 text-xs text-gray-500">Lower = first</p>
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        Start Date
                                    </label>
                                    <input type="datetime-local" id="start_date" name="start_date"
                                           value="{{ old('start_date', $slide->start_date ? $slide->start_date->format('Y-m-d\TH:i') : '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>

                                <!-- End Date -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">
                                        End Date
                                    </label>
                                    <input type="datetime-local" id="end_date" name="end_date"
                                           value="{{ old('end_date', $slide->end_date ? $slide->end_date->format('Y-m-d\TH:i') : '') }}"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}
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
                                 style="background: linear-gradient(135deg, #76d37a 0%, #021c47 100%);
                                        {{ $slide->media_path && $slide->media_type === 'image' ? 'background-image: url(' . $slide->media_path . ');' : '' }}
                                        background-size: cover; background-position: center;">
                                <div class="bg-black/50 p-4 rounded-lg">
                                    <h3 id="preview-title" class="text-lg font-bold mb-2">{{ $slide->title }}</h3>
                                    <p id="preview-description" class="text-sm opacity-90 mb-3">{{ $slide->description ?: 'Description goes here' }}</p>
                                    <button id="preview-button" class="px-4 py-1.5 rounded-full text-sm font-medium"
                                            style="background: {{ $slide->button_color ?: '#93db4d' }}; color: {{ $slide->button_color ? 'white' : '#021c47' }};">
                                        {{ $slide->button_text ?: 'Button Text' }}
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Slide
                            </button>
                            <a href="{{ route('admin.slides.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                Cancel
                            </a>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-white rounded-xl border border-red-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-red-200 bg-red-50">
                            <h2 class="font-semibold text-red-700">Danger Zone</h2>
                        </div>
                        <div class="p-4">
                            <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this slide? This action cannot be undone.')"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Slide
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
function toggleMediaInputEdit(type) {
    const fileSection = document.getElementById('file-upload-section-edit');
    const urlSection = document.getElementById('url-input-section-edit');
    const fileInput = document.getElementById('media_file');
    const urlInput = document.getElementById('media_path');

    fileSection.classList.add('hidden');
    urlSection.classList.add('hidden');
    fileInput.required = false;
    urlInput.required = false;

    if (type === 'file') {
        fileSection.classList.remove('hidden');
        fileInput.required = true;
        fileInput.value = '';
        urlInput.value = '';
    } else if (type === 'url') {
        urlSection.classList.remove('hidden');
        urlInput.required = true;
        fileInput.value = '';
    } else {
        // keep current - no additional inputs
        fileInput.value = '';
        urlInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Live preview
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const buttonTextInput = document.getElementById('button_text');
    const buttonColorInput = document.getElementById('button_color');
    const buttonColorText = document.getElementById('button_color_text');
    const mediaPathInput = document.getElementById('media_path');
    const mediaTypeInput = document.getElementById('media_type');

    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const previewButton = document.getElementById('preview-button');
    const slidePreview = document.getElementById('slide-preview');

    function updatePreview() {
        previewTitle.textContent = titleInput.value || 'Slide Title';
        previewDescription.textContent = descriptionInput.value || 'Slide description';
        previewButton.textContent = buttonTextInput.value || 'Button Text';
        previewButton.style.backgroundColor = buttonColorInput.value || '#93db4d';

        if (mediaPathInput.value && mediaTypeInput.value === 'image') {
            slidePreview.style.backgroundImage = `url(${mediaPathInput.value})`;
        }
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
    mediaPathInput.addEventListener('input', updatePreview);
    mediaTypeInput.addEventListener('change', updatePreview);

    // File size warning
    const fileInput = document.getElementById('media_file');
    const fileSizeWarning = document.getElementById('file-size-warning-edit');
    const fileSizeMessage = document.getElementById('file-size-message-edit');

    if (fileInput) {
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
    }
});
</script>
@endpush
