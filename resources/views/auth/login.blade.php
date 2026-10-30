<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="auth-page d-flex align-items-center justify-content-center login-container">
    <div class="login-wrapper">
        <div class="login-left">
            <div class="login-card">
                <a class="auth-home-link" href="{{ route('home') }}"><i class="bi bi-arrow-left"></i> Back to home</a>
                <a class="auth-brand text-decoration-none" href="{{ route('home') }}">
                    <img src="{{ asset('img/logo-rm.png') }}" alt="">
                    <span>NR FITNESS</span>
                </a>
                <div class="auth-heading">
                    <h1>Welcome back</h1>
                    <p>Your next personal best starts here. Sign in to continue your journey.</p>
                </div>

                <form method="post" action="{{ route('login.post') }}">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-warning">{{ session('error') }}</div>
                    @endif

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control rounded-3 @error('email') is-invalid @enderror" id="email" placeholder="Enter your email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control rounded-3 @error('password') is-invalid @enderror" id="password" placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('forgotPassword') }}" class="small text-decoration-none">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn w-100">Sign in to your account</button>

                    <p class="mt-3 text-center small">Don’t have an account?
                        <a href="{{ route('signUp') }}" class="text-decoration-none">Register</a>
                    </p>
                </form>

                <div class="text-center mt-4 small">
                    <a href="{{ route('termsAndConditions') }}" class="text-muted text-decoration-none">Terms</a>
                    <span>·</span>
                    <a href="{{ route('privacyPolicy') }}" class="text-muted text-decoration-none">Privacy</a>
                </div>
            </div>
        </div>

        <div class="login-right">
            <img src="{{ asset('img/login-background.webp') }}" alt="Athlete training" class="login-image">
            <div class="auth-visual-copy">
                <span>Strength is a journey</span>
                <h2>Show up today.<br>Surprise yourself tomorrow.</h2>
                <p>Build consistency, track what matters, and celebrate every step forward.</p>
            </div>
        </div>
    </div>
</body>

</html>
