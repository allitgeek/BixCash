@extends('layouts.admin')

@section('title', 'View Hero Slide - BixCash Admin')
@section('page-title', 'View Hero Slide')

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
                    <h1 class="text-2xl font-bold text-[#021c47]">{{ $slide->title ?: 'Untitled Slide' }}</h1>
                    <p class="text-gray-500 mt-1">Slide #{{ $slide->order }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.slides.edit', $slide) }}" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Slide
                </a>
                @if($slide->is_active)
                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" onclick="return confirm('Deactivate this slide?')" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg hover:bg-[#7bc73d] transition-colors font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Activate
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Slide Preview -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h2 class="font-semibold text-[#021c47]">Preview</h2>
                    </div>
                    <div class="p-4">
                        <div class="relative rounded-xl overflow-hidden min-h-[300px] flex items-center justify-center"
                             style="background: linear-gradient(135deg, #76d37a 0%, #021c47 100%);
                                    {{ $slide->media_path && $slide->media_type === 'image' ? 'background-image: url(' . $slide->media_path . ');' : '' }}
                                    background-size: cover; background-position: center;">
                            @if($slide->media_type === 'video' && $slide->media_path)
                                <video autoplay muted loop class="absolute inset-0 w-full h-full object-cover">
                                    <source src="{{ $slide->media_path }}" type="video/mp4">
                                </video>
                            @endif

                            <div class="relative z-10 bg-black/60 p-8 rounded-xl text-center text-white max-w-lg">
                                <h1 class="text-3xl font-bold mb-3" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                                    {{ $slide->title }}
                                </h1>
                                @if($slide->description)
                                    <p class="text-lg mb-6 opacity-90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                                        {{ $slide->description }}
                                    </p>
                                @endif
                                @if($slide->button_text && $slide->target_url)
                                    <a href="{{ $slide->target_url }}" target="_blank"
                                       class="inline-block px-6 py-3 rounded-full font-bold shadow-lg transition-transform hover:scale-105"
                                       style="background: {{ $slide->button_color ?: '#93db4d' }}; color: {{ $slide->button_color ? 'white' : '#021c47' }};">
                                        {{ $slide->button_text }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Basic Information</h2>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Title</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $slide->title ?: '-' }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Description</dt>
                                    <dd class="text-sm font-medium text-gray-900 text-right max-w-[200px]">{{ $slide->description ?: '-' }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-sm text-gray-500">Media Type</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $slide->media_type === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ ucfirst($slide->media_type) }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-sm text-gray-500">Status</dt>
                                    <dd>
                                        @if($slide->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a9a2e]">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">Inactive</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Display Order</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $slide->order }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Button & Links -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Button & Links</h2>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm text-gray-500 mb-1">Target URL</dt>
                                    <dd class="text-sm font-medium">
                                        @if($slide->target_url)
                                            <a href="{{ $slide->target_url }}" target="_blank" class="text-[#021c47] hover:text-[#93db4d] break-all flex items-center gap-1">
                                                {{ Str::limit($slide->target_url, 35) }}
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400">No target URL</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Button Text</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $slide->button_text ?: '-' }}</dd>
                                </div>
                                <div class="flex justify-between items-center">
                                    <dt class="text-sm text-gray-500">Button Color</dt>
                                    <dd class="flex items-center gap-2">
                                        @if($slide->button_color)
                                            <span class="w-5 h-5 rounded border border-gray-200" style="background: {{ $slide->button_color }}"></span>
                                            <span class="text-sm font-mono text-gray-600">{{ $slide->button_color }}</span>
                                        @else
                                            <span class="text-sm text-gray-400">Default</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500 mb-1">Media URL</dt>
                                    <dd class="text-sm font-medium">
                                        @if($slide->media_path)
                                            <a href="{{ $slide->media_path }}" target="_blank" class="text-[#021c47] hover:text-[#93db4d] break-all flex items-center gap-1">
                                                {{ Str::limit($slide->media_path, 35) }}
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="text-gray-400">No media URL</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Scheduling (if applicable) -->
                @if($slide->start_date || $slide->end_date)
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                            <h2 class="font-semibold text-[#021c47]">Scheduling</h2>
                        </div>
                        <div class="p-4">
                            <div class="grid grid-cols-2 gap-4">
                                @if($slide->start_date)
                                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-green-600 font-medium">Start Date</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $slide->start_date->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                                @if($slide->end_date)
                                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-red-600 font-medium">End Date</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $slide->end_date->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Metadata -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h2 class="font-semibold text-[#021c47]">Metadata</h2>
                    </div>
                    <div class="p-4">
                        <dl class="space-y-3">
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Created</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $slide->created_at->format('M j, Y') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Time</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $slide->created_at->format('g:i A') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm text-gray-500">Updated</dt>
                                <dd class="text-sm font-medium text-gray-900">{{ $slide->updated_at->format('M j, Y g:i A') }}</dd>
                            </div>
                            @if($slide->creator)
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Created by</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $slide->creator->name }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                        <h2 class="font-semibold text-[#021c47]">Quick Actions</h2>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.slides.edit', $slide) }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit Slide
                        </a>

                        @if($slide->is_active)
                            <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Deactivate this slide?')" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                    </svg>
                                    Deactivate
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg hover:bg-[#7bc73d] transition-colors font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Activate
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to delete this slide? This action cannot be undone.')" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete Slide
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Back Link -->
                <a href="{{ route('admin.slides.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 text-gray-600 hover:text-[#021c47] bg-white rounded-xl border border-gray-200 shadow-sm hover:border-[#93db4d] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to All Slides
                </a>
            </div>
        </div>
    </div>
@endsection
