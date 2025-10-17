<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Profile - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header with Glassmorphism --}}
    <header class="bg-white/80 backdrop-blur-xl shadow-lg shadow-blue-900/5 border-b border-gray-200/60 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">
                        Partner Profile
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">View and manage your profile</p>
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('partner.dashboard') }}" class="px-3 sm:px-4 py-2 rounded-full bg-white border border-gray-200 text-gray-700 text-xs font-semibold hover:bg-gray-50 hover:border-gray-300 hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-1.5 shadow-sm">
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
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto px-4 py-6 space-y-6">

        {{-- Profile Header Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 overflow-hidden">
            <div class="relative">
                {{-- Background Pattern --}}
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-900 opacity-90"></div>
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

                {{-- Content --}}
                <div class="relative px-6 py-8">
                    <div class="flex items-center gap-6">
                        {{-- Partner Logo (Replace this with actual logo) --}}
                        <div class="w-24 h-24 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-white/30 overflow-hidden">
                            {{-- Default Icon (shown when no logo uploaded) --}}
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{-- Uncomment below to use actual partner logo --}}
                            {{-- @if($partnerProfile->logo)
                            <img src="{{ asset('storage/' . $partnerProfile->logo) }}" alt="{{ $partnerProfile->business_name }} Logo" class="w-full h-full object-cover">
                            @else
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            @endif --}}
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
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center shadow-sm">
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
                        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-amber-600 flex items-center justify-center shadow-sm">
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

        </div>

    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-blue-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-4 max-w-7xl mx-auto">
            {{-- Dashboard --}}
            <a href="{{ route('partner.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Dashboard</span>
            </a>

            {{-- History --}}
            <a href="{{ route('partner.transactions') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span class="text-xs font-medium">History</span>
            </a>

            {{-- Profits --}}
            <a href="{{ route('partner.profits') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-xs font-medium">Profits</span>
            </a>

            {{-- Profile (Active) --}}
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-blue-600 to-blue-900 border-t-2 border-blue-500 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-bold">Profile</span>
            </a>
        </div>
    </nav>

</body>
</html>
