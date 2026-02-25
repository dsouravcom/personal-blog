@extends('layouts.app')

@section('title', '#' . $tag->name . ' — Sourav Dutta')

@section('meta')
    <meta name="description" content="All posts tagged with #{{ $tag->name }} on Sourav Dutta's blog.">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

    {{-- Tag Header --}}
    <section class="py-12 sm:py-16 animate-fade-in border-b border-dashed border-zinc-200 dark:border-zinc-800 font-mono">
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-2 text-sm text-zinc-400 dark:text-zinc-500">
                <a href="{{ route('blog.index') }}" class="hover:text-zinc-900 dark:hover:text-white transition-colors">~/blog</a>
                <span>/</span>
                <span>tags</span>
                <span>/</span>
                <span class="text-zinc-700 dark:text-zinc-300">#{{ $tag->name }}</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-bold text-zinc-900 dark:text-white tracking-tight">
                <span class="text-blue-500 dark:text-blue-400">#</span>{{ $tag->name }}
            </h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-sm">
                // {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} tagged with this
            </p>
        </div>
    </section>

    {{-- Posts List --}}
    <section class="py-12 sm:py-16 animate-fade-in delay-100 font-mono">
        <div class="mb-8 flex items-center justify-between text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 border-b border-zinc-100 dark:border-zinc-800 pb-4 select-none">
            <span>Listing directory contents... <span class="text-blue-400">tag=</span><span class="text-green-400">'{{ $tag->slug }}'</span></span>
            <span>{{ $posts->total() }} files found</span>
        </div>

        @if ($posts->isEmpty())
            <div class="py-20 text-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-lg bg-zinc-50/50 dark:bg-zinc-900/20">
                <p class="text-zinc-500 dark:text-zinc-400 mb-2">No posts found for this tag.</p>
                <p class="text-sm text-zinc-400 dark:text-zinc-600">$ find . -tag {{ $tag->name }} // returns 0</p>
            </div>
        @else
            <div class="flex flex-col divide-y divide-zinc-100 dark:divide-zinc-800/50 border-t border-b border-zinc-100 dark:border-zinc-800/50">
                @foreach ($posts as $post)
                    <article class="group relative flex flex-col sm:flex-row sm:items-baseline gap-2 sm:gap-8 py-6 hover:bg-zinc-50 dark:hover:bg-zinc-900/30 transition-colors px-2 -mx-2 rounded-lg">

                        {{-- Date --}}
                        <div class="flex items-center gap-4 sm:w-48 shrink-0 text-xs text-zinc-400 dark:text-zinc-500 font-mono select-none">
                            <span class="hidden sm:inline-block opacity-50">-rw-r--r--</span>
                            <time datetime="{{ $post->published_at?->format('Y-m-d') }}">
                                {{ $post->published_at?->format('M d H:i') }}
                            </time>
                        </div>

                        {{-- Title & Excerpt --}}
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg md:text-xl font-bold text-zinc-900 dark:text-zinc-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                <a href="{{ route('blog.show', $post->slug) }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    <span class="text-blue-500 dark:text-blue-400 mr-2">./</span>{{ $post->title }}<span class="text-zinc-400 dark:text-zinc-600">.md</span>
                                </a>
                            </h2>
                            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1 group-hover:text-zinc-600 dark:group-hover:text-zinc-300">
                                // {{ $post->excerpt }}
                            </p>
                            {{-- Tags --}}
                            @if($post->tags->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-1.5" onclick="event.stopPropagation()">
                                    @foreach($post->tags as $t)
                                        <a href="{{ route('blog.tag', $t->slug) }}"
                                           class="inline-block px-2 py-0.5 rounded text-[11px] font-mono {{ $t->id === $tag->id ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-700' : 'bg-zinc-100 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-500 border border-zinc-200 dark:border-zinc-800' }} hover:border-blue-400 transition-colors">
                                            # {{ $t->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Counts --}}
                        <div class="hidden sm:flex flex-col items-end gap-1 w-24 text-right text-xs text-zinc-400 dark:text-zinc-500 font-mono shrink-0">
                            <span>{{ $post->views_count }} views</span>
                            <span>{{ $post->likes_count }} ♥</span>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <nav class="relative z-0 flex justify-between items-center w-full mt-16 pt-8 border-t border-dashed border-zinc-300 dark:border-zinc-700 font-mono text-sm">
                    <div class="flex-1 flex justify-start">
                        @if ($posts->onFirstPage())
                            <span class="opacity-30 cursor-not-allowed select-none flex items-center gap-2 text-zinc-900 dark:text-white font-bold">← return back;</span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" rel="prev" class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold hover:text-blue-600 dark:hover:text-blue-400 transition-colors">← return back;</a>
                        @endif
                    </div>
                    <span class="text-zinc-400 text-xs">{{ $posts->currentPage() }} / {{ $posts->lastPage() }}</span>
                    <div class="flex-1 flex justify-end">
                        @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" rel="next" class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold hover:text-blue-600 dark:hover:text-blue-400 transition-colors">next.page(); →</a>
                        @else
                            <span class="opacity-30 cursor-not-allowed select-none flex items-center gap-2 text-zinc-900 dark:text-white font-bold">end_of_file; ■</span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif
    </section>

@endsection
