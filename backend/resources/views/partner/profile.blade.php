<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Profile - BixCash</title>
    @vite(['resources/css/app.css'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-neutral-50 min-h-screen pb-32 pt-0 px-0" style="margin: 0;">

    {{-- Clean Profile Header --}}
    <header class="bg-white border-b border-neutral-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3">
            {{-- Top Row: Avatar + User Info + Actions --}}
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    {{-- Partner Brand Logo --}}
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow-md flex-shrink-0 overflow-hidden border-2 border-gray-200">
                        @if($partnerProfile->logo)
                        <img src="{{ asset('storage/' . $partnerProfile->logo) }}" alt="Logo" class="w-full h-full object-cover rounded-xl">
                        @else
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        @endif
                    </div>
                    {{-- User Info --}}
                    <div class="flex-1 min-w-0">
                        <h1 class="text-base sm:text-lg font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent truncate">
                            {{ $partnerProfile->contact_person_name }}
                        </h1>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $partnerProfile->business_name }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Member since {{ $stats['member_since']->format('M Y') }}</p>
                    </div>
                </div>
                {{-- Action Buttons --}}
                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0">
                    <a href="{{ route('partner.dashboard') }}" class="px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                    <form method="POST" action="{{ route('partner.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 sm:px-4 py-2 rounded-full bg-gradient-to-r from-red-600 to-red-700 text-white text-xs font-semibold shadow-md shadow-red-500/30 hover:shadow-lg hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Quick Stats Row: Clean Minimal Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                {{-- Total Orders --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-blue-300 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded bg-blue-50 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <p class="text-xs text-neutral-500 font-medium">Orders</p>
                    </div>
                    <p class="text-xl font-semibold text-neutral-900">{{ $stats['total_orders'] }}</p>
                </div>

                {{-- Total Profit --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-emerald-300 hover:shadow-sm transition-all duration-200">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded bg-emerald-50 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs text-neutral-500 font-medium">Profit</p>
                    </div>
                    <p class="text-base font-semibold text-neutral-900">Rs {{ number_format($stats['total_earned'], 0) }}</p>
                </div>

                {{-- Account Status --}}
                <div class="bg-white border border-neutral-200 rounded-lg p-3 hover:border-neutral-300 hover:shadow-sm transition-all duration-200 col-span-2 sm:col-span-1">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 rounded bg-neutral-100 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-xs text-neutral-500 font-medium">Status</p>
                    </div>
                    <p class="text-xl font-semibold text-neutral-900 capitalize">{{ $partnerProfile->status }}</p>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800 mb-1">There were errors with your submission:</p>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden">
            <div class="relative">
                {{-- Background Pattern --}}
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-900 opacity-90"></div>
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

                {{-- Content --}}
                <div class="relative px-6 py-8">
                    <div class="flex items-center gap-6">
                        {{-- Partner Logo with Click-to-Upload (64x64px to match all headers) --}}
                        <div class="relative group cursor-pointer" onclick="document.getElementById('logoInput').click()">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-white flex items-center justify-center shadow-xl border-4 border-white overflow-hidden" id="logoUploadContainer">
                                @if($partnerProfile->logo)
                                <img src="{{ asset('storage/' . $partnerProfile->logo) }}" alt="{{ $partnerProfile->business_name }} Logo" class="w-full h-full object-cover" id="logoPreview">
                                @else
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                @endif
                            </div>

                            @if($partnerProfile->logo)
                            {{-- Remove button (X) on top-right corner --}}
                            <form method="POST" action="{{ route('partner.profile.remove-logo') }}" class="absolute -top-1 -right-1 z-10" onsubmit="return confirm('Remove logo? This action cannot be undone.');" onclick="event.stopPropagation();">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-6 h-6 rounded-full bg-red-500 hover:bg-red-600 text-white shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                            @endif

                            {{-- Upload overlay on hover --}}
                            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200">
                                <div class="text-center">
                                    <svg class="w-6 h-6 text-white mx-auto mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-[10px] text-white font-semibold">{{ $partnerProfile->logo ? 'Change' : 'Upload' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1">
                            <h2 class="text-3xl font-bold text-white mb-2">
                                {{ $partnerProfile->business_name }}
                            </h2>
                            <p class="text-blue-100 text-sm capitalize mb-3">{{ $partnerProfile->business_type }}</p>

                            {{-- Status Badge --}}
                            @if($partnerProfile->status === 'active')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white text-green-700 shadow-lg">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                {{ ucfirst($partnerProfile->status) }}
                            </span>
                            @elseif($partnerProfile->status === 'pending')
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white text-orange-700 shadow-lg">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                {{ ucfirst($partnerProfile->status) }}
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-white text-gray-700 shadow-lg">
                                {{ ucfirst($partnerProfile->status) }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Business Information Card --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden hover:border-blue-800/40 hover:shadow-xl hover:shadow-blue-900/10 transition-all duration-300">
                <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-blue-900 bg-clip-text text-transparent">Business Information</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500 mb-0.5">Business Name</p>
                            <p class="text-sm font-bold text-gray-900">{{ $partnerProfile->business_name }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500 mb-0.5">Business Type</p>
                            <p class="text-sm font-bold text-gray-900 capitalize">{{ $partnerProfile->business_type }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500 mb-0.5">Contact Person</p>
                            <p class="text-sm font-bold text-gray-900">{{ $partnerProfile->contact_person_name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information Card --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden hover:border-green-800/40 hover:shadow-xl hover:shadow-green-900/10 transition-all duration-300">
                <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Contact Information</h3>
                    </div>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500 mb-0.5">Phone Number</p>
                            <p class="text-sm font-bold text-gray-900">{{ $partner->phone }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-medium text-gray-500 mb-0.5">Email Address</p>
                            <p class="text-sm font-bold text-gray-900">{{ $partner->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Location Information Card --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden hover:border-orange-800/40 hover:shadow-xl hover:shadow-orange-900/10 transition-all duration-300 md:col-span-2">
                <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-orange-50/70 via-orange-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-orange-700 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-orange-900 bg-clip-text text-transparent">Location</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 mb-0.5">Business Address</p>
                                <p class="text-sm font-bold text-gray-900">{{ $partnerProfile->business_address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-medium text-gray-500 mb-0.5">City</p>
                                <p class="text-sm font-bold text-gray-900">{{ $partnerProfile->business_city ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bank Details Card --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden hover:border-orange-800/40 hover:shadow-xl hover:shadow-orange-900/10 transition-all duration-300 md:col-span-2">
                <div class="px-5 py-4 border-b border-gray-200/60 bg-gradient-to-r from-orange-50/70 via-orange-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-orange-700 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-orange-900 bg-clip-text text-transparent">Bank Details</h3>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-sm text-gray-500 mb-4">Required for withdrawal requests</p>

                    @if($partnerProfile && $partnerProfile->bank_name)
                        {{-- Current Bank Details --}}
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-semibold text-gray-800">Current Bank Account</h4>
                                <button type="button" onclick="toggleBankEdit()" class="px-3 py-1.5 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition-colors">Change</button>
                            </div>
                            <div class="grid gap-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Bank:</span>
                                    <span class="font-semibold">{{ $partnerProfile->bank_name }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Account Title:</span>
                                    <span class="font-semibold">{{ $partnerProfile->account_title }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Account Number:</span>
                                    <span class="font-semibold">{{ maskAccountNumber($partnerProfile->account_number) }}</span>
                                </div>
                                @if($partnerProfile->iban)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">IBAN:</span>
                                    <span class="font-semibold">{{ maskIban($partnerProfile->iban) }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Edit Bank Form (Hidden) --}}
                        <div id="bankEditForm" class="hidden">
                    @else
                        {{-- No Bank Details Yet --}}
                        <div id="bankEditForm">
                    @endif

                            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded-lg mb-4">
                                <p class="text-sm text-orange-900 font-semibold mb-1">⚠️ Security Notice:</p>
                                <p class="text-sm text-orange-800">Changing bank details requires verification and will lock withdrawals for 24 hours.</p>
                            </div>

                            <form method="POST" action="{{ route('partner.bank-details.request-otp') }}" id="bankDetailsForm">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Bank Name *</label>
                                        <input type="text" name="bank_name" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="e.g., HBL, UBL" value="{{ old('bank_name', $partnerProfile->bank_name ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Account Title *</label>
                                        <input type="text" name="account_title" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="Account holder name" value="{{ old('account_title', $partnerProfile->account_title ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">Account Number *</label>
                                        <input type="text" name="account_number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="Your account number" value="{{ old('account_number', $partnerProfile->account_number ?? '') }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-2">IBAN (Optional)</label>
                                        <input type="text" name="iban" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" placeholder="PK36XXXX..." value="{{ old('iban', $partnerProfile->iban ?? '') }}">
                                    </div>
                                </div>

                                {{-- Authentication Method Selector --}}
                                <div class="mb-6 p-4 bg-gray-50 rounded-2xl border-2 border-gray-200">
                                    <label class="block text-sm font-semibold text-gray-800 mb-3">Choose Verification Method:</label>
                                    <div class="space-y-3">
                                        {{-- OTP Option --}}
                                        <label class="flex items-start cursor-pointer group">
                                            <input type="radio" name="auth_method" value="otp"
                                                   @if(!Auth::user()->pin_hash) checked @endif
                                                   onchange="switchAuthMethod('otp')"
                                                   class="mt-1 w-5 h-5 text-orange-600 focus:ring-orange-500 focus:ring-2">
                                            <div class="ml-3">
                                                <span class="block font-semibold text-gray-800 group-hover:text-orange-600 transition-colors">OTP via SMS</span>
                                                <p class="text-xs text-gray-500 mt-0.5">Receive a 6-digit code on your registered phone number</p>
                                            </div>
                                        </label>

                                        {{-- TPIN Option --}}
                                        <label class="flex items-start cursor-pointer group">
                                            <input type="radio" name="auth_method" value="tpin"
                                                   @if(Auth::user()->pin_hash) checked @endif
                                                   @if(!Auth::user()->pin_hash) disabled @endif
                                                   onchange="switchAuthMethod('tpin')"
                                                   class="mt-1 w-5 h-5 text-orange-600 focus:ring-orange-500 focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                            <div class="ml-3">
                                                <span class="block font-semibold text-gray-800 group-hover:text-orange-600 transition-colors @if(!Auth::user()->pin_hash) opacity-50 @endif">4-Digit TPIN</span>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    @if(Auth::user()->pin_hash)
                                                        Use your Transaction PIN for faster verification
                                                    @else
                                                        TPIN not set up. <a href="{{ route('partner.profile') }}" class="underline text-orange-600 hover:text-orange-700">Set up TPIN first</a>
                                                    @endif
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit" id="submitBankDetailsBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-orange-800 hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-orange-500/30">
                                        <span id="submitBtnText">@if(Auth::user()->pin_hash) Proceed with TPIN @else Request OTP @endif</span>
                                    </button>
                                    @if($partnerProfile && $partnerProfile->bank_name)
                                    <button type="button" onclick="toggleBankEdit()" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-xl hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                </div>
            </div>

        </div>

    </div>

    {{-- TPIN Verification Modal --}}
    <div id="tpinModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] flex items-center justify-center hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 relative">
            {{-- Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-orange-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Enter Your TPIN</h3>
                <p class="text-gray-600">Enter your 4-digit Transaction PIN to update bank details</p>
            </div>

            {{-- TPIN Input Boxes --}}
            <div class="flex justify-center gap-3 mb-2">
                <input type="password" id="tpin1" maxlength="1" inputmode="numeric"
                       class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 outline-none transition-all"
                       oninput="moveTpinFocus(this, 'tpin2')"
                       onkeydown="handleTpinBackspace(event, this, null)">
                <input type="password" id="tpin2" maxlength="1" inputmode="numeric"
                       class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 outline-none transition-all"
                       oninput="moveTpinFocus(this, 'tpin3')"
                       onkeydown="handleTpinBackspace(event, this, 'tpin1')">
                <input type="password" id="tpin3" maxlength="1" inputmode="numeric"
                       class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 outline-none transition-all"
                       oninput="moveTpinFocus(this, 'tpin4')"
                       onkeydown="handleTpinBackspace(event, this, 'tpin2')">
                <input type="password" id="tpin4" maxlength="1" inputmode="numeric"
                       class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 outline-none transition-all"
                       oninput="moveTpinFocus(this, null)"
                       onkeydown="handleTpinBackspace(event, this, 'tpin3')">
            </div>

            {{-- Error Message --}}
            <div id="tpinError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-2xl">
                <p class="text-sm text-red-800 text-center font-medium">
                    <span id="tpinErrorText">Invalid PIN. Please try again.</span>
                </p>
            </div>

            {{-- Lockout Message --}}
            <div id="tpinLockout" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-2xl">
                <p class="text-sm text-yellow-900 text-center font-medium">
                    <span id="tpinLockoutText">Too many failed attempts. Account locked for 15 minutes.</span>
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeTpinModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 font-semibold rounded-2xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="verifyTpin()" id="verifyTpinBtn" disabled class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-2xl hover:from-orange-700 hover:to-orange-800 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Verify PIN
                </button>
            </div>

            {{-- Forgot TPIN Link --}}
            <p class="text-center mt-4">
                <a href="#" class="text-sm text-orange-600 hover:text-orange-700 underline">Forgot TPIN?</a>
            </p>
        </div>
    </div>

    {{-- OTP Verification Modal --}}
    <div id="otpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] flex items-center justify-center {{ session('show_otp_modal') ? '' : 'hidden' }}">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 relative">
            {{-- Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-orange-700 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Phone</h3>
                <p class="text-gray-600">We've sent a code to <span class="font-semibold">{{ Auth::user()->phone }}</span></p>
            </div>

            {{-- OTP Input --}}
            <div class="mb-4">
                <input type="text" id="otpCode" maxlength="6" inputmode="numeric" autocomplete="one-time-code"
                       class="w-full px-4 py-3 text-center text-2xl tracking-widest font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 outline-none transition-all"
                       placeholder="------"
                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) { document.getElementById('verifyBtn').disabled = false; }"
                       onkeydown="if(event.key === 'Enter' && this.value.length === 6) { verifyOtp(); }">
            </div>

            {{-- Timer & Resend --}}
            <div class="flex items-center justify-between mb-6 text-sm">
                <span id="otpTimer" class="text-gray-600">
                    Code expires in <span id="timerValue" class="font-semibold text-orange-600">2:00</span>
                </span>
                <button type="button" id="resendBtn" onclick="resendOtp()" disabled class="text-orange-600 font-semibold hover:text-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    Resend Code
                </button>
            </div>

            {{-- Error Message --}}
            <div id="otpError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-2xl">
                <p class="text-sm text-red-800 text-center font-medium">
                    <span id="otpErrorText">Invalid code. Please try again.</span>
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeOtpModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 font-semibold rounded-2xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="verifyOtp()" id="verifyBtn" disabled class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-2xl hover:from-orange-700 hover:to-orange-800 transition-all shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    Verify Code
                </button>
            </div>

            {{-- reCAPTCHA Container (invisible) --}}
            <div id="recaptcha-container"></div>
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto place-items-center">
            {{-- Dashboard --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Transactions --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">Transactions</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('partner.wallet') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Commissions --}}
            <a href="{{ route('partner.commissions') }}" class="flex flex-col items-center justify-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Commissions</span>
            </a>

            {{-- Profile (Active) --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center justify-center py-3 px-2 w-full text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-bold">Profile</span>
            </a>
        </div>
    </nav>

    {{-- Hidden Logo Upload Form --}}
    <form id="logoForm" action="{{ route('partner.profile.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" id="logoInput" name="logo" accept="image/jpeg,image/jpg,image/png" onchange="handleLogoUpload(event)">
        <input type="hidden" name="contact_person_name" value="{{ $partnerProfile->contact_person_name ?? '' }}">
        <input type="hidden" name="email" value="{{ $partner->email ?? '' }}">
        <input type="hidden" name="business_address" value="{{ $partnerProfile->business_address ?? '' }}">
        <input type="hidden" name="business_city" value="{{ $partnerProfile->business_city ?? '' }}">
        <input type="hidden" name="logo_only" value="1">
        <button type="submit" id="logoSubmitBtn" style="display: none;">Submit</button>
    </form>

    {{-- JavaScript for Logo Upload --}}
    <script>
        function handleLogoUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                event.target.value = '';
                return;
            }

            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG and PNG images are allowed');
                event.target.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    // Create preview if not exists
                    const logoContainer = document.getElementById('logoUploadContainer');
                    if (logoContainer) {
                        logoContainer.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-full h-full object-cover" id="logoPreview">`;
                    }
                }
            };
            reader.readAsDataURL(file);

            // Auto-submit form after file selection using submit button (more reliable for file uploads)
            if (confirm('Upload this logo?')) {
                // Use hidden submit button instead of form.submit() for better file upload compatibility
                document.getElementById('logoSubmitBtn').click();
            } else {
                event.target.value = '';
                location.reload(); // Reset preview
            }
        }

        // Toggle bank edit form
        function toggleBankEdit() {
            const form = document.getElementById('bankEditForm');
            form.classList.toggle('hidden');
        }

        // ============================================
        // BANK DETAILS VERIFICATION SYSTEM
        // ============================================

        // Configuration Variables
        window.SHOW_OTP_MODAL = @json(session('show_otp_modal') ?? false);
        window.USER_PHONE = "{{ Auth::user()->phone }}";
        window.REQUEST_OTP_URL = "{{ route('partner.bank-details.request-otp') }}";
        window.VERIFY_OTP_URL = "{{ route('partner.bank-details.verify-otp') }}";
        window.VERIFY_TPIN_URL = "{{ route('partner.bank-details.verify-tpin') }}";
        window.CANCEL_OTP_URL = "{{ route('partner.bank-details.cancel-otp') }}";
        window.DEFAULT_AUTH_METHOD = @if(Auth::user()->pin_hash) 'tpin' @else 'otp' @endif;

        // State Variables
        let selectedAuthMethod = window.DEFAULT_AUTH_METHOD;
        let confirmationResult = null;
        let otpTimerInterval = null;

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // ============================================
        // AUTHENTICATION METHOD SWITCHING
        // ============================================

        function switchAuthMethod(method) {
            selectedAuthMethod = method;
            const submitBtnText = document.getElementById('submitBtnText');

            if (method === 'otp') {
                submitBtnText.textContent = 'Request OTP';
            } else if (method === 'tpin') {
                submitBtnText.textContent = 'Proceed with TPIN';
            }
        }

        // ============================================
        // TPIN MODAL FUNCTIONS
        // ============================================

        function showTpinModal() {
            document.getElementById('tpinModal').classList.remove('hidden');
            clearTpinInputs();
            hideTpinError();
            hideTpinLockout();
            document.getElementById('tpin1').focus();
        }

        function closeTpinModal() {
            document.getElementById('tpinModal').classList.add('hidden');
            clearTpinInputs();
            hideTpinError();
            hideTpinLockout();
        }

        function clearTpinInputs() {
            for (let i = 1; i <= 4; i++) {
                document.getElementById('tpin' + i).value = '';
            }
            document.getElementById('verifyTpinBtn').disabled = true;
        }

        function getTpinValue() {
            let pin = '';
            for (let i = 1; i <= 4; i++) {
                pin += document.getElementById('tpin' + i).value;
            }
            return pin;
        }

        function moveTpinFocus(currentInput, nextInputId) {
            // Only allow numeric input
            currentInput.value = currentInput.value.replace(/[^0-9]/g, '');

            if (currentInput.value.length === 1 && nextInputId) {
                document.getElementById(nextInputId).focus();
            } else if (!nextInputId && getTpinValue().length === 4) {
                // All 4 digits entered, enable verify button
                document.getElementById('verifyTpinBtn').disabled = false;
            }
        }

        function handleTpinBackspace(event, currentInput, prevInputId) {
            if (event.key === 'Backspace' && currentInput.value === '' && prevInputId) {
                event.preventDefault();
                const prevInput = document.getElementById(prevInputId);
                prevInput.focus();
                prevInput.value = '';
                document.getElementById('verifyTpinBtn').disabled = true;
            } else if (event.key === 'Enter' && getTpinValue().length === 4) {
                event.preventDefault();
                verifyTpin();
            }
        }

        function showTpinError(message) {
            const errorDiv = document.getElementById('tpinError');
            const errorText = document.getElementById('tpinErrorText');
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            hideTpinLockout();
        }

        function hideTpinError() {
            document.getElementById('tpinError').classList.add('hidden');
        }

        function showTpinLockout(message, minutesRemaining) {
            const lockoutDiv = document.getElementById('tpinLockout');
            const lockoutText = document.getElementById('tpinLockoutText');

            if (minutesRemaining) {
                lockoutText.textContent = `Too many failed attempts. Account locked for ${minutesRemaining} minutes.`;
            } else {
                lockoutText.textContent = message;
            }

            lockoutDiv.classList.remove('hidden');
            hideTpinError();
            document.getElementById('verifyTpinBtn').disabled = true;
        }

        function hideTpinLockout() {
            document.getElementById('tpinLockout').classList.add('hidden');
        }

        async function verifyTpin() {
            const pin = getTpinValue();

            if (pin.length !== 4) {
                showTpinError('Please enter all 4 digits');
                return;
            }

            const verifyBtn = document.getElementById('verifyTpinBtn');
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Verifying...';

            try {
                const response = await fetch(window.VERIFY_TPIN_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ pin: pin })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ ' + data.message);
                    window.location.reload();
                } else if (data.locked) {
                    showTpinLockout(data.message, data.minutes_remaining);
                    verifyBtn.textContent = 'Verify PIN';
                } else {
                    showTpinError(data.message);
                    clearTpinInputs();
                    verifyBtn.textContent = 'Verify PIN';
                    document.getElementById('tpin1').focus();
                }
            } catch (error) {
                console.error('TPIN verification error:', error);
                showTpinError('Network error. Please try again.');
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify PIN';
            }
        }

        // ============================================
        // FORM SUBMISSION HANDLING
        // ============================================

        const bankDetailsForm = document.getElementById('bankDetailsForm');
        if (bankDetailsForm) {
            bankDetailsForm.addEventListener('submit', async function(e) {
                if (selectedAuthMethod === 'tpin') {
                    e.preventDefault();

                    // Validate form
                    if (!bankDetailsForm.checkValidity()) {
                        bankDetailsForm.reportValidity();
                        return;
                    }

                    // Submit to backend via AJAX to store in session
                    const formData = new FormData(bankDetailsForm);

                    try {
                        const response = await fetch(window.REQUEST_OTP_URL, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            // Data stored in session, show TPIN modal
                            showTpinModal();
                        } else {
                            alert('Error: ' + (result.message || 'Failed to process request'));
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                        alert('Network error. Please try again. Error: ' + error.message);
                    }
                }
                // If OTP selected, allow normal form submission (will redirect and show OTP modal)
            });
        }
    </script>

    {{-- Firebase SDK (for OTP verification) --}}
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    <script>
        // Firebase Config
        const firebaseConfig = {
            apiKey: "{{ config('firebase.web.api_key') }}",
            authDomain: "{{ config('firebase.web.auth_domain') }}",
            projectId: "{{ config('firebase.web.project_id') }}",
            storageBucket: "{{ config('firebase.web.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.web.messaging_sender_id') }}",
            appId: "{{ config('firebase.web.app_id') }}"
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();

        // ============================================
        // OTP MODAL FUNCTIONS
        // ============================================

        // Auto-show OTP modal if session flag is set
        if (window.SHOW_OTP_MODAL) {
            document.addEventListener('DOMContentLoaded', function() {
                showOtpModal();
            });
        }

        function showOtpModal() {
            const modal = document.getElementById('otpModal');
            modal.classList.remove('hidden');
            document.getElementById('otpCode').value = '';
            document.getElementById('verifyBtn').disabled = true;
            hideOtpError();

            // Initialize Firebase and send OTP
            initializeRecaptchaAndSendOtp(window.USER_PHONE);
        }

        function closeOtpModal() {
            if (window.recaptchaVerifier) {
                window.recaptchaVerifier.clear();
                window.recaptchaVerifier = null;
            }

            if (otpTimerInterval) {
                clearInterval(otpTimerInterval);
                otpTimerInterval = null;
            }

            // Redirect to cancel URL to clear session
            window.location.href = window.CANCEL_OTP_URL;
        }

        function initializeRecaptchaAndSendOtp(phoneNumber) {
            // Setup reCAPTCHA verifier (invisible)
            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function(response) {
                    console.log('reCAPTCHA solved');
                },
                'error-callback': function(error) {
                    console.error('reCAPTCHA error:', error);
                    showOtpError('reCAPTCHA verification failed. Please try again.');
                }
            });

            // Send OTP
            auth.signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                .then(function(result) {
                    confirmationResult = result;
                    console.log('OTP sent successfully');
                    startOtpTimer();
                })
                .catch(function(error) {
                    console.error('Error sending OTP:', error);
                    let errorMessage = 'Failed to send OTP. ';

                    if (error.code === 'auth/invalid-phone-number') {
                        errorMessage += 'Invalid phone number.';
                    } else if (error.code === 'auth/too-many-requests') {
                        errorMessage += 'Too many requests. Please try again later.';
                    } else {
                        errorMessage += error.message;
                    }

                    showOtpError(errorMessage);

                    if (window.recaptchaVerifier) {
                        window.recaptchaVerifier.clear();
                    }
                });
        }

        function startOtpTimer() {
            let timeLeft = 120; // 2 minutes
            const timerValue = document.getElementById('timerValue');
            const resendBtn = document.getElementById('resendBtn');

            otpTimerInterval = setInterval(function() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerValue.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(otpTimerInterval);
                    timerValue.textContent = '0:00';
                    resendBtn.disabled = false;
                }
            }, 1000);
        }

        async function verifyOtp() {
            const otpCode = document.getElementById('otpCode').value.trim();

            if (otpCode.length !== 6) {
                showOtpError('Please enter the 6-digit code');
                return;
            }

            const verifyBtn = document.getElementById('verifyBtn');
            verifyBtn.disabled = true;
            verifyBtn.textContent = 'Verifying...';

            try {
                // Verify OTP with Firebase
                const result = await confirmationResult.confirm(otpCode);
                console.log('Firebase verification successful:', result);

                // Get Firebase ID token
                const idToken = await result.user.getIdToken();

                // Send to backend for verification and bank details update
                const response = await fetch(window.VERIFY_OTP_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        firebase_token: idToken
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('✓ ' + data.message);
                    window.location.reload();
                } else {
                    showOtpError(data.message || 'Verification failed');
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = 'Verify Code';
                }

            } catch (error) {
                console.error('OTP verification error:', error);

                let errorMessage = 'Invalid code. Please try again.';

                if (error.code === 'auth/invalid-verification-code') {
                    errorMessage = 'Invalid verification code';
                } else if (error.code === 'auth/code-expired') {
                    errorMessage = 'Code expired. Please request a new one.';
                } else if (error.message) {
                    errorMessage = error.message;
                }

                showOtpError(errorMessage);
                verifyBtn.disabled = false;
                verifyBtn.textContent = 'Verify Code';
            }
        }

        function resendOtp() {
            const resendBtn = document.getElementById('resendBtn');
            resendBtn.disabled = true;

            // Clear previous reCAPTCHA
            if (window.recaptchaVerifier) {
                window.recaptchaVerifier.clear();
                window.recaptchaVerifier = null;
            }

            // Reset timer
            if (otpTimerInterval) {
                clearInterval(otpTimerInterval);
                otpTimerInterval = null;
            }

            // Clear OTP input
            document.getElementById('otpCode').value = '';
            document.getElementById('verifyBtn').disabled = true;
            hideOtpError();

            // Send new OTP
            initializeRecaptchaAndSendOtp(window.USER_PHONE);
        }

        function showOtpError(message) {
            const errorDiv = document.getElementById('otpError');
            const errorText = document.getElementById('otpErrorText');
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
        }

        function hideOtpError() {
            document.getElementById('otpError').classList.add('hidden');
        }
    </script>

</body>
</html>
