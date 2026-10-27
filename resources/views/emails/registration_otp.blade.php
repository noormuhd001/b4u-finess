<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify your account</title>
</head>
<body style="margin:0;padding:32px 16px;background:#f3f6f1;font-family:Arial,sans-serif;color:#18251d">
    <div style="max-width:520px;margin:0 auto;padding:32px;border-radius:20px;background:#fff">
        <p style="margin:0 0 8px;color:#54735c;font-size:12px;font-weight:bold;letter-spacing:2px">NR FITNESS</p>
        <h1 style="margin:0 0 16px;font-size:25px">Verify your email</h1>
        <p>Hello {{ $name }}, enter this one-time code to activate your account:</p>
        <p style="margin:24px 0;padding:18px;border-radius:12px;background:#f3f6f1;text-align:center;font-size:32px;font-weight:bold;letter-spacing:10px">{{ $otp }}</p>
        <p>This code expires in 10 minutes. If you did not create an account, you can ignore this email.</p>
        <p style="margin-top:28px;color:#77847c">Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
