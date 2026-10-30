<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NR Fitness')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body class="auth-page d-flex align-items-center justify-content-center">
    <div class="forgot-wrapper">
        <div class="forgot-card">
            <a class="auth-brand text-decoration-none" href="{{ route('login') }}">
                <img src="{{ asset('img/logo-rm.png') }}" alt="">
                <span>NR FITNESS</span>
            </a>
            <div class="auth-heading">
                <h1>Forgot your password?</h1>
                <p>Enter your account email and we’ll send you a secure reset link.</p>
            </div>

            <form method="post" action="{{ route('sendOtp') }}">
                @csrf

                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-warning mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email"
                        class="form-control rounded-3 @error('email') is-invalid @enderror" id="email"
                        placeholder="Enter your email" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn w-100">Send reset link</button>

                <div class="text-center mt-3">
                    <p class="mb-0 small">Already have account?
                        <a href="{{ route('login') }}" class="text-decoration-none">Log In</a>
                    </p>
                </div>
            </form>
            <a class="auth-back" href="{{ route('login') }}"><i class="bi bi-arrow-left"></i> Back to sign in</a>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
