@extends('layouts.admin')

@section('title', 'View Hero Slide - BixCash Admin')
@section('page-title', 'View Hero Slide')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $slide->title }}</h3>
            <div>
                <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary">
                    Back to Slides
                </a>
                <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-warning">
                    Edit Slide
                </a>
                @if($slide->is_active)
                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this slide?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success">
                            Activate
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Slide Preview -->
                    <div class="slide-preview" style="
                        background: linear-gradient(135deg, #76d37a 0%, #021c47 100%);
                        {{ $slide->media_path && $slide->media_type === 'image' ? 'background-image: url(' . $slide->media_path . ');' : '' }}
                        background-size: cover;
                        background-position: center;
                        color: white;
                        padding: 4rem 2rem;
                        border-radius: 15px;
                        text-align: center;
                        position: relative;
                        margin-bottom: 2rem;
                        min-height: 300px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        @if($slide->media_type === 'video' && $slide->media_path)
                            <video autoplay muted loop style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                <source src="{{ $slide->media_path }}" type="video/mp4">
                            </video>
                        @endif

                        <div style="background: rgba(0,0,0,0.6); padding: 2rem; border-radius: 10px; position: relative; z-index: 2;">
                            <h1 style="font-size: 2.5rem; margin-bottom: 1rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                                {{ $slide->title }}
                            </h1>
                            @if($slide->description)
                                <p style="font-size: 1.2rem; margin-bottom: 2rem; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                                    {{ $slide->description }}
                                </p>
                            @endif
                            @if($slide->button_text && $slide->target_url)
                                <a href="{{ $slide->target_url }}"
                                   target="_blank"
                                   style="
                                       background: {{ $slide->button_color ?: '#3498db' }};
                                       color: white;
                                       padding: 1rem 2rem;
                                       text-decoration: none;
                                       border-radius: 25px;
                                       font-weight: bold;
                                       box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                                       display: inline-block;
                                   ">
                                    {{ $slide->button_text }}
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Slide Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $slide->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $slide->description ?: 'No description' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Media Type:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($slide->media_type) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($slide->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Display Order:</strong></td>
                                    <td>{{ $slide->order }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Action Button & Links</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Target URL:</strong></td>
                                    <td>
                                        @if($slide->target_url)
                                            <a href="{{ $slide->target_url }}" target="_blank">
                                                {{ $slide->target_url }}
                                                <i class="fas fa-external-link-alt" style="font-size: 0.8em;"></i>
                                            </a>
                                        @else
                                            No target URL
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Button Text:</strong></td>
                                    <td>{{ $slide->button_text ?: 'No button text' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Button Color:</strong></td>
                                    <td>
                                        @if($slide->button_color)
                                            <span style="
                                                background: {{ $slide->button_color }};
                                                color: white;
                                                padding: 0.25rem 0.5rem;
                                                border-radius: 3px;
                                                font-size: 0.8rem;
                                            ">{{ $slide->button_color }}</span>
                                        @else
                                            Default (#3498db)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Media URL:</strong></td>
                                    <td>
                                        @if($slide->media_path)
                                            <a href="{{ $slide->media_path }}" target="_blank" style="word-break: break-all;">
                                                {{ Str::limit($slide->media_path, 40) }}
                                                <i class="fas fa-external-link-alt" style="font-size: 0.8em;"></i>
                                            </a>
                                        @else
                                            No media URL
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Scheduling Information -->
                    @if($slide->start_date || $slide->end_date)
                        <div class="mt-4">
                            <h5>Scheduling</h5>
                            <table class="table table-sm">
                                @if($slide->start_date)
                                    <tr>
                                        <td><strong>Start Date:</strong></td>
                                        <td>{{ $slide->start_date->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @endif
                                @if($slide->end_date)
                                    <tr>
                                        <td><strong>End Date:</strong></td>
                                        <td>{{ $slide->end_date->format('M j, Y g:i A') }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Metadata -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Metadata</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $slide->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $slide->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                @if($slide->creator)
                                    <tr>
                                        <td><strong>Created by:</strong></td>
                                        <td>{{ $slide->creator->name }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.slides.edit', $slide) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Slide
                                </a>

                                @if($slide->is_active)
                                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to deactivate this slide?')">
                                            <i class="fas fa-eye-slash"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-eye"></i> Activate
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this slide? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection