@extends('layouts.admin')

@section('title', 'Edit Hero Slide - BixCash Admin')
@section('page-title', 'Edit Hero Slide')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Slide: {{ $slide->title }}</h3>
            <div>
                <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary">
                    Back to Slides
                </a>
                <a href="{{ route('admin.slides.show', $slide) }}" class="btn btn-info">
                    View Slide
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.slides.update', $slide) }}" id="slideForm">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="title">Title *</label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $slide->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3">{{ old('description', $slide->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Media Configuration -->
                        <div class="form-group">
                            <label for="media_type">Media Type *</label>
                            <select class="form-control @error('media_type') is-invalid @enderror"
                                    id="media_type"
                                    name="media_type"
                                    required>
                                <option value="image" {{ old('media_type', $slide->media_type) == 'image' ? 'selected' : '' }}>Image</option>
                                <option value="video" {{ old('media_type', $slide->media_type) == 'video' ? 'selected' : '' }}>Video</option>
                            </select>
                            @error('media_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="media_path">Media URL *</label>
                            <input type="url"
                                   class="form-control @error('media_path') is-invalid @enderror"
                                   id="media_path"
                                   name="media_path"
                                   value="{{ old('media_path', $slide->media_path) }}"
                                   placeholder="https://example.com/image.jpg"
                                   required>
                            @error('media_path')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter the full URL to your image or video file</small>
                        </div>

                        <!-- Action Button Configuration -->
                        <div class="form-group">
                            <label for="target_url">Target URL</label>
                            <input type="url"
                                   class="form-control @error('target_url') is-invalid @enderror"
                                   id="target_url"
                                   name="target_url"
                                   value="{{ old('target_url', $slide->target_url) }}"
                                   placeholder="https://example.com">
                            @error('target_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Where should the button link to? (optional)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="button_text">Button Text</label>
                                    <input type="text"
                                           class="form-control @error('button_text') is-invalid @enderror"
                                           id="button_text"
                                           name="button_text"
                                           value="{{ old('button_text', $slide->button_text) }}"
                                           placeholder="Learn More">
                                    @error('button_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="button_color">Button Color</label>
                                    <input type="color"
                                           class="form-control @error('button_color') is-invalid @enderror"
                                           id="button_color"
                                           name="button_color"
                                           value="{{ old('button_color', $slide->button_color ?: '#3498db') }}">
                                    @error('button_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Scheduling -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="datetime-local"
                                           class="form-control @error('start_date') is-invalid @enderror"
                                           id="start_date"
                                           name="start_date"
                                           value="{{ old('start_date', $slide->start_date ? $slide->start_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="datetime-local"
                                           class="form-control @error('end_date') is-invalid @enderror"
                                           id="end_date"
                                           name="end_date"
                                           value="{{ old('end_date', $slide->end_date ? $slide->end_date->format('Y-m-d\TH:i') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order">Display Order *</label>
                                    <input type="number"
                                           class="form-control @error('order') is-invalid @enderror"
                                           id="order"
                                           name="order"
                                           value="{{ old('order', $slide->order) }}"
                                           min="0"
                                           required>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check" style="margin-top: 2rem;">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', $slide->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active (visible on website)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Live Preview -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Live Preview</h5>
                            </div>
                            <div class="card-body" style="background: #f8f9fa;">
                                <div id="slidePreview" style="
                                    background: linear-gradient(135deg, #76d37a 0%, #021c47 100%);
                                    color: white;
                                    padding: 2rem 1rem;
                                    border-radius: 10px;
                                    text-align: center;
                                    min-height: 200px;
                                    display: flex;
                                    flex-direction: column;
                                    justify-content: center;
                                    background-size: cover;
                                    background-position: center;
                                    position: relative;
                                ">
                                    <div style="background: rgba(0,0,0,0.5); padding: 1rem; border-radius: 8px;">
                                        <h3 id="previewTitle">{{ $slide->title }}</h3>
                                        <p id="previewDescription">{{ $slide->description }}</p>
                                        <button id="previewButton"
                                                style="background: {{ $slide->button_color ?: '#3498db' }}; color: white; border: none; padding: 0.5rem 1rem; border-radius: 25px; margin-top: 0.5rem;">
                                            {{ $slide->button_text ?: 'Learn More' }}
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted mt-2 d-block">Preview updates as you type</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Update Slide</button>
                    <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Live preview updates
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('title');
            const descriptionInput = document.getElementById('description');
            const buttonTextInput = document.getElementById('button_text');
            const buttonColorInput = document.getElementById('button_color');
            const mediaPathInput = document.getElementById('media_path');
            const mediaTypeInput = document.getElementById('media_type');

            const previewTitle = document.getElementById('previewTitle');
            const previewDescription = document.getElementById('previewDescription');
            const previewButton = document.getElementById('previewButton');
            const slidePreview = document.getElementById('slidePreview');

            function updatePreview() {
                previewTitle.textContent = titleInput.value || 'Slide Title';
                previewDescription.textContent = descriptionInput.value || 'Slide description appears here';
                previewButton.textContent = buttonTextInput.value || 'Learn More';
                previewButton.style.backgroundColor = buttonColorInput.value || '#3498db';

                if (mediaPathInput.value && mediaTypeInput.value === 'image') {
                    slidePreview.style.backgroundImage = `url(${mediaPathInput.value})`;
                } else {
                    slidePreview.style.backgroundImage = '';
                }
            }

            titleInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            buttonTextInput.addEventListener('input', updatePreview);
            buttonColorInput.addEventListener('input', updatePreview);
            mediaPathInput.addEventListener('input', updatePreview);
            mediaTypeInput.addEventListener('change', updatePreview);
        });
    </script>
@endsection