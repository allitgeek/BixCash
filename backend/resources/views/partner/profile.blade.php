<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Profile - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen pb-24">

    {{-- Glassmorphism Header --}}
    <header class="bg-white/90 backdrop-blur-xl shadow-lg shadow-blue-900/10 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <h1 class="text-xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent">
                        Partner Profile
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">View and manage your profile</p>
                </div>
                <a href="{{ route('partner.dashboard') }}" class="px-4 py-2 rounded-full bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-semibold shadow-sm shadow-blue-500/30 hover:shadow-md hover:shadow-blue-500/40 hover:-translate-y-0.5 transition-all duration-200">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-3xl mx-auto px-4 py-6">

        {{-- Profile Card --}}
        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm overflow-hidden hover:border-blue-800/40 hover:shadow-lg hover:shadow-blue-900/10 transition-all duration-200">
            
            {{-- Card Header with Navy Gradient and Avatar --}}
            <div class="px-6 py-8 border-b border-gray-200/60 bg-gradient-to-r from-blue-50/70 via-blue-900/5 to-transparent">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-blue-900 flex items-center justify-center shadow-lg shadow-blue-900/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-blue-900 bg-clip-text text-transparent">
                            {{ $partnerProfile->business_name }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1 capitalize">{{ $partnerProfile->business_type }}</p>
                        @if($partnerProfile->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 border border-green-200/60 shadow-sm shadow-green-900/5 mt-2">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ ucfirst($partnerProfile->status) }}
                        </span>
                        @elseif($partnerProfile->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-50 to-orange-50 text-orange-700 border border-orange-200/60 shadow-sm shadow-orange-900/5 mt-2">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            {{ ucfirst($partnerProfile->status) }}
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-50 to-slate-50 text-gray-700 border border-gray-200/60 shadow-sm mt-2">
                            {{ ucfirst($partnerProfile->status) }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Profile Information --}}
            <div class="p-6">
                <div class="space-y-1">
                    
                    {{-- Business Information Section --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Business Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Business Name</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->business_name }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Business Type</span>
                                <span class="text-sm font-semibold text-gray-900 capitalize">{{ $partnerProfile->business_type }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Contact Person</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->contact_person_name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information Section --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            Contact Information
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Phone</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $partner->phone }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Email</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $partner->email ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Location Information Section --}}
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Location
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">Address</span>
                                <span class="text-sm font-semibold text-gray-900 text-right">{{ $partnerProfile->business_address ?? 'Not provided' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 px-4 rounded-lg hover:bg-blue-50/50 transition-colors duration-150">
                                <span class="text-sm font-medium text-gray-600">City</span>
                                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->business_city ?? 'Not provided' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Logout Button --}}
                <form method="POST" action="{{ route('partner.logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
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
            <a href="{{ route('partner.profile') }}" class="flex flex-col items-center py-3 px-2 text-blue-600 bg-blue-50/50 border-t-2 border-blue-600 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-bold">Profile</span>
            </a>
        </div>
    </nav>

</body>
</html>
