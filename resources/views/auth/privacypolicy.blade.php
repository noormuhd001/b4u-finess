<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - NR Fitness</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body class="auth-page legal-page">
    <main class="legal-card">
        <a class="legal-brand" href="{{ route('login') }}"><img src="{{ asset('img/logo-rm.png') }}" alt="">NR FITNESS</a>
        <h1>Privacy Policy</h1>
        <p><strong>Last updated:</strong> October 28, 2025</p>

        <p>At <strong>NR Fitness</strong>, your privacy is important to us. This Privacy Policy describes how we
            collect, use, and protect your personal information when you use our services.</p>

        <h3>1. Information We Collect</h3>
        <ul>
            <li>Personal information such as your name, email, and profile details when you register.</li>
            <li>Workout progress, fitness data, and usage preferences.</li>
            <li>Technical data like your device type, browser, and IP address.</li>
        </ul>

        <h3>2. How We Use Your Information</h3>
        <p>We use your information to improve your fitness experience, provide personalized recommendations, and
            maintain service quality.</p>

        <h3>3. Data Security</h3>
        <p>We use secure servers and encryption to protect your data. Your information is never sold or shared with
            unauthorized third parties.</p>

        <h3>4. Your Rights</h3>
        <p>You may access, modify, or delete your personal data at any time by contacting us.</p>

        <h3>5. Updates</h3>
        <p>We may update this Privacy Policy from time to time. The latest version will always be available on this
            page.</p>

        <p>For any privacy concerns, contact us at <a
                href="mailto:noornjr123@gmail.com">noornjr123@gmail.com</a></p>
    </main>

    <footer>
        © 2025 NR Fitness | <a href="{{ route('termsAndConditions') }}">Terms & Conditions</a>
    </footer>
</body>

</html>
