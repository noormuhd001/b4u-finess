<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions - NR Fitness</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="auth-page legal-page">
    <main class="legal-card">
        <a class="legal-brand" href="{{ route('login') }}"><img src="{{ asset('img/logo-rm.png') }}" alt="">NR FITNESS</a>
        <h1>Terms and Conditions</h1>
        <p><strong>Last updated:</strong> October 28, 2025</p>

        <p>Welcome to <strong>NR Fitness</strong>. By using our website, app, or services, you agree to the following
            terms and conditions.</p>

        <h3>1. Use of Service</h3>
        <p>You agree to use our services responsibly and in compliance with applicable laws. You must not misuse or
            attempt to harm the functionality of our platform.</p>

        <h3>2. Account Responsibility</h3>
        <p>You are responsible for maintaining the confidentiality of your login credentials and for any actions under
            your account.</p>

        <h3>3. Intellectual Property</h3>
        <p>All logos, designs, and content on this site are the intellectual property of NR Fitness and may not be used
            without written permission.</p>

        <h3>4. Limitation of Liability</h3>
        <p>NR Fitness will not be liable for any indirect, incidental, or consequential damages arising from the use of
            our platform.</p>

        <h3>5. Modifications</h3>
        <p>We may update these Terms periodically. Continued use of the service implies your acceptance of the revised
            Terms.</p>

        <p>For questions or concerns, contact us at <a
                href="mailto:noor.bytekat@gmail.com">noor.bytekat@gmail.com</a></p>
    </main>

    <footer>
        © 2025 NR Fitness | <a href="{{ route('privacyPolicy') }}">Privacy Policy</a>
    </footer>
</body>

</html>
