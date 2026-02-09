@extends('layouts.admin')

@section('title', 'Customer Queries - BixCash Admin')
@section('page-title', 'Customer Queries')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-[#021c47]">Customer Queries</h1>
        <p class="text-gray-500 mt-1">Manage and respond to customer inquiries</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#021c47]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#021c47]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Total</p><p class="text-xl font-bold text-[#021c47]">{{ $stats['total'] }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">New</p><p class="text-xl font-bold text-red-600">{{ $stats['new'] }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">In Progress</p><p class="text-xl font-bold text-amber-600">{{ $stats['in_progress'] }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#93db4d]/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Resolved</p><p class="text-xl font-bold text-[#93db4d]">{{ $stats['resolved'] }}</p></div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div><p class="text-sm text-gray-500">Unread</p><p class="text-xl font-bold text-purple-600">{{ $stats['unread'] }}</p></div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                       class="flex-1 px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                <select name="status" class="px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                    <option value="">All Statuses</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Filter</button>
                    <a href="{{ route('admin.queries.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Clear</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        @if($queries->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#021c47] text-white text-left">
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Name</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Message</th>
                        <th class="px-4 py-3 font-semibold">Submitted</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($queries as $query)
                    <tr class="border-b border-gray-100 hover:bg-[#93db4d]/5 transition-colors {{ $query->read_at ? '' : 'bg-amber-50' }}">
                        <td class="px-4 py-3">
                            @if($query->status == 'new')
                                <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">New</span>
                            @elseif($query->status == 'in_progress')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">In Progress</span>
                            @elseif($query->status == 'resolved')
                                <span class="px-2.5 py-1 bg-[#93db4d]/20 text-[#5a9a2e] rounded-full text-xs font-medium">Resolved</span>
                            @else
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Closed</span>
                            @endif
                            @if(!$query->read_at)<p class="text-xs text-amber-600 mt-1">Unread</p>@endif
                        </td>
                        <td class="px-4 py-3 font-medium text-[#021c47]">{{ $query->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $query->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ Str::limit($query->message, 60) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            <p>{{ $query->created_at->format('M j, Y') }}</p>
                            <p class="text-xs">{{ $query->created_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.queries.show', $query) }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.queries.destroy', $query) }}" class="inline" onsubmit="return confirm('Delete this query?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($queries->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">{{ $queries->links() }}</div>
        @endif
        @else
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">No queries found</h3>
            <p class="text-gray-500">Queries from the contact form will appear here.</p>
        </div>
        @endif
    </div>
</div>
@endsection
