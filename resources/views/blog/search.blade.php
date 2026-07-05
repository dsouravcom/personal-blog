@extends('layouts.app')

@section('title', ($q ? '“' . $q . '” — Search' : 'Search') . ' — ' . config('site.name'))
@section('description', 'Search the writing of ' . config('site.author.name') . '.')
@section('robots', 'noindex, follow')

@section('content')

    <section class="max-w-2xl">
        <h1 class="reveal text-3xl sm:text-4xl font-semibold tracking-tight text-ink">Search</h1>
        <form action="{{ route('blog.search') }}" method="GET" class="reveal mt-6 flex items-center gap-2" style="animation-delay: 60ms">
            <div class="relative flex-1">
                <x-icon name="search" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-faint" />
                <input type="text" name="q" value="{{ $q }}" autofocus placeholder="Search posts…"
                       class="w-full rounded-xl border border-line bg-surface py-3 pl-10 pr-4 text-sm text-ink placeholder:text-faint focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20">
            </div>
            <button type="submit" class="btn btn-primary pressable">Search</button>
        </form>
    </section>

    <section class="mt-10">
        @if($q === '')
            <p class="text-muted">Type a query above, or press <kbd class="rounded border border-line bg-surface-2 px-1.5 py-0.5 font-mono text-xs">⌘K</kbd> anywhere to search.</p>
        @elseif($posts->isEmpty())
            <div class="rounded-2xl border border-dashed border-line py-16 text-center">
                <p class="text-muted">No results for “<span class="text-ink">{{ $q }}</span>”.</p>
                <a href="{{ route('blog.index') }}" class="mt-4 inline-flex btn btn-ghost pressable"><x-icon name="arrow-left" class="w-4 h-4" /> All writing</a>
            </div>
        @else
            <p class="mb-4 font-mono text-xs uppercase tracking-wider text-faint">
                {{ $posts->total() }} {{ Str::plural('result', $posts->total()) }} for “{{ $q }}”
            </p>
            <div class="divide-y divide-line border-t border-line">
                @foreach ($posts as $post)
                    <x-post-card :post="$post" :index="$loop->index" />
                @endforeach
            </div>
            <div class="mt-10">
                <x-pager :paginator="$posts" />
            </div>
        @endif
    </section>

@endsection
