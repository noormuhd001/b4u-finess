<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - B4U Fitness</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-gradient-primary login-bg">

    <div class="forgot-wrapper">
        <div class="forgot-card bg-white p-4 rounded-4 shadow">
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Project Logo" class="img-fluid" style="width: 100px;">
                <h3 class="mt-3">Reset Password</h3>
            </div>

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('updatePassword') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- New Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password"
                        class="form-control rounded-3 @error('password') is-invalid @enderror" id="password"
                        placeholder="Enter your new password" required>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Confirm New Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="form-control rounded-3 @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" placeholder="Re-enter your new password" required>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 rounded-3">Reset Password</button>

                <!-- Login Link -->
                <div class="text-center mt-3">
                    <p class="mb-0 small">Remembered your password? <a href="{{ route('login') }}"
                            class="text-decoration-none">Log In</a></p>
                </div>

                <!-- Session Error -->
                @if (session('error'))
                    <div class="alert alert-warning mt-3">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Session Success -->
                @if (session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif
            </form>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
