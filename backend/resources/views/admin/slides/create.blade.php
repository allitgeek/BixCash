@extends('layouts.admin')

@section('title', 'Create Hero Slide - BixCash Admin')
@section('page-title', 'Create New Hero Slide')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New Hero Slide</h3>
            <div>
                <a href="{{ route('admin.slides.index') }}" class="btn" style="background: #6c757d; color: white;">
                    Back to Slides
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <!-- Left Column -->
                    <div>
                        <!-- Title -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Title <span style="color: #e74c3c;">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;"
                                   placeholder="Enter slide title">
                        </div>

                        <!-- Description -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; resize: vertical;"
                                      placeholder="Enter slide description">{{ old('description') }}</textarea>
                        </div>

                        <!-- Media Type -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="media_type" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Media Type <span style="color: #e74c3c;">*</span>
                            </label>
                            <select id="media_type" name="media_type" required
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                                <option value="image" {{ old('media_type', 'image') === 'image' ? 'selected' : '' }}>Image</option>
                                <option value="video" {{ old('media_type') === 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                        </div>

                        <!-- Media Input Type Toggle -->
                        <div style="margin-bottom: 1rem;">
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Media Source <span style="color: #e74c3c;">*</span>
                            </label>
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <label style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="radio" name="media_source" value="file" checked
                                           style="margin-right: 0.5rem;" onchange="toggleMediaInput('file')">
                                    <span>Upload File</span>
                                </label>
                                <label style="display: flex; align-items: center; cursor: pointer;">
                                    <input type="radio" name="media_source" value="url"
                                           style="margin-right: 0.5rem;" onchange="toggleMediaInput('url')">
                                    <span>External URL</span>
                                </label>
                            </div>
                        </div>

                        <!-- File Upload Option -->
                        <div id="file-upload-section" style="margin-bottom: 1.5rem;">
                            <label for="media_file" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Upload Media File <span style="color: #e74c3c;">*</span>
                            </label>
                            <input type="file" id="media_file" name="media_file"
                                   accept="image/*,video/*"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            <small style="color: #666;">
                                <strong>Supported formats:</strong> JPG, PNG, GIF, WebP, MP4, AVI, MOV, WMV (Max: 20MB)<br>
                                <strong>📸 Best for Images:</strong> WebP/JPG at 1920x1080px (500KB-1MB) |
                                <strong>🎥 Best for Videos:</strong> MP4 H.264 at 1920x1080p (3-7MB)
                            </small>
                        </div>

                        <!-- URL Input Option (Hidden by default) -->
                        <div id="url-input-section" style="margin-bottom: 1.5rem; display: none;">
                            <label for="media_path" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Media URL <span style="color: #e74c3c;">*</span>
                            </label>
                            <input type="url" id="media_path" name="media_path" value="{{ old('media_path') }}"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;"
                                   placeholder="https://example.com/image.jpg">
                            <small style="color: #666;">Enter the full URL to your image or video file</small>
                        </div>

                        <!-- Target URL -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="target_url" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Target URL
                            </label>
                            <input type="url" id="target_url" name="target_url" value="{{ old('target_url') }}"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;"
                                   placeholder="https://example.com">
                            <small style="color: #666;">Where should users go when they click this slide?</small>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Button Text -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="button_text" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Button Text
                            </label>
                            <input type="text" id="button_text" name="button_text" value="{{ old('button_text') }}"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;"
                                   placeholder="Learn More">
                        </div>

                        <!-- Button Color -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="button_color" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Button Color
                            </label>
                            <input type="color" id="button_color" name="button_color" value="{{ old('button_color', '#3498db') }}"
                                   style="width: 100%; height: 50px; border: 1px solid #ddd; border-radius: 5px;">
                        </div>

                        <!-- Order -->
                        <div style="margin-bottom: 1.5rem;">
                            <label for="order" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                Display Order <span style="color: #e74c3c;">*</span>
                                <small style="color: #666; font-weight: normal;">(Lower numbers appear first)</small>
                            </label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" required min="0"
                                   style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                        </div>

                        <!-- Schedule -->
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                            <div>
                                <label for="start_date" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                    Start Date
                                </label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            </div>
                            <div>
                                <label for="end_date" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333;">
                                    End Date
                                </label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem;">
                            </div>
                        </div>

                        <!-- Status -->
                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                       style="margin-right: 0.5rem; transform: scale(1.2);">
                                <span style="font-weight: 500; color: #333;">Active (Slide will be visible on website)</span>
                            </label>
                        </div>

                        <!-- Preview Box -->
                        <div style="background: #f8f9fa; padding: 1rem; border-radius: 5px; border: 1px solid #dee2e6;">
                            <h5 style="margin-bottom: 0.5rem; color: #333;">Preview</h5>
                            <div id="slide-preview" style="background: #3498db; color: white; padding: 1rem; border-radius: 3px; min-height: 100px; text-align: center;">
                                <h6 id="preview-title">Your slide title will appear here</h6>
                                <p id="preview-description" style="margin: 0.5rem 0; opacity: 0.9; font-size: 0.9rem;">Description goes here</p>
                                <button id="preview-button" style="background: #3498db; color: white; border: 1px solid white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                    Button Text
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        Create Slide
                    </button>
                    <a href="{{ route('admin.slides.index') }}" class="btn" style="background: #6c757d; color: white;">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Toggle between file upload and URL input
    function toggleMediaInput(type) {
        const fileSection = document.getElementById('file-upload-section');
        const urlSection = document.getElementById('url-input-section');
        const fileInput = document.getElementById('media_file');
        const urlInput = document.getElementById('media_path');

        if (type === 'file') {
            fileSection.style.display = 'block';
            urlSection.style.display = 'none';
            fileInput.required = true;
            urlInput.required = false;
            urlInput.value = ''; // Clear URL when switching to file
        } else {
            fileSection.style.display = 'none';
            urlSection.style.display = 'block';
            fileInput.required = false;
            urlInput.required = true;
            fileInput.value = ''; // Clear file when switching to URL
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Live preview functionality
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        const buttonTextInput = document.getElementById('button_text');
        const buttonColorInput = document.getElementById('button_color');

        const previewTitle = document.getElementById('preview-title');
        const previewDescription = document.getElementById('preview-description');
        const previewButton = document.getElementById('preview-button');

        function updatePreview() {
            previewTitle.textContent = titleInput.value || 'Your slide title will appear here';
            previewDescription.textContent = descriptionInput.value || 'Description goes here';
            previewButton.textContent = buttonTextInput.value || 'Button Text';
            previewButton.style.backgroundColor = buttonColorInput.value;
        }

        titleInput.addEventListener('input', updatePreview);
        descriptionInput.addEventListener('input', updatePreview);
        buttonTextInput.addEventListener('input', updatePreview);
        buttonColorInput.addEventListener('input', updatePreview);

        // Enhanced form validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const title = titleInput.value.trim();
            const order = document.getElementById('order').value;
            const mediaSource = document.querySelector('input[name="media_source"]:checked').value;

            if (!title) {
                e.preventDefault();
                alert('Please enter a slide title.');
                titleInput.focus();
                return;
            }

            // Check media input based on selected source
            if (mediaSource === 'file') {
                const fileInput = document.getElementById('media_file');
                if (!fileInput.files || !fileInput.files[0]) {
                    e.preventDefault();
                    alert('Please select a media file to upload.');
                    fileInput.focus();
                    return;
                }
            } else {
                const mediaPath = document.getElementById('media_path').value.trim();
                if (!mediaPath) {
                    e.preventDefault();
                    alert('Please enter a media URL.');
                    document.getElementById('media_path').focus();
                    return;
                }
            }

            if (order === '' || order < 0) {
                e.preventDefault();
                alert('Please enter a valid display order (0 or higher).');
                document.getElementById('order').focus();
                return;
            }
        });

        // File upload preview
        const fileInput = document.getElementById('media_file');
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                if (fileSize > 20) {
                    alert('File size must be less than 20MB');
                    this.value = '';
                    return;
                }

                console.log(`Selected file: ${file.name} (${fileSize}MB)`);
            }
        });
    });
</script>
@endpush