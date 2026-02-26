<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Post: {{ $post->title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f5;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            color: #18181b;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border: 1px solid #e4e4e7;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #18181b;
            padding: 24px;
            color: #e4e4e7;
            font-size: 14px;
        }
        .terminal-dots { display: flex; gap: 6px; margin-bottom: 16px; }
        .dot { width: 10px; height: 10px; border-radius: 50%; background-color: #3f3f46; }
        .prompt { color: #a1a1aa; }
        .body { padding: 32px 24px; }
        .tag {
            display: inline-block;
            background: #f4f4f5;
            border: 1px solid #e4e4e7;
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 11px;
            color: #71717a;
            margin-right: 4px;
            margin-bottom: 4px;
        }
        .cta {
            display: inline-block;
            margin-top: 24px;
            background-color: #18181b;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-size: 13px;
            letter-spacing: 0.05em;
        }
        .footer {
            padding: 16px 24px;
            border-top: 1px solid #f4f4f5;
            font-size: 11px;
            color: #a1a1aa;
            text-align: center;
        }
        .footer a { color: #a1a1aa; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Terminal-style header --}}
    <div class="header">
        <div class="terminal-dots">
            <div class="dot" style="background:#ef4444;"></div>
            <div class="dot" style="background:#f59e0b;"></div>
            <div class="dot" style="background:#22c55e;"></div>
        </div>
        <span class="prompt">~/blog $</span>
        <span style="color:#4ade80;"> git push origin</span>
        <span style="color:#e4e4e7;"> new-post</span>
    </div>

    {{-- Body --}}
    <div class="body">
        <p style="font-size:12px; color:#71717a; margin:0 0 8px 0;">// new commit on master</p>
        <h2 style="font-size:20px; font-weight:700; margin:0 0 12px 0; color:#18181b;">
            {{ $post->title }}
        </h2>

        @if($post->excerpt)
        <p style="font-size:14px; color:#3f3f46; line-height:1.7; margin:0 0 16px 0;">
            {{ $post->excerpt }}
        </p>
        @endif

        @if($post->tags->isNotEmpty())
        <div style="margin-bottom:16px;">
            @foreach($post->tags as $tag)
                <span class="tag"># {{ $tag->name }}</span>
            @endforeach
        </div>
        @endif

        <p style="font-size:12px; color:#a1a1aa; margin:0 0 4px 0;">
            <span style="color:#71717a;">published_at:</span>
            {{ $post->published_at?->format('D, d M Y H:i') }} UTC
        </p>

        <a href="{{ route('blog.show', $post->slug) }}" class="cta">./read_post.sh →</a>
    </div>

    {{-- Footer --}}
    <div class="footer">
        You're receiving this because you subscribed to
        <a href="{{ route('blog.index') }}">{{ config('app.name') }}</a>.<br>
        <a href="{{ URL::signedRoute('blog.unsubscribe', ['subscriber' => $subscriber->id]) }}">Unsubscribe</a>
    </div>

</div>
</body>
</html>
