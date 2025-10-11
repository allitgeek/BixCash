@extends('layouts.admin')

@section('title', 'Hero Slides Management - BixCash Admin')
@section('page-title', 'Hero Slides')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Hero Slides</h3>
            <div>
                <a href="{{ route('admin.slides.create') }}" class="btn btn-primary">
                    Add New Slide
                </a>
            </div>
        </div>
        <div class="card-body">

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.slides.index') }}" class="mb-4">
                <div style="display: flex; gap: 1rem; align-items: end;">
                    <div style="flex: 1;">
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by title or description..."
                               style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label for="status" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Status</label>
                        <select id="status" name="status" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.slides.index') }}" class="btn" style="background: #6c757d; color: white; margin-left: 0.5rem;">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Slides Table with Drag & Drop -->
            @if($slides->count() > 0)
                <div style="overflow-x: auto;">
                    <table id="slidesTable" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600; width: 50px;">
                                    <span title="Drag to reorder">⋮⋮</span>
                                </th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Order</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Title</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Media</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Schedule</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Created By</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortableSlides">
                            @foreach($slides as $slide)
                                <tr class="slide-row" data-slide-id="{{ $slide->id }}" style="border-bottom: 1px solid #dee2e6; cursor: move;">
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div class="drag-handle" style="cursor: grab; font-size: 1.2rem; color: #666; user-select: none;" title="Drag to reorder">
                                            ⋮⋮
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span class="order-badge" style="background: #3498db; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-weight: 500;">
                                            {{ $slide->order }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <div>
                                            <strong>{{ $slide->title ?: 'Untitled' }}</strong>
                                            @if($slide->button_text)
                                                <br><small style="color: #666;">Button: {{ $slide->button_text }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span style="background: {{ $slide->media_type === 'video' ? '#e74c3c' : '#27ae60' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem; text-transform: uppercase;">
                                            {{ $slide->media_type }}
                                        </span>
                                        <br><small style="color: #666;">{{ Str::limit($slide->media_path, 30) }}</small>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($slide->is_active)
                                            <span style="background: #27ae60; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Active
                                            </span>
                                        @else
                                            <span style="background: #e74c3c; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-size: 0.8rem;">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($slide->start_date || $slide->end_date)
                                            <small style="color: #666;">
                                                @if($slide->start_date)
                                                    From: {{ $slide->start_date->format('M j, Y') }}<br>
                                                @endif
                                                @if($slide->end_date)
                                                    To: {{ $slide->end_date->format('M j, Y') }}
                                                @endif
                                            </small>
                                        @else
                                            <span style="color: #999;">Always Active</span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $slide->creator->name ?? 'Unknown' }}<br>
                                            {{ $slide->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="{{ route('admin.slides.show', $slide) }}"
                                               class="btn" style="background: #17a2b8; color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                View
                                            </a>
                                            <a href="{{ route('admin.slides.edit', $slide) }}"
                                               class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.slides.toggle-status', $slide) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="btn {{ $slide->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                    {{ $slide->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.slides.destroy', $slide) }}"
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this slide?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                    Delete
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
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $slides->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No slides found</h4>
                    <p>{{ request()->hasAny(['search', 'status']) ? 'Try adjusting your search criteria.' : 'Get started by creating your first hero slide.' }}</p>
                    @if(!request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.slides.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
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
        // Simple pagination styling
        const paginationLinks = document.querySelectorAll('.pagination a, .pagination span');
        paginationLinks.forEach(link => {
            link.style.cssText = 'padding: 0.5rem 0.75rem; margin: 0 0.25rem; border: 1px solid #dee2e6; border-radius: 3px; text-decoration: none; color: #495057;';
            if (link.classList.contains('active')) {
                link.style.cssText += 'background: #3498db; color: white; border-color: #3498db;';
            }
        });

        // Drag and Drop Functionality
        const tbody = document.getElementById('sortableSlides');
        if (!tbody) return;

        let draggedElement = null;
        let placeholder = null;

        // Add drag event listeners to all rows
        const slideRows = tbody.querySelectorAll('.slide-row');
        slideRows.forEach(row => {
            row.draggable = true;

            // Drag start
            row.addEventListener('dragstart', function(e) {
                draggedElement = this;
                this.style.opacity = '0.5';

                // Create placeholder
                placeholder = document.createElement('tr');
                placeholder.className = 'drag-placeholder';
                placeholder.innerHTML = `<td colspan="8" style="height: 4px; background: #3498db; border: 2px dashed #3498db; text-align: center; color: #3498db; font-weight: bold;">Drop here</td>`;

                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.outerHTML);
            });

            // Drag end
            row.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                draggedElement = null;

                // Remove placeholder if it exists
                if (placeholder && placeholder.parentNode) {
                    placeholder.parentNode.removeChild(placeholder);
                }
            });

            // Drag over
            row.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';

                if (draggedElement && draggedElement !== this) {
                    const rect = this.getBoundingClientRect();
                    const midPoint = rect.top + rect.height / 2;

                    // Remove existing placeholder
                    if (placeholder && placeholder.parentNode) {
                        placeholder.parentNode.removeChild(placeholder);
                    }

                    // Insert placeholder
                    if (e.clientY < midPoint) {
                        tbody.insertBefore(placeholder, this);
                    } else {
                        tbody.insertBefore(placeholder, this.nextSibling);
                    }
                }
            });

            // Drop
            row.addEventListener('drop', function(e) {
                e.preventDefault();

                if (draggedElement && draggedElement !== this) {
                    const rect = this.getBoundingClientRect();
                    const midPoint = rect.top + rect.height / 2;

                    // Move the dragged element
                    if (e.clientY < midPoint) {
                        tbody.insertBefore(draggedElement, this);
                    } else {
                        tbody.insertBefore(draggedElement, this.nextSibling);
                    }

                    // Update order and save
                    updateSlideOrder();
                }

                // Remove placeholder
                if (placeholder && placeholder.parentNode) {
                    placeholder.parentNode.removeChild(placeholder);
                }
            });
        });

        // Update visual order and send to server
        function updateSlideOrder() {
            const rows = tbody.querySelectorAll('.slide-row');
            const slideOrder = [];

            // Update visual order badges
            rows.forEach((row, index) => {
                const orderBadge = row.querySelector('.order-badge');
                if (orderBadge) {
                    orderBadge.textContent = index + 1;
                }

                const slideId = row.getAttribute('data-slide-id');
                slideOrder.push({
                    id: parseInt(slideId),
                    order: index + 1
                });
            });

            // Show loading feedback
            showNotification('Updating slide order...', 'info');

            // Send to server
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
                    showNotification('Slide order updated successfully!', 'success');
                } else {
                    showNotification('Error updating slide order: ' + (data.message || 'Unknown error'), 'error');
                    // Reload page on error to restore correct order
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Network error updating slide order', 'error');
                // Reload page on error to restore correct order
                setTimeout(() => location.reload(), 2000);
            });
        }

        // Notification system
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existing = document.querySelectorAll('.slide-notification');
            existing.forEach(n => n.remove());

            const notification = document.createElement('div');
            notification.className = 'slide-notification';
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 5px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                ${type === 'success' ? 'background: #27ae60;' : ''}
                ${type === 'error' ? 'background: #e74c3c;' : ''}
                ${type === 'info' ? 'background: #3498db;' : ''}
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Auto remove after 3 seconds (except for loading messages)
            if (type !== 'info') {
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 3000);
            }
        }

        // Enhanced drag handle styling
        const dragHandles = document.querySelectorAll('.drag-handle');
        dragHandles.forEach(handle => {
            handle.addEventListener('mousedown', function() {
                this.style.cursor = 'grabbing';
                this.style.color = '#3498db';
            });

            handle.addEventListener('mouseup', function() {
                this.style.cursor = 'grab';
                this.style.color = '#666';
            });
        });
    });
</script>
@endpush