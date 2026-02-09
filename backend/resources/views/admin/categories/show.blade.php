@extends('layouts.admin')

@section('title', 'View Category - BixCash Admin')
@section('page-title', 'View Category')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-[#021c47]">{{ $category->name }}</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Back to Categories
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-medium hover:bg-yellow-200 transition-colors">
                    Edit Category
                </a>
                @if($category->is_active)
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to deactivate this category?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors">
                            Activate
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <!-- Category Preview -->
                    <div class="bg-gray-50 rounded-xl p-8 mb-6 text-center">
                        <div class="inline-flex flex-col items-center justify-center bg-white rounded-xl p-6 w-36 h-48" style="border: 2px solid {{ $category->color ?: '#021c47' }};">
                            @if($category->icon_path)
                                <img src="{{ $category->icon_path }}"
                                     alt="{{ $category->name }}"
                                     class="w-[90px] h-[90px] object-cover rounded mb-4"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-[90px] h-[90px] bg-gray-100 rounded mb-4 items-center justify-center text-gray-400 text-sm">
                                    No Icon
                                </div>
                            @else
                                <div class="w-[90px] h-[90px] bg-gray-100 rounded mb-4 flex items-center justify-center text-gray-400 text-sm">
                                    No Icon
                                </div>
                            @endif
                            <span class="font-bold" style="color: {{ $category->color ?: '#021c47' }};">{{ $category->name }}</span>
                        </div>
                    </div>

                    <!-- Category Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="text-lg font-semibold text-[#021c47] mb-4">Basic Information</h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Name:</span>
                                    <span class="text-gray-900">{{ $category->name }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Description:</span>
                                    <span class="text-gray-900 text-right max-w-[200px]">{{ $category->description ?: 'No description' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Color:</span>
                                    @if($category->color)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium text-white" style="background: {{ $category->color }};">{{ $category->color }}</span>
                                    @else
                                        <span class="text-gray-500">Default (#021c47)</span>
                                    @endif
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Status:</span>
                                    @if($category->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="font-medium text-gray-600">Display Order:</span>
                                    <span class="text-gray-900">{{ $category->order }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-[#021c47] mb-4">SEO & Links</h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Icon URL:</span>
                                    <span class="text-gray-900">
                                        @if($category->icon_path)
                                            <a href="{{ $category->icon_path }}" target="_blank" class="text-[#021c47] hover:text-[#93db4d] transition-colors break-all">
                                                {{ Str::limit($category->icon_path, 30) }}
                                            </a>
                                        @else
                                            No icon URL
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Meta Title:</span>
                                    <span class="text-gray-900 text-right max-w-[200px]">{{ $category->meta_title ?: 'Not set' }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="font-medium text-gray-600">Meta Description:</span>
                                    <span class="text-gray-900 text-right max-w-[200px]">{{ $category->meta_description ?: 'Not set' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Associated Brands -->
                    @if($category->brands->count() > 0)
                        <div class="mt-8">
                            <h5 class="text-lg font-semibold text-[#021c47] mb-4">Associated Brands ({{ $category->brands->count() }})</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($category->brands as $brand)
                                    <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3">
                                        @if($brand->logo_path)
                                            <img src="{{ $brand->logo_path }}"
                                                 alt="{{ $brand->name }}"
                                                 class="w-10 h-10 object-contain"
                                                 onerror="this.style.display='none';">
                                        @endif
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $brand->name }}</p>
                                            <div class="flex gap-1 mt-1">
                                                @if($brand->is_active)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                                                @else
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                                @endif
                                                @if($brand->is_featured)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-700">Featured</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.brands.index', ['category_id' => $category->id]) }}" class="inline-flex items-center mt-4 px-4 py-2 border border-[#021c47] text-[#021c47] rounded-lg font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                View All Brands in This Category
                            </a>
                        </div>
                    @else
                        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <p class="font-semibold text-blue-800 mb-2">No brands associated with this category yet.</p>
                            <a href="{{ route('admin.brands.create') }}" class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                                Create First Brand
                            </a>
                        </div>
                    @endif
                </div>

                <div>
                    <!-- Metadata -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h5 class="font-semibold text-[#021c47]">Metadata</h5>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Created:</span>
                                <span class="text-sm text-gray-900">{{ $category->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Updated:</span>
                                <span class="text-sm text-gray-900">{{ $category->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($category->creator)
                                <div class="flex justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600">Created by:</span>
                                    <span class="text-sm text-gray-900">{{ $category->creator->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h5 class="font-semibold text-[#021c47]">Quick Actions</h5>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="block w-full text-center py-2 px-4 bg-yellow-100 text-yellow-700 rounded-lg font-medium hover:bg-yellow-200 transition-colors">
                                Edit Category
                            </a>

                            @if($category->is_active)
                                <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2 px-4 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to deactivate this category?')">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2 px-4 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors">
                                        Activate
                                    </button>
                                </form>
                            @endif

                            @if($category->brands->count() == 0)
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-2 px-4 border border-red-500 text-red-500 rounded-lg font-medium hover:bg-red-500 hover:text-white transition-colors" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                        Delete Category
                                    </button>
                                </form>
                            @else
                                <button class="w-full py-2 px-4 bg-gray-100 text-gray-400 rounded-lg font-medium cursor-not-allowed" disabled title="Cannot delete category with associated brands">
                                    Delete ({{ $category->brands->count() }} brands)
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
