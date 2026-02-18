@extends('layouts.admin')

@section('title', $integration->name . ' Integration - BixCash Admin')
@section('page-title', $integration->name . ' Integration')

@section('content')
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.integrations.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-[#021c47] transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back
            </a>
            <h2 class="text-xl font-bold text-[#021c47]">{{ $integration->name }}</h2>
            <span class="text-sm text-gray-500">{{ $integration->partner->partnerProfile->business_name ?? $integration->partner->name }}</span>
            @if($integration->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
            @endif
        </div>
        <form action="{{ route('admin.integrations.toggle-status', $integration) }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ $integration->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                {{ $integration->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Credentials Card -->
    @if(session('api_key') || session('api_secret'))
        <div class="mb-6 bg-yellow-50 border border-yellow-300 rounded-xl p-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div class="flex-1">
                    <h4 class="text-sm font-bold text-yellow-800 mb-2">Save these credentials now! The API Secret will not be shown again.</h4>
                    @if(session('api_key'))
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-yellow-700 mb-1">API Key</label>
                            <div class="flex items-center">
                                <code id="flash-api-key" class="flex-1 px-3 py-2 bg-white border border-yellow-300 rounded-lg text-sm font-mono text-gray-800 break-all">{{ session('api_key') }}</code>
                                <button type="button" onclick="copyToClipboard('flash-api-key')"
                                        class="ml-2 px-3 py-2 bg-yellow-200 text-yellow-800 rounded-lg text-xs font-medium hover:bg-yellow-300 transition-colors">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif
                    @if(session('api_secret'))
                        <div>
                            <label class="block text-xs font-medium text-yellow-700 mb-1">API Secret</label>
                            <div class="flex items-center">
                                <code id="flash-api-secret" class="flex-1 px-3 py-2 bg-white border border-yellow-300 rounded-lg text-sm font-mono text-gray-800 break-all">{{ session('api_secret') }}</code>
                                <button type="button" onclick="copyToClipboard('flash-api-secret')"
                                        class="ml-2 px-3 py-2 bg-yellow-200 text-yellow-800 rounded-lg text-xs font-medium hover:bg-yellow-300 transition-colors">
                                    Copy
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Credentials & Settings -->
        <div class="lg:col-span-1 space-y-6">
            <!-- API Credentials -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-[#021c47]">API Credentials</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">API Key</label>
                        <div class="flex items-center">
                            <code id="api-key-display" class="flex-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-xs font-mono text-gray-700 break-all">{{ $integration->api_key }}</code>
                            <button type="button" onclick="copyToClipboard('api-key-display')"
                                    class="ml-2 px-2 py-2 text-gray-500 hover:text-[#021c47] transition-colors" title="Copy">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">API Secret</label>
                        <p class="text-xs text-gray-400 italic">Hidden - regenerate to get new credentials</p>
                    </div>
                    <div class="pt-2">
                        <button type="button" onclick="document.getElementById('regenerate-modal').classList.remove('hidden')"
                                class="w-full px-4 py-2 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-lg hover:bg-yellow-200 transition-colors">
                            Regenerate Keys
                        </button>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-[#021c47]">Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        @if($integration->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Allowed IPs</label>
                        @if($integration->allowed_ips && count($integration->allowed_ips) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach($integration->allowed_ips as $ip)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-gray-100 text-gray-700">{{ $ip }}</span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-gray-400">All IPs allowed</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Rate Limit</label>
                        <p class="text-sm text-gray-700">{{ $integration->rate_limit_per_minute }} requests/minute</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Created</label>
                        <p class="text-sm text-gray-700">{{ $integration->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats & Transactions -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="text-xs font-medium text-gray-500 mb-1">Total Transactions</p>
                    <p class="text-xl font-bold text-[#021c47]">{{ number_format($totalTransactions) }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="text-xs font-medium text-gray-500 mb-1">Total Amount</p>
                    <p class="text-xl font-bold text-[#021c47]">Rs {{ number_format($totalAmount, 2) }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="text-xs font-medium text-gray-500 mb-1">Today</p>
                    <p class="text-xl font-bold text-[#021c47]">{{ number_format($todayCount) }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                    <p class="text-xs font-medium text-gray-500 mb-1">This Month</p>
                    <p class="text-xl font-bold text-[#021c47]">{{ number_format($monthCount) }}</p>
                </div>
            </div>

            <!-- Transaction Log -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-[#021c47] mb-3">Transaction Log</h3>
                    <form method="GET" action="{{ route('admin.integrations.show', $integration) }}" class="flex flex-wrap gap-3">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search order ID, customer..."
                               class="rounded-lg border-gray-300 text-sm focus:border-[#021c47] focus:ring-[#021c47] w-48">
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="rounded-lg border-gray-300 text-sm focus:border-[#021c47] focus:ring-[#021c47]">
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="rounded-lg border-gray-300 text-sm focus:border-[#021c47] focus:ring-[#021c47]">
                        <button type="submit"
                                class="px-4 py-2 bg-[#021c47] text-white text-sm rounded-lg hover:bg-[#032d6b] transition-colors">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'date_from', 'date_to']))
                            <a href="{{ route('admin.integrations.show', $integration) }}"
                               class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                @if($transactions->isEmpty())
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-500">No transactions recorded yet.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">External Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Transaction Code</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($transactions as $txn)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-600">{{ $txn->transaction_date->format('d M Y, h:i A') }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-700">{{ $txn->external_order_id }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            <div class="text-gray-900">{{ $txn->customer->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-gray-500">{{ $txn->customer->phone ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">Rs {{ number_format($txn->invoice_amount, 2) }}</td>
                                        <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $txn->transaction_code }}</td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ ucfirst($txn->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $transactions->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Regenerate Keys Confirmation Modal -->
    <div id="regenerate-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50" onclick="document.getElementById('regenerate-modal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Regenerate API Keys?</h3>
                <p class="text-sm text-gray-600 mb-6">This will invalidate the current API key and secret. The external partner will need to update their credentials. This action cannot be undone.</p>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('regenerate-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <form action="{{ route('admin.integrations.regenerate-keys', $integration) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                            Regenerate
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const el = document.getElementById(elementId);
            const text = el.textContent || el.innerText;
            navigator.clipboard.writeText(text.trim()).then(() => {
                const btn = el.nextElementSibling;
                const originalText = btn.innerHTML;
                btn.innerHTML = 'Copied!';
                btn.classList.add('text-green-600');
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.classList.remove('text-green-600');
                }, 2000);
            });
        }
    </script>
@endsection
