<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Application Under Review - BixCash</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><circle cx='50' cy='50' r='45' fill='%2376d37a'/><text x='50' y='68' font-size='55' font-weight='bold' fill='white' text-anchor='middle' font-family='Arial'>B</text></svg>">
    @vite(['resources/css/app.css'])
    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .animate-custom-pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 p-8 text-center">
        {{-- Icon --}}
        <div class="text-6xl mb-6 animate-custom-pulse">⏳</div>

        {{-- Title --}}
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Application Under Review</h1>

        {{-- Subtitle --}}
        <p class="text-gray-500 text-base leading-relaxed mb-8">
            Your partner application is being reviewed by our team.
            You'll receive an SMS once approved.
        </p>

        {{-- Info Card --}}
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-500">Business Name</span>
                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->business_name }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-500">Contact Person</span>
                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->contact_person_name }}</span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                <span class="text-sm font-medium text-gray-500">Submitted On</span>
                <span class="text-sm font-semibold text-gray-900">{{ $partnerProfile->registration_date ? \Carbon\Carbon::parse($partnerProfile->registration_date)->format('M d, Y') : 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-sm font-medium text-gray-500">Status</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 border border-orange-200">
                    {{ ucfirst($partnerProfile->status) }}
                </span>
            </div>
        </div>

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('partner.logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-colors duration-200"
            >
                Logout
            </button>
        </form>
    </div>
</body>
</html>
