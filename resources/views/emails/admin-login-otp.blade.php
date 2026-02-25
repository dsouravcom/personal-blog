<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login OTP</title>
    <style>
        body {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            background-color: #f4f4f5;
            padding: 40px;
            color: #18181b;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e4e4e7;
            padding: 24px;
            border-radius: 8px;
            text-align: center;
        }
        .code {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 4px;
            margin: 24px 0;
            color: #000;
        }
        p {
            color: #71717a;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Login Attempt Detected</p>
        <div class="code">{{ $otp }}</div>
        <p>This code will be valid for 5 minutes.</p>
    </div>
</body>
</html>
