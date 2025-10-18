<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'B4U Fitness')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo-rm.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body class="d-flex align-items-center justify-content-center vh-100 bg-gradient-primary login-bg">
    <div class="forgot-wrapper">
        <div class="forgot-card bg-white p-4 rounded-4 shadow">
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo.png') }}" alt="Project Logo" class="img-fluid" style="width: 100px;">
                <h3 class="mt-3">Forgot Password ?</h3>
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

                <button type="submit" class="btn btn-primary w-100 rounded-3">Submit</button>

                <div class="text-center mt-3">
                    <p class="mb-0 small">Already have account?
                        <a href="{{ route('login') }}" class="text-decoration-none">Log In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
