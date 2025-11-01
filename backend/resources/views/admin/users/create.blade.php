@extends('layouts.admin')

@section('title', 'Create User - BixCash Admin')
@section('page-title', 'Create User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Create New User</h3>
            <div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    Back to Users
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
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
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   minlength="6">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password *</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   minlength="6">
                            <small class="text-muted">Re-enter the password for confirmation</small>
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
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
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
                                       {{ old('is_active', true) ? 'checked' : '' }}>
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
                                <h5 class="card-title">User Information</h5>
                            </div>
                            <div class="card-body">
                                <h6>Role Permissions:</h6>
                                <div id="rolePermissions">
                                    <small class="text-muted">Select a role to view permissions</small>
                                </div>

                                <hr>

                                <h6>Password Requirements:</h6>
                                <ul style="font-size: 0.875rem;">
                                    <li>Minimum 6 characters</li>
                                    <li>Must match confirmation</li>
                                </ul>

                                <hr>

                                <div class="alert alert-info" style="font-size: 0.875rem;">
                                    <strong>Note:</strong> Only admin users (Super Admin, Admin, Moderator) can be created here. Customers and Partners have separate registration processes.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #dee2e6;">
                    <button type="submit" class="btn btn-primary">Create User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Show role permissions when role is selected
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role_id');
            const rolePermissionsDiv = document.getElementById('rolePermissions');

            const roleDescriptions = {
                'Super Admin': 'Full system access including user management, settings, and all modules',
                'Admin': 'Manage content, users, brands, categories, and view analytics',
                'Moderator': 'View-only access to most modules with limited editing capabilities'
            };

            roleSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const roleName = selectedOption.text;

                if (roleName && roleDescriptions[roleName]) {
                    rolePermissionsDiv.innerHTML = `<small class="text-muted">${roleDescriptions[roleName]}</small>`;
                } else {
                    rolePermissionsDiv.innerHTML = '<small class="text-muted">Select a role to view permissions</small>';
                }
            });
        });
    </script>
@endsection
