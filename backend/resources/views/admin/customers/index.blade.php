@extends('layouts.admin')

@section('title', 'Customer Management - BixCash Admin')
@section('page-title', 'Customer Management')

@section('content')
    {{-- Page Header --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Manage Customers</h3>
            <p class="text-sm text-gray-500 mt-1">View and manage customer accounts registered via mobile app</p>
        </div>
        
        <div class="p-6">
            {{-- Search and Filter Form --}}
            <form method="GET" action="{{ route('admin.customers.index') }}" class="mb-6">
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[250px]">
                        <label for="search" class="block text-sm font-medium text-[#021c47] mb-2">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                               placeholder="Search by name, email, or phone..."
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-[#021c47] mb-2">Status</label>
                        <select id="status" name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg min-w-[130px] focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="phone_verified" class="block text-sm font-medium text-[#021c47] mb-2">Phone</label>
                        <select id="phone_verified" name="phone_verified" class="px-4 py-2.5 border border-gray-200 rounded-lg min-w-[130px] focus:outline-none focus:border-[#93db4d] focus:ring-2 focus:ring-[#93db4d]/20 transition-all">
                            <option value="">All</option>
                            <option value="1" {{ request('phone_verified') === '1' ? 'selected' : '' }}>Verified</option>
                            <option value="0" {{ request('phone_verified') === '0' ? 'selected' : '' }}>Unverified</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-5 py-2.5 bg-[#021c47] text-white font-medium rounded-lg hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'phone_verified']))
                            <a href="{{ route('admin.customers.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-medium rounded-lg hover:bg-gray-200 transition-all duration-200">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- Statistics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                {{-- Total Customers --}}
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#021c47]"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Total Customers</p>
                        <p class="text-3xl font-bold text-[#021c47] mt-1">{{ $customers->total() }}</p>
                    </div>
                </div>
                
                {{-- Active Customers --}}
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#93db4d]"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Active Customers</p>
                        <p class="text-3xl font-bold text-[#93db4d] mt-1">
                            {{ $customers->where('is_active', true)->count() }}
                        </p>
                    </div>
                </div>
                
                {{-- Active (Criteria) --}}
                <div class="relative bg-white rounded-xl border border-gray-200 p-5 hover:border-[#93db4d] hover:shadow-md transition-all duration-200 overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#021c47]"></div>
                    <div class="pl-3">
                        <p class="text-sm font-medium text-gray-500">Active (Criteria)</p>
                        <p class="text-3xl font-bold text-[#021c47] mt-1">
                            {{ $customers->filter(function($c) use ($minSpending) {
                                return floatval($c->total_spending ?? 0) >= $minSpending;
                            })->count() }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Current month only</p>
                    </div>
                </div>
            </div>

            {{-- Customers Table --}}
            @if($customers->count() > 0)
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#021c47] text-white">
                                <th class="px-4 py-3 text-left text-sm font-semibold">Customer</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Phone</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Email</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Account Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Criteria Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Wallet Balance</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Last Transaction</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">PIN</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Registered</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($customers as $customer)
                                <tr class="hover:bg-[#93db4d]/5 transition-colors duration-150">
                                    <td class="px-4 py-3">
                                        <div>
                                            <p class="font-semibold text-[#021c47]">{{ $customer->name }}</p>
                                            @if($customer->customerProfile && $customer->customerProfile->full_name)
                                                <p class="text-xs text-gray-500">{{ $customer->customerProfile->full_name }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm">{{ $customer->phone }}</span>
                                            @php
                                                $profile = $customer->customerProfile;
                                                $phoneVerified = $customer->hasVerifiedPhone();
                                                $manuallyVerified = $profile && $profile->is_verified;
                                            @endphp

                                            @if($phoneVerified && $manuallyVerified)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#93db4d]/20 text-[#021c47]">
                                                    ✓ Verified
                                                </span>
                                            @elseif($phoneVerified && !$manuallyVerified)
                                                <form method="POST" action="{{ route('admin.customers.verify-phone', $customer) }}" class="inline" onsubmit="return confirm('Have you called this customer to confirm their identity?');">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors">
                                                        ⚠ Verify Phone
                                                    </button>
                                                </form>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    ✗ Unverified
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600">
                                        {{ $customer->email ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($customer->is_active)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#93db4d]/20 text-[#021c47]">
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $totalSpending = floatval($customer->total_spending ?? 0);
                                            $meetsCriteria = $totalSpending >= $minSpending;
                                        @endphp
                                        @if($meetsCriteria)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-[#93db4d]/20 text-[#021c47]" title="Total spending: Rs. {{ number_format($totalSpending, 0) }} (Min: Rs. {{ number_format($minSpending, 0) }})">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800" title="Total spending: Rs. {{ number_format($totalSpending, 0) }} (Min: Rs. {{ number_format($minSpending, 0) }})">
                                                ✗ Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($customer->wallet)
                                            <span class="font-semibold text-[#93db4d]">
                                                Rs. {{ number_format($customer->wallet->balance, 0) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Rs. 0</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($customer->last_transaction_date)
                                            <span class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($customer->last_transaction_date)->format('M j, Y') }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400 italic">No transactions</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($customer->pin_hash)
                                            @if($customer->isPinLocked())
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Locked
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-[#93db4d]/20 text-[#021c47]">
                                                    Set
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-400">Not Set</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-600">
                                            {{ $customer->created_at->format('M j, Y') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-1 justify-center flex-wrap">
                                            <a href="{{ route('admin.customers.show', $customer) }}"
                                               class="px-3 py-1.5 text-xs font-medium bg-[#021c47] text-white rounded hover:bg-[#93db4d] hover:text-[#021c47] transition-all duration-200">
                                                View
                                            </a>
                                            <a href="{{ route('admin.customers.edit', $customer) }}"
                                               class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-all duration-200">
                                                Edit
                                            </a>
                                            <a href="{{ route('admin.customers.transactions', $customer) }}"
                                               class="px-3 py-1.5 text-xs font-medium bg-[#93db4d]/20 text-[#021c47] rounded hover:bg-[#93db4d] hover:text-white transition-all duration-200">
                                                Txns
                                            </a>
                                            <form method="POST" action="{{ route('admin.customers.toggle-status', $customer) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-3 py-1.5 text-xs font-medium rounded transition-all duration-200 {{ $customer->is_active ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-[#93db4d] text-white hover:bg-[#021c47]' }}">
                                                    {{ $customer->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6 flex justify-center">
                    {{ $customers->withQueryString()->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h4 class="mt-4 text-lg font-semibold text-[#021c47]">No customers found</h4>
                    <p class="mt-2 text-gray-500">
                        {{ request()->hasAny(['search', 'status', 'phone_verified']) ? 'Try adjusting your search criteria.' : 'Customers will appear here once they register via the mobile app.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection
