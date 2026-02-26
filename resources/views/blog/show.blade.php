@extends('layouts.app', ['maxWidth' => 'max-w-6xl', 'py' => 'py-6'])

@section('title', ($post->meta_title ?: $post->title) . ' — Sourav Dutta')

@section('description', $post->meta_description ?? $post->excerpt ?? 'Read "' . $post->title . '" and other insightful articles on technology, design, and software engineering by Sourav Dutta.')

@section('meta')
    <meta name="keywords" content="{{ $post->meta_keywords }}">
    @if($post->canonical_url)
        <link rel="canonical" href="{{ $post->canonical_url }}">
    @else
        <link rel="canonical" href="{{ url()->current() }}">
    @endif
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
    <meta property="og:description" content="{{ $post->og_description ?? $post->meta_description ?? $post->excerpt }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url()->current() }}">
    @php
        $ogImage    = $post->og_image ?: $post->cover_image ?: asset('images/og-default.png');
        $ogImageAlt = $post->cover_image_alt ?? $post->title;
    @endphp
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:alt" content="{{ $ogImageAlt }}">
    @endif
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->og_title ?? $post->meta_title ?? $post->title }}">
    <meta name="twitter:description" content="{{ $post->og_description ?? $post->meta_description ?? $post->excerpt ?? '' }}">
    {{-- twitter:image — these are full public R2 URLs, no asset() wrapper needed --}}
    @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
        <meta name="twitter:image:alt" content="{{ $ogImageAlt }}">
    @endif
    {{-- Article Schema --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "BlogPosting",
        "headline": "{{ addslashes($post->meta_title ?: $post->title) }}",
        "description": "{{ addslashes($post->meta_description ?? $post->excerpt ?? '') }}",
        "datePublished": "{{ $post->published_at?->toIso8601String() }}",
        "dateModified": "{{ $post->updated_at->toIso8601String() }}",
        "url": "{{ url()->current() }}",
        "author": { "@@type": "Person", "name": "Sourav Dutta", "url": "https://sourav.dev" }@if($ogImage),
        "image": "{{ $ogImage }}"@endif
    }
    </script>
@endsection

@section('content')

<style>
    /* Enhanced Code Block Scrollbar */
    .prose pre {
        overflow-x: auto !important;
        white-space: pre !important; 
        max-width: 100% !important;
        padding: 1rem !important;
        border-radius: 0.5rem;
        /* Scrollbar styles */
        scrollbar-width: thin;
        scrollbar-color: #52525b #27272a;
    }
    /* Webkit Scrollbar */
    .prose pre::-webkit-scrollbar {
        height: 6px;
        background-color: transparent;
    }
    .prose pre::-webkit-scrollbar-track {
        background-color: rgba(255, 255, 255, 0.05);
        border-radius: 0 0 0.5rem 0.5rem;
    }
    .prose pre::-webkit-scrollbar-thumb {
        background-color: #52525b; /* zinc-600 */
        border-radius: 3px;
    }
    .prose pre::-webkit-scrollbar-thumb:hover {
        background-color: #71717a; /* zinc-500 */
    }
    /* Ensure code text doesn't wrap awkwardly */
    .prose pre code {
        display: inline-block;
        min-width: 100%;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 0.9em;
    }
</style>

<article class="w-full max-w-5xl mx-auto pb-12 md:pb-20 animate-fade-in">

    {{-- Featured Image (Top Placement) --}}
    @if($post->cover_image)
        <figure class="mb-8 rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <img src="{{ $post->cover_image }}"
                 alt="{{ $post->cover_image_alt ?? $post->title }}"
                 class="w-full h-auto max-h-150 object-cover hover:scale-[1.02] transition-transform duration-700 ease-out">
            @if($post->cover_image_caption)
                <figcaption class="text-center text-xs text-zinc-400 py-3 bg-zinc-50 dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 font-mono">
                    // {{ $post->cover_image_caption }}
                </figcaption>
            @endif
        </figure>
    @endif

    {{-- Back link --}}
    <div class="mb-8 font-mono">
        <a href="{{ route('blog.index') }}" class="group inline-flex items-center gap-2 text-sm text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
            <span class="text-green-500">➜</span> cd ..
        </a>
    </div>

    {{-- Post Header --}}
    <header class="mb-10 font-mono">
        {{-- File Metadata Row --}}
        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-zinc-400 dark:text-zinc-500 mb-4 border-b border-zinc-100 dark:border-zinc-800 pb-4">
            <time datetime="{{ $post->published_at?->format('Y-m-d') }}">{{ $post->published_at?->format('M d, Y') }}</time>
            <span>|</span>
            <span>{{ $post->readingTime() }} min read</span>
            <span>|</span>
            {{-- View Count --}}
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ number_format($viewCount) }} views
            </span>
            <span>|</span>
            {{-- Like Count --}}
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span id="like-count">{{ $post->likes_count }}</span> likes
            </span>
            <span>|</span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                {{ $post->comments_count }} comments
            </span>
        </div>

        {{-- Title --}}
        <h1 class="text-3xl md:text-5xl font-bold tracking-tight text-zinc-900 dark:text-white leading-tight mb-6">
            <span class="text-blue-500 dark:text-blue-400">./</span>{{ $post->title }}<span class="text-zinc-400 dark:text-zinc-600">.md</span>
        </h1>

        {{-- Excerpt --}}
        @if ($post->excerpt)
            <div class="text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed border-l-2 border-zinc-200 dark:border-zinc-800 pl-4 italic mb-6">
                <span class="text-zinc-400 dark:text-zinc-600 not-italic select-none">// </span>{{ $post->excerpt }}
            </div>
        @endif

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="inline-flex items-center px-3 py-1 rounded-full text-xs font-mono bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 hover:border-blue-400 dark:hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-all">
                        # {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </header>

    <div class="w-full border-t border-dashed border-zinc-200 dark:border-zinc-800 mb-12"></div>

    {{-- Post Body --}}
    <div class="prose prose-lg dark:prose-invert max-w-none 
        font-mono leading-relaxed
        prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-zinc-900 dark:prose-headings:text-white
        prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-a:underline prose-a:decoration-dotted prose-a:underline-offset-4 hover:prose-a:decoration-solid
        prose-img:rounded prose-img:border prose-img:border-zinc-200 dark:prose-img:border-zinc-800
        prose-pre:bg-zinc-900 dark:prose-pre:bg-black prose-pre:border prose-pre:border-zinc-800
        prose-code:text-purple-600 dark:prose-code:text-purple-400 prose-code:bg-zinc-100 dark:prose-code:bg-zinc-900 prose-code:border prose-code:border-zinc-200 dark:prose-code:border-zinc-800 prose-code:rounded prose-code:px-1 prose-code:py-0.5 prose-code:before:content-none prose-code:after:content-none
    ">
        {!! $post->content !!}
    </div>

    <div class="w-full border-t border-zinc-100 dark:border-zinc-800 mt-16 mb-8"></div>

    {{-- Like & Tags Footer --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 my-8">
        {{-- Like Button --}}
        <button id="like-btn"
                data-post-id="{{ $post->id }}"
                data-liked="{{ $userLiked ? '1' : '0' }}"
                onclick="toggleLike(this)"
                class="group flex items-center gap-3 px-6 py-3 rounded-lg border-2 font-mono text-sm font-bold transition-all {{ $userLiked ? 'border-red-400 bg-red-50 dark:bg-red-900/20 text-red-500' : 'border-zinc-200 dark:border-zinc-700 text-zinc-500 dark:text-zinc-400 hover:border-red-400 hover:text-red-500 dark:hover:border-red-500 dark:hover:text-red-400' }}">
            <svg id="like-icon" class="w-5 h-5 transition-all {{ $userLiked ? 'fill-red-500 text-red-500' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span id="like-label">{{ $userLiked ? 'Liked ✓' : 'Like this post' }}</span>
            <span class="text-xs opacity-60">(<span id="like-count-btn">{{ $post->likes_count }}</span>)</span>
        </button>

        {{-- Tags --}}
        @if($post->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 justify-center md:justify-end">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="px-3 py-1 rounded-full text-xs font-mono bg-zinc-100 dark:bg-zinc-900 text-zinc-500 dark:text-zinc-500 border border-zinc-200 dark:border-zinc-800 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        # {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="w-full border-t border-dashed border-zinc-200 dark:border-zinc-800 my-8"></div>

    {{-- Comments Section --}}
    <section id="comments" class="my-12 font-mono">
        <h2 class="text-xl font-bold text-zinc-900 dark:text-white mb-8 flex items-center gap-2">
            <span class="text-green-500">$</span> comments.log
            <span class="text-sm text-zinc-400 font-normal ml-2">// {{ $post->comments_count }} Comment{{ $post->comments_count !== 1 ? 's' : '' }}</span>
        </h2>

        {{-- Success Message --}}
        @if(session('comment_submitted'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded text-sm">
                {{ session('comment_submitted') }}
            </div>
        @endif

        {{-- Existing Comments --}}
        @if($post->approvedComments->isEmpty())
            <div class="text-zinc-400 dark:text-zinc-600 text-sm py-6 border border-dashed border-zinc-200 dark:border-zinc-800 rounded text-center mb-8">
                // No comments yet. Be the first to comment.
            </div>
        @else
            <div class="space-y-6 mb-10">
                @foreach($post->approvedComments as $comment)
                    <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-100 dark:border-zinc-800 p-5">
                        <div class="flex items-center justify-between mb-3 gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-300 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($comment->name, 0, 1)) }}
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-zinc-900 dark:text-white">{{ $comment->name }}</span>
                                    <span class="text-zinc-400 text-xs ml-2">// {{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed pl-11">{{ $comment->body }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Comment Toggle Button --}}
        <div id="comment-form-trigger" class="text-center my-8 {{ $errors->any() ? 'hidden' : '' }}">
            <button onclick="toggleCommentForm()" 
                    class="group relative inline-flex items-center justify-center px-8 py-4 font-mono font-bold text-white transition-all duration-200 bg-zinc-900 dark:bg-zinc-800 rounded-lg hover:bg-zinc-800 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 w-full md:w-auto">
                <span class="absolute left-4 opacity-0 group-hover:opacity-100 transition-opacity text-green-500">➜</span>
                <span>./write_comment.sh</span>
            </button>
        </div>

        {{-- Comment Form --}}
        <div id="comment-form-wrapper" class="{{ $errors->any() ? 'block' : 'hidden' }} bg-zinc-50 dark:bg-zinc-900/50 rounded-lg border border-zinc-200 dark:border-zinc-800 p-6 md:p-8 mt-8 animate-fade-in">
            <h3 class="font-bold text-zinc-900 dark:text-white mb-6 text-sm flex justify-between items-center">
                <span><span class="text-green-500">➜</span> ./add_comment.sh</span>
                <button onclick="toggleCommentForm()" class="text-xs text-red-500 hover:underline">[ CANCEL ]</button>
            </h3>

            <form action="{{ route('blog.comments.store', $post) }}" method="POST" class="space-y-4">
                @csrf
                {{-- Honeypot --}}
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-zinc-500 dark:text-zinc-500 mb-1">NAME *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your name..."
                               class="w-full bg-white dark:bg-black border border-zinc-200 dark:border-zinc-700 rounded px-4 py-2.5 text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 dark:text-zinc-500 mb-1">EMAIL * <span class="text-zinc-400">(not published)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com"
                               class="w-full bg-white dark:bg-black border border-zinc-200 dark:border-zinc-700 rounded px-4 py-2.5 text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-blue-500 transition-colors">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-zinc-500 dark:text-zinc-500 mb-1">COMMENT *</label>
                    <textarea name="body" required rows="4" placeholder="Write your comment..."
                              class="w-full bg-white dark:bg-black border border-zinc-200 dark:border-zinc-700 rounded px-4 py-2.5 text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:border-blue-500 transition-colors resize-none">{{ old('body') }}</textarea>
                    @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-6 py-2.5 bg-zinc-900 dark:bg-white text-white dark:text-black rounded font-bold text-sm font-mono hover:opacity-90 transition-opacity">
                    Submit Comment →
                </button>
            </form>
        </div>
    </section>

    <div class="w-full border-t border-gray-100 dark:border-zinc-800 mt-16 mb-12"></div>

    {{-- Subscribe --}}
    @if (! session('subscribed'))
        <div class="bg-zinc-50 dark:bg-zinc-900/30 rounded border border-dashed border-zinc-300 dark:border-zinc-700 p-6 font-mono text-sm">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 text-zinc-800 dark:text-zinc-200 font-bold">
                        <span class="text-green-500 text-xs">➜</span>
                        <span>./subscribe.sh</span>
                    </div>
                    <p class="text-zinc-500 dark:text-zinc-500 text-xs mt-1">
                        <span class="text-blue-500">#</span> Get new posts via email. No spam.
                    </p>
                </div>
                
                <form action="{{ route('blog.subscribe') }}" method="POST" class="flex items-center gap-2 w-full md:w-auto">
                    @csrf
                    <div class="relative flex-1 md:w-64">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400 text-xs">$</span>
                        <input type="email" name="email" placeholder="enter_email..." required value="{{ old('email') }}"
                               class="w-full pl-6 pr-3 py-2 text-xs rounded bg-white dark:bg-black border border-zinc-300 dark:border-zinc-700 text-zinc-800 dark:text-gray-300 placeholder-zinc-400 focus:outline-none focus:border-zinc-500 transition-colors">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-zinc-800 dark:bg-zinc-700 text-white rounded text-xs font-bold hover:bg-zinc-700 dark:hover:bg-zinc-600 transition-colors">
                        [EXEC]
                    </button>
                </form>
            </div>
        </div>
    @endif

</article>

{{-- Like Button AJAX --}}
<script>
function toggleCommentForm() {
    const trigger = document.getElementById('comment-form-trigger');
    const wrapper = document.getElementById('comment-form-wrapper');
    
    // Toggle visibility
    trigger.classList.toggle('hidden');
    wrapper.classList.toggle('hidden');
    
    // If opening, scroll to form
    if (!wrapper.classList.contains('hidden')) {
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// Auto-open comments if there are validation errors or a success message
@if($errors->any() || session('comment_submitted'))
    document.addEventListener('DOMContentLoaded', () => {
        // Ensure form is visible
        document.getElementById('comment-form-trigger').classList.add('hidden');
        document.getElementById('comment-form-wrapper').classList.remove('hidden');
        
        // Scroll to form area
        document.getElementById('comment-form-wrapper').scrollIntoView({ behavior: 'smooth' });
    });
@endif

async function toggleLike(btn) {
    btn.disabled = true;
    const liked = btn.dataset.liked === '1';

    try {
        const res = await fetch('/posts/{{ $post->id }}/like', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        if (res.status === 429) {
            alert('Too many requests. Please wait a moment.');
            btn.disabled = false;
            return;
        }

        const data = await res.json();

        // Update count in all places
        document.getElementById('like-count').textContent    = data.count;
        document.getElementById('like-count-btn').textContent = data.count;

        const icon = document.getElementById('like-icon');
        const label = document.getElementById('like-label');

        if (data.liked) {
            btn.dataset.liked = '1';
            btn.classList.add('border-red-400', 'bg-red-50', 'text-red-500');
            btn.classList.remove('border-zinc-200', 'text-zinc-500');
            icon.classList.add('fill-red-500', 'text-red-500');
            icon.classList.remove('fill-none');
            label.textContent = 'Liked ✓';
        } else {
            btn.dataset.liked = '0';
            btn.classList.remove('border-red-400', 'bg-red-50', 'text-red-500');
            btn.classList.add('border-zinc-200', 'text-zinc-500');
            icon.classList.remove('fill-red-500', 'text-red-500');
            icon.classList.add('fill-none');
            label.textContent = 'Like this post';
        }
    } catch (e) {
        console.error(e);
    }

    btn.disabled = false;
}
</script>
@endsection
