@extends('layouts.app')

@section('title', 'Sourav Dutta — Software Engineer & Writer')
@section('description', 'Join me as I explore software engineering, system design, and the art of programming. Articles, tutorials, and deep dives.')

@section('meta')
    <meta name="keywords" content="javascript, software engineering, web development, blog, sourav dutta">
    
    {{-- Schema.org --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "WebSite",
        "url": "{{ route('blog.index') }}",
        "name": "Sourav Dutta",
        "author": {
            "@@type": "Person",
            "name": "Sourav Dutta"
        },
        "description": "Personal blog about software engineering and web development.",
        "potentialAction": {
            "@@type": "SearchAction",
            "target": "{{ route('blog.index') }}?q={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
@endsection

@section('content')

    {{-- Hero Section (Vim / Editor Style) --}}
    <section class="mb-6 pt-8 pb-12 sm:pt-12 sm:pb-16 animate-fade-in border-b border-dashed border-zinc-200 dark:border-zinc-800 font-mono">
        <div class="flex flex-col gap-6">
            
            {{-- Editor Tab --}}
            <div class="flex border-b border-zinc-200 dark:border-zinc-800 w-fit">
                <div class="px-6 py-2 bg-zinc-100 dark:bg-zinc-900 border-t-2 border-zinc-900 dark:border-zinc-500 text-sm font-medium text-zinc-900 dark:text-zinc-200 flex items-center gap-3">
                    <svg class="w-4 h-4 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                    README.md <span class="text-zinc-400 text-xs ml-2">M+</span>
                </div>
            </div>

            {{-- Editor Content (Line Numbers + Text) --}}
            <div class="flex gap-4 sm:gap-6 text-lg sm:text-xl md:text-2xl pt-4 font-medium leading-relaxed">
                {{-- Line Numbers --}}
                <div class="flex flex-col text-right text-zinc-300 dark:text-zinc-700 select-none text-base sm:text-lg pt-1">
                    <span>1</span>
                    <span>2</span>
                    <span>3</span>
                    <span>4</span>
                    <span>5</span>
                </div>

                {{-- Code Content --}}
                <div class="flex flex-col text-zinc-800 dark:text-zinc-300">
                    <h1 class="font-bold text-zinc-900 dark:text-white">
                        <span class="text-blue-600 dark:text-blue-400">#</span> Sourav Dutta
                    </h1>
                    <div class="min-h-[1em]"></div>
                    <p>
                        <span class="text-purple-600 dark:text-purple-400">></span> Building software & trying out new tech.
                    </p>
                    <p>
                        I write practical <span class="text-yellow-600 dark:text-yellow-500">`tutorials`</span> and 
                        <span class="text-green-600 dark:text-green-500">`notes`</span> for my future self, 
                        so I never have to solve the same <span class="text-red-500 dark:text-red-400">`confusion`</span> twice.
                    </p>
                    <div class="flex items-center gap-2">
                         <span class="text-zinc-400 text-base">// scroll down to read_more();</span>
                         <span class="w-2.5 h-5 bg-zinc-400/50 animate-pulse"></span>
                    </div>
                </div>
            </div>

            {{-- Status Bar --}}
            <div class="mt-8 flex items-center justify-between text-[10px] sm:text-xs text-zinc-400 dark:text-zinc-500 bg-zinc-50 dark:bg-zinc-900/50 p-2 rounded border border-zinc-100 dark:border-zinc-800/50 select-none font-mono">
                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="font-bold text-zinc-600 dark:text-zinc-300 bg-zinc-200 dark:bg-zinc-800 px-1.5 py-0.5 rounded">NORMAL</span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        master*
                    </span>
                    <span class="hidden xs:inline">markdown</span>
                    <span class="hidden sm:inline">utf-8</span>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <span class="hidden sm:inline">buffers: 1</span>
                    <span class="whitespace-nowrap">ln 5, col 42</span>
                    <span class="hidden sm:inline">100%</span>
                </div>
            </div>

        </div>
    </section>

    {{-- Posts List (File Directory Style) --}}
    <section class="pb-6 animate-fade-in delay-100 font-mono">
        <div class="mb-8 flex items-center justify-between text-xs uppercase tracking-widest text-zinc-400 dark:text-zinc-500 border-b border-zinc-100 dark:border-zinc-800 pb-4 select-none">
            <span>Listing directory contents...</span>
            <span>{{ $posts->total() }} files found</span>
        </div>

        @if ($posts->isEmpty())
            <div class="py-20 text-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-lg bg-zinc-50/50 dark:bg-zinc-900/20">
                <p class="text-zinc-500 dark:text-zinc-400 mb-2">Directory is empty.</p>
                <p class="text-sm text-zinc-400 dark:text-zinc-600">$ touch new_post.md</p>
            </div>
        @else
            <div class="flex flex-col divide-y divide-zinc-100 dark:divide-zinc-800/50 border-t border-b border-zinc-100 dark:border-zinc-800/50">
                @foreach ($posts as $post)
                    <article class="group relative flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-8 py-6 hover:bg-zinc-50 dark:hover:bg-zinc-900/30 transition-colors px-2 -mx-2 rounded-lg">
                        
                        {{-- Permission / Date Column --}}
                        <div class="flex items-center gap-4 sm:w-48 shrink-0 text-xs text-zinc-400 dark:text-zinc-500 font-mono select-none pt-1">
                            <span class="hidden sm:inline-block opacity-50">-rw-r--r--</span>
                            <time datetime="{{ $post->published_at?->format('Y-m-d') }}">
                                {{ $post->published_at?->format('M d H:i') }}
                            </time>
                        </div>

                        {{-- File Content --}}
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
                                <div class="mt-2 flex flex-wrap gap-1.5 relative z-10">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('blog.tag', $tag->slug) }}"
                                           class="inline-block px-2 py-0.5 rounded text-[11px] font-mono bg-zinc-100 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-500 border border-zinc-200 dark:border-zinc-800 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            # {{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="hidden sm:flex flex-col items-end gap-1 w-28 shrink-0 text-right text-xs text-zinc-400 dark:text-zinc-500 font-mono pt-1">
                            <span>{{ number_format($post->views_count) }} views</span>
                            <span>{{ $post->likes_count }} ♥</span>
                            <span>{{ $post->comments_count }} cmts</span>
                        </div>

                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($posts->hasPages())
                <nav class="relative z-0 flex justify-between items-center w-full pt-6 border-t border-dashed border-zinc-300 dark:border-zinc-700 font-mono text-sm selection:bg-zinc-900 selection:text-white dark:selection:bg-white dark:selection:text-zinc-900">
                    
                    {{-- Previous Page Link --}}
                    <div class="flex-1 flex justify-start">
                        @if ($posts->onFirstPage())
                            <span class="group flex flex-col items-start opacity-30 cursor-not-allowed select-none" aria-disabled="true">
                                <span class="text-[0.65rem] uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors duration-300">
                                    // History
                                </span>
                                <span class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold text-lg md:text-xl transform transition-transform duration-300 group-hover:-translate-x-2">
                                    <span>←</span> <span>return back;</span>
                                </span>
                            </span>
                        @else
                            <a href="{{ $posts->previousPageUrl() }}" rel="prev" class="group flex flex-col items-start">
                                <span class="text-[0.65rem] uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors duration-300">
                                    // History
                                </span>
                                <span class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold text-lg md:text-xl transform transition-transform duration-300 group-hover:-translate-x-2">
                                    <span>←</span> <span>return back;</span>
                                </span>
                            </a>
                        @endif
                    </div>

                    {{-- Current Page Indicator --}}
                    <div class="hidden md:flex flex-col items-center justify-center">
                         <div class="relative w-24 h-24 flex items-center justify-center rounded-full border border-zinc-200 dark:border-zinc-800 transition-all duration-700 hover:scale-110 hover:border-zinc-900 dark:hover:border-white">
                            <svg class="absolute inset-0 w-full h-full -rotate-90 stroke-zinc-900 dark:stroke-white transition-all duration-1000 ease-out" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="48" fill="transparent" stroke-width="1" stroke-dasharray="301.59" stroke-dashoffset="{{ 301.59 - (301.59 * $posts->currentPage() / $posts->lastPage()) }}" class="opacity-100" />
                            </svg>
                            <div class="flex flex-col items-center leading-none">
                                <span class="text-2xl font-black text-zinc-900 dark:text-white">{{ $posts->currentPage() }}</span>
                                <span class="h-px w-6 bg-zinc-300 dark:bg-zinc-700 my-1"></span>
                                <span class="text-xs text-zinc-400 dark:text-zinc-500">{{ $posts->lastPage() }}</span>
                            </div>
                         </div>
                    </div>

                    {{-- Next Page Link --}}
                    <div class="flex-1 flex justify-end">
                        @if ($posts->hasMorePages())
                            <a href="{{ $posts->nextPageUrl() }}" rel="next" class="group flex flex-col items-end text-right">
                                <span class="text-[0.65rem] uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1 group-hover:text-zinc-900 dark:group-hover:text-white transition-colors duration-300">
                                    // Explore
                                </span>
                                <span class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold text-lg md:text-xl transform transition-transform duration-300 group-hover:translate-x-2">
                                    <span>next.page();</span> <span>→</span>
                                </span>
                            </a>
                        @else
                            <span class="group flex flex-col items-end text-right opacity-30 cursor-not-allowed select-none" aria-disabled="true">
                                <span class="text-[0.65rem] uppercase tracking-widest text-zinc-400 dark:text-zinc-500 mb-1">
                                    // Explore
                                </span>
                                <span class="flex items-center gap-2 text-zinc-900 dark:text-white font-bold text-lg md:text-xl">
                                    <span>end_of_file;</span> <span>■</span>
                                </span>
                            </span>
                        @endif
                    </div>
                </nav>
            @endif
        @endif
    </section>

    {{-- Newsletter Section (Minimal Terminal Input Style) --}}
    <section id="subscribe" class="py-12 border-t border-dashed border-zinc-200 dark:border-zinc-800 animate-fade-in delay-200 font-mono text-sm">
        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded border border-dashed border-zinc-300 dark:border-zinc-700 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 md:gap-12">
            
            <div class="text-center md:text-left flex-1">
                <div class="flex items-center justify-center md:justify-start gap-2 text-zinc-800 dark:text-zinc-200 font-bold mb-1">
                     <span class="text-green-500 text-xs">➜</span>
                     <span>./subscribe.sh</span>
                </div>
                <p class="text-zinc-500 dark:text-zinc-500 text-xs">
                    <span class="text-blue-500">#</span> Join the mailing list. <span class="text-zinc-400 dark:text-zinc-600">// No spam. All signals will be caught.</span>
                </p>
            </div>

            <div class="w-full md:w-auto flex-1 max-w-md">
                @if (session('subscribed'))
                    <div class="bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 px-4 py-2 rounded border border-green-200 dark:border-green-800 font-mono text-xs text-center border-dashed">
                        <span class="font-bold">SUCCESS:</span> Mailing list updated.
                    </div>
                @else
                    <form action="{{ route('blog.subscribe') }}" method="POST" class="flex items-center gap-2 w-full">
                        @csrf
                        {{-- Honeypot: hidden field that bots fill but humans don't --}}
                        <input type="text" name="_hp_email" style="display:none !important;" tabindex="-1" autocomplete="off">
                        <div class="relative flex-1">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs">$</span>
                            <input
                                type="email"
                                name="email"
                                placeholder="enter_email..."
                                required
                                class="w-full pl-6 pr-3 py-2 text-xs rounded bg-white dark:bg-black border border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-gray-300 placeholder-zinc-400 focus:outline-none focus:border-zinc-500 transition-colors"
                            >
                        </div>
                        <button type="submit" class="px-4 py-2 bg-zinc-800 dark:bg-zinc-700 text-white rounded text-xs font-bold hover:bg-zinc-700 dark:hover:bg-zinc-600 transition-colors shrink-0">
                            [EXEC]
                        </button>
                    </form>
                    @error('email')
                        <p class="text-red-500 text-[10px] mt-1 font-mono text-center md:text-left md:pl-1">Error: {{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>
    </section>

@endsection
