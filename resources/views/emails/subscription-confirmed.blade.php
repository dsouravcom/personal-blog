<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription Confirmed</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1f2937;
        }
        .wrapper {
            max-width: 560px;
            margin: 48px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        .header {
            background-color: #4f46e5;
            padding: 36px 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }
        .body {
            padding: 36px 40px;
        }
        .body p {
            margin: 0 0 16px;
            font-size: 15px;
            line-height: 1.65;
            color: #374151;
        }
        .body p:last-child { margin-bottom: 0; }
        .highlight {
            background: #eef2ff;
            border-left: 3px solid #4f46e5;
            border-radius: 6px;
            padding: 14px 18px;
            margin: 24px 0;
            font-size: 14px;
            color: #3730a3;
            font-style: italic;
        }
        .btn {
            display: inline-block;
            margin-top: 24px;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 8px;
        }
        .footer {
            border-top: 1px solid #e5e7eb;
            padding: 24px 40px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            background: #f9fafb;
        }
        .footer a { color: #6b7280; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>
        <div class="body">
            <p>Hey there 👋</p>
            <p>
                You're now subscribed to <strong>{{ config('app.name') }}</strong>. 
                I'll send you a note whenever I publish something new — no spam, ever.
            </p>
            <div class="highlight">
                "The best time to start writing was yesterday. The second best time is right now."
            </div>
            <p>
                In the meantime, feel free to browse the latest posts on the blog.
            </p>
            <a href="{{ config('app.url') }}" class="btn">Browse Posts &rarr;</a>
        </div>
        <div class="footer">
            <p>
                You're receiving this because you subscribed at 
                <a href="{{ config('app.url') }}">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</a>.
            </p>
            <p style="margin-top:8px;">
                This email was sent to <strong>{{ $email }}</strong>.
            </p>
        </div>
    </div>
</body>
</html>
