<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1a5928">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - BixCash</title>
    @vite(['resources/css/app.css', 'resources/js/profile.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body class="bg-gray-50 min-h-screen pb-24" style="margin: 0; padding: 0;">

    {{-- Header - Dashboard Style --}}
    <header class="text-white px-4 py-4 shadow-lg" style="background: linear-gradient(to bottom right, rgba(0,0,0,0.15), rgba(0,0,0,0.25)), #76d37a;">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                {{-- BixCash Logo - Links to Main Website --}}
                <a href="https://bixcash.com" class="flex-shrink-0 hover:opacity-80 transition-opacity" target="_blank" rel="noopener noreferrer">
                    <img src="/images/logos/logos-01.png" alt="BixCash Logo" class="h-10 w-auto brightness-0 invert">
                </a>
                <h1 class="text-base sm:text-lg font-bold whitespace-nowrap">My Profile</h1>
            </div>
            {{-- Back to Dashboard Button --}}
            <a href="{{ route('customer.dashboard') }}" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:shadow-xl hover:scale-110 transition-all duration-200 shadow-md flex-shrink-0">
                <svg class="w-5 h-5 text-[#76d37a]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </header>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto px-4 pt-6 pb-20 space-y-5">

        {{-- Profile Header Card - Compact Design --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden">
            <div class="relative">
                {{-- Background Pattern --}}
                <div class="absolute inset-0 bg-gradient-to-r from-[#76d37a] to-[#93db4d] opacity-90"></div>
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

                {{-- Content - Reduced Padding --}}
                <div class="relative px-5 py-5">
                    <div class="flex items-center gap-4">
                        {{-- Avatar - Smaller --}}
                        <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-lg border-3 border-white/30 overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-2xl font-bold text-white">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        </div>

                        {{-- Info - Smaller Text --}}
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-white mb-2">
                                {{ $user->name }}
                            </h2>

                            {{-- Refined Membership Badge --}}
                            <div class="inline-flex items-center gap-3 bg-white/15 backdrop-blur-lg rounded-xl px-4 py-2.5 border border-white/25 shadow-lg hover:bg-white/20 transition-all duration-200">
                                {{-- Premium Member Icon --}}
                                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-400/30 to-amber-500/30 backdrop-blur-sm border border-yellow-400/40">
                                    <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>

                                {{-- Membership Info --}}
                                <div class="flex flex-col">
                                    <span class="text-white/70 text-[9px] uppercase tracking-[0.1em] font-semibold leading-none mb-1">Member ID</span>
                                    <span class="text-white font-bold text-lg tracking-wide leading-none" id="membershipNumber" style="font-family: 'Courier New', Consolas, monospace;">
                                        {{ preg_replace('/^\+?92/', '', $user->phone) }}
                                    </span>
                                </div>

                                {{-- Copy Button --}}
                                <button
                                    type="button"
                                    onclick="copyMembershipNumber()"
                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/15 hover:bg-white/30 border border-white/20 hover:border-white/40 transition-all duration-200 group"
                                    title="Copy to clipboard">
                                    <svg class="w-4 h-4 text-white/80 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Error Messages --}}
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl">
            <p class="text-sm font-semibold text-red-900 mb-2">Please fix the following errors:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-800">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- Personal Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden hover:border-green-800/40 hover:shadow-lg transition-all duration-300">
                <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#76d37a] to-[#93db4d] flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Personal Information</h3>
                    </div>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Full Name *</label>
                                <input type="text" name="name" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" required value="{{ old('name', $user->name) }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Phone Number</label>
                                <input type="text" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm bg-gray-50" value="{{ $user->phone }}" disabled>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Email</label>
                                <input type="email" name="email" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" value="{{ old('email', $user->email) }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-[#76d37a] focus:ring-4 focus:ring-green-500/10 transition-all" value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}">
                            </div>
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-2xl hover:from-[#5cb85c] hover:to-[#76d37a] hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Location Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden hover:border-green-800/40 hover:shadow-lg transition-all duration-300">
                <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-green-50/70 via-green-900/5 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-green-600 to-emerald-600 flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-green-900 bg-clip-text text-transparent">Location</h3>
                    </div>
                </div>
                <div class="p-4">
                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">Address</label>
                                <input type="text" name="address" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all" value="{{ old('address', $profile->address ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1.5">City</label>
                                <input type="text" name="city" class="w-full px-4 py-2 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-green-500 focus:ring-4 focus:ring-green-500/10 transition-all" value="{{ old('city', $profile->city ?? '') }}">
                            </div>
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white font-semibold rounded-2xl hover:from-green-700 hover:to-green-800 hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-green-500/30 hover:shadow-md hover:shadow-green-500/40">
                                Update Location
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {{-- Bank Details Card --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-md overflow-hidden hover:border-green-800/40 hover:shadow-lg transition-all duration-300">
            <div class="px-4 py-3 border-b border-gray-200/60 bg-gradient-to-r from-orange-50/70 via-orange-900/5 to-transparent">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-orange-600 to-amber-600 flex items-center justify-center shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold bg-gradient-to-r from-gray-800 to-orange-900 bg-clip-text text-transparent">Bank Details</h3>
                </div>
            </div>
            <div class="p-4">
                <p class="text-sm text-gray-500 mb-4">Required for withdrawal requests</p>

                @if($profile && $profile->bank_name)
                    {{-- Current Bank Details --}}
                    <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-semibold text-gray-800">Current Bank Account</h4>
                            <button type="button" onclick="toggleBankEdit()" class="px-3 py-1.5 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition-colors">Change</button>
                        </div>
                        <div class="grid gap-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Bank:</span>
                                <span class="font-semibold">{{ $profile->bank_name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Account Title:</span>
                                <span class="font-semibold">{{ $profile->account_title }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Account Number:</span>
                                <span class="font-semibold">{{ maskAccountNumber($profile->account_number) }}</span>
                            </div>
                            @if($profile->iban)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">IBAN:</span>
                                <span class="font-semibold">{{ maskIban($profile->iban) }}</span>
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
                            <p class="text-sm text-orange-800">Changing bank details requires OTP verification and will lock withdrawals for 24 hours.</p>
                        </div>

                        <form method="POST" action="{{ route('customer.bank-details.request-otp') }}">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Bank Name *</label>
                                    <input type="text" name="bank_name" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="e.g., HBL, UBL">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Account Title *</label>
                                    <input type="text" name="account_title" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="Account holder name">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Account Number *</label>
                                    <input type="text" name="account_number" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" required placeholder="Your account number">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">IBAN (Optional)</label>
                                    <input type="text" name="iban" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/10 transition-all" placeholder="PK36XXXX...">
                                </div>
                            </div>

                            {{-- Authentication Method Selector --}}
                            <div class="mb-6 p-4 bg-gray-50 rounded-2xl border-2 border-gray-200">
                                <label class="block text-sm font-semibold text-gray-800 mb-3">Choose Verification Method:</label>
                                <div class="space-y-3">
                                    <label class="flex items-start cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="auth_method"
                                            value="otp"
                                            class="mt-1 w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500"
                                            onchange="switchAuthMethod('otp')"
                                        >
                                        <div class="ml-3">
                                            <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">OTP via SMS</span>
                                            <p class="text-xs text-gray-500 mt-0.5">Receive a 6-digit code on your registered phone number</p>
                                        </div>
                                    </label>
                                    <label class="flex items-start cursor-pointer group">
                                        <input
                                            type="radio"
                                            name="auth_method"
                                            value="tpin"
                                            @if(Auth::user()->pin_hash) checked @endif
                                            class="mt-1 w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500"
                                            onchange="switchAuthMethod('tpin')"
                                            @if(!Auth::user()->pin_hash) disabled @endif
                                        >
                                        <div class="ml-3">
                                            <span class="font-semibold text-gray-900 group-hover:text-orange-600 transition-colors">4-Digit TPIN</span>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                @if(Auth::user()->pin_hash)
                                                    Use your Transaction PIN for faster verification
                                                @else
                                                    <span class="text-red-600">TPIN not set up. <a href="{{ url('/') }}" class="underline hover:text-red-700">Set up TPIN</a></span>
                                                @endif
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit" id="submitBankDetailsBtn" class="flex-1 px-4 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-2xl hover:from-orange-700 hover:to-orange-800 hover:-translate-y-0.5 transition-all duration-200 shadow-sm shadow-orange-500/30">
                                    <span id="submitBtnText">Proceed with TPIN</span>
                                </button>
                                @if($profile && $profile->bank_name)
                                <button type="button" onclick="toggleBankEdit()" class="px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-2xl hover:bg-gray-300 transition-colors">
                                    Cancel
                                </button>
                                @endif
                            </div>
                        </form>
                    </div>
            </div>
        </div>

    </div>

    {{-- OTP Verification Modal --}}
    <div id="otpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] flex items-center justify-center {{ session('show_otp_modal') ? '' : 'hidden' }}" onclick="if(event.target === this) closeOtpModal()">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all" onclick="event.stopPropagation()">
            {{-- Modal Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-[#76d37a] to-[#93db4d] rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Verify Your Phone</h3>
                <p class="text-gray-600">We've sent a verification code to<br><span class="font-semibold text-[#76d37a]">{{ $profile->phone ?? '+92XXXXXXXXXX' }}</span></p>
            </div>

            {{-- Loading State --}}
            <div id="otpLoading" class="text-center py-8 hidden">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-200 border-t-[#76d37a]"></div>
                <p class="mt-4 text-gray-600">Sending verification code...</p>
            </div>

            {{-- OTP Input Form --}}
            <div id="otpInputForm">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Enter 6-Digit Code</label>
                    <input
                        type="text"
                        id="otpCode"
                        maxlength="6"
                        class="w-full px-4 py-3 text-center text-2xl tracking-widest font-bold border-2 border-gray-200 rounded-2xl focus:border-[#76d37a] focus:ring-2 focus:ring-[#76d37a]/20 transition-all duration-200"
                        placeholder="------"
                        autocomplete="off"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                    >
                </div>

                {{-- Timer & Resend --}}
                <div class="flex items-center justify-between mb-6">
                    <span id="otpTimer" class="text-sm text-gray-600">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        Code expires in <span id="timerValue" class="font-semibold">2:00</span>
                    </span>
                    <button
                        type="button"
                        id="resendBtn"
                        onclick="resendOtp()"
                        class="text-sm font-semibold text-[#76d37a] hover:text-[#93db4d] transition-colors disabled:text-gray-400 disabled:cursor-not-allowed"
                        disabled
                    >
                        Resend Code
                    </button>
                </div>

                {{-- Error Message --}}
                <div id="otpError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span id="otpErrorText">Invalid code. Please try again.</span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeOtpModal()"
                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-2xl hover:bg-gray-50 transition-all duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        onclick="verifyOtp()"
                        id="verifyBtn"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-[#76d37a] to-[#93db4d] text-white font-semibold rounded-2xl hover:shadow-lg hover:shadow-green-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Verify
                    </button>
                </div>
            </div>

            {{-- reCAPTCHA Container (required by Firebase) --}}
            <div id="recaptcha-container" class="mt-4"></div>
        </div>
    </div>

    {{-- TPIN Verification Modal --}}
    <div id="tpinModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[1000] flex items-center justify-center hidden" onclick="if(event.target === this) closeTpinModal()">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all" onclick="event.stopPropagation()">
            {{-- Modal Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-orange-600 to-orange-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Enter Your TPIN</h3>
                <p class="text-gray-600">Enter your 4-digit Transaction PIN to update bank details</p>
            </div>

            {{-- TPIN Input Form --}}
            <div id="tpinInputForm">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 text-center">4-Digit PIN</label>
                    <div class="flex justify-center gap-3 mb-2">
                        <input type="text" id="tpin1" maxlength="1" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all" oninput="moveTpinFocus(this, 'tpin2')" onkeydown="handleTpinBackspace(event, this, null)">
                        <input type="text" id="tpin2" maxlength="1" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all" oninput="moveTpinFocus(this, 'tpin3')" onkeydown="handleTpinBackspace(event, this, 'tpin1')">
                        <input type="text" id="tpin3" maxlength="1" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all" oninput="moveTpinFocus(this, 'tpin4')" onkeydown="handleTpinBackspace(event, this, 'tpin2')">
                        <input type="text" id="tpin4" maxlength="1" class="w-14 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-2xl focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 transition-all" oninput="moveTpinFocus(this, null)" onkeydown="handleTpinBackspace(event, this, 'tpin3')">
                    </div>
                    <p class="text-xs text-center text-gray-500">
                        <a href="{{ url('/') }}" class="text-orange-600 hover:text-orange-700 underline">Forgot TPIN?</a>
                    </p>
                </div>

                {{-- Error Message --}}
                <div id="tpinError" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span id="tpinErrorText">Invalid PIN. Please try again.</span>
                </div>

                {{-- Lockout Message --}}
                <div id="tpinLockout" class="hidden mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-2xl text-yellow-800 text-sm">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                    <span id="tpinLockoutText">Account locked. Try again in 15 minutes.</span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="closeTpinModal()"
                        class="flex-1 px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-2xl hover:bg-gray-50 transition-all duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        type="button"
                        onclick="verifyTpin()"
                        id="verifyTpinBtn"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-600 to-orange-700 text-white font-semibold rounded-2xl hover:shadow-lg hover:shadow-orange-500/30 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Verify
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation --}}
    <nav class="fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-xl shadow-lg shadow-green-900/10 border-t border-gray-200/60 z-50">
        <div class="grid grid-cols-5 max-w-7xl mx-auto">
            {{-- Home --}}
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Home</span>
            </a>

            {{-- Wallet --}}
            <a href="{{ route('customer.wallet') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-medium">Wallet</span>
            </a>

            {{-- Purchases --}}
            <a href="{{ route('customer.purchases') }}" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-[#76d37a] hover:bg-green-50/50 transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
                <span class="text-xs font-medium">Purchases</span>
            </a>

            {{-- Profile (Active) --}}
            <a href="{{ route('customer.profile') }}" class="flex flex-col items-center py-3 px-2 text-white bg-gradient-to-r from-[#76d37a] to-[#93db4d] border-t-2 border-[#76d37a] transition-all duration-200">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-bold">Profile</span>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('customer.logout') }}" class="contents" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="submit" class="flex flex-col items-center py-3 px-2 text-gray-500 hover:text-red-600 hover:bg-red-50/50 transition-all duration-200">
                    <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xs font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div id="successMessage" class="fixed top-5 right-5 bg-green-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div id="errorMessage" class="fixed top-5 right-5 bg-red-500 text-white px-6 py-4 rounded-2xl shadow-2xl z-[2000] flex items-center gap-3 animate-slideIn">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
        </svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- Animations --}}
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease;
        }

        /* Mobile Bottom Navigation */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 8px 0;
            padding-bottom: calc(8px + env(safe-area-inset-bottom));
        }

        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex;
                justify-content: space-around;
                align-items: center;
            }
            body {
                padding-bottom: 70px !important;
            }
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #666;
            font-size: 10px;
            padding: 4px 12px;
            transition: color 0.2s ease;
            min-width: 50px;
        }

        .bottom-nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .bottom-nav-item span {
            white-space: nowrap;
        }

        .bottom-nav-item.active,
        .bottom-nav-item:hover {
            color: var(--bix-dark-blue, #1a365d) !important;
        }

        .bottom-nav-item.active i,
        .bottom-nav-item.active svg {
            color: var(--bix-light-green, #76d37a) !important;
            transform: scale(1.1);
            transition: transform 0.15s ease, color 0.15s ease;
        }

        .bottom-nav-item.active span {
            color: var(--bix-light-green, #76d37a) !important;
            font-weight: 600;
        }

        /* Logout button styling */
        .bottom-nav-item-form {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bottom-nav-item.logout-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .bottom-nav-item.logout-btn:hover i,
        .bottom-nav-item.logout-btn:hover span {
            color: #dc2626 !important;
        }
    </style>

    {{-- Firebase SDK (for OTP) --}}
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.1/firebase-auth-compat.js"></script>

    {{-- Configuration for external JavaScript --}}
    <script>
        // Firebase Configuration
        window.FIREBASE_CONFIG = {
            apiKey: "{{ config('firebase.web.api_key') }}",
            authDomain: "{{ config('firebase.web.auth_domain') }}",
            projectId: "{{ config('firebase.web.project_id') }}",
            storageBucket: "{{ config('firebase.web.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.web.messaging_sender_id') }}",
            appId: "{{ config('firebase.web.app_id') }}"
        };

        // OTP Configuration
        window.SHOW_OTP_MODAL = @json(session('show_otp_modal') ?? false);
        window.USER_PHONE = "{{ $profile->phone ?? '' }}";

        // Route URLs
        window.REQUEST_OTP_URL = "{{ route('customer.bank-details.request-otp') }}";
        window.VERIFY_OTP_URL = "{{ route('customer.bank-details.verify-otp') }}";
        window.VERIFY_TPIN_URL = "{{ route('customer.bank-details.verify-tpin') }}";
        window.CANCEL_OTP_URL = "{{ route('customer.bank-details.cancel-otp') }}";

        // Default Auth Method
        window.DEFAULT_AUTH_METHOD = @if(Auth::user()->pin_hash) 'tpin' @else 'otp' @endif;
    </script>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <a href="{{ route('customer.dashboard') }}" class="bottom-nav-item">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('customer.wallet') }}" class="bottom-nav-item">
            <i class="fas fa-wallet"></i>
            <span>Wallet</span>
        </a>
        <a href="{{ route('customer.purchases') }}" class="bottom-nav-item">
            <i class="fas fa-shopping-cart"></i>
            <span>Purchases</span>
        </a>
        <a href="{{ route('customer.profile') }}" class="bottom-nav-item active">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
        <form method="POST" action="{{ route('customer.logout') }}" class="bottom-nav-item-form" onsubmit="return confirm('Are you sure you want to logout?');">
            @csrf
            <button type="submit" class="bottom-nav-item logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>

</body>
</html>
