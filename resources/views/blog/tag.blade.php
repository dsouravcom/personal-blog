@extends('layouts.app')

@section('title', '#' . $tag->name . ' — ' . config('site.name'))
@section('description', 'Articles tagged “' . $tag->name . '” — ' . $posts->total() . ' ' . Str::plural('post', $posts->total()) . ' on software engineering by ' . config('site.author.name') . '.')

@push('head')
    <meta property="og:type" content="website">
    <meta property="og:title" content="#{{ $tag->name }} — {{ config('site.name') }}">
    <meta property="og:url" content="{{ url()->current() }}">

    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => '#' . $tag->name,
        'url' => url()->current(),
        'isPartOf' => ['@id' => url('/') . '#website'],
        'about' => $tag->name,
    ]" />
    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => $tag->name, 'item' => url()->current()],
        ],
    ]" />
@endpush

@section('content')

    {{-- Header --}}
    <section class="max-w-2xl">
        <nav class="reveal flex items-center gap-2 font-mono text-xs text-faint" aria-label="Breadcrumb">
            <a href="{{ route('blog.index') }}" class="transition-colors hover:text-ink">Home</a>
            <x-icon name="chevron-right" class="w-3 h-3" />
            <span class="text-muted">Topics</span>
        </nav>
        <h1 class="reveal mt-4 text-4xl sm:text-5xl font-semibold tracking-tight text-ink" style="animation-delay: 60ms">
            <span class="text-accent">#</span>{{ $tag->name }}
        </h1>
        <p class="reveal mt-3 text-muted" style="animation-delay: 120ms">
            {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} tagged with this topic.
        </p>
    </section>

    {{-- Posts --}}
    <section class="mt-12">
        @if ($posts->isEmpty())
            <div class="rounded-2xl border border-dashed border-line py-20 text-center">
                <p class="text-muted">Nothing here yet.</p>
                <a href="{{ route('blog.index') }}" class="mt-4 inline-flex btn btn-ghost pressable">
                    <x-icon name="arrow-left" class="w-4 h-4" /> Back to all writing
                </a>
            </div>
        @else
            <div class="divide-y divide-line border-t border-line">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" :index="$loop->index" :activeTag="$tag" />
                @endforeach
            </div>
            <div class="mt-10">
                <x-pager :paginator="$posts" />
            </div>
        @endif
    </section>

@endsection
