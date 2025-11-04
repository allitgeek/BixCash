@extends('layouts.admin')

@section('title', 'Profit Sharing - BixCash Admin')
@section('page-title', 'Profit Sharing')

@section('content')
    <div class="max-w-7xl mx-auto">
        {{-- Page Header --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Profit Sharing Management</h2>
            <p class="text-gray-600">Manage partner profit sharing, commissions, and payouts</p>
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
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                1
                            </td>
                            <td id="profit_level_1" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_1" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                2
                            </td>
                            <td id="profit_level_2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_2" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                3
                            </td>
                            <td id="profit_level_3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_3" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                4
                            </td>
                            <td id="profit_level_4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_4" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                5
                            </td>
                            <td id="profit_level_5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_5" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="bg-gray-50/50 hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                6
                            </td>
                            <td id="profit_level_6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_6" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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
                        <tr class="hover:bg-blue-50/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 border-r border-gray-200">
                                7
                            </td>
                            <td id="profit_level_7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                <div class="flex items-center space-x-1">
                                    <input type="number" id="percentage_7" step="0.01" min="0" max="100" placeholder="0.00" oninput="calculatePercentageTotal()" class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="text-sm text-gray-600">%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-200">
                                ---
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

        // Divide profit by 7 levels
        const profitPerLevel = amount / 7;

        // Format with commas, remove trailing zeros
        const formattedProfit = profitPerLevel.toLocaleString('en-US', {
            maximumFractionDigits: 2
        });

        // Update each level's Profit/Level column
        for (let i = 1; i <= 7; i++) {
            document.getElementById(`profit_level_${i}`).textContent = formattedProfit;
        }

        // Update Total row with original amount
        const formattedTotal = amount.toLocaleString('en-US');
        document.getElementById('profit_level_total').textContent = formattedTotal;

        console.log('Calculated profit per level:', profitPerLevel);
    });

    // Disperse button click handler (placeholder)
    document.getElementById('disperseBtn').addEventListener('click', function() {
        const amount = getNumericValue(document.getElementById('profit_amount'));
        console.log('Disperse clicked with amount:', amount);
        // TODO: Add disperse functionality
    });
</script>
@endpush
