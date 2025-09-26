@extends('layouts.admin')

@section('title', 'User Management - BixCash Admin')
@section('page-title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Manage Users</h3>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    Add New User
                </a>
            </div>
        </div>
        <div class="card-body">

            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
                <div style="display: flex; gap: 1rem; align-items: end;">
                    <div style="flex: 1;">
                        <label for="search" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name or email..."
                               style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                    <div>
                        <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Role</label>
                        <select id="role" name="role" style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="">All Roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                </option>
                            @endforeach
                        </select>
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
                        @if(request()->hasAny(['search', 'role', 'status']))
                            <a href="{{ route('admin.users.index') }}" class="btn" style="background: #6c757d; color: white; margin-left: 0.5rem;">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Users Table -->
            @if($users->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Name</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Email</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Role</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Status</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Last Login</th>
                                <th style="padding: 0.75rem; text-align: left; font-weight: 600;">Created</th>
                                <th style="padding: 0.75rem; text-align: center; font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 0.75rem;">
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->id === auth()->id())
                                                <small style="background: #17a2b8; color: white; padding: 0.2rem 0.4rem; border-radius: 3px; font-size: 0.7rem; margin-left: 0.5rem;">
                                                    YOU
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        {{ $user->email }}
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <span class="role-badge">
                                            {{ $user->role->display_name ?? 'No Role' }}
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        @if($user->is_active)
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
                                            {{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem;">
                                        <small style="color: #666;">
                                            {{ $user->created_at->format('M j, Y') }}
                                        </small>
                                    </td>
                                    <td style="padding: 0.75rem; text-align: center;">
                                        <div style="display: flex; gap: 0.25rem; justify-content: center;">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn" style="background: #17a2b8; color: white; padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                View
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                Edit
                                            </a>
                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                            class="btn {{ $user->is_active ? 'btn-warning' : 'btn-success' }}"
                                                            style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span style="color: #999; font-size: 0.8rem; padding: 0.25rem;">
                                                    (Current User)
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                    {{ $users->withQueryString()->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 3rem; color: #666;">
                    <h4>No users found</h4>
                    <p>{{ request()->hasAny(['search', 'role', 'status']) ? 'Try adjusting your search criteria.' : 'Get started by creating your first user.' }}</p>
                    @if(!request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="margin-top: 1rem;">
                            Create First User
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Simple pagination styling
    document.addEventListener('DOMContentLoaded', function() {
        const paginationLinks = document.querySelectorAll('.pagination a, .pagination span');
        paginationLinks.forEach(link => {
            link.style.cssText = 'padding: 0.5rem 0.75rem; margin: 0 0.25rem; border: 1px solid #dee2e6; border-radius: 3px; text-decoration: none; color: #495057;';
            if (link.classList.contains('active')) {
                link.style.cssText += 'background: #3498db; color: white; border-color: #3498db;';
            }
        });
    });
</script>
@endpush