<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#021c47">
    <title>Partner Registration - BixCash</title>
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #93db4d;
            --primary-dark: #7bc33a;
            --secondary: #021c47;
            --text-dark: #1a202c;
            --text-light: #718096;
            --border: #e2e8f0;
            --bg-light: #f7fafc;
            --success: #48bb78;
            --danger: #f56565;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, var(--secondary) 0%, #0a2f5f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .logo p {
            color: var(--text-light);
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-label .required {
            color: var(--danger);
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(147, 219, 77, 0.1);
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-light);
            margin-top: 0.25rem;
        }

        .checkbox-group {
            display: flex;
            align-items: start;
            gap: 0.75rem;
        }

        .checkbox-group input[type="checkbox"] {
            margin-top: 0.25rem;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .checkbox-group label {
            font-size: 0.875rem;
            color: var(--text-dark);
            line-height: 1.5;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(147, 219, 77, 0.3);
        }

        .btn-primary:disabled {
            background: var(--border);
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .error-list {
            list-style: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            .logo h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <h1>🚀 Partner with BixCash</h1>
            <p>Join our network and grow your business</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('partner.register.submit') }}" id="partnerRegisterForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Business Name <span class="required">*</span></label>
                <input type="text" name="business_name" class="form-input" required
                       value="{{ old('business_name') }}" placeholder="e.g., KFC Lahore">
            </div>

            <div class="form-group">
                <label class="form-label">Mobile Number <span class="required">*</span></label>
                <div style="display: flex; gap: 0.5rem;">
                    <div style="padding: 0.875rem 1rem; background: var(--bg-light); border: 2px solid var(--border); border-radius: 12px; font-weight: 600;">+92</div>
                    <input type="text" name="phone" class="form-input" required maxlength="10" pattern="[0-9]{10}"
                           value="{{ old('phone') }}" placeholder="3001234567">
                </div>
                <div class="form-hint">Enter 10-digit mobile number without +92</div>
            </div>

            <div class="form-group">
                <label class="form-label">Contact Person Name <span class="required">*</span></label>
                <input type="text" name="contact_person_name" class="form-input" required
                       value="{{ old('contact_person_name') }}" placeholder="Full name">
            </div>

            <div class="form-group">
                <label class="form-label">Email (Optional)</label>
                <input type="email" name="email" class="form-input"
                       value="{{ old('email') }}" placeholder="your@email.com">
            </div>

            <div class="form-group">
                <label class="form-label">Business Type <span class="required">*</span></label>
                <select name="business_type" class="form-select" required>
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

            <div class="form-group">
                <label class="form-label">Business Address (Optional)</label>
                <input type="text" name="business_address" class="form-input"
                       value="{{ old('business_address') }}" placeholder="Complete address">
            </div>

            <div class="form-group">
                <label class="form-label">City (Optional)</label>
                <input type="text" name="city" class="form-input"
                       value="{{ old('city') }}" placeholder="e.g., Lahore">
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required
                           {{ old('agree_terms') ? 'checked' : '' }}>
                    <label for="agree_terms">
                        I agree to BixCash <a href="#" style="color: var(--primary);">Terms & Conditions</a>
                        and confirm that all information provided is accurate.
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>

        <div class="back-link">
            <a href="/">← Back to Home</a>
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

        // Form submission
        const form = document.getElementById('partnerRegisterForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;
            });
        }
    </script>
</body>
</html>
