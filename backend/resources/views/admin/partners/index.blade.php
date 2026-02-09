@extends('layouts.admin')

@section('title', 'Partner Management - BixCash Admin')
@section('page-title', 'Partner Management')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#021c47]">Manage Partners</h3>
                    <p class="text-sm text-gray-500 mt-1">View and manage all business partners</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.partners.create') }}" class="px-4 py-2 bg-[#93db4d] text-[#021c47] font-medium rounded-lg hover:bg-[#7fc93d] transition-all duration-200">
                        + Create Partner
                    </a>
                    <a href="{{ route('admin.partners.pending') }}" class="px-4 py-2 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-600 transition-all duration-200 flex items-center gap-2">
                        Pending Applications
                        @php
                            $pendingCount = \App\Models\User::whereHas('role', function($q) {
                                $q->where('name', 'partner');
                            })->whereHas('partnerProfile', function($q) {
                                $q->where('status', 'pending');
                            })->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="px-2 py-0.5 text-xs font-bold bg-white text-yellow-600 rounded-full">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            {{-- Search and Filter --}}
            <form method="GET" action="{{ route('admin.partners.index') }}" class="mb-6">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[250px]">
                        <label for="search" class="block text-sm font-medium text-[#021c47] mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, phone, or business name..."
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-[#021c47] mb-2">Status</label>
                        <select id="status" name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg min-w-[150px] focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.partners.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#021c47]"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Total Partners</p>
                        <p class="text-3xl font-bold text-[#021c47] mt-1">{{ $partners->total() }}</p>
                    </div>
                </div>
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#93db4d]"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Approved Partners</p>
                        <p class="text-3xl font-bold text-[#93db4d] mt-1">
                            {{ $partners->filter(fn($p) => $p->partnerProfile && $p->partnerProfile->status === 'approved')->count() }}
                        </p>
                    </div>
                </div>
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-500"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Active (Criteria)</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-1">
                            {{ $partners->filter(function($p) use ($minCustomers, $minAmount) {
                                $uniqueCustomers = intval($p->unique_customers_count ?? 0);
                                $totalAmount = floatval($p->total_transaction_amount ?? 0);
                                return ($uniqueCustomers >= $minCustomers) && ($totalAmount >= $minAmount);
                            })->count() }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Current month only</p>
                    </div>
                </div>
            </div>

            {{-- Partners Table --}}
            @if($partners->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-3 py-3 text-left font-semibold">Partner</th>
                                <th class="px-3 py-3 text-left font-semibold">Business</th>
                                <th class="px-3 py-3 text-left font-semibold">Commission</th>
                                <th class="px-3 py-3 text-left font-semibold">Phone</th>
                                <th class="px-3 py-3 text-center font-semibold">Status</th>
                                <th class="px-3 py-3 text-center font-semibold">Account</th>
                                <th class="px-3 py-3 text-center font-semibold">Criteria</th>
                                <th class="px-3 py-3 text-right font-semibold">Wallet</th>
                                <th class="px-3 py-3 text-left font-semibold">Last Txn</th>
                                <th class="px-3 py-3 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($partners as $partner)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors">
                                    <td class="px-3 py-3">
                                        <span class="font-semibold text-[#021c47]">{{ $partner->name }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-gray-600">
                                        {{ $partner->partnerProfile->business_name ?? '-' }}
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($partner->partnerProfile && $partner->partnerProfile->commission_rate > 0)
                                            <span class="font-medium text-[#021c47]">{{ number_format($partner->partnerProfile->commission_rate, 2) }}%</span>
                                        @else
                                            <span class="text-gray-400">0%</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center gap-1">
                                            <span class="text-[#021c47]">{{ $partner->phone }}</span>
                                            @php
                                                $profile = $partner->partnerProfile;
                                                $phoneVerified = $partner->hasVerifiedPhone();
                                                $manuallyVerified = $profile && $profile->is_verified;
                                            @endphp
                                            @if($phoneVerified && $manuallyVerified)
                                                <span class="px-1.5 py-0.5 text-xs font-medium rounded bg-[#93db4d]/20 text-[#65a030]">✓</span>
                                            @elseif($phoneVerified && !$manuallyVerified)
                                                <form method="POST" action="{{ route('admin.partners.verify-phone', $partner) }}" class="inline" onsubmit="return confirm('Verify this phone?')">
                                                    @csrf
                                                    <button type="submit" class="px-1.5 py-0.5 text-xs font-medium rounded bg-yellow-100 text-yellow-700">⚠</button>
                                                </form>
                                            @else
                                                <span class="px-1.5 py-0.5 text-xs font-medium rounded bg-red-100 text-red-600">✗</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if($partner->partnerProfile)
                                            @if($partner->partnerProfile->status === 'approved')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Approved</span>
                                            @elseif($partner->partnerProfile->status === 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Rejected</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @if($partner->is_active)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        @php
                                            $uniqueCustomers = intval($partner->unique_customers_count ?? 0);
                                            $totalAmount = floatval($partner->total_transaction_amount ?? 0);
                                            $meetsCriteria = ($uniqueCustomers >= $minCustomers) && ($totalAmount >= $minAmount);
                                        @endphp
                                        @if($meetsCriteria)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-[#93db4d]/20 text-[#65a030]" title="Customers: {{ $uniqueCustomers }}, Amount: Rs. {{ number_format($totalAmount, 0) }}">✓</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-600" title="Customers: {{ $uniqueCustomers }}, Amount: Rs. {{ number_format($totalAmount, 0) }}">✗</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-right">
                                        @if($partner->wallet)
                                            <span class="font-semibold text-[#93db4d]">Rs. {{ number_format($partner->wallet->balance, 0) }}</span>
                                        @else
                                            <span class="text-gray-400">Rs. 0</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3">
                                        @if($partner->last_transaction_date)
                                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($partner->last_transaction_date)->format('M j, Y') }}</span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <div class="flex gap-1 justify-center">
                                            <a href="{{ route('admin.partners.show', $partner) }}" class="px-2 py-1 text-xs font-medium bg-[#021c47] text-white rounded hover:bg-[#93db4d] hover:text-[#021c47] transition-all">View</a>
                                            <a href="{{ route('admin.partners.transactions', $partner) }}" class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-all">Txns</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($partners->hasPages())
                    <div class="mt-6 flex justify-center">
                        {{ $partners->withQueryString()->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <h4 class="text-lg font-semibold text-[#021c47] mb-2">No partners found</h4>
                    <p class="text-gray-500">{{ request()->hasAny(['search', 'status']) ? 'Try adjusting your search criteria.' : 'Partners will appear here once they register.' }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
