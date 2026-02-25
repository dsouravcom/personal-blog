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
            background-color: #f4f4f5; /* zinc-100 */
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            color: #18181b; /* zinc-900 */
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #e4e4e7; /* zinc-200 */
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #18181b; /* zinc-900 */
            padding: 24px;
            color: #e4e4e7; /* zinc-200 */
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 14px;
        }
        .terminal-dots {
            display: flex;
            gap: 6px;
            margin-bottom: 16px;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #3f3f46; /* zinc-700 */
        }
        .command-line {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .prompt {
            color: #a1a1aa; /* zinc-400 */
        }
        .cursor {
            display: inline-block;
            width: 8px;
            height: 16px;
            background-color: #ffffff;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0; }
        }
        .content {
            padding: 40px;
            line-height: 1.7;
        }
        h1 {
            font-size: 18px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 24px;
            border-bottom: 1px dashed #e4e4e7;
            padding-bottom: 16px;
        }
        p {
            margin: 0 0 16px;
            color: #3f3f46; /* zinc-700 */
        }
        .btn {
            display: inline-block;
            margin-top: 16px;
            background-color: #18181b; /* zinc-900 */
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 4px;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .btn:hover {
            background-color: #27272a; /* zinc-800 */
        }
        .footer {
            background-color: #fafafa; /* zinc-50 */
            padding: 24px 40px;
            border-top: 1px solid #e4e4e7; /* zinc-200 */
            font-size: 12px;
            color: #a1a1aa; /* zinc-400 */
            text-align: center;
        }
        .footer a {
            color: #71717a; /* zinc-500 */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <!-- Mock Terminal Header -->
            <div style="display:flex; gap:6px; margin-bottom:12px;">
                <span style="width:10px; height:10px; border-radius:50%; background:#ef4444;"></span>
                <span style="width:10px; height:10px; border-radius:50%; background:#eab308;"></span>
                <span style="width:10px; height:10px; border-radius:50%; background:#22c55e;"></span>
            </div>
            <div class="command-line">
                <span style="color: #22c55e;">➜</span>
                <span style="color: #60a5fa;">~</span>
                <span class="prompt">/blog $</span>
                <span>./cat welcome_message.txt</span>
            </div>
        </div>
        
        <div class="content">
            <h1>Success: Subscription Confirmed</h1>
            
            <p><strong>Hello!</strong></p>
            
            <p>
                You have successfully subscribed to <strong>{{ config('app.name') }}</strong>. 
                Consider this the initial commit to our correspondence.
            </p>
            
            <p>
                I'll push updates to your inbox whenever I publish new content on technology, design, and code.
                No spam, no bloat, just the source.
            </p>

            <br>
            
            <a href="{{ config('app.url') }}" class="btn">
                <span style="margin-right: 6px;">>_</span> Return to Home
            </a>
        </div>

        <div class="footer">
            @php $host = parse_url(config('app.url'), PHP_URL_HOST); @endphp
            <p>
                Sent from the {{ $host }} server.<br>
                You subscribed with <strong>{{ $subscriber->email }}</strong>.
            </p>
            <p style="margin-top: 12px;">
                <a href="{{ config('app.url') }}">Visit Website</a>
                <span style="color: #d4d4d8; margin: 0 8px;">|</span>
                <a href="{{ URL::signedRoute('blog.unsubscribe', ['subscriber' => $subscriber->id]) }}">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>
