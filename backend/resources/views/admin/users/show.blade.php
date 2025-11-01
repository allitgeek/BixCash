@extends('layouts.admin')

@section('title', 'View User - BixCash Admin')
@section('page-title', 'View User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $user->name }}</h3>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    Back to Users
                </a>
                @if($user->id !== auth()->id())
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                        Edit User
                    </a>
                    @if($user->is_active)
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                Deactivate
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">
                                Activate
                            </button>
                        </form>
                    @endif
                @else
                    <span class="badge badge-info">This is your account</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- User Info Preview -->
                    <div class="user-preview" style="
                        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                        padding: 2rem;
                        border-radius: 15px;
                        margin-bottom: 2rem;
                        text-align: center;
                    ">
                        <div style="
                            background: linear-gradient(135deg, #021c47 0%, #032a6b 100%);
                            border-radius: 50%;
                            width: 120px;
                            height: 120px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            color: white;
                            font-size: 3rem;
                            font-weight: bold;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                        ">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? $user->name, 0, 1)) }}
                        </div>
                        <div class="mt-3">
                            <h4 style="color: #021c47; margin-bottom: 0.5rem;">{{ $user->name }}</h4>
                            <p style="color: #6c757d; margin-bottom: 1rem;">{{ $user->email }}</p>
                            <span class="badge badge-primary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                                {{ $user->role->display_name ?? 'No Role' }}
                            </span>
                            @if($user->is_active)
                                <span class="badge badge-success" style="font-size: 0.9rem; padding: 0.5rem 1rem;">Active</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 0.9rem; padding: 0.5rem 1rem;">Inactive</span>
                            @endif
                            @if($user->id === auth()->id())
                                <span class="badge badge-info" style="font-size: 0.9rem; padding: 0.5rem 1rem;">You</span>
                            @endif
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Basic Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $user->role->display_name ?? 'No Role' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Activity Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $user->created_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $user->updated_at->format('M j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Age:</strong></td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($user->role && $user->role->name)
                        <!-- Role Permissions -->
                        <div class="mt-4">
                            <h5>Role Permissions</h5>
                            <div class="alert alert-info">
                                <strong>{{ $user->role->display_name }}:</strong>
                                @if($user->role->name === 'super_admin')
                                    Full system access including user management, settings, and all modules
                                @elseif($user->role->name === 'admin')
                                    Manage content, users, brands, categories, and view analytics
                                @elseif($user->role->name === 'moderator')
                                    View-only access to most modules with limited editing capabilities
                                @else
                                    {{ $user->role->description ?? 'No description available' }}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <!-- Quick Actions -->
                    @if($user->id !== auth()->id())
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Quick Actions</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                        Edit User
                                    </a>

                                    @if($user->is_active)
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm w-100">
                                                Activate
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Your Account</h5>
                            </div>
                            <div class="card-body">
                                <p>This is your current account. You can edit your information using the button below.</p>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm w-100">
                                    Edit My Profile
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Statistics</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>User ID:</strong></td>
                                    <td>#{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role ID:</strong></td>
                                    <td>#{{ $user->role_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Active Status:</strong></td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-danger">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
