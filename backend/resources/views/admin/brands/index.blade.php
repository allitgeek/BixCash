@extends('layouts.admin')

@section('title', 'Brands - BixCash Admin')
@section('page-title', 'Brands')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Brands</h3>
            <div>
                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                    Add New Brand
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($brands->count() > 0)
                <div style="overflow-x: auto;">
                    <table id="brandsTable" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600; width: 50px;">
                                    <span title="Drag to reorder">⋮⋮</span>
                                </th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Order</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Commission</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Created</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortableBrands">
                            @foreach($brands as $brand)
                                <tr class="brand-row" data-brand-id="{{ $brand->id }}" style="border-bottom: 1px solid #dee2e6; cursor: move;">
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div class="drag-handle" style="cursor: grab; font-size: 1.2rem; color: #666; user-select: none;" title="Drag to reorder">
                                            ⋮⋮
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span class="order-badge" style="background: #3498db; color: white; padding: 0.25rem 0.5rem; border-radius: 3px; font-weight: 500;">
                                            {{ $brand->order ?? $loop->iteration }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $brand->name }}</strong>
                                        @if($brand->description)
                                            <br><small style="color: #666;">{{ Str::limit($brand->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($brand->is_active)
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
                                        <span style="color: #27ae60; font-weight: 500;">
                                            {{ $brand->commission_rate ?? '0' }}%
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $brand->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this brand?')">
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
                    {{ $brands->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No brands found</h4>
                    <p>Get started by creating your first brand.</p>
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        Create First Brand
                    </a>
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

    // SIMPLE, RELIABLE DRAG AND DROP SYSTEM FOR BRANDS
    const tbody = document.getElementById('sortableBrands');
    if (!tbody) return;

    let draggedElement = null;
    let isUpdating = false;

    // Add drag and drop to each row
    function initializeDragAndDrop() {
        const rows = tbody.querySelectorAll('.brand-row');

        rows.forEach(row => {
            row.draggable = true;

            row.addEventListener('dragstart', function(e) {
                draggedElement = this;
                this.style.opacity = '0.5';
                console.log('Drag started:', this.querySelector('strong').textContent);
            });

            row.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                draggedElement = null;
                console.log('Drag ended');
            });

            row.addEventListener('dragover', function(e) {
                e.preventDefault();
                if (draggedElement && draggedElement !== this) {
                    e.dataTransfer.dropEffect = 'move';
                }
            });

            row.addEventListener('drop', function(e) {
                e.preventDefault();

                if (!draggedElement || draggedElement === this || isUpdating) {
                    return;
                }

                console.log('Drop on:', this.querySelector('strong').textContent);

                // Get the bounding rectangle to determine drop position
                const rect = this.getBoundingClientRect();
                const midpoint = rect.top + rect.height / 2;

                // Insert before or after based on drop position
                if (e.clientY < midpoint) {
                    tbody.insertBefore(draggedElement, this);
                } else {
                    tbody.insertBefore(draggedElement, this.nextSibling);
                }

                // Update order after a short delay
                setTimeout(updateBrandOrder, 100);
            });
        });
    }

    // Update brand order in database
    function updateBrandOrder() {
        if (isUpdating) return;

        isUpdating = true;
        const rows = tbody.querySelectorAll('.brand-row');
        const brandOrder = [];

        // Update visual order badges and collect data
        rows.forEach((row, index) => {
            const orderBadge = row.querySelector('.order-badge');
            if (orderBadge) {
                orderBadge.textContent = index + 1;
            }

            const brandId = row.getAttribute('data-brand-id');
            if (brandId) {
                brandOrder.push({
                    id: parseInt(brandId),
                    order: index + 1
                });
            }
        });

        console.log('Updating brand order:', brandOrder);
        showNotification('Updating brand order...', 'info');

        // Send AJAX request
        fetch('{{ route("admin.brands.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ brands: brandOrder })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Server response:', data);
            if (data.success) {
                showNotification('Brand order updated successfully!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error updating brand order', 'error');
        })
        .finally(() => {
            isUpdating = false;
        });
    }

    // Simple notification system
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.brand-notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = 'brand-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 5px;
            color: white;
            font-weight: 500;
            z-index: 10000;
            ${type === 'success' ? 'background: #27ae60;' : ''}
            ${type === 'error' ? 'background: #e74c3c;' : ''}
            ${type === 'info' ? 'background: #3498db;' : ''}
        `;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Auto remove
        if (type !== 'info') {
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        } else {
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 1000);
        }
    }

    // Add hover effects
    const style = document.createElement('style');
    style.textContent = `
        .brand-row {
            transition: background-color 0.2s;
        }
        .brand-row:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        .drag-handle {
            transition: all 0.2s;
        }
        .drag-handle:hover {
            color: #3498db !important;
            transform: scale(1.1);
        }
    `;
    document.head.appendChild(style);

    // Initialize the system
    initializeDragAndDrop();
    console.log('Simple drag-and-drop system initialized for brands');

    // Re-initialize if content changes (for pagination)
    const observer = new MutationObserver(() => {
        setTimeout(initializeDragAndDrop, 100);
    });
    observer.observe(tbody, { childList: true });
});
</script>
@endpush