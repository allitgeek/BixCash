@extends('layouts.admin')

@section('title', 'Profit Sharing - BixCash Admin')
@section('page-title', 'Profit Sharing')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Profit Sharing Management</h2>
                <p class="text-gray-600">Manage partner profit sharing, commissions, and payouts</p>
            </div>
            <div class="flex items-center space-x-3">
                <a
                    href="{{ route('admin.profit-sharing.history') }}"
                    class="px-5 py-2.5 bg-gradient-to-r from-orange-600 to-orange-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-orange-700 hover:to-orange-950 transition-all duration-200 hover:-translate-y-0.5"
                >
                    📜 Distribution History
                </a>
                <button
                    onclick="recalculateLevels()"
                    class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-purple-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-purple-700 hover:to-purple-950 transition-all duration-200 hover:-translate-y-0.5"
                >
                    🔄 Recalculate FIFO Levels
                </button>
            </div>
        </div>

        {{-- Stats Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            {{-- Monthly Profit --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-600 to-green-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Monthly Profit</h3>
                <p class="text-3xl font-bold text-gray-900">Rs. 0</p>
                <p class="text-xs text-green-600 mt-2">Selected month</p>
            </div>

            {{-- Pending Payouts --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-orange-600 to-orange-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Pending Payouts</h3>
                <p class="text-3xl font-bold text-gray-900">Rs. 0</p>
                <p class="text-xs text-orange-600 mt-2">Awaiting processing</p>
            </div>

            {{-- Each Level Amount --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Each Level Amount</h3>
                <p class="text-3xl font-bold text-gray-900">Rs. 0</p>
                <p class="text-xs text-blue-600 mt-2">Per distribution level</p>
            </div>

            {{-- Active Customers --}}
            <div class="bg-white rounded-xl border border-gray-200/60 p-6 shadow-sm hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-600 to-purple-900 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">Active Customers</h3>
                <p class="text-3xl font-bold text-gray-900">0</p>
                <p class="text-xs text-purple-600 mt-2">In profit distribution</p>
            </div>
        </div>

        {{-- Main Content Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center justify-between">
                    {{-- Left: Title Section --}}
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">
                                Profit Sharing Center
                            </h3>
                            <p class="text-sm text-gray-500 mt-0.5">Manage partner commissions and payouts</p>
                        </div>
                    </div>

                    {{-- Center: Month Selector --}}
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <label for="profit_month" class="text-sm font-semibold text-gray-700">
                            Select Month:
                        </label>
                        <input
                            type="month"
                            id="profit_month"
                            name="profit_month"
                            value="{{ now()->format('Y-m') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-900 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        />
                    </div>

                    {{-- Right: Profit Amount Input & Buttons --}}
                    <div class="flex items-center space-x-3">
                        <label for="profit_amount" class="text-sm font-semibold text-gray-700">
                            Profit Amount:
                        </label>
                        <input
                            type="text"
                            id="profit_amount"
                            name="profit_amount"
                            placeholder="0"
                            onkeyup="formatNumberWithCommas(this)"
                            class="w-40 px-4 py-2 border border-gray-300 rounded-lg text-gray-900 text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        />
                        <button
                            type="button"
                            id="calculateBtn"
                            class="px-5 py-2 bg-gradient-to-r from-blue-600 to-blue-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-950 transition-all duration-200 hover:-translate-y-0.5"
                        >
                            Calculate
                        </button>
                        <button
                            type="button"
                            id="disperseBtn"
                            class="px-5 py-2 bg-gradient-to-r from-green-600 to-green-900 text-white text-sm font-semibold rounded-lg shadow-md hover:shadow-lg hover:from-green-700 hover:to-green-950 transition-all duration-200 hover:-translate-y-0.5"
                        >
                            Disperse
                        </button>
                    </div>
                </div>
            </div>

            {{-- Profit Distribution Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    {{-- Table Header --}}
                    <thead>
                        <tr class="bg-gradient-to-r from-blue-600 to-blue-900">
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Level
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Profit/Level
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Percentage%
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Customers
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider border-r border-blue-500/30">
                                Amount/Customer
                            </th>
                            <th class="px-6 py-4 text-left text-sm font-bold text-white uppercase tracking-wider">
                                Customers
                            </th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- Level 1 --}}
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150" data-level="1" data-customer-count="{{ $levels[1]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                1
                            </td>
                            <td id="profit_level_1" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(1, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_1" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[1]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[1]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[1]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[1]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_1" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(1, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 2 --}}
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150" data-level="2" data-customer-count="{{ $levels[2]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                2
                            </td>
                            <td id="profit_level_2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(2, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_2" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[2]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[2]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[2]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[2]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(2, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 3 --}}
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150" data-level="3" data-customer-count="{{ $levels[3]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                3
                            </td>
                            <td id="profit_level_3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(3, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_3" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[3]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[3]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[3]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[3]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(3, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 4 --}}
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150" data-level="4" data-customer-count="{{ $levels[4]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                4
                            </td>
                            <td id="profit_level_4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(4, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_4" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[4]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[4]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[4]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[4]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(4, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 5 --}}
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150" data-level="5" data-customer-count="{{ $levels[5]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                5
                            </td>
                            <td id="profit_level_5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(5, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_5" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[5]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[5]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[5]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[5]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(5, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 6 --}}
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150" data-level="6" data-customer-count="{{ $levels[6]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                6
                            </td>
                            <td id="profit_level_6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(6, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_6" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[6]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[6]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[6]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[6]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(6, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Level 7 --}}
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150" data-level="7" data-customer-count="{{ $levels[7]['total'] ?? 0 }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                7
                            </td>
                            <td id="profit_level_7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(7, 'profit')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_7" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm border-r border-gray-200">
                                @if(isset($levels[7]))
                                    <div class="text-gray-900 font-semibold">{{ $levels[7]['total'] }}</div>
                                    <div class="text-xs text-gray-600">A = <span class="text-green-600 font-medium">{{ $levels[7]['active'] }}</span>, I = <span class="text-red-600 font-medium">{{ $levels[7]['inactive'] }}</span></div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td id="amount_customer_7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                <div class="flex items-center space-x-2">
                                    <span class="value-display">---</span>
                                    <button type="button" class="edit-btn hidden text-blue-600 hover:text-blue-800 transition-colors duration-150" onclick="enableEdit(7, 'amount')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button type="button" class="text-blue-600 hover:text-blue-800 transition-colors duration-150">
                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>

                    {{-- Table Footer (Total Row) --}}
                    <tfoot>
                        <tr class="bg-gradient-to-r from-gray-100 to-gray-200 border-t-2 border-gray-300">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                Total:
                            </td>
                            <td id="profit_level_total" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                ---
                            </td>
                            <td id="percentage_total" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                0%
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-r border-gray-300">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                ---
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    /**
     * Format number input with commas (e.g., 10,000)
     */
    function formatNumberWithCommas(input) {
        // Get the current value and remove all non-digit characters
        let value = input.value.replace(/[^0-9]/g, '');

        // If empty, just return
        if (value === '') {
            input.value = '';
            return;
        }

        // Convert to number and format with commas
        let number = parseInt(value, 10);

        // Format with commas
        input.value = number.toLocaleString('en-US');
    }

    /**
     * Get the actual numeric value without commas
     */
    function getNumericValue(input) {
        return parseInt(input.value.replace(/[^0-9]/g, ''), 10) || 0;
    }

    /**
     * Calculate and display the total percentage
     */
    function calculatePercentageTotal() {
        let total = 0;

        // Sum all 7 percentage inputs
        for (let i = 1; i <= 7; i++) {
            const input = document.getElementById(`percentage_${i}`);
            const value = parseFloat(input.value) || 0;
            total += value;
        }

        // Display without trailing zeros and % sign
        const formattedPercentage = total % 1 === 0 ? total.toString() : total.toFixed(2).replace(/\.?0+$/, '');
        document.getElementById('percentage_total').textContent = formattedPercentage + '%';

        // Optional: Add warning if total is not 100%
        const totalCell = document.getElementById('percentage_total');
        if (total !== 100 && total > 0) {
            totalCell.classList.add('text-orange-600');
            totalCell.classList.remove('text-gray-900');
        } else if (total === 100) {
            totalCell.classList.add('text-green-600');
            totalCell.classList.remove('text-gray-900', 'text-orange-600');
        } else {
            totalCell.classList.add('text-gray-900');
            totalCell.classList.remove('text-orange-600', 'text-green-600');
        }
    }

    // Calculate button click handler
    document.getElementById('calculateBtn').addEventListener('click', function() {
        const profitInput = document.getElementById('profit_amount');
        const amount = getNumericValue(profitInput);

        if (amount === 0) {
            alert('Please enter a profit amount');
            return;
        }

        // Collect all percentages
        const percentages = [];
        let percentageTotal = 0;
        for (let i = 1; i <= 7; i++) {
            const percentage = parseFloat(document.getElementById(`percentage_${i}`).value) || 0;
            percentages.push(percentage);
            percentageTotal += percentage;
        }

        // Determine distribution method
        let useEqualDistribution = false;

        if (percentageTotal === 0) {
            // All percentages are empty/zero - divide equally by 7
            useEqualDistribution = true;
        } else if (percentageTotal !== 100) {
            // Percentages entered but don't total 100% - warn user
            if (!confirm(`Warning: Percentages total ${percentageTotal}% (not 100%). Continue anyway?`)) {
                return;
            }
        }

        let calculatedTotal = 0;

        // Calculate profit for each level
        for (let i = 1; i <= 7; i++) {
            let profitForLevel;

            if (useEqualDistribution) {
                // Equal distribution: divide by 7
                profitForLevel = amount / 7;
            } else {
                // Percentage-based distribution
                const percentage = percentages[i - 1];
                profitForLevel = (amount * percentage) / 100;
            }

            calculatedTotal += profitForLevel;

            // Format profit with commas
            const formattedProfit = profitForLevel.toLocaleString('en-US', {
                maximumFractionDigits: 0,
                minimumFractionDigits: 0
            });

            // Update Profit/Level cell (target the .value-display span)
            const profitCell = document.getElementById(`profit_level_${i}`);
            const profitValueSpan = profitCell.querySelector('.value-display');
            profitValueSpan.textContent = formattedProfit;

            // Show the edit button for Profit/Level
            const profitEditBtn = profitCell.querySelector('.edit-btn');
            profitEditBtn.classList.remove('hidden');

            // Calculate Amount/Customer
            const row = document.querySelector(`tr[data-level="${i}"]`);
            const customerCount = parseInt(row.getAttribute('data-customer-count')) || 0;

            const amountCell = document.getElementById(`amount_customer_${i}`);
            const amountValueSpan = amountCell.querySelector('.value-display');
            const amountEditBtn = amountCell.querySelector('.edit-btn');

            if (customerCount === 0) {
                amountValueSpan.textContent = 'N/A (0 customers)';
                amountEditBtn.classList.add('hidden'); // Hide edit button if no customers
            } else {
                const amountPerCustomer = profitForLevel / customerCount;
                const formattedAmount = amountPerCustomer.toLocaleString('en-US', {
                    maximumFractionDigits: 0,
                    minimumFractionDigits: 0
                });
                amountValueSpan.textContent = formattedAmount;
                amountEditBtn.classList.remove('hidden'); // Show edit button
            }
        }

        // Update Total row with original amount (not calculated total, to avoid rounding issues)
        const formattedTotal = amount.toLocaleString('en-US');
        document.getElementById('profit_level_total').textContent = formattedTotal;

        console.log('Calculated profit distribution using percentages');
    });

    // Disperse button click handler with modal confirmation
    document.getElementById('disperseBtn').addEventListener('click', function() {
        const amount = getNumericValue(document.getElementById('profit_amount'));
        const month = document.getElementById('profit_month').value;

        if (amount === 0) {
            alert('Please calculate profit distribution first!');
            return;
        }

        // Collect level data
        const levelsData = [];
        let totalActiveUsers = 0;

        for (let i = 1; i <= 7; i++) {
            const profitCell = document.getElementById(`profit_level_${i}`);
            const amountCell = document.getElementById(`amount_customer_${i}`);
            const percentageInput = document.getElementById(`percentage_${i}`);
            const row = document.querySelector(`tr[data-level="${i}"]`);

            const profit = parseFloat(profitCell.querySelector('.value-display').textContent.replace(/,/g, '')) || 0;
            const perCustomer = parseFloat(amountCell.querySelector('.value-display').textContent.replace(/,/g, '')) || 0;
            const percentage = parseFloat(percentageInput.value) || 0;
            const customerCount = parseInt(row.querySelector('.text-green-600')?.textContent) || 0;

            levelsData.push({ profit, per_customer: perCustomer, percentage });
            totalActiveUsers += customerCount;
        }

        // Show confirmation modal
        showDisperseModal(amount, month, levelsData, totalActiveUsers);
    });

    function showDisperseModal(amount, month, levelsData, totalActiveUsers) {
        // Create modal overlay
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">⚠️ Confirm Profit Distribution</h3>
                    <p class="text-gray-700 mb-4">You are about to disperse <strong>Rs. ${amount.toLocaleString()}</strong> for <strong>${month}</strong></p>

                    <div class="bg-gray-50 rounded-lg p-4 mb-4 space-y-2">
                        ${levelsData.map((level, idx) =>
                            level.profit > 0 ? `<div class="flex justify-between text-sm">
                                <span>✓ Level ${idx + 1}:</span>
                                <span>Rs. ${level.profit.toLocaleString()} (Rs. ${level.per_customer.toLocaleString()}/user)</span>
                            </div>` : ''
                        ).join('')}
                        <div class="border-t border-gray-300 mt-2 pt-2 flex justify-between font-semibold">
                            <span>Total Recipients (active only):</span>
                            <span>${totalActiveUsers} users</span>
                        </div>
                    </div>

                    <p class="text-red-600 text-sm mb-4">⚠️ This action cannot be undone!</p>

                    <div class="flex space-x-3">
                        <button onclick="closeDisperseModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                        <button onclick="showFinalConfirm(${amount}, '${month}', ${JSON.stringify(levelsData).replace(/"/g, '&quot;')})" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Continue</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        window.currentDisperseModal = modal;
    }

    window.closeDisperseModal = function() {
        if (window.currentDisperseModal) {
            window.currentDisperseModal.remove();
        }
        if (window.finalConfirmModal) {
            window.finalConfirmModal.remove();
        }
    };

    window.showFinalConfirm = function(amount, month, levelsData) {
        closeDisperseModal();

        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        modal.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🔐 Final Confirmation Required</h3>
                    <p class="text-gray-700 mb-4">This will credit Rs. ${amount.toLocaleString()} to active user wallets.</p>

                    <label class="block text-sm font-medium text-gray-700 mb-2">Type "DISPERSE" to confirm:</label>
                    <input type="text" id="confirmText" class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4" autocomplete="off">

                    <div class="flex space-x-3">
                        <button onclick="closeDisperseModal()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                        <button onclick="executeDisperse(${amount}, '${month}', ${JSON.stringify(levelsData).replace(/"/g, '&quot;')})" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Execute Disperse</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        window.finalConfirmModal = modal;

        setTimeout(() => document.getElementById('confirmText')?.focus(), 100);
    };

    window.executeDisperse = function(amount, month, levelsData) {
        const confirmText = document.getElementById('confirmText').value;

        if (confirmText !== 'DISPERSE') {
            alert('Please type "DISPERSE" to confirm');
            return;
        }

        const button = event.target;
        button.disabled = true;
        button.textContent = 'Dispersing...';

        fetch('{{ route("admin.profit-sharing.disperse") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                distribution_month: month,
                total_amount: amount,
                levels: levelsData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeDisperseModal();
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
                button.disabled = false;
                button.textContent = 'Execute Disperse';
            }
        })
        .catch(error => {
            alert('Error dispersing: ' + error);
            button.disabled = false;
            button.textContent = 'Execute Disperse';
        });
    };

    /**
     * Enable inline editing for a cell
     * @param {number} level - The level number (1-7)
     * @param {string} type - Either 'profit' or 'amount'
     */
    function enableEdit(level, type) {
        const cellId = type === 'profit' ? `profit_level_${level}` : `amount_customer_${level}`;
        const cell = document.getElementById(cellId);
        const valueSpan = cell.querySelector('.value-display');
        const editBtn = cell.querySelector('.edit-btn');

        // Get current value (remove commas and non-numeric characters except decimal point)
        const currentValue = valueSpan.textContent.replace(/,/g, '').replace(/[^0-9.]/g, '');

        // Replace span with input field
        const input = document.createElement('input');
        input.type = 'number';
        input.step = '0.01';
        input.value = currentValue;
        input.className = 'w-32 px-2 py-1 text-sm border border-blue-500 rounded focus:ring-2 focus:ring-blue-500';
        input.dataset.level = level;
        input.dataset.type = type;

        // Save on blur
        input.addEventListener('blur', function() {
            saveEdit(level, type, this.value);
        });

        // Save on Enter key
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.blur(); // Trigger blur event which saves
            }
        });

        // Replace the value span with input
        valueSpan.replaceWith(input);
        input.focus();
        input.select();

        // Hide edit button while editing
        editBtn.classList.add('hidden');
    }

    /**
     * Save the edited value and restore display
     * @param {number} level - The level number (1-7)
     * @param {string} type - Either 'profit' or 'amount'
     * @param {string} value - The new value
     */
    function saveEdit(level, type, value) {
        const cellId = type === 'profit' ? `profit_level_${level}` : `amount_customer_${level}`;
        const cell = document.getElementById(cellId);
        const input = cell.querySelector('input');
        const editBtn = cell.querySelector('.edit-btn');

        // Parse and validate the value
        const numericValue = parseFloat(value) || 0;

        // Format the value with commas
        const formattedValue = numericValue.toLocaleString('en-US', {
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        });

        // Create new value span
        const valueSpan = document.createElement('span');
        valueSpan.className = 'value-display';
        valueSpan.textContent = formattedValue;

        // Replace input with span
        input.replaceWith(valueSpan);

        // Show edit button again
        editBtn.classList.remove('hidden');

        // If editing Profit/Level, recalculate Amount/Customer for this level
        if (type === 'profit') {
            recalculateAmountPerCustomer(level, numericValue);
        }

        // Update total row
        updateTotalRow();
    }

    /**
     * Recalculate Amount/Customer when Profit/Level is manually edited
     * @param {number} level - The level number (1-7)
     * @param {number} profitForLevel - The new profit amount for this level
     */
    function recalculateAmountPerCustomer(level, profitForLevel) {
        const row = document.querySelector(`tr[data-level="${level}"]`);
        const customerCount = parseInt(row.getAttribute('data-customer-count')) || 0;

        const amountCell = document.getElementById(`amount_customer_${level}`);
        const amountValueSpan = amountCell.querySelector('.value-display');
        const amountEditBtn = amountCell.querySelector('.edit-btn');

        if (customerCount === 0) {
            amountValueSpan.textContent = 'N/A (0 customers)';
            amountEditBtn.classList.add('hidden');
        } else {
            const amountPerCustomer = profitForLevel / customerCount;
            const formattedAmount = amountPerCustomer.toLocaleString('en-US', {
                maximumFractionDigits: 0,
                minimumFractionDigits: 0
            });
            amountValueSpan.textContent = formattedAmount;
            amountEditBtn.classList.remove('hidden');
        }
    }

    /**
     * Update the total row by summing all Profit/Level values
     */
    function updateTotalRow() {
        let total = 0;

        // Sum all 7 profit levels
        for (let i = 1; i <= 7; i++) {
            const cell = document.getElementById(`profit_level_${i}`);
            const valueSpan = cell.querySelector('.value-display');
            const value = valueSpan.textContent.replace(/,/g, '').replace(/[^0-9.]/g, '');
            total += parseFloat(value) || 0;
        }

        // Format and update total
        const formattedTotal = total.toLocaleString('en-US', {
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        });
        document.getElementById('profit_level_total').textContent = formattedTotal;
    }

    /**
     * Recalculate FIFO levels by running the assignment command
     */
    function recalculateLevels() {
        if (!confirm('This will recalculate all profit sharing levels based on current criteria and thresholds. Continue?')) {
            return;
        }

        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '⏳ Calculating...';
        button.disabled = true;

        fetch('{{ route("admin.profit-sharing.run-assignment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error running assignment: ' + error);
            console.error('Assignment error:', error);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
</script>
@endpush
