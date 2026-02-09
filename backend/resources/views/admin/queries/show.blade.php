@extends('layouts.admin')

@section('title', 'Query Details - BixCash Admin')
@section('page-title', 'Query Details')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.queries.index') }}" class="p-2 text-gray-500 hover:text-[#021c47] hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-[#021c47]">Query from {{ $query->name }}</h1>
            <p class="text-gray-500 mt-1">Submitted {{ $query->created_at->format('M j, Y') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Query Info -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Query Information</h2>
                <dl class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1">
                            @if($query->status == 'new')<span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">New</span>
                            @elseif($query->status == 'in_progress')<span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">In Progress</span>
                            @elseif($query->status == 'resolved')<span class="px-2.5 py-1 bg-[#93db4d]/20 text-[#5a9a2e] rounded-full text-xs font-medium">Resolved</span>
                            @else<span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">Closed</span>@endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Submitted</dt>
                        <dd class="mt-1 text-sm font-medium">{{ $query->created_at->format('M j, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Customer</dt>
                        <dd class="mt-1 text-sm font-medium">{{ $query->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="mt-1"><a href="mailto:{{ $query->email }}" class="text-sm text-[#021c47] hover:text-[#93db4d]">{{ $query->email }}</a></dd>
                    </div>
                    @if($query->assignedUser)
                    <div>
                        <dt class="text-sm text-gray-500">Assigned To</dt>
                        <dd class="mt-1 text-sm font-medium">{{ $query->assignedUser->name }}</dd>
                    </div>
                    @endif
                </dl>

                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">Message</p>
                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-[#021c47] whitespace-pre-wrap text-sm">{{ $query->message }}</div>
                </div>

                @if($query->admin_notes)
                <div class="mt-4">
                    <p class="text-sm text-gray-500 mb-2">Admin Notes</p>
                    <div class="bg-amber-50 p-4 rounded-lg border-l-4 border-amber-500 whitespace-pre-wrap text-sm">{{ $query->admin_notes }}</div>
                </div>
                @endif
            </div>

            <!-- Reply Form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-semibold text-[#021c47] mb-4 pb-2 border-b border-gray-200">Reply to Customer</h2>
                <form method="POST" action="{{ route('admin.queries.reply', $query) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="message" rows="5" required placeholder="Type your reply to {{ $query->name }}..."
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20"></textarea>
                        @error('message')<p class="mt-1 text-sm text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700">
                        <strong>Note:</strong> Reply will be sent via email to <strong>{{ $query->email }}</strong>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Send Reply</button>
                </form>
            </div>

            <!-- Reply History -->
            @if($query->replies->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <h2 class="font-semibold text-[#021c47]">Reply History ({{ $query->replies->count() }})</h2>
                </div>
                <div class="p-4 space-y-4">
                    @foreach($query->replies()->with('user')->orderBy('created_at', 'desc')->get() as $reply)
                    <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-[#93db4d]">
                        <div class="flex justify-between items-start mb-2">
                            <p class="font-medium text-[#021c47]">{{ $reply->user->name }}</p>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $reply->created_at->format('M j, Y h:i A') }}</p>
                                @if($reply->sent_at)<p class="text-xs text-[#93db4d]">✓ Sent</p>@endif
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $reply->message }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Update Form -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-[#021c47] mb-3">Update Query</h3>
                <form method="POST" action="{{ route('admin.queries.update', $query) }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" required class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                            <option value="new" {{ $query->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="in_progress" {{ $query->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $query->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $query->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                        <select name="assigned_to" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">
                            <option value="">Unassigned</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $query->assigned_to == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" rows="4" placeholder="Add notes..."
                                  class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20">{{ $query->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-[#021c47] text-white rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-colors font-medium">Update</button>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl border border-red-200 shadow-sm p-4">
                <h3 class="font-semibold text-red-700 mb-2">Danger Zone</h3>
                <form method="POST" action="{{ route('admin.queries.destroy', $query) }}" onsubmit="return confirm('Delete this query?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium">Delete Query</button>
                </form>
            </div>

            <a href="{{ route('admin.queries.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 text-gray-600 hover:text-[#021c47] bg-white rounded-xl border border-gray-200 shadow-sm hover:border-[#93db4d] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Queries
            </a>
        </div>
    </div>
</div>
@endsection
