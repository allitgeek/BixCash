@extends('layouts.admin')

@section('title', 'Analytics - BixCash Admin')
@section('page-title', 'Analytics')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Analytics Dashboard</h3>
            <p class="text-sm text-gray-500 mt-1">Comprehensive insights and performance metrics</p>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-[#021c47] flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-[#021c47] mb-2">Analytics Dashboard</h4>
                <p class="text-gray-500 mb-8">Comprehensive analytics and insights will be available here.</p>
                
                <div class="max-w-md mx-auto">
                    <p class="text-sm font-semibold text-[#021c47] mb-4">Features coming soon:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">User engagement metrics</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Brand performance</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Category trends</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Revenue tracking</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors sm:col-span-2">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Real-time dashboard updates</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
