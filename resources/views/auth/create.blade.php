<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B4U Fitness - Register')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="vh-100 d-flex align-items-center justify-content-center login-container">

    <div class="login-wrapper">
        <!-- Left Section: Form -->
        <div class="login-left">
            <div class="login-card">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/logo.png') }}" alt="Project Logo" class="img-fluid mb-3" style="width: 80px;">
                    <h3 class="fw-bold">Create Account</h3>
                    <p class="text-muted">Join the Fitness Community</p>
                </div>

                <form method="post" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Full Name</label>
                        <input type="text" name="username"
                            class="form-control rounded-3 @error('username') is-invalid @enderror"
                            id="username" placeholder="Enter your name" value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" name="email"
                            class="form-control rounded-3 @error('email') is-invalid @enderror"
                            id="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control rounded-3 @error('password') is-invalid @enderror"
                            id="password" placeholder="Enter your password" required>
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

                    <button type="submit" class="btn btn-dark w-100 rounded-3">Register</button>

                    <p class="mt-3 text-center small">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-decoration-none">Log In</a>
                    </p>

                    @if (session('error'))
                        <div class="alert alert-warning mt-3">{{ session('error') }}</div>
                    @endif
                </form>

                <div class="text-center mt-4 small">
                    <a href="#" class="text-muted text-decoration-none">Terms of Use</a> |
                    <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                </div>
            </div>
        </div>

        <!-- Right Section: Fitness Image -->
        <div class="login-right">
            <img src="{{ asset('img/login-background.webp') }}" alt="Fitness Image" class="login-image">
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
