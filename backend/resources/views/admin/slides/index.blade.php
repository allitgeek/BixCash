@extends('layouts.admin')

@section('title', 'Hero Slides Management - BixCash Admin')
@section('page-title', 'Hero Slides')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[#021c47]">Hero Slides</h1>
                <p class="text-gray-500 mt-1">Manage hero carousel slides for the homepage</p>
            </div>
            <a href="{{ route('admin.slides.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Slide
            </a>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#021c47]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#021c47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Slides</p>
                        <p class="text-xl font-bold text-[#021c47]">{{ $slides->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#93db4d]/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active</p>
                        <p class="text-xl font-bold text-[#93db4d]">{{ $slides->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Images</p>
                        <p class="text-xl font-bold text-blue-600">{{ $slides->where('media_type', 'image')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Videos</p>
                        <p class="text-xl font-bold text-purple-600">{{ $slides->where('media_type', 'video')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Search and Filter -->
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <form method="GET" action="{{ route('admin.slides.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors"
                                   placeholder="Search by title or description...">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <select id="status" name="status" class="px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.slides.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            @if($slides->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#021c47] text-white text-left">
                                <th class="px-4 py-3 font-semibold text-center w-12">
                                    <span title="Drag to reorder">⋮⋮</span>
                                </th>
                                <th class="px-4 py-3 font-semibold text-center w-16">#</th>
                                <th class="px-4 py-3 font-semibold">Title</th>
                                <th class="px-4 py-3 font-semibold">Media</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Schedule</th>
                                <th class="px-4 py-3 font-semibold">Created</th>
                                <th class="px-4 py-3 font-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortableSlides">
                            @foreach($slides as $slide)
                                <tr class="slide-row border-b border-gray-100 hover:bg-[#93db4d]/5 transition-colors cursor-move" data-slide-id="{{ $slide->id }}">
                                    <td class="px-4 py-3 text-center">
                                        <div class="drag-handle text-gray-400 hover:text-[#021c47] cursor-grab" title="Drag to reorder">
                                            <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"/>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="order-badge inline-flex items-center justify-center w-8 h-8 bg-[#021c47] text-white rounded-full text-sm font-semibold">
                                            {{ $slide->order }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-medium text-[#021c47]">{{ $slide->title ?: 'Untitled' }}</p>
                                            @if($slide->button_text)
                                                <p class="text-sm text-gray-500 mt-0.5">Button: {{ $slide->button_text }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $slide->media_type === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                            @if($slide->media_type === 'video')
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            @else
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            @endif
                                            {{ ucfirst($slide->media_type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($slide->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a9a2e]">
                                                <span class="w-1.5 h-1.5 bg-[#93db4d] rounded-full mr-1.5"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-600">
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($slide->start_date || $slide->end_date)
                                            <div class="text-sm text-gray-600">
                                                @if($slide->start_date)
                                                    <p>From: {{ $slide->start_date->format('M j, Y') }}</p>
                                                @endif
                                                @if($slide->end_date)
                                                    <p>To: {{ $slide->end_date->format('M j, Y') }}</p>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">Always Active</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-600">
                                            <p>{{ $slide->creator->name ?? 'Unknown' }}</p>
                                            <p class="text-gray-400">{{ $slide->created_at->format('M j, Y') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('admin.slides.show', $slide) }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors" title="View">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.slides.edit', $slide) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="p-2 text-gray-500 hover:text-{{ $slide->is_active ? 'red' : 'green' }}-600 hover:bg-{{ $slide->is_active ? 'red' : 'green' }}-50 rounded-lg transition-colors" title="{{ $slide->is_active ? 'Deactivate' : 'Activate' }}">
                                                    @if($slide->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this slide?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($slides->hasPages())
                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                        {{ $slides->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No slides found</h3>
                    <p class="text-gray-500 mb-4">
                        {{ request()->hasAny(['search', 'status']) ? 'Try adjusting your search criteria.' : 'Get started by creating your first hero slide.' }}
                    </p>
                    @if(!request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.slides.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create First Slide
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('sortableSlides');
    if (!tbody) return;

    let draggedElement = null;
    let placeholder = null;

    const slideRows = tbody.querySelectorAll('.slide-row');
    slideRows.forEach(row => {
        row.draggable = true;

        row.addEventListener('dragstart', function(e) {
            draggedElement = this;
            this.style.opacity = '0.5';
            placeholder = document.createElement('tr');
            placeholder.className = 'drag-placeholder';
            placeholder.innerHTML = '<td colspan="8" class="py-2 bg-[#93db4d]/20 border-2 border-dashed border-[#93db4d] text-center text-[#5a9a2e] font-medium">Drop here</td>';
            e.dataTransfer.effectAllowed = 'move';
        });

        row.addEventListener('dragend', function(e) {
            this.style.opacity = '';
            draggedElement = null;
            if (placeholder && placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }
        });

        row.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
            if (draggedElement && draggedElement !== this) {
                const rect = this.getBoundingClientRect();
                const midPoint = rect.top + rect.height / 2;
                if (placeholder && placeholder.parentNode) {
                    placeholder.parentNode.removeChild(placeholder);
                }
                if (e.clientY < midPoint) {
                    tbody.insertBefore(placeholder, this);
                } else {
                    tbody.insertBefore(placeholder, this.nextSibling);
                }
            }
        });

        row.addEventListener('drop', function(e) {
            e.preventDefault();
            if (draggedElement && draggedElement !== this) {
                const rect = this.getBoundingClientRect();
                const midPoint = rect.top + rect.height / 2;
                if (e.clientY < midPoint) {
                    tbody.insertBefore(draggedElement, this);
                } else {
                    tbody.insertBefore(draggedElement, this.nextSibling);
                }
                updateSlideOrder();
            }
            if (placeholder && placeholder.parentNode) {
                placeholder.parentNode.removeChild(placeholder);
            }
        });
    });

    function updateSlideOrder() {
        const rows = tbody.querySelectorAll('.slide-row');
        const slideOrder = [];
        rows.forEach((row, index) => {
            const orderBadge = row.querySelector('.order-badge');
            if (orderBadge) orderBadge.textContent = index + 1;
            slideOrder.push({ id: parseInt(row.getAttribute('data-slide-id')), order: index + 1 });
        });

        showNotification('Updating slide order...', 'info');

        fetch('{{ route("admin.slides.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({ slides: slideOrder })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Slide order updated!', 'success');
            } else {
                showNotification('Error: ' + (data.message || 'Unknown error'), 'error');
                setTimeout(() => location.reload(), 2000);
            }
        })
        .catch(error => {
            showNotification('Network error', 'error');
            setTimeout(() => location.reload(), 2000);
        });
    }

    function showNotification(message, type) {
        document.querySelectorAll('.slide-notification').forEach(n => n.remove());
        const notification = document.createElement('div');
        notification.className = 'slide-notification fixed top-5 right-5 px-4 py-3 rounded-lg text-white font-medium z-50 shadow-lg';
        notification.style.background = type === 'success' ? '#93db4d' : type === 'error' ? '#ef4444' : '#021c47';
        notification.style.color = type === 'success' ? '#021c47' : 'white';
        notification.textContent = message;
        document.body.appendChild(notification);
        if (type !== 'info') setTimeout(() => notification.remove(), 3000);
    }
});
</script>
@endpush
