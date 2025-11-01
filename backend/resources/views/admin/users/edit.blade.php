@extends('layouts.admin')

@section('title', 'Edit User - BixCash Admin')
@section('page-title', 'Edit User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit User: {{ $user->name }}</h3>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    Back to Users
                </a>
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info">
                    View User
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Optional for editing) -->
                        <div class="form-group">
                            <label for="password">New Password (leave blank to keep current)</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   minlength="6">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Only fill this if you want to change the password (minimum 6 characters)</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   minlength="6">
                            <small class="text-muted">Re-enter the new password for confirmation</small>
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role_id">Role *</label>
                            <select class="form-control @error('role_id') is-invalid @enderror"
                                    id="role_id"
                                    name="role_id"
                                    required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Active Status -->
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (user can login)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Info Panel -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">User Details</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Created:</strong><br>{{ $user->created_at->format('M j, Y g:i A') }}</p>
                                <p><strong>Last Updated:</strong><br>{{ $user->updated_at->format('M j, Y g:i A') }}</p>
                                <p><strong>Last Login:</strong><br>{{ $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never' }}</p>

                                <hr>

                                <h6>Current Role:</h6>
                                <div class="alert alert-info" style="font-size: 0.875rem;">
                                    <strong>{{ $user->role->display_name ?? 'No Role' }}</strong>
                                </div>

                                <hr>

                                @if($user->id === auth()->id())
                                    <div class="alert alert-warning" style="font-size: 0.875rem;">
                                        <strong>Note:</strong> You are editing your own account. Be careful when changing your role or status.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>

                    @if($user->id !== auth()->id())
                        <button type="button" class="btn btn-danger float-right" onclick="if(confirm('Are you sure you want to delete this user?')) { document.getElementById('delete-form').submit(); }">
                            Delete User
                        </button>
                    @endif
                </div>
            </form>

            @if($user->id !== auth()->id())
                <form id="delete-form" method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
@endsection
