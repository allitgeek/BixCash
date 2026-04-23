@extends('layouts.admin')

@section('title', 'Add Explore Partner - BixCash Admin')
@section('page-title', 'Add Explore Partner')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-500">New homepage partner card.</p>
    </div>
    <a href="{{ route('admin.explore-partners.index') }}"
       class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
        ← Back to list
    </a>
</div>

@include('admin.explore-partners._form', ['partner' => $partner])
@endsection
