<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Partner Registration - BixCash</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-gray-50 via-blue-50/20 to-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-xl border border-gray-200/60 shadow-lg shadow-blue-900/5 p-6 sm:p-8">
        {{-- Logo Section --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-blue-900 to-blue-700 bg-clip-text text-transparent mb-2">
                Partner with BixCash
            </h1>
            <p class="text-gray-500 text-sm sm:text-base">Join our network and grow your business</p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <p class="text-sm font-semibold text-red-900 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Registration Form --}}
        <form method="POST" action="{{ route('partner.register.submit') }}" id="partnerRegisterForm" class="space-y-5">
            @csrf

            {{-- Business Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Business Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="business_name"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                    required
                    value="{{ old('business_name') }}"
                    placeholder="e.g., KFC Lahore"
                >
            </div>

            {{-- Mobile Number --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Mobile Number <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <div class="px-4 py-2.5 bg-gray-50 border-2 border-gray-200 rounded-xl font-semibold text-gray-700 flex items-center">+92</div>
                    <input
                        type="text"
                        name="phone"
                        class="flex-1 px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                        required
                        maxlength="10"
                        pattern="[0-9]{10}"
                        value="{{ old('phone') }}"
                        placeholder="3001234567"
                    >
                </div>
                <p class="mt-1.5 text-xs text-gray-500">Enter 10-digit mobile number without +92</p>
            </div>

            {{-- Contact Person Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Contact Person Name <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="contact_person_name"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                    required
                    value="{{ old('contact_person_name') }}"
                    placeholder="Full name"
                >
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email (Optional)</label>
                <input
                    type="email"
                    name="email"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                    value="{{ old('email') }}"
                    placeholder="your@email.com"
                >
            </div>

            {{-- Business Type --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Business Type <span class="text-red-500">*</span>
                </label>
                <select
                    name="business_type"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all bg-white"
                    required
                >
                    <option value="">Select business type</option>
                    <option value="Restaurant" {{ old('business_type') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
                    <option value="Retail" {{ old('business_type') == 'Retail' ? 'selected' : '' }}>Retail Store</option>
                    <option value="Cafe" {{ old('business_type') == 'Cafe' ? 'selected' : '' }}>Cafe</option>
                    <option value="Grocery" {{ old('business_type') == 'Grocery' ? 'selected' : '' }}>Grocery Store</option>
                    <option value="Fashion" {{ old('business_type') == 'Fashion' ? 'selected' : '' }}>Fashion & Clothing</option>
                    <option value="Electronics" {{ old('business_type') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Salon" {{ old('business_type') == 'Salon' ? 'selected' : '' }}>Salon & Spa</option>
                    <option value="Pharmacy" {{ old('business_type') == 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                    <option value="Services" {{ old('business_type') == 'Services' ? 'selected' : '' }}>Services</option>
                    <option value="Other" {{ old('business_type') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Business Address --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Business Address (Optional)</label>
                <input
                    type="text"
                    name="business_address"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                    value="{{ old('business_address') }}"
                    placeholder="Complete address"
                >
            </div>

            {{-- City --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">City (Optional)</label>
                <input
                    type="text"
                    name="city"
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all"
                    value="{{ old('city') }}"
                    placeholder="e.g., Lahore"
                >
            </div>

            {{-- Terms & Conditions --}}
            <div class="flex items-start gap-3">
                <input
                    type="checkbox"
                    name="agree_terms"
                    id="agree_terms"
                    class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 cursor-pointer"
                    required
                    {{ old('agree_terms') ? 'checked' : '' }}
                >
                <label for="agree_terms" class="text-sm text-gray-700 leading-relaxed">
                    I agree to BixCash <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">Terms & Conditions</a>
                    and confirm that all information provided is accurate.
                </label>
            </div>

            {{-- Submit Button --}}
            <button
                type="submit"
                class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 hover:-translate-y-0.5 transition-all duration-200 shadow-md shadow-blue-500/30 hover:shadow-lg hover:shadow-blue-500/40"
            >
                Submit Application
            </button>
        </form>

        {{-- Back Link --}}
        <div class="text-center mt-6">
            <a href="/" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                ← Back to Home
            </a>
        </div>
    </div>

    <script>
        // Phone number validation
        const phoneInput = document.querySelector('input[name="phone"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);
            });
        }

        // Form submission - disable button
        const form = document.getElementById('partnerRegisterForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        }
    </script>
</body>
</html>
