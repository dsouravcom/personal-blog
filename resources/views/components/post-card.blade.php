@props(['post', 'index' => 0, 'activeTag' => null])

<article
    class="reveal group relative"
    style="animation-delay: {{ min($index * 60, 400) }}ms">
    <div class="relative flex gap-5 sm:gap-7 rounded-2xl -mx-3 sm:-mx-4 px-3 sm:px-4 py-5 sm:py-6
                transition-colors duration-300 hover:bg-surface-2/70">

        {{-- Index marker --}}
        <div class="hidden sm:flex shrink-0 pt-1 select-none">
            <span class="font-mono text-xs text-faint tabular-nums">
                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
            </span>
        </div>

        {{-- Body --}}
        <div class="flex-1 min-w-0">
            {{-- Meta line --}}
            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mb-2 font-mono text-[0.7rem] uppercase tracking-wider text-muted">
                <time datetime="{{ $post->published_at?->toDateString() }}">
                    {{ $post->published_at?->format('M j, Y') }}
                </time>
                <span class="text-faint" aria-hidden="true">/</span>
                <span>{{ $post->readingTime() }} min read</span>
                @isset($post->views_count)
                    <span class="text-faint" aria-hidden="true">/</span>
                    <span class="inline-flex items-center gap-1">
                        <x-icon name="eye" class="w-3 h-3" /> {{ number_format($post->views_count) }}
                    </span>
                @endisset
            </div>

            {{-- Title --}}
            <h3 class="text-xl sm:text-2xl font-semibold tracking-tight text-ink leading-snug">
                <a href="{{ route('blog.show', $post->slug) }}"
                   class="transition-colors duration-200 group-hover:text-accent focus:outline-none">
                    <span class="absolute inset-0" aria-hidden="true"></span>
                    {{ $post->title }}
                </a>
            </h3>

            {{-- Excerpt --}}
            @if($post->excerpt)
                <p class="mt-2 text-[0.95rem] leading-relaxed text-muted line-clamp-2 max-w-2xl">
                    {{ $post->excerpt }}
                </p>
            @endif

            {{-- Tags + stats --}}
            <div class="mt-3.5 flex flex-wrap items-center gap-2 relative z-10">
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="tag pressable {{ $activeTag && $activeTag->id === $tag->id ? '!text-accent !border-accent/50 !bg-accent-soft' : '' }}">
                        <span class="text-faint">#</span>{{ $tag->name }}
                    </a>
                @endforeach

                <span class="ml-auto hidden md:inline-flex items-center gap-3 font-mono text-[0.7rem] text-faint">
                    @isset($post->likes_count)
                        <span class="inline-flex items-center gap-1"><x-icon name="heart" class="w-3 h-3" />{{ $post->likes_count }}</span>
                    @endisset
                    @isset($post->comments_count)
                        <span class="inline-flex items-center gap-1"><x-icon name="comment" class="w-3 h-3" />{{ $post->comments_count }}</span>
                    @endisset
                </span>
            </div>
        </div>

        {{-- Cover thumbnail --}}
        @if($post->cover_image)
            <div class="hidden sm:block shrink-0 w-28 lg:w-36">
                <div class="aspect-4/3 overflow-hidden rounded-xl border border-line bg-surface-2">
                    <img src="{{ $post->cover_image }}"
                         alt="{{ $post->cover_image_alt ?? $post->title }}"
                         loading="lazy" decoding="async"
                         class="w-full h-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                </div>
            </div>
        @endif
    </div>
</article>
