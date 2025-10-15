<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - BixCash</title>
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
        }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; background: var(--bg-light); padding-bottom: 80px; }
        .header { background: var(--secondary); color: white; padding: 1.5rem 1rem; }
        .header-content { max-width: 1200px; margin: 0 auto; display: flex; align-items: center; gap: 1rem; }
        .back-btn { color: white; text-decoration: none; font-size: 1.5rem; }
        .header-title { font-size: 1.25rem; font-weight: 700; }
        .content { max-width: 800px; margin: 0 auto; padding: 1.5rem 1rem; }
        .section { background: white; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .section-title { font-size: 1.125rem; font-weight: 700; margin-bottom: 1rem; color: var(--text-dark); }
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-dark); }
        .form-input { width: 100%; padding: 0.75rem 1rem; border: 2px solid var(--border); border-radius: 12px; font-size: 1rem; }
        .form-input:focus { outline: none; border-color: var(--primary); }
        .btn { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s ease; }
        .btn-primary { background: var(--primary); color: white; width: 100%; }
        .btn-primary:hover { background: var(--primary-dark); }
        .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid var(--border); padding: 0.75rem 0; }
        .nav-items { display: flex; justify-content: space-around; max-width: 600px; margin: 0 auto; }
        .nav-item { display: flex; flex-direction: column; align-items: center; gap: 0.25rem; color: var(--text-light); text-decoration: none; padding: 0.5rem 1rem; }
        .nav-item.active { color: var(--primary); }
        .nav-item svg { width: 24px; height: 24px; }
        .nav-item span { font-size: 0.7rem; font-weight: 600; }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-content">
            <a href="{{ route('customer.dashboard') }}" class="back-btn">←</a>
            <h1 class="header-title">My Profile</h1>
        </div>
    </header>

    <main class="content">

        <!-- Personal Information -->
        <div class="section">
            <h2 class="section-title">Personal Information</h2>
            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" required value="{{ $user->name }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" class="form-input" value="{{ $user->phone }}" disabled style="background: var(--bg-light);">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $user->email }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-input" value="{{ $profile->date_of_birth ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-input" value="{{ $profile->address ?? '' }}">
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-input" value="{{ $profile->city ?? '' }}">
                </div>
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>

        <!-- Bank Details -->
        <div class="section">
            <h2 class="section-title">Bank Details</h2>
            <p style="color: var(--text-light); font-size: 0.875rem; margin-bottom: 1rem;">Required for withdrawal requests</p>
            <form method="POST" action="{{ route('customer.bank-details.update') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Bank Name *</label>
                    <input type="text" name="bank_name" class="form-input" required value="{{ $profile->bank_name ?? '' }}" placeholder="e.g., HBL, UBL, Meezan Bank">
                </div>
                <div class="form-group">
                    <label class="form-label">Account Title *</label>
                    <input type="text" name="account_title" class="form-input" required value="{{ $profile->account_title ?? '' }}" placeholder="Account holder name">
                </div>
                <div class="form-group">
                    <label class="form-label">Account Number *</label>
                    <input type="text" name="account_number" class="form-input" required value="{{ $profile->account_number ?? '' }}" placeholder="Your account number">
                </div>
                <div class="form-group">
                    <label class="form-label">IBAN (Optional)</label>
                    <input type="text" name="iban" class="form-input" value="{{ $profile->iban ?? '' }}" placeholder="PK36XXXX0000001234567890">
                </div>
                <button type="submit" class="btn btn-primary">Save Bank Details</button>
            </form>
        </div>

    </main>

    <nav class="bottom-nav">
        <div class="nav-items">
            <a href="{{ route('customer.dashboard') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('customer.wallet') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                <span>Wallet</span>
            </a>
            <a href="{{ route('customer.purchases') }}" class="nav-item">
                <svg fill="currentColor" viewBox="0 0 20 20"><path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path></svg>
                <span>Purchases</span>
            </a>
            <a href="{{ route('customer.profile') }}" class="nav-item active">
                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                <span>Profile</span>
            </a>
        </div>
    </nav>

    @if(session('success'))
    <div style="position: fixed; top: 20px; right: 20px; background: #48bb78; color: white; padding: 1rem 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 2000;">
        {{ session('success') }}
    </div>
    @endif

</body>
</html>
