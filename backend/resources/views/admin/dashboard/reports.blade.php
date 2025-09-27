@extends('layouts.admin')

@section('title', 'Reports - BixCash Admin')
@section('page-title', 'Reports')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reports Center</h3>
                </div>
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Reports Center</h4>
                        <p class="text-muted">Detailed reports and data exports will be available here.</p>
                        <p class="text-muted">Report types coming soon:</p>
                        <ul class="list-unstyled text-muted">
                            <li>• User activity reports</li>
                            <li>• Brand performance reports</li>
                            <li>• Financial and commission reports</li>
                            <li>• Content engagement reports</li>
                            <li>• System usage statistics</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection