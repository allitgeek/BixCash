@extends('layouts.admin')

@section('title', 'Project Roadmap - BixCash Admin')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-[#021c47] to-[#0a3a7d] rounded-2xl shadow-xl p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold flex items-center gap-3">
                    <svg class="w-8 h-8 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    Project Roadmap
                </h1>
                <p class="mt-2 text-white/70">Track development progress across all BixCash features</p>
            </div>
            <div class="mt-4 md:mt-0 text-right">
                <p class="text-sm text-white/60">Last Updated</p>
                <p class="text-lg font-semibold text-[#93db4d]">{{ now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $totalFeatures = 0;
            $doneFeatures = 0;
            $inProgressFeatures = 0;
            $plannedFeatures = 0;
            
            foreach ($roadmap as $module) {
                foreach ($module['features'] as $feature) {
                    $totalFeatures++;
                    if ($feature['status'] === 'done') $doneFeatures++;
                    elseif ($feature['status'] === 'in_progress') $inProgressFeatures++;
                    elseif (in_array($feature['status'], ['planned', 'not_started'])) $plannedFeatures++;
                }
            }
            $overallProgress = $totalFeatures > 0 ? round(($doneFeatures / $totalFeatures) * 100) : 0;
        @endphp
        
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-[#93db4d]">
            <p class="text-sm text-gray-500 uppercase tracking-wide">Total Features</p>
            <p class="text-3xl font-bold text-gray-800">{{ $totalFeatures }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <p class="text-sm text-gray-500 uppercase tracking-wide">Completed</p>
            <p class="text-3xl font-bold text-green-600">{{ $doneFeatures }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <p class="text-sm text-gray-500 uppercase tracking-wide">In Progress</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $inProgressFeatures }}</p>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 uppercase tracking-wide">Planned</p>
            <p class="text-3xl font-bold text-blue-600">{{ $plannedFeatures }}</p>
        </div>
    </div>

    {{-- Overall Progress Bar --}}
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-800">Overall Project Progress</h3>
            <span class="text-2xl font-bold text-[#93db4d]">{{ $overallProgress }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div class="bg-gradient-to-r from-[#93db4d] to-green-400 h-4 rounded-full transition-all duration-500" style="width: {{ $overallProgress }}%"></div>
        </div>
    </div>

    {{-- Legend --}}
    <div class="bg-white rounded-xl shadow-md p-4">
        <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Status Legend</h3>
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-green-500"></span>
                <span class="text-sm text-gray-600">Done</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-yellow-500"></span>
                <span class="text-sm text-gray-600">In Progress</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-blue-500"></span>
                <span class="text-sm text-gray-600">Planned</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-4 h-4 rounded-full bg-gray-400"></span>
                <span class="text-sm text-gray-600">Not Started</span>
            </div>
        </div>
    </div>

    {{-- Module Cards --}}
    @foreach ($roadmap as $key => $module)
    <div class="bg-white rounded-xl shadow-md overflow-hidden" x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }">
        {{-- Module Header --}}
        <button @click="expanded = !expanded" class="w-full px-6 py-4 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white hover:from-gray-100 hover:to-gray-50 transition-colors border-b border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-[#021c47] to-[#0a3a7d] flex items-center justify-center shadow-lg">
                    @switch($key)
                        @case('admin_panel')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            @break
                        @case('withdrawal_system')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @break
                        @case('customer_portal')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            @break
                        @case('partner_portal')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            @break
                        @case('website_frontend')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            @break
                        @case('future_features')
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            @break
                        @default
                            <svg class="w-6 h-6 text-[#93db4d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                    @endswitch
                </div>
                <div class="text-left">
                    <h2 class="text-xl font-bold text-gray-800">{{ $module['title'] }}</h2>
                    <p class="text-sm text-gray-500">{{ $module['description'] }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                {{-- Progress Circle --}}
                <div class="relative w-14 h-14">
                    <svg class="w-14 h-14 transform -rotate-90">
                        <circle cx="28" cy="28" r="24" stroke="#e5e7eb" stroke-width="4" fill="none" />
                        <circle cx="28" cy="28" r="24" stroke="{{ $module['overall_progress'] >= 75 ? '#22c55e' : ($module['overall_progress'] >= 40 ? '#eab308' : '#3b82f6') }}" stroke-width="4" fill="none" stroke-linecap="round" stroke-dasharray="{{ $module['overall_progress'] * 1.51 }} 151" />
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-sm font-bold text-gray-700">{{ $module['overall_progress'] }}%</span>
                </div>
                {{-- Expand Icon --}}
                <svg class="w-6 h-6 text-gray-400 transform transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </button>

        {{-- Features List --}}
        <div x-show="expanded" x-collapse class="divide-y divide-gray-100">
            @foreach ($module['features'] as $feature)
            <div class="px-6 py-4 flex items-start gap-4 hover:bg-gray-50 transition-colors">
                {{-- Status Indicator --}}
                <div class="flex-shrink-0 mt-1">
                    @switch($feature['status'])
                        @case('done')
                            <span class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            @break
                        @case('in_progress')
                            <span class="w-6 h-6 rounded-full bg-yellow-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            @break
                        @case('planned')
                            <span class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            @break
                        @default
                            <span class="w-6 h-6 rounded-full bg-gray-400 flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </span>
                    @endswitch
                </div>

                {{-- Feature Details --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-gray-800">{{ $feature['name'] }}</h3>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            @switch($feature['status'])
                                @case('done') bg-green-100 text-green-700 @break
                                @case('in_progress') bg-yellow-100 text-yellow-700 @break
                                @case('planned') bg-blue-100 text-blue-700 @break
                                @default bg-gray-100 text-gray-600
                            @endswitch
                        ">
                            {{ ucfirst(str_replace('_', ' ', $feature['status'])) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $feature['description'] }}</p>
                    @if(isset($feature['completed_date']))
                        <p class="text-xs text-green-600 mt-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Completed: {{ \Carbon\Carbon::parse($feature['completed_date'])->format('M d, Y') }}
                        </p>
                    @endif
                    @if(isset($feature['notes']))
                        <p class="text-xs text-yellow-600 mt-1 italic">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Note: {{ $feature['notes'] }}
                        </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    {{-- Footer Info --}}
    <div class="bg-gradient-to-r from-gray-100 to-gray-50 rounded-xl p-6 border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">About This Document</h3>
                <p class="text-sm text-gray-500 mt-1">This roadmap is maintained by MegaMind (AI Assistant) and updated when features are completed. For development requests or questions, contact the development team.</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400">Document Reference</p>
                <p class="text-sm font-mono text-gray-600">BIXCASH-ROADMAP-2026</p>
            </div>
        </div>
    </div>
</div>
@endsection
