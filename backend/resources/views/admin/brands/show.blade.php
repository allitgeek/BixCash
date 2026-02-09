@extends('layouts.admin')

@section('title', 'View Brand - BixCash Admin')
@section('page-title', 'View Brand')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-lg font-semibold text-[#021c47]">{{ $brand->name }}</h3>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.brands.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                    Back to Brands
                </a>
                <a href="{{ route('admin.brands.edit', $brand) }}" class="inline-flex items-center px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-medium hover:bg-yellow-200 transition-colors">
                    Edit Brand
                </a>
                @if($brand->is_active)
                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to deactivate this brand?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}" class="inline">
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
                    <!-- Brand Preview -->
                    <div class="bg-gray-50 rounded-xl p-8 mb-6 text-center">
                        <div class="inline-flex items-center justify-center bg-white rounded-xl shadow-sm p-6 w-64 h-40">
                            @if($brand->logo_path)
                                <img src="{{ $brand->logo_path }}"
                                     alt="{{ $brand->name }}"
                                     class="max-w-[200px] max-h-[100px] object-contain"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="hidden w-[200px] h-[100px] bg-gray-100 rounded items-center justify-center text-gray-400">
                                    No Logo
                                </div>
                            @else
                                <div class="w-[200px] h-[100px] bg-gray-100 rounded flex items-center justify-center text-gray-400">
                                    No Logo
                                </div>
                            @endif
                        </div>
                        <div class="mt-4">
                            <h4 class="text-xl font-bold text-[#021c47] mb-2">{{ $brand->name }}</h4>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @if($brand->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#021c47] text-white">{{ $brand->category->name }}</span>
                                @endif
                                @if($brand->is_featured)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Featured</span>
                                @endif
                                @if($brand->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Brand Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="text-lg font-semibold text-[#021c47] mb-4">Basic Information</h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Name:</span>
                                    <span class="text-gray-900">{{ $brand->name }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Description:</span>
                                    <span class="text-gray-900 text-right max-w-[200px]">{{ $brand->description ?: 'No description' }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Category:</span>
                                    <span class="text-gray-900">
                                        @if($brand->category)
                                            <a href="{{ route('admin.categories.show', $brand->category) }}" class="text-[#021c47] hover:text-[#93db4d] transition-colors">
                                                {{ $brand->category->name }}
                                            </a>
                                        @else
                                            No category assigned
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Website:</span>
                                    <span class="text-gray-900">
                                        @if($brand->website)
                                            <a href="{{ $brand->website }}" target="_blank" class="text-[#021c47] hover:text-[#93db4d] transition-colors break-all">
                                                {{ Str::limit($brand->website, 30) }}
                                            </a>
                                        @else
                                            No website
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Commission Rate:</span>
                                    <span class="font-semibold text-[#93db4d]">{{ $brand->commission_rate ? $brand->commission_rate . '%' : 'Not set' }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="text-lg font-semibold text-[#021c47] mb-4">Status & Settings</h5>
                            <div class="space-y-3">
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Status:</span>
                                    @if($brand->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#93db4d]/20 text-[#5a8a2e]">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                                    @endif
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Featured:</span>
                                    @if($brand->is_featured)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Yes</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">No</span>
                                    @endif
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Display Order:</span>
                                    <span class="text-gray-900">{{ $brand->order }}</span>
                                </div>
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="font-medium text-gray-600">Logo URL:</span>
                                    <span class="text-gray-900">
                                        @if($brand->logo_path)
                                            <a href="{{ $brand->logo_path }}" target="_blank" class="text-[#021c47] hover:text-[#93db4d] transition-colors break-all">
                                                {{ Str::limit($brand->logo_path, 30) }}
                                            </a>
                                        @else
                                            No logo URL
                                        @endif
                                    </span>
                                </div>
                                @if($brand->partner)
                                    <div class="flex justify-between py-2 border-b border-gray-100">
                                        <span class="font-medium text-gray-600">Partner:</span>
                                        <a href="{{ route('admin.users.show', $brand->partner) }}" class="text-[#021c47] hover:text-[#93db4d] transition-colors">
                                            {{ $brand->partner->name }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
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
                                <span class="text-sm text-gray-900">{{ $brand->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Updated:</span>
                                <span class="text-sm text-gray-900">{{ $brand->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($brand->creator)
                                <div class="flex justify-between py-2">
                                    <span class="text-sm font-medium text-gray-600">Created by:</span>
                                    <span class="text-sm text-gray-900">{{ $brand->creator->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h5 class="font-semibold text-[#021c47]">Quick Actions</h5>
                        </div>
                        <div class="p-4 space-y-3">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="block w-full text-center py-2 px-4 bg-yellow-100 text-yellow-700 rounded-lg font-medium hover:bg-yellow-200 transition-colors">
                                Edit Brand
                            </a>

                            @if($brand->is_active)
                                <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2 px-4 bg-red-100 text-red-700 rounded-lg font-medium hover:bg-red-200 transition-colors" onclick="return confirm('Are you sure you want to deactivate this brand?')">
                                        Deactivate
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-2 px-4 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors">
                                        Activate
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('admin.brands.toggle-featured', $brand) }}">
                                @csrf
                                @method('PATCH')
                                @if($brand->is_featured)
                                    <button type="submit" class="w-full py-2 px-4 border border-yellow-400 text-yellow-700 rounded-lg font-medium hover:bg-yellow-50 transition-colors">
                                        Remove from Featured
                                    </button>
                                @else
                                    <button type="submit" class="w-full py-2 px-4 bg-yellow-100 text-yellow-700 rounded-lg font-medium hover:bg-yellow-200 transition-colors">
                                        Mark as Featured
                                    </button>
                                @endif
                            </form>

                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full py-2 px-4 border border-red-500 text-red-500 rounded-lg font-medium hover:bg-red-500 hover:text-white transition-colors" onclick="return confirm('Are you sure you want to delete this brand? This action cannot be undone.')">
                                    Delete Brand
                                </button>
                            </form>
                        </div>
                    </div>

                    @if($brand->website)
                        <!-- Website Preview -->
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h5 class="font-semibold text-[#021c47]">Website Preview</h5>
                            </div>
                            <div class="p-4 text-center">
                                <a href="{{ $brand->website }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-[#021c47] text-[#021c47] rounded-lg font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                    Visit Website
                                </a>
                                <p class="text-sm text-gray-500 mt-2 break-all">{{ $brand->website }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
