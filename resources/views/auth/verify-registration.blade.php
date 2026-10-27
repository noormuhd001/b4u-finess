<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your account | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body class="auth-page d-flex align-items-center justify-content-center login-container">
    <main class="verify-card">
        <a class="auth-brand text-decoration-none" href="{{ route('login') }}">
            <img src="{{ asset('img/logo-rm.png') }}" alt="">
            <span>NR FITNESS</span>
        </a>
        <div class="verify-icon"><i class="bi bi-envelope-check"></i></div>
        <div class="auth-heading text-center">
            <h1>Check your inbox</h1>
            @if (session('otp_sent'))
                <p>We sent a 6-digit verification code to <strong>{{ $email }}</strong>. Enter it below to activate your account.</p>
            @elseif (session('error'))
                <p>Your account is not active yet. We could not send a code to <strong>{{ $email }}</strong>; try sending a new code below.</p>
            @else
                <p>Enter the 6-digit code sent to <strong>{{ $email }}</strong>. If you have not received it, request a new code below.</p>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('registration.verify.submit') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <div class="mb-3">
                <label for="otp" class="form-label">Verification code</label>
                <input id="otp" name="otp" type="text" inputmode="numeric" autocomplete="one-time-code"
                    pattern="[0-9]{6}" maxlength="6" required autofocus
                    class="form-control text-center @error('otp') is-invalid @enderror"
                    placeholder="000000" value="{{ old('otp') }}">
                @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn w-100">Verify and activate account</button>
        </form>

        <form method="POST" action="{{ route('registration.verify.resend') }}" class="resend-code-form">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <span class="text-muted small">Code expired or missing?</span>
            <button type="submit" class="resend-code-button">Send a new code</button>
        </form>
        <p class="text-center small mt-4 mb-0"><a href="{{ route('signUp') }}">Back to registration</a></p>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
