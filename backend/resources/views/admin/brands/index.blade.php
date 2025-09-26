@extends('layouts.admin')

@section('title', 'Brands - BixCash Admin')
@section('page-title', 'Brands')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Brands</h3>
            <div>
                <a href="#" class="btn btn-primary">
                    Add New Brand
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($brands->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Commission</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Created</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                                <tr style="border-bottom: 1px solid #dee2e6;">
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
                                            <a href="#" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            <button class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Delete
                                            </button>
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
                    <a href="#" class="btn btn-primary" style="margin-top: 1rem;">
                        Create First Brand
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection