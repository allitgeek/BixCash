@extends('layouts.admin')

@section('title', 'View Brand - BixCash Admin')
@section('page-title', 'View Brand')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $brand->name }}</h3>
            <div>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    Back to Brands
                </a>
                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning">
                    Edit Brand
                </a>
                @if($brand->is_active)
                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this brand?')">
                            Deactivate
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}" style="display: inline;">
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
                    <!-- Brand Preview -->
                    <div class="brand-preview" style="
                        background: #f8f9fa;
                        padding: 2rem;
                        border-radius: 15px;
                        margin-bottom: 2rem;
                        text-align: center;
                    ">
                        <div style="
                            background: white;
                            border-radius: 10px;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                            padding: 1.5rem;
                            width: 250px;
                            height: 150px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            transition: all 0.3s ease;
                        ">
                            @if($brand->logo_path)
                                <img src="{{ $brand->logo_path }}"
                                     alt="{{ $brand->name }}"
                                     style="max-width: 200px; max-height: 100px; object-fit: contain;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div style="display: none; max-width: 200px; max-height: 100px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                    No Logo
                                </div>
                            @else
                                <div style="max-width: 200px; max-height: 100px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; font-size: 1rem;">
                                    No Logo
                                </div>
                            @endif
                        </div>
                        <div class="mt-3">
                            <h4 style="color: #021c47; margin-bottom: 0.5rem;">{{ $brand->name }}</h4>
                            @if($brand->category)
                                <span class="badge badge-primary">{{ $brand->category->name }}</span>
                            @endif
                            @if($brand->is_featured)
                                <span class="badge badge-warning">Featured</span>
                            @endif
                            @if($brand->is_active)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <!-- Brand Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Description:</strong></td>
                                    <td>{{ $brand->description ?: 'No description' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Category:</strong></td>
                                    <td>
                                        @if($brand->category)
                                            <a href="{{ route('admin.categories.show', $brand->category) }}">
                                                {{ $brand->category->name }}
                                            </a>
                                        @else
                                            No category assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Website:</strong></td>
                                    <td>
                                        @if($brand->website)
                                            <a href="{{ $brand->website }}" target="_blank" style="word-break: break-all;">
                                                {{ Str::limit($brand->website, 40) }}
                                                <i class="fas fa-external-link-alt" style="font-size: 0.8em;"></i>
                                            </a>
                                        @else
                                            No website
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Commission Rate:</strong></td>
                                    <td>{{ $brand->commission_rate ? $brand->commission_rate . '%' : 'Not set' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Status & Settings</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($brand->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Featured:</strong></td>
                                    <td>
                                        @if($brand->is_featured)
                                            <span class="badge badge-warning">Yes</span>
                                        @else
                                            <span class="badge badge-light">No</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Display Order:</strong></td>
                                    <td>{{ $brand->order }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Logo URL:</strong></td>
                                    <td>
                                        @if($brand->logo_path)
                                            <a href="{{ $brand->logo_path }}" target="_blank" style="word-break: break-all;">
                                                {{ Str::limit($brand->logo_path, 40) }}
                                                <i class="fas fa-external-link-alt" style="font-size: 0.8em;"></i>
                                            </a>
                                        @else
                                            No logo URL
                                        @endif
                                    </td>
                                </tr>
                                @if($brand->partner)
                                    <tr>
                                        <td><strong>Partner:</strong></td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $brand->partner) }}">
                                                {{ $brand->partner->name }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
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
                                    <td>{{ $brand->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Updated:</strong></td>
                                    <td>{{ $brand->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                @if($brand->creator)
                                    <tr>
                                        <td><strong>Created by:</strong></td>
                                        <td>{{ $brand->creator->name }}</td>
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
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit Brand
                                </a>

                                @if($brand->is_active)
                                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to deactivate this brand?')">
                                            <i class="fas fa-eye-slash"></i> Deactivate
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.brands.toggle-status', $brand) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-eye"></i> Activate
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.brands.toggle-featured', $brand) }}">
                                    @csrf
                                    @method('PATCH')
                                    @if($brand->is_featured)
                                        <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                            <i class="fas fa-star-half-alt"></i> Remove from Featured
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-warning btn-sm w-100">
                                            <i class="fas fa-star"></i> Mark as Featured
                                        </button>
                                    @endif
                                </form>

                                <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this brand? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete Brand
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    @if($brand->website)
                        <!-- Website Preview -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h5 class="card-title">Website Preview</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <a href="{{ $brand->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-external-link-alt"></i> Visit Website
                                    </a>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <strong>URL:</strong> {{ $brand->website }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection