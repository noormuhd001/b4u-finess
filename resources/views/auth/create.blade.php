<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NR Fitness - Register')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="auth-page d-flex align-items-center justify-content-center login-container">

    <div class="login-wrapper">
        <!-- Left Section: Form -->
        <div class="login-left">
            <div class="login-card" id="mobileview">
                <a class="auth-brand text-decoration-none" href="{{ route('login') }}">
                    <img src="{{ asset('img/logo-rm.png') }}" alt="">
                    <span>NR FITNESS</span>
                </a>
                <div class="auth-heading">
                    <h1>Create your account</h1>
                    <p>Start building a stronger, more consistent routine.</p>
                </div>

                <form method="post" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Full Name</label>
                        <input type="text" name="username"
                            class="form-control rounded-3 @error('username') is-invalid @enderror" id="username"
                            placeholder="Enter your name" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror" id="email"
                            placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="plan-picker-title">
                            <span class="form-label d-block">Choose your membership</span>
                            <small>{{ $membershipPlans->count() }} plans available</small>
                        </div>
                        <div class="plan-options @error('membership_plan_id') is-invalid @enderror" role="radiogroup" aria-label="Choose your membership">
                            @foreach ($membershipPlans as $plan)
                                <label class="plan-option">
                                    <input type="radio" name="membership_plan_id" value="{{ $plan->id }}"
                                        @checked(old('membership_plan_id') == $plan->id) required>
                                    <span class="plan-option-card">
                                        <span class="plan-radio"><i class="bi bi-check"></i></span>
                                        <span class="plan-option-details">
                                            <span class="plan-option-name">{{ $plan->name }}</span>
                                            <span class="plan-option-description">{{ $plan->description ?: 'A plan for your fitness journey' }}</span>
                                        </span>
                                        <span class="plan-option-price">
                                            ₹{{ number_format((float) $plan->price, 0) }}
                                            <small>/ {{ $plan->duration_months }} {{ \Illuminate\Support\Str::plural('month', $plan->duration_months) }}</small>
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @if ($membershipPlans->isEmpty())
                            <p class="text-muted small mb-0">Membership plans are temporarily unavailable. Please try again later.</p>
                        @endif
                        @error('membership_plan_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <p class="plan-payment-note"><i class="bi bi-cash-coin me-1"></i>Pay in cash directly to the manager. Your payment will show as pending until received.</p>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror" id="password"
                            placeholder="Enter your password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password"
                            class="form-control rounded-3 @error('confirm_password') is-invalid @enderror"
                            id="confirm_password" placeholder="Re-enter your password" required>
                        @error('confirm_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-100" @disabled($membershipPlans->isEmpty())>Create account</button>

                    <p class="mt-3 text-center small">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-decoration-none">Log In</a>
                    </p>

                    @if (session('error'))
                        <div class="alert alert-warning mt-3">{{ session('error') }}</div>
                    @endif
                </form>

                <div class="text-center mt-4 small">
                    <a href="{{ route('termsAndConditions') }}" class="text-muted text-decoration-none">Terms</a>
                    <span>·</span>
                    <a href="{{ route('privacyPolicy') }}" class="text-muted text-decoration-none">Privacy</a>
                </div>
            </div>
        </div>

        <!-- Right Section: Fitness Image -->
        <div class="login-right">
            <img src="{{ asset('img/login-background.webp') }}" alt="Fitness Image" class="login-image">
            <div class="auth-visual-copy">
                <span>Make progress personal</span>
                <h2>Small steps.<br>Lasting strength.</h2>
                <p>Find your rhythm, record your workouts, and see how far you can go.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const form = document.querySelector("form");
            const submitButton = form.querySelector("button[type='submit']");

            form.addEventListener("submit", function() {
                // Disable the button immediately
                submitButton.disabled = true;
                submitButton.innerText = "Submitting...";

                // Optional: Add spinner (Bootstrap-style)
                submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Submitting...
            `;
            });
        });
    </script>
</body>

</html>
