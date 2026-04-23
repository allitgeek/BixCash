@extends('layouts.admin')

@section('title', $partner->name . ' - BixCash Admin')
@section('page-title', $partner->name)

@section('content')
<div class="mb-4 flex items-center justify-between flex-wrap gap-3">
    <a href="{{ route('admin.explore-partners.index') }}"
       class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50">← Back to list</a>
    <a href="{{ route('admin.explore-partners.edit', $partner) }}"
       class="inline-flex items-center gap-2 px-5 py-2 bg-[#93db4d] text-[#021c47] font-semibold rounded-lg hover:bg-[#7ec43f]">Edit</a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
    <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center">
            @if($partner->logo_path)
                <img src="{{ asset('storage/'.$partner->logo_path) }}" alt="" class="w-full h-full object-cover">
            @endif
        </div>
        <div>
            <div class="text-lg font-bold text-[#021c47]">{{ $partner->name }}</div>
            <div class="text-xs text-gray-500">{{ ucfirst($partner->type) }} · /{{ $partner->slug }}</div>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
        <div><span class="text-gray-500">Category:</span> {{ $partner->category_tag ?: '—' }}</div>
        <div><span class="text-gray-500">Media:</span> {{ $partner->media->count() }} slide(s)</div>
        <div><span class="text-gray-500">Branches:</span> {{ $partner->branches->count() }}</div>
        <div><span class="text-gray-500">Active:</span> {{ $partner->is_active ? 'Yes' : 'No' }}</div>
        <div><span class="text-gray-500">Featured:</span> {{ $partner->is_featured ? 'Yes' : 'No' }}</div>
        <div><span class="text-gray-500">New:</span> {{ $partner->is_new ? 'Yes' : 'No' }}</div>
    </div>
    @if($partner->short_description)
        <p class="text-sm text-gray-600">{{ $partner->short_description }}</p>
    @endif
</div>
@endsection
