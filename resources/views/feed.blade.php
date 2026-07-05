<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title>{{ config('site.name') }}</title>
    <link>{{ route('blog.index') }}</link>
    <description>{{ config('site.description') }}</description>
    <language>en-us</language>
    <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
    <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml"/>
    @foreach ($posts as $post)
    <item>
        <title>{{ $post->title }}</title>
        <link>{{ route('blog.show', $post->slug) }}</link>
        <guid isPermaLink="true">{{ route('blog.show', $post->slug) }}</guid>
        <pubDate>{{ $post->published_at?->toRssString() }}</pubDate>
        <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">{{ config('site.author.name') }}</dc:creator>
        @if($post->excerpt)<description>{{ $post->excerpt }}</description>@endif
        <content:encoded><![CDATA[{!! $post->content !!}]]></content:encoded>
        @foreach ($post->tags as $tag)
        <category>{{ $tag->name }}</category>
        @endforeach
    </item>
    @endforeach
</channel>
</rss>
