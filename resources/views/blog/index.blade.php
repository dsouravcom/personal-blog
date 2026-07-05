@extends('layouts.app')

@section('title', config('site.title'))
@section('description', config('site.description'))

@push('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('site.title') }}">
    <meta property="og:description" content="{{ config('site.description') }}">
    <meta property="og:url" content="{{ route('blog.index') }}">
    <meta property="og:image" content="{{ url(config('site.og_image')) }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ url(config('site.og_image')) }}">

    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'Blog',
        '@id' => route('blog.index') . '#blog',
        'name' => config('site.name'),
        'description' => config('site.description'),
        'url' => route('blog.index'),
        'inLanguage' => 'en',
        'author' => ['@id' => url('/') . '#person'],
        'blogPost' => $posts->take(10)->map(fn ($p) => [
            '@type' => 'BlogPosting',
            'headline' => $p->title,
            'url' => route('blog.show', $p->slug),
            'datePublished' => $p->published_at?->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => config('site.author.name')],
        ])->all(),
    ]" />
@endpush

@section('content')

    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <section class="max-w-3xl">
        <p class="reveal font-mono text-xs uppercase tracking-[0.2em] text-accent">
            Personal blog
        </p>
        <h1 class="reveal mt-4 text-4xl sm:text-5xl md:text-6xl font-semibold tracking-tight leading-[1.05] text-ink"
            style="animation-delay: 60ms">
            I build software &amp;
            <span class="italic font-serif font-normal text-ink">write</span>
            about the craft.
        </h1>
        <p class="reveal mt-6 max-w-xl text-lg leading-relaxed text-muted" style="animation-delay: 120ms">
            I’m {{ config('site.author.name') }}, a {{ strtolower(config('site.author.job_title')) }}.
            {{ config('site.author.bio') }}
        </p>

        <div class="reveal mt-8 flex flex-wrap items-center gap-3" style="animation-delay: 180ms">
            <button type="button" data-command-open class="btn btn-primary pressable">
                <x-icon name="search" class="w-4 h-4" /> Search writing
                <kbd class="ml-1 rounded border border-white/20 px-1.5 py-0.5 font-mono text-[0.6rem] opacity-80">⌘K</kbd>
            </button>
            <a href="{{ config('site.socials.github') }}" target="_blank" rel="noopener" class="btn btn-ghost pressable">
                <x-icon name="github" class="w-4 h-4" /> GitHub
            </a>
            <a href="{{ url('/feed') }}" class="btn btn-ghost pressable">
                <x-icon name="rss" class="w-4 h-4" /> RSS
            </a>
        </div>
    </section>

    {{-- ── Featured ─────────────────────────────────────────────────────────── --}}
    @if($featured)
        <section class="reveal mt-16 sm:mt-20" style="animation-delay: 120ms" aria-label="Featured post">
            <a href="{{ route('blog.show', $featured->slug) }}"
               class="card group block overflow-hidden hover:border-muted hover:shadow-[0_20px_60px_-30px_rgba(0,0,0,0.35)] focus:outline-none">
                <div class="grid md:grid-cols-2">
                    @if($featured->cover_image)
                        <div class="relative order-first overflow-hidden md:order-last aspect-video md:aspect-auto md:min-h-[22rem]">
                            <img src="{{ $featured->cover_image }}"
                                 alt="{{ $featured->cover_image_alt ?? $featured->title }}"
                                 class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                        </div>
                    @endif
                    <div class="flex flex-col justify-center p-7 sm:p-10">
                        <div class="mb-4 inline-flex w-fit items-center gap-2 rounded-full border border-accent/40 bg-accent-soft px-3 py-1 font-mono text-[0.7rem] uppercase tracking-wider text-accent">
                            <x-icon name="sparkles" class="w-3.5 h-3.5" /> Latest
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight leading-tight text-ink transition-colors group-hover:text-accent">
                            {{ $featured->title }}
                        </h2>
                        @if($featured->excerpt)
                            <p class="mt-3 leading-relaxed text-muted line-clamp-3">{{ $featured->excerpt }}</p>
                        @endif
                        <div class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-1 font-mono text-[0.72rem] uppercase tracking-wider text-faint">
                            <time datetime="{{ $featured->published_at?->toDateString() }}">{{ $featured->published_at?->format('M j, Y') }}</time>
                            <span aria-hidden="true">/</span>
                            <span>{{ $featured->readingTime() }} min read</span>
                            <span aria-hidden="true">/</span>
                            <span class="inline-flex items-center gap-1"><x-icon name="eye" class="w-3 h-3" /> {{ number_format($featured->views_count) }}</span>
                        </div>
                        <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-medium text-ink transition-transform duration-300 group-hover:translate-x-1">
                            Read article <x-icon name="arrow-right" class="w-4 h-4" />
                        </span>
                    </div>
                </div>
            </a>
        </section>
    @endif

    {{-- ── Tag filter strip ─────────────────────────────────────────────────── --}}
    @if($tags->isNotEmpty())
        <section class="mt-14 flex flex-wrap items-center gap-2" aria-label="Topics">
            <span class="mr-1 font-mono text-[0.7rem] uppercase tracking-wider text-faint">Topics</span>
            @foreach($tags as $tag)
                <a href="{{ route('blog.tag', $tag->slug) }}" class="tag pressable">
                    <span class="text-faint">#</span>{{ $tag->name }}
                    <span class="text-faint">{{ $tag->posts_count }}</span>
                </a>
            @endforeach
        </section>
    @endif

    {{-- ── Post list ────────────────────────────────────────────────────────── --}}
    <section class="mt-14" aria-label="All writing">
        <div class="flex items-baseline justify-between border-b border-line pb-4">
            <h2 class="font-mono text-sm uppercase tracking-wider text-ink">All writing</h2>
            <span class="font-mono text-xs text-faint">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
        </div>

        @if ($posts->isEmpty())
            <div class="mt-10 rounded-2xl border border-dashed border-line py-20 text-center">
                <p class="text-muted">No posts published yet.</p>
                <p class="mt-1 font-mono text-sm text-faint">Check back soon.</p>
            </div>
        @else
            <div class="mt-2 divide-y divide-line">
                @php $i = 0; @endphp
                @foreach ($posts as $post)
                    @continue($featured && $post->id === $featured->id)
                    <x-post-card :post="$post" :index="$i++" />
                @endforeach
            </div>

            <div class="mt-10">
                <x-pager :paginator="$posts" />
            </div>
        @endif
    </section>

    {{-- ── Newsletter ───────────────────────────────────────────────────────── --}}
    <div class="mt-20">
        <x-newsletter />
    </div>

@endsection
