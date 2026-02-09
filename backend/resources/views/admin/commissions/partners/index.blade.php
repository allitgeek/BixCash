@extends('layouts.admin')

@section('title', 'Partner Commissions - BixCash Admin')
@section('page-title', 'Partner Commissions')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Partner name or business..."
                           class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter</label>
                    <select name="outstanding_only" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="">All Partners</option>
                        <option value="1" {{ request('outstanding_only') ? 'selected' : '' }}>With Outstanding Only</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-colors">
                        <option value="total_outstanding" {{ request('sort') == 'total_outstanding' ? 'selected' : '' }}>Outstanding (High to Low)</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-[#021c47] text-white py-2 px-4 rounded-lg font-medium hover:bg-[#93db4d] hover:text-[#021c47] transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('admin.commissions.partners.index') }}" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Partners Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-[#021c47]">All Partners with Commissions</h3>
            <a href="{{ route('admin.commissions.export.ledgers', request()->query()) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#93db4d] text-[#021c47] rounded-lg font-medium hover:bg-[#7bc62e] transition-colors"
               onclick="return confirm('Export commission ledgers to Excel?\n\nCurrent filters and sorting will be applied to the export.');">
                Export Ledgers
                @if(request()->hasAny(['search', 'outstanding_only']))
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-yellow-400 text-yellow-900">Filtered</span>
                @endif
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#021c47] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Partner</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Commission Rate</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total Ledgers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pending Ledgers</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Outstanding Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($partners as $partner)
                        @php
                            $profile = $partner->partnerProfile;
                        @endphp
                        <tr class="hover:bg-[#93db4d]/5 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-semibold text-gray-900">{{ $profile->business_name ?? $partner->name }}</span><br>
                                <span class="text-sm text-gray-500">{{ $partner->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $partner->phone }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ number_format($profile->commission_rate ?? 0, 2) }}%</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $partner->total_ledgers }}</td>
                            <td class="px-4 py-3">
                                @if($partner->pending_ledgers > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">{{ $partner->pending_ledgers }}</span>
                                @else
                                    <span class="text-gray-400">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($partner->total_outstanding > 0)
                                    <span class="font-semibold text-red-600">Rs {{ number_format($partner->total_outstanding, 2) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.commissions.partners.show', $partner->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 border border-[#021c47] text-[#021c47] rounded-lg text-sm font-medium hover:bg-[#021c47] hover:text-white transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500">No partners found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($partners->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $partners->links() }}
            </div>
        @endif
    </div>
@endsection
