@extends('layouts.admin')

@section('title', 'Reports - BixCash Admin')
@section('page-title', 'Reports')

@section('content')
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#021c47]">Reports Center</h3>
            <p class="text-sm text-gray-500 mt-1">Generate and export detailed business reports</p>
        </div>
        <div class="p-6">
            <div class="text-center py-12">
                <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-[#021c47] flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-[#021c47] mb-2">Reports Center</h4>
                <p class="text-gray-500 mb-8">Detailed reports and data exports will be available here.</p>
                
                <div class="max-w-md mx-auto">
                    <p class="text-sm font-semibold text-[#021c47] mb-4">Report types coming soon:</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#021c47]"></div>
                            <span class="text-sm text-gray-600">User activity reports</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#021c47]"></div>
                            <span class="text-sm text-gray-600">Brand performance</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Financial reports</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors">
                            <div class="w-2 h-2 rounded-full bg-[#93db4d]"></div>
                            <span class="text-sm text-gray-600">Commission reports</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-[#93db4d]/10 transition-colors sm:col-span-2">
                            <div class="w-2 h-2 rounded-full bg-[#021c47]"></div>
                            <span class="text-sm text-gray-600">System usage statistics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
