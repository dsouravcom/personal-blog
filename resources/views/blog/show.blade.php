@extends('layouts.app', ['maxWidth' => 'max-w-6xl', 'py' => 'py-8 sm:py-10', 'progress' => true])

@section('title', ($post->meta_title ?: $post->title) . ' — ' . config('site.name'))
@section('description', $post->meta_description ?? $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 155))
@section('canonical', $post->canonical_url ?: url()->current())

@php
    $ogImage    = $post->og_image ?: $post->cover_image ?: url(config('site.og_image'));
    $ogImageAlt = $post->cover_image_alt ?? $post->title;
    $wordCount  = str_word_count(strip_tags($post->content));
    $shareUrl   = urlencode(url()->current());
    $shareText  = urlencode($post->title);
    $faviconUrl = asset('favicon.ico');
    $authorEmail = strtolower(trim((string) config('mail.from.address')));
    $authorName = strtolower(trim((string) config('site.author.name')));
@endphp

@push('head')
    @if($post->meta_keywords)<meta name="keywords" content="{{ $post->meta_keywords }}">@endif

    {{-- Open Graph --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $post->og_description ?? $post->meta_description ?? $post->excerpt }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    <meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ config('site.author.name') }}">
    @foreach($post->tags as $tag)
        <meta property="article:tag" content="{{ $tag->name }}">
    @endforeach
    @if($post->tags->isNotEmpty())<meta property="article:section" content="{{ $post->tags->first()->name }}">@endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
    <meta name="twitter:description" content="{{ $post->og_description ?? $post->meta_description ?? $post->excerpt }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="{{ $ogImageAlt }}">

    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $post->meta_title ?: $post->title,
        'description' => $post->meta_description ?? $post->excerpt,
        'image' => $ogImage,
        'datePublished' => $post->published_at?->toIso8601String(),
        'dateModified' => $post->updated_at->toIso8601String(),
        'wordCount' => $wordCount,
        'timeRequired' => 'PT' . $post->readingTime() . 'M',
        'url' => url()->current(),
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
        'inLanguage' => 'en',
        'commentCount' => $post->comments_count,
        'keywords' => $post->tags->pluck('name')->implode(', '),
        'articleSection' => $post->tags->first()->name ?? null,
        'author' => ['@type' => 'Person', 'name' => config('site.author.name'), 'url' => config('site.author.url')],
        'publisher' => ['@id' => url('/') . '#person'],
    ]" />

    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('blog.index')],
            $post->tags->isNotEmpty()
                ? ['@type' => 'ListItem', 'position' => 2, 'name' => $post->tags->first()->name, 'item' => route('blog.tag', $post->tags->first()->slug)]
                : null,
            ['@type' => 'ListItem', 'position' => $post->tags->isNotEmpty() ? 3 : 2, 'name' => $post->title, 'item' => url()->current()],
        ])),
    ]" />
@endpush

