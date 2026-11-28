<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainer sign in | NR Fitness</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favicon-rounded.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;600;700;800&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/trainer-portal.css') }}">
</head>
<body class="trainer-auth-page">
    <main class="trainer-login-card">
        <a class="trainer-brand trainer-login-brand" href="{{ route('home') }}">
            <img src="{{ asset('img/logo-rm.png') }}" alt="">
            <span>NR FITNESS <small>TRAINER PORTAL</small></span>
        </a>
        <span class="trainer-eyebrow">COACH WORKSPACE</span>
        <h1>Welcome back</h1>
        <p class="trainer-login-intro">Sign in to support your members and manage their daily coaching plans.</p>
        <form method="POST" action="{{ route('trainer.login.submit') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="trainer-email">Email address</label>
                <input class="form-control @error('email') is-invalid @enderror" id="trainer-email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="trainer-password">Password</label>
                <input class="form-control @error('password') is-invalid @enderror" id="trainer-password" name="password" type="password" autocomplete="current-password" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-check mb-4">
                <input class="form-check-input" id="trainer-remember" name="remember" type="checkbox" value="1">
                <label class="form-check-label" for="trainer-remember">Remember me</label>
            </div>
            <button class="btn trainer-primary-btn w-100" type="submit">Sign in to trainer portal</button>
        </form>
        <p class="trainer-login-footer"><a href="{{ route('login') }}">Member sign in</a><span>·</span><a href="{{ route('home') }}">Back to NR Fitness</a></p>
    </main>
</body>
</html>
