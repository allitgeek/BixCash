@extends('layouts.admin')

@section('title', 'API Integrations - BixCash Admin')
@section('page-title', 'API Integrations')

@section('content')
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#021c47]">
                <p class="text-sm font-medium text-gray-500 mb-1">Total Integrations</p>
                <p class="text-2xl font-bold text-[#021c47]">{{ $integrations->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-[#93db4d]">
                <p class="text-sm font-medium text-gray-500 mb-1">Active</p>
                <p class="text-2xl font-bold text-[#021c47]">{{ $integrations->where('is_active', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-yellow-400">
                <p class="text-sm font-medium text-gray-500 mb-1">Total API Transactions</p>
                <p class="text-2xl font-bold text-[#021c47]">{{ number_format($totalApiTransactions) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-l-4 border-emerald-500">
                <p class="text-sm font-medium text-gray-500 mb-1">Total API Volume</p>
                <p class="text-2xl font-bold text-[#021c47]">Rs {{ number_format($totalApiVolume, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Integrations Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">Integrations</h3>
            <a href="{{ route('admin.integrations.create') }}"
               class="inline-flex items-center px-4 py-2 bg-[#021c47] text-white text-sm font-medium rounded-lg hover:bg-[#032d6b] transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Integration
            </a>
        </div>

        @if($integrations->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No integrations</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new API integration.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#021c47] text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">API Key</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total Requests</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Last Used</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($integrations as $integration)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $integration->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $integration->partner->partnerProfile->business_name ?? $integration->partner->name }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($integration->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 font-mono">
                                    {{ substr($integration->api_key, 0, 8) }}...
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ number_format($integration->total_requests) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $integration->last_used_at ? $integration->last_used_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.integrations.show', $integration) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-[#021c47] text-white text-xs font-medium rounded-lg hover:bg-[#032d6b] transition-colors">
                                            View
                                        </a>
                                        <form action="{{ route('admin.integrations.toggle-status', $integration) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg transition-colors {{ $integration->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                                {{ $integration->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
