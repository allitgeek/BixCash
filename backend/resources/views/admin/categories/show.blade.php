@extends('layouts.admin')

@section('title', 'View Category - BixCash Admin')
@section('page-title', 'View Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $category->name }}</h3>
            <div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Back to Categories
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                    Edit Category
                </a>
                @if($category->is_active)
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this category?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}" style="display: inline;">
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
                    <!-- Category Preview -->
                    <div class="category-preview" style="
                        background: #f8f9fa;
                        padding: 2rem;
                        border-radius: 15px;
                        margin-bottom: 2rem;
                        text-align: center;
                    ">
                        <div style="
                            background: white;
                            border: 2px solid {{ $category->color ?: '#021c47' }};
                            border-radius: 8px;
                            padding: 1.5rem;
                            width: 150px;
                            height: 200px;
                            display: inline-flex;
                            flex-direction: column;
                            justify-content: center;
                            align-items: center;
                            transition: all 0.3s ease;
                        ">
                            @if($category->icon_path)
                                <img src="{{ $category->icon_path }}"
                                     alt="{{ $category->name }}"
                                     style="width: 90px; height: 90px; object-fit: cover; border-radius: 4px; margin-bottom: 1rem;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none; width: 90px; height: 90px; background: #f0f0f0; border-radius: 4px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #999;">
                                    No Icon
                                </div>
                            @else
                                <div style="width: 90px; height: 90px; background: #f0f0f0; border-radius: 4px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #999; font-size: 0.8rem;">
                                    No Icon
                                </div>
                            @endif
                            <span style="color: {{ $category->color ?: '#021c47' }}; font-weight: bold; font-size: 1rem;">
                                {{ $category->name }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $category->description ?: 'No description' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Color:</strong></td>
                                    <td>
                                        @if($category->color)
                                            <span style="
                                                background: {{ $category->color }};
                                                color: white;
                                                padding: 0.25rem 0.5rem;
                                                border-radius: 3px;
                                                font-size: 0.8rem;
                                            ">{{ $category->color }}</span>
                                        @else
                                            Default (#3498db)
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($category->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Display Order:</strong></td>
                                    <td>{{ $category->order }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>SEO & Links</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Icon URL:</strong></td>
                                    <td>
                                        @if($category->icon_path)
                                            <a href="{{ $category->icon_path }}" target="_blank" style="word-break: break-all;">
                                                {{ Str::limit($category->icon_path, 40) }}
                                                <i class="fas fa-external-link-alt" style="font-size: 0.8em;"></i>
                                            </a>
                                        @else
                                            No icon URL
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Meta Title:</strong></td>
                                    <td>{{ $category->meta_title ?: 'Not set' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Meta Description:</strong></td>
                                    <td>{{ $category->meta_description ?: 'Not set' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Associated Brands -->
                    @if($category->brands->count() > 0)
                        <div class="mt-4">
                            <h5>Associated Brands ({{ $category->brands->count() }})</h5>
                            <div class="row">
                                @foreach($category->brands as $brand)
                                    <div class="col-md-4 mb-3">
                                        <div class="card card-sm">
                                            <div class="card-body d-flex align-items-center">
                                                @if($brand->logo_path)
                                                    <img src="{{ $brand->logo_path }}"
                                                         alt="{{ $brand->name }}"
                                                         style="width: 40px; height: 40px; object-fit: contain; margin-right: 0.75rem;"
                                                         onerror="this.style.display='none';">
                                                @endif
                                                <div>
                                                    <strong>{{ $brand->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        @if($brand->is_active)
                                                            <span class="badge badge-success badge-sm">Active</span>
                                                        @else
                                                            <span class="badge badge-secondary badge-sm">Inactive</span>
                                                        @endif
                                                        @if($brand->is_featured)
                                                            <span class="badge badge-warning badge-sm">Featured</span>
                                                        @endif
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('admin.brands.index', ['category_id' => $category->id]) }}" class="btn btn-outline-primary">
                                View All Brands in This Category
                            </a>
                        </div>
                    @else
                        <div class="mt-4">
                            <div class="alert alert-info">
                                <strong>No brands associated with this category yet.</strong>
                                <br>
                                <a href="{{ route('admin.brands.create') }}" class="btn btn-sm btn-primary mt-2">
                                    Create First Brand
                                </a>
                            </div>
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
                                    <td>{{ $category->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $category->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                @if($category->creator)
                                    <tr>
                                        <td><strong>Created by:</strong></td>
                                        <td>{{ $category->creator->name }}</td>
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
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Category
                                </a>

                                @if($category->is_active)
                                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to deactivate this category?')">
                                            <i class="fas fa-eye-slash"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.categories.toggle-status', $category) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-eye"></i> Activate
                                        </button>
                                    </form>
                                @endif

                                @if($category->brands->count() == 0)
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-danger btn-sm w-100" disabled title="Cannot delete category with associated brands">
                                        <i class="fas fa-trash"></i> Delete ({{ $category->brands->count() }} brands)
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection