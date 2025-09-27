@extends('layouts.admin')

@section('title', 'Settings - BixCash Admin')
@section('page-title', 'Settings')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Settings</h3>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-cogs fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">System Settings</h4>
                        <p class="text-muted">System configuration and settings will be available here.</p>
                        <p class="text-muted">Settings categories coming soon:</p>
                        <ul class="list-unstyled text-muted">
                            <li>• General system configuration</li>
                            <li>• Email and notification settings</li>
                            <li>• Security and authentication settings</li>
                            <li>• API and integration settings</li>
                            <li>• Theme and appearance settings</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection