@extends('layouts.admin')

@section('title', 'Brands - BixCash Admin')
@section('page-title', 'Brands')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-[#021c47]">Manage Brands</h3>
            <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                Add New Brand
            </a>
        </div>
        <div class="p-6">
            @if($brands->count() > 0)
                <div class="overflow-x-auto">
                    <table id="brandsTable" class="w-full">
                        <thead class="bg-[#021c47] text-white">
                            <tr>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider w-12" title="Drag to reorder">
                                    <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                    </svg>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Order</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Created</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="sortableBrands" class="divide-y divide-gray-200">
                            @foreach($brands as $brand)
                                <tr class="brand-row hover:bg-[#93db4d]/5 transition-colors cursor-move" data-brand-id="{{ $brand->id }}">
                                    <td class="px-4 py-3 text-center">
                                        <div class="drag-handle cursor-grab text-gray-400 hover:text-[#021c47] transition-colors" title="Drag to reorder">
                                            <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="order-badge inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#021c47] text-white">
                                            {{ $brand->order ?? $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-gray-900">{{ $brand->name }}</span>
                                        @if($brand->description)
                                            <br><span class="text-sm text-gray-500">{{ Str::limit($brand->description, 60) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($brand->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-medium text-[#93db4d]">{{ $brand->commission_rate ?? '0' }}%</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-500">{{ $brand->created_at->format('M j, Y') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            <a href="{{ route('admin.brands.edit', $brand) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-700 rounded-lg text-sm font-medium hover:bg-yellow-200 transition-colors">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this brand?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition-colors">
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
                @if($brands->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $brands->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                    </svg>
                    <h4 class="text-lg font-semibold text-gray-700 mb-2">No brands found</h4>
                    <p class="text-gray-500 mb-4">Get started by creating your first brand.</p>
                    <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
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
    const tbody = document.getElementById('sortableBrands');
    if (!tbody) return;

    let draggedElement = null;
    let isUpdating = false;

    function initializeDragAndDrop() {
        const rows = tbody.querySelectorAll('.brand-row');

        rows.forEach(row => {
            row.draggable = true;

            row.addEventListener('dragstart', function(e) {
                draggedElement = this;
                this.style.opacity = '0.5';
            });

            row.addEventListener('dragend', function(e) {
                this.style.opacity = '';
                draggedElement = null;
            });

            row.addEventListener('dragover', function(e) {
                e.preventDefault();
                if (draggedElement && draggedElement !== this) {
                    e.dataTransfer.dropEffect = 'move';
                }
            });

            row.addEventListener('drop', function(e) {
                e.preventDefault();
                if (!draggedElement || draggedElement === this || isUpdating) return;

                const rect = this.getBoundingClientRect();
                const midpoint = rect.top + rect.height / 2;

                if (e.clientY < midpoint) {
                    tbody.insertBefore(draggedElement, this);
                } else {
                    tbody.insertBefore(draggedElement, this.nextSibling);
                }

                setTimeout(updateBrandOrder, 100);
            });
        });
    }

    function updateBrandOrder() {
        if (isUpdating) return;
        isUpdating = true;

        const rows = tbody.querySelectorAll('.brand-row');
        const brandOrder = [];

        rows.forEach((row, index) => {
            const orderBadge = row.querySelector('.order-badge');
            if (orderBadge) orderBadge.textContent = index + 1;

            const brandId = row.getAttribute('data-brand-id');
            if (brandId) {
                brandOrder.push({ id: parseInt(brandId), order: index + 1 });
            }
        });

        showNotification('Updating brand order...', 'info');

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
            if (data.success) {
                showNotification('Brand order updated!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error updating brand order', 'error');
        })
        .finally(() => {
            isUpdating = false;
        });
    }

    function showNotification(message, type = 'info') {
        document.querySelectorAll('.brand-notification').forEach(n => n.remove());

        const notification = document.createElement('div');
        notification.className = 'brand-notification fixed top-5 right-5 px-6 py-3 rounded-xl font-medium z-50 shadow-lg';

        if (type === 'success') notification.className += ' bg-[#93db4d] text-[#021c47]';
        else if (type === 'error') notification.className += ' bg-red-500 text-white';
        else notification.className += ' bg-[#021c47] text-white';

        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => notification.remove(), type === 'info' ? 1000 : 3000);
    }

    initializeDragAndDrop();

    const observer = new MutationObserver(() => setTimeout(initializeDragAndDrop, 100));
    observer.observe(tbody, { childList: true });
});
</script>
@endpush
