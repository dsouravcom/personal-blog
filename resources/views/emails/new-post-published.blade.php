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
        .command-line {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .prompt {
            color: #a1a1aa;
        }
        .content {
            padding: 40px;
            line-height: 1.7;
        }
        .tag {
            display: inline-block;
            background: #f4f4f5;
            border: 1px solid #e4e4e7;
            color: #71717a;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 4px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            margin-top: 24px;
            margin-bottom: 8px;
            color: #18181b;
            line-height: 1.4;
        }
        .meta {
            font-size: 12px;
            color: #a1a1aa;
            margin-bottom: 24px;
        }
        .divider {
            border: none;
            border-top: 1px dashed #e4e4e7;
            margin: 24px 0;
        }
        .intro {
            font-size: 14px;
            color: #3f3f46;
            margin-bottom: 16px;
        }
        .excerpt {
            background: #fafafa;
            border-left: 3px solid #18181b;
            padding: 16px 20px;
            font-size: 14px;
            color: #52525b;
            line-height: 1.8;
            margin: 24px 0;
            border-radius: 0 4px 4px 0;
        }
        .btn {
            display: inline-block;
            margin-top: 24px;
            background-color: #18181b;
            color: #ffffff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 12px 24px;
            border-radius: 4px;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .cover {
            width: 100%;
            max-height: 260px;
            object-fit: cover;
            display: block;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .signature {
            margin-top: 32px;
            font-size: 14px;
            color: #52525b;
        }
        .signature strong {
            display: block;
            color: #18181b;
            font-size: 15px;
        }
        .footer {
            background-color: #fafafa;
            padding: 24px 40px;
            border-top: 1px solid #e4e4e7;
            font-size: 12px;
            color: #a1a1aa;
            text-align: center;
        }
        .footer a {
            color: #71717a;
            text-decoration: underline;
        }
        p { margin: 0 0 16px; }
    </style>
</head>
<body>
    <div class="wrapper">

        {{-- Terminal-style header --}}
        <div class="header">
            <div style="display:flex; gap:6px; margin-bottom:12px;">
                <span style="width:10px; height:10px; border-radius:50%; background:#ef4444;"></span>
                <span style="width:10px; height:10px; border-radius:50%; background:#eab308;"></span>
                <span style="width:10px; height:10px; border-radius:50%; background:#22c55e;"></span>
            </div>
            <div class="command-line">
                <span style="color:#22c55e;">➜</span>
                <span style="color:#60a5fa;">~</span>
                <span class="prompt">/blog $</span>
                <span>git push origin new-post</span>
            </div>
            <div style="margin-top: 8px; color: #71717a; font-size: 13px;">
                &nbsp;&nbsp;Enumerating objects... done.<br>
                &nbsp;&nbsp;Writing objects: 100% — <span style="color:#22c55e;">pushed successfully.</span>
            </div>
        </div>

        <div class="content">

            <p class="intro" style="margin-top: 0; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; color: #22c55e;">
                // System Update: New content deployed
            </p>

            <hr class="divider">

            {{-- Cover image if present --}}
            @if ($post->cover_image)
                <img src="{{ $post->cover_image }}"
                     alt="{{ $post->cover_image_alt ?? $post->title }}"
                     class="cover">
            @endif

            {{-- Tags --}}
            @if ($post->tags->isNotEmpty())
                <div style="margin-bottom: 4px;">
                    @foreach ($post->tags as $tag)
                        <span class="tag">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <h1>{{ $post->title }}</h1>

            <p class="meta">
                Published {{ $post->published_at->format('F j, Y') }} &nbsp;·&nbsp; by Sourav Dutta
            </p>

            {{-- Excerpt / description block --}}
            @if ($post->excerpt)
                <div class="excerpt">
                    {{ $post->excerpt }}
                </div>
            @endif

            <p class="intro">
                The build passed, the tests are green, and I’ve just pushed a new article to production.
                It’s un-minified, fully commented, and ready for code review.
            </p>

            <a href="{{ route('blog.show', $post->slug) }}" class="btn">
                <span style="margin-right:6px;">>_</span> ./read_post.sh
            </a>

            {{-- Signature --}}
            <div class="signature">
                <br>
                Cheers,<br>
                <strong>Sourav Dutta</strong>
                <span style="color:#a1a1aa;">— writer, builder, occasional over-thinker.</span>
            </div>
        </div>

        <div class="footer">
            <p>
                This email was sent to <strong>{{ $subscriber->email }}</strong> because you subscribed to Sourav Dutta's Personal Blog.
            </p>
            <p style="margin-top: 12px; color: #71717a;">
                &copy; {{ date('Y') }} Sourav Dutta. All rights reserved.
            </p>
            <p style="margin-top: 12px;">
                <a href="{{ route('blog.index') }}">Visit Website</a>
                <span style="color: #d4d4d8; margin: 0 8px;">|</span>
                <a href="{{ URL::signedRoute('blog.unsubscribe', ['subscriber' => $subscriber->id]) }}">Unsubscribe</a>
            </p>
        </div>

    </div>
</body>
</html>
