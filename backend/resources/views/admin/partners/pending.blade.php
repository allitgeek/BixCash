@extends('layouts.admin')

@section('title', 'Pending Partner Applications - BixCash Admin')
@section('page-title', 'Pending Partner Applications')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-[#021c47]">Pending Applications</h3>
                <p class="text-sm text-gray-500 mt-1">Review and approve partner applications</p>
            </div>
            <a href="{{ route('admin.partners.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                All Partners
            </a>
        </div>
        
        <div class="p-6">
            @if($applications->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-4 py-3 text-left text-sm font-semibold">Partner</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Business Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Phone</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Business Type</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Applied</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($applications as $partner)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-[#021c47]">{{ $partner->name }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-[#021c47]">{{ $partner->partnerProfile->business_name ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-[#021c47]">{{ $partner->phone }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">{{ $partner->partnerProfile->business_type ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-[#021c47]">{{ $partner->created_at->format('M j, Y') }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $partner->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('admin.partners.show', $partner) }}" class="px-4 py-2 bg-[#021c47] text-white text-xs font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                                            Review Application
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($applications->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $applications->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-[#021c47] mb-2">No pending applications</h4>
                    <p class="text-gray-500">All partner applications have been reviewed.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