@section('content')
<div class="mx-auto flex max-w-6xl justify-center gap-10">

    {{-- ── TOC rail (desktop) ───────────────────────────────────────────────── --}}
    <aside data-toc-wrap class="hidden xl:block w-52 shrink-0">
        <div class="sticky top-28">
            <p class="mb-3 font-mono text-[0.7rem] uppercase tracking-wider text-faint">On this page</p>
            <nav data-toc aria-label="Table of contents"></nav>
        </div>
    </aside>

    {{-- ── Reading column ───────────────────────────────────────────────────── --}}
    <div class="w-full min-w-0 max-w-2xl">

        {{-- Breadcrumb --}}
        <nav class="reveal mb-8 flex flex-wrap items-center gap-2 font-mono text-xs text-faint" aria-label="Breadcrumb">
            <a href="{{ route('blog.index') }}" class="transition-colors hover:text-ink">Home</a>
            @if($post->tags->isNotEmpty())
                <x-icon name="chevron-right" class="w-3 h-3" />
                <a href="{{ route('blog.tag', $post->tags->first()->slug) }}" class="transition-colors hover:text-ink">{{ $post->tags->first()->name }}</a>
            @endif
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="truncate text-muted">{{ Str::limit($post->title, 40) }}</span>
        </nav>

        {{-- Header --}}
        <header class="reveal" style="animation-delay: 60ms">
            @if($post->tags->isNotEmpty())
                <div class="mb-5 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="tag pressable"><span class="text-faint">#</span>{{ $tag->name }}</a>
                    @endforeach
                </div>
            @endif

            <h1 class="text-3xl sm:text-4xl md:text-[2.75rem] font-semibold tracking-tight leading-[1.1] text-ink">
                {{ $post->title }}
            </h1>

            @if($post->excerpt)
                <p class="mt-5 font-serif text-xl leading-relaxed text-muted">{{ $post->excerpt }}</p>
            @endif

            {{-- Byline --}}
            <div class="mt-7 flex flex-wrap items-center gap-x-4 gap-y-2 border-y border-line py-4">
                <div class="flex items-center gap-2.5">
                    <img src="{{ $faviconUrl }}" alt="{{ config('site.author.name') }}"
                         class="h-9 w-9 rounded-full border border-line bg-surface object-cover p-1">
                    <div class="leading-tight">
                        <a href="{{ config('site.author.url') }}" class="text-sm font-medium text-ink hover:text-accent">{{ config('site.author.name') }}</a>
                        <p class="font-mono text-[0.68rem] uppercase tracking-wider text-faint">{{ config('site.author.job_title') }}</p>
                    </div>
                </div>
                <div class="ml-auto flex flex-wrap items-center gap-x-3 gap-y-1 font-mono text-[0.72rem] text-faint">
                    <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M j, Y') }}</time>
                    <span aria-hidden="true">·</span>
                    <span>{{ $post->readingTime() }} min</span>
                    <span aria-hidden="true">·</span>
                    <span class="inline-flex items-center gap-1"><x-icon name="eye" class="w-3 h-3" /> {{ number_format($viewCount) }}</span>
                </div>
            </div>
        </header>

        {{-- Cover --}}
        @if($post->cover_image)
            <figure class="reveal mt-8 overflow-hidden rounded-2xl border border-line" style="animation-delay: 120ms">
                <img src="{{ $post->cover_image }}" alt="{{ $post->cover_image_alt ?? $post->title }}"
                     class="w-full object-cover" loading="eager" decoding="async">
                @if($post->cover_image_caption)
                    <figcaption class="border-t border-line bg-surface-2 py-2.5 text-center font-mono text-xs text-muted">{{ $post->cover_image_caption }}</figcaption>
                @endif
            </figure>
        @endif

        {{-- Body --}}
        <article data-article class="prose dark:prose-invert mt-10 max-w-none">
            {!! $post->content !!}
        </article>

        {{-- Inline actions (mobile / tablet) --}}
        <div class="mt-12 flex flex-wrap items-center gap-3 border-t border-line pt-8 xl:hidden">
            <button type="button" data-like data-like-url="{{ route('blog.posts.like', $post) }}" data-liked="{{ $userLiked ? '1' : '0' }}"
                    class="like-btn btn btn-ghost pressable {{ $userLiked ? 'is-liked' : '' }}">
                <x-icon name="heart" data-like-icon class="w-4 h-4 {{ $userLiked ? 'fill-current' : '' }}" />
                <span data-like-label>{{ $userLiked ? 'Liked' : 'Like' }}</span>
                <span class="text-faint">·</span>
                <span data-like-count>{{ $post->likes_count }}</span>
            </button>
            <button type="button" data-copy-link="{{ url()->current() }}" class="btn btn-ghost pressable">
                <x-icon name="link" class="w-4 h-4" /> <span data-copy-label>Copy link</span>
            </button>
            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener" class="btn btn-ghost pressable">
                <x-icon name="twitter" class="w-3.5 h-3.5" /> Share
            </a>
        </div>

        {{-- Prev / next --}}
        @if($prev || $next)
            <nav class="mt-12 grid gap-3 sm:grid-cols-2" aria-label="More posts">
                @if($prev)
                    <a href="{{ route('blog.show', $prev->slug) }}" class="card pressable group p-5 hover:border-muted">
                        <span class="font-mono text-[0.68rem] uppercase tracking-wider text-faint">← Previous</span>
                        <p class="mt-1.5 font-medium text-ink transition-colors group-hover:text-accent line-clamp-2">{{ $prev->title }}</p>
                    </a>
                @else <span></span> @endif
                @if($next)
                    <a href="{{ route('blog.show', $next->slug) }}" class="card pressable group p-5 text-right hover:border-muted">
                        <span class="font-mono text-[0.68rem] uppercase tracking-wider text-faint">Next →</span>
                        <p class="mt-1.5 font-medium text-ink transition-colors group-hover:text-accent line-clamp-2">{{ $next->title }}</p>
                    </a>
                @endif
            </nav>
        @endif

        {{-- Comments --}}
        <section id="comments" class="mt-16 scroll-mt-24">
            <h2 class="flex items-center gap-2 text-xl font-semibold tracking-tight text-ink">
                Comments
                <span class="rounded-full bg-surface-2 px-2 py-0.5 font-mono text-xs text-muted">{{ $post->comments_count }}</span>
            </h2>

            @if(session('comment_submitted'))
                <div class="mt-5 flex items-center gap-3 rounded-xl border border-accent/40 bg-accent-soft px-4 py-3 text-sm text-ink">
                    <x-icon name="check" class="w-4 h-4 text-accent" /> {{ session('comment_submitted') }}
                </div>
            @endif

            {{-- List --}}
            @if($post->approvedComments->isEmpty())
                <p class="mt-6 rounded-xl border border-dashed border-line py-8 text-center text-sm text-muted">
                    No comments yet — be the first to share your thoughts.
                </p>
            @else
                <ul class="mt-6 space-y-4">
                    @foreach($post->approvedComments as $comment)
                        @php
                            $commentEmail = strtolower(trim((string) $comment->email));
                            $commentName = strtolower(trim((string) $comment->name));
                            $isAuthorReply = ($authorEmail !== '' && $commentEmail === $authorEmail) || ($authorName !== '' && $commentName === $authorName);
                            $commentAvatar = $isAuthorReply
                                ? $faviconUrl
                                : 'https://www.gravatar.com/avatar/' . md5($commentEmail) . '?s=80&d=mp';
                        @endphp
                        <li class="rounded-xl border border-line bg-surface p-5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $commentAvatar }}" alt="{{ $comment->name }} avatar"
                                     class="h-8 w-8 shrink-0 rounded-full border border-line bg-surface-2 object-cover">
                                <span class="text-sm font-medium text-ink">{{ $comment->name }}</span>
                                <time class="ml-auto font-mono text-xs text-faint" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->diffForHumans() }}</time>
                            </div>
                            <p class="mt-3 whitespace-pre-line text-[0.95rem] leading-relaxed text-ink-soft">{{ $comment->body }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Form --}}
            <details class="group mt-6 rounded-2xl border border-line bg-surface" {{ $errors->any() ? 'open' : '' }}>
                <summary class="pressable flex cursor-pointer list-none items-center justify-between gap-3 px-6 py-4 font-medium text-ink [&::-webkit-details-marker]:hidden">
                    <span class="inline-flex items-center gap-2"><x-icon name="pen" class="w-4 h-4 text-accent" /> Write a comment</span>
                    <x-icon name="chevron-right" class="w-4 h-4 text-faint transition-transform duration-200 group-open:rotate-90" />
                </summary>
                <form action="{{ route('blog.comments.store', $post) }}" method="POST" class="space-y-4 border-t border-line p-6">
                    @csrf
                    <div class="hidden" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="c-name" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-muted">Name</label>
                            <input id="c-name" type="text" name="name" value="{{ old('name') }}" required placeholder="Your name"
                                   class="w-full rounded-lg border border-line bg-paper px-4 py-2.5 text-sm text-ink placeholder:text-faint focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('name')<p class="mt-1 font-mono text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="c-email" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-muted">Email <span class="normal-case text-faint">(private)</span></label>
                            <input id="c-email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com"
                                   class="w-full rounded-lg border border-line bg-paper px-4 py-2.5 text-sm text-ink placeholder:text-faint focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
                            @error('email')<p class="mt-1 font-mono text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="c-body" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-muted">Comment</label>
                        <textarea id="c-body" name="body" required rows="4" placeholder="Share your thoughts…"
                                  class="w-full resize-y rounded-lg border border-line bg-paper px-4 py-2.5 text-sm text-ink placeholder:text-faint focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">{{ old('body') }}</textarea>
                        @error('body')<p class="mt-1 font-mono text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary pressable">Post comment <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    <p class="font-mono text-xs text-faint">Comments are reviewed before appearing.</p>
                </form>
            </details>
        </section>

        {{-- Related --}}
        @if($related->isNotEmpty())
            <section class="mt-16">
                <h2 class="font-mono text-sm uppercase tracking-wider text-ink">Related reading</h2>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    @foreach($related as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="card pressable group flex flex-col p-5 hover:border-muted">
                            <span class="font-mono text-[0.68rem] uppercase tracking-wider text-faint">{{ $rel->published_at?->format('M Y') }} · {{ $rel->readingTime() }} min</span>
                            <p class="mt-2 font-medium leading-snug text-ink transition-colors group-hover:text-accent line-clamp-3">{{ $rel->title }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Newsletter --}}
        <div class="mt-16">
            <x-newsletter id="subscribe" />
        </div>
    </div>

    {{-- ── Share rail (desktop) ─────────────────────────────────────────────── --}}
    <aside class="hidden xl:block w-14 shrink-0">
        <div class="sticky top-28 flex flex-col items-center gap-2">
            <button type="button" data-like data-like-url="{{ route('blog.posts.like', $post) }}" data-liked="{{ $userLiked ? '1' : '0' }}"
                    class="like-btn grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-muted transition-colors hover:text-ink pressable {{ $userLiked ? 'is-liked' : '' }}"
                    aria-label="Like this post">
                <x-icon name="heart" data-like-icon class="w-4 h-4 {{ $userLiked ? 'fill-current' : '' }}" />
            </button>
            <span data-like-count class="font-mono text-xs text-faint">{{ $post->likes_count }}</span>

            <div class="my-1 h-px w-6 bg-line"></div>

            <button type="button" data-copy-link="{{ url()->current() }}"
                    class="grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-muted transition-colors hover:text-ink pressable"
                    aria-label="Copy link">
                <x-icon name="link" class="w-4 h-4" />
            </button>
            <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener"
               class="grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-muted transition-colors hover:text-ink pressable"
               aria-label="Share on Twitter">
                <x-icon name="twitter" class="w-3.5 h-3.5" />
            </a>
            <a href="#comments" class="grid h-11 w-11 place-items-center rounded-full border border-line bg-surface text-muted transition-colors hover:text-ink pressable" aria-label="Jump to comments">
                <x-icon name="comment" class="w-4 h-4" />
            </a>
        </div>
    </aside>
</div>
@endsection

@if($errors->any())
@push('scripts')
<script>document.getElementById('comments')?.scrollIntoView({ behavior: 'smooth' });</script>
@endpush
@endif
