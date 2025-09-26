<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- Email Card -->
                <div class="card shadow-sm">

                    <!-- Header -->
                    <div class="card-header text-white text-center bg-warning">
                        <h4 class="mb-0">Password Reset Request</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body">
                        <p>Hello {{ $user->name }},</p>
                        <p>We received a request to reset your password. Click the button below to reset it:</p>
                        <div class="text-center my-3">
                            <a href="{{ $resetUrl }}" class="btn btn-warning btn-lg">Reset Password</a>
                        </div>
                        <p>If you did not request a password reset, you can safely ignore this email.</p>
                        <p>Thanks,<br>{{ config('app.name') }}</p>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer text-center text-muted small">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </div>

                </div>
                <!-- /Email Card -->

            </div>
        </div>
    </div>

</body>
</html>
