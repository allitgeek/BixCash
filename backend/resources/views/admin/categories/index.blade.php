@extends('layouts.admin')

@section('title', 'Categories - BixCash Admin')
@section('page-title', 'Categories')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Categories</h3>
            <div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    Add New Category
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Created</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <strong>{{ $category->name }}</strong>
                                        @if($category->description)
                                            <br><small style="color: #666;">{{ Str::limit($category->description, 60) }}</small>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($category->is_active)
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
                                        <small style="color: #666;">
                                            {{ $category->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                    {{ $categories->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No categories found</h4>
                    <p>Get started by creating your first category.</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                        Create First Category
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection