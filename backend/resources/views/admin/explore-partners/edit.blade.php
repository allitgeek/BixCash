@extends('layouts.admin')

@section('title', 'Edit ' . $partner->name . ' - BixCash Admin')
@section('page-title', 'Edit: ' . $partner->name)

@section('content')
<div class="mb-4 flex items-center justify-between flex-wrap gap-3">
    <div>
        <div class="inline-flex items-center gap-2 text-xs text-gray-500">
            <span>{{ ucfirst($partner->type) }}</span>
            <span>·</span>
            <span class="font-mono">/{{ $partner->slug }}</span>
            <span>·</span>
            <span>{{ $partner->is_active ? 'Active' : 'Inactive' }}</span>
        </div>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.explore-partners.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
            ← Back to list
        </a>
    </div>
</div>

@include('admin.explore-partners._form', ['partner' => $partner])
@endsection
