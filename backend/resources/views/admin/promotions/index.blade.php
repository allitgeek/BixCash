@extends('layouts.admin')

@section('title', 'Promotions - BixCash Admin')
@section('page-title', 'Promotions')

@section('content')
    <div class="space-y-6">
        <!-- Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-[#021c47]/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#021c47]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-xl font-bold text-[#021c47]">{{ $stats['total'] }}</p>
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
                        <p class="text-xl font-bold text-[#93db4d]">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Inactive</p>
                        <p class="text-xl font-bold text-red-500">{{ $stats['inactive'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Coming Soon</p>
                        <p class="text-xl font-bold text-purple-600">{{ $stats['coming_soon'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-semibold text-[#021c47]">Manage Promotions</h3>
                <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Promotion
                </a>
            </div>

            <!-- Search & Filter Bar -->
            <div class="px-6 py-3 border-b border-gray-100 bg-gray-50/50">
                <form method="GET" action="{{ route('admin.promotions.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brands..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#021c47]/20 focus:border-[#021c47] outline-none">
                    </div>
                    <select name="status" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#021c47]/20 focus:border-[#021c47] outline-none bg-white">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <select name="discount_type" class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#021c47]/20 focus:border-[#021c47] outline-none bg-white">
                        <option value="">All Types</option>
                        <option value="upto" {{ request('discount_type') === 'upto' ? 'selected' : '' }}>Up To</option>
                        <option value="flat" {{ request('discount_type') === 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="coming_soon" {{ request('discount_type') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 text-sm font-medium bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'discount_type']))
                            <a href="{{ route('admin.promotions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="p-4">
                @if($promotions->count() > 0)
                    <div class="overflow-x-auto">
                        <table id="promotionsTable" class="w-full">
                            <thead class="bg-[#021c47] text-white">
                                <tr>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider w-10" title="Drag to reorder">
                                        <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                        </svg>
                                    </th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider w-14">Order</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider">Brand</th>
                                    <th class="px-3 py-2.5 text-left text-xs font-semibold uppercase tracking-wider">Discount</th>
                                    <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider w-48">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortablePromotions" class="divide-y divide-gray-200">
                                @foreach($promotions as $promotion)
                                    <tr class="promotion-row hover:bg-[#93db4d]/5 transition-colors cursor-move" data-promotion-id="{{ $promotion->id }}">
                                        <!-- Drag Handle -->
                                        <td class="px-3 py-2.5 text-center">
                                            <div class="drag-handle cursor-grab text-gray-400 hover:text-[#021c47] transition-colors" title="Drag to reorder">
                                                <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                                                </svg>
                                            </div>
                                        </td>
                                        <!-- Order -->
                                        <td class="px-3 py-2.5 text-center">
                                            <span class="order-badge inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold bg-[#021c47] text-white">
                                                {{ $promotion->order ?? $loop->iteration }}
                                            </span>
                                        </td>
                                        <!-- Brand (Logo + Name merged) -->
                                        <td class="px-3 py-2.5">
                                            <div class="flex items-center gap-3">
                                                @if($promotion->logo_path)
                                                    <img src="{{ $promotion->logo_url }}" alt="{{ $promotion->brand_name }}" class="w-9 h-9 object-contain rounded-lg border border-gray-100 flex-shrink-0">
                                                @else
                                                    <div class="w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <span class="font-semibold text-gray-900">{{ $promotion->brand_name }}</span>
                                            </div>
                                        </td>
                                        <!-- Discount (Value + Type badge merged) -->
                                        <td class="px-3 py-2.5">
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">
                                                    {{ $promotion->formatted_discount }}
                                                </span>
                                                @php
                                                    $typeClasses = match($promotion->discount_type) {
                                                        'upto' => 'bg-blue-100 text-blue-700',
                                                        'flat' => 'bg-orange-100 text-orange-700',
                                                        'coming_soon' => 'bg-purple-100 text-purple-700',
                                                        default => 'bg-gray-100 text-gray-700'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium uppercase {{ $typeClasses }}">
                                                    {{ str_replace('_', ' ', $promotion->discount_type) }}
                                                </span>
                                            </div>
                                        </td>
                                        <!-- Actions (Toggle + Edit + Delete merged) -->
                                        <td class="px-3 py-2.5">
                                            <div class="flex items-center justify-center gap-5">
                                                <!-- Toggle Switch -->
                                                <button type="button"
                                                    class="status-toggle relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none"
                                                    style="background-color: {{ $promotion->is_active ? '#93db4d' : '#ef4444' }};"
                                                    data-promotion-id="{{ $promotion->id }}"
                                                    data-active="{{ $promotion->is_active ? '1' : '0' }}"
                                                    title="{{ $promotion->is_active ? 'Active — click to deactivate' : 'Inactive — click to activate' }}">
                                                    <span class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow-md transition duration-200 ease-in-out" style="transform: translateX({{ $promotion->is_active ? '1.25rem' : '0' }});"></span>
                                                </button>
                                                <!-- Edit -->
                                                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="text-blue-600 hover:text-[#021c47] transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}" class="inline" onsubmit="return confirm('Delete this promotion?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                    @if($promotions->hasPages())
                        <div class="mt-6 flex justify-center">
                            {{ $promotions->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6.207.293a1 1 0 00-1.414 0l-6 6a1 1 0 101.414 1.414l6-6a1 1 0 000-1.414zM12.5 10a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd"></path>
                        </svg>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">No promotions found</h4>
                        <p class="text-gray-500 mb-4">
                            @if(request()->hasAny(['search', 'status', 'discount_type']))
                                No promotions match your filters. <a href="{{ route('admin.promotions.index') }}" class="text-[#021c47] underline">Clear filters</a>
                            @else
                                Get started by creating your first promotion.
                            @endif
                        </p>
                        @unless(request()->hasAny(['search', 'status', 'discount_type']))
                            <a href="{{ route('admin.promotions.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                                Create First Promotion
                            </a>
                        @endunless
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('sortablePromotions');
    if (!tbody) return;

    let draggedElement = null;
    let isUpdating = false;

    // ─── Drag & Drop ───────────────────────────────────────
    function initializeDragAndDrop() {
        const rows = tbody.querySelectorAll('.promotion-row');

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

                setTimeout(updatePromotionOrder, 100);
            });
        });
    }

    function updatePromotionOrder() {
        if (isUpdating) return;
        isUpdating = true;

        const rows = tbody.querySelectorAll('.promotion-row');
        const promotionOrder = [];

        rows.forEach((row, index) => {
            const orderBadge = row.querySelector('.order-badge');
            if (orderBadge) orderBadge.textContent = index + 1;

            const promotionId = row.getAttribute('data-promotion-id');
            if (promotionId) {
                promotionOrder.push({ id: parseInt(promotionId), order: index + 1 });
            }
        });

        showNotification('Updating order...', 'info');

        fetch('{{ route("admin.promotions.reorder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ promotions: promotionOrder })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Order updated!', 'success');
            } else {
                showNotification('Error: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error updating order', 'error');
        })
        .finally(() => {
            isUpdating = false;
        });
    }

    // ─── Status Toggle ─────────────────────────────────────
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const btn = this;
            const promotionId = btn.dataset.promotionId;
            const isActive = btn.dataset.active === '1';
            const knob = btn.querySelector('span');

            // Optimistic UI update
            function applyToggleState(button, knobEl, active) {
                button.style.backgroundColor = active ? '#93db4d' : '#ef4444';
                knobEl.style.transform = active ? 'translateX(1.25rem)' : 'translateX(0)';
                button.dataset.active = active ? '1' : '0';
                button.title = active ? 'Active — click to deactivate' : 'Inactive — click to activate';
            }

            applyToggleState(btn, knob, !isActive);

            fetch(`/admin/promotions/${promotionId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                } else {
                    applyToggleState(btn, knob, isActive);
                    showNotification('Failed to toggle status', 'error');
                }
            })
            .catch(() => {
                applyToggleState(btn, knob, isActive);
                showNotification('Network error', 'error');
            });
        });
    });

    // ─── Notifications ─────────────────────────────────────
    function showNotification(message, type = 'info') {
        document.querySelectorAll('.promotion-notification').forEach(n => n.remove());

        const colors = {
            success: { bg: '#93db4d', text: '#021c47' },
            error:   { bg: '#ef4444', text: '#ffffff' },
            info:    { bg: '#021c47', text: '#ffffff' }
        };
        const c = colors[type] || colors.info;

        const notification = document.createElement('div');
        notification.className = 'promotion-notification fixed z-50';
        notification.style.cssText = `top:1.25rem;right:1.25rem;padding:0.75rem 1.5rem;border-radius:0.75rem;font-weight:600;box-shadow:0 10px 15px -3px rgba(0,0,0,0.1);background-color:${c.bg};color:${c.text};`;
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
