<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('site.title'))</title>
    <meta name="description" content="@yield('description', config('site.description'))">
    <meta name="author" content="{{ config('site.author.name') }}">
    <meta name="robots" content="@yield('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- Theme --}}
    <meta name="theme-color" content="#faf9f7" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">
    <meta name="color-scheme" content="light dark">

    {{-- Icons + feed --}}
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="alternate" type="application/rss+xml" title="{{ config('site.name') }} — RSS" href="{{ url('/feed') }}">

    {{-- Default social card (pages override via @push('head')) --}}
    <meta property="og:site_name" content="{{ config('site.name') }}">
    <meta property="og:locale" content="{{ config('site.locale') }}">
    <meta name="twitter:site" content="{{ config('site.socials.twitter_handle') }}">
    <meta name="twitter:creator" content="{{ config('site.socials.twitter_handle') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;0,6..72,600;1,6..72,400&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- No-flash theme boot + scroll-reveal opt-in (before first paint) --}}
    <script>
        (function () {
            try {
                var t = localStorage.theme;
                if (t === 'dark' || (!t && matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
                if ('IntersectionObserver' in window && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    document.documentElement.classList.add('js-reveal');
                }
            } catch (e) {}
        })();
    </script>

    {{-- Global structured data: author + website --}}
    <x-json-ld :data="[
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Person',
                '@id' => url('/') . '#person',
                'name' => config('site.author.name'),
                'url' => config('site.author.url'),
                'jobTitle' => config('site.author.job_title'),
                'description' => config('site.author.bio'),
                'sameAs' => array_values(array_filter([
                    config('site.socials.twitter'),
                    config('site.socials.github'),
                    config('site.socials.linkedin'),
                    config('site.socials.portfolio'),
                ])),
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => config('site.name'),
                'description' => config('site.description'),
                'inLanguage' => 'en',
                'publisher' => ['@id' => url('/') . '#person'],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => [
                        '@type' => 'EntryPoint',
                        'urlTemplate' => url('/search') . '?q={search_term_string}',
                    ],
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ]" />

    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-paper text-ink antialiased font-sans selection:bg-accent">

    {{-- Skip link --}}
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[100] focus:rounded-lg focus:bg-ink focus:px-4 focus:py-2 focus:text-paper focus:text-sm">
        Skip to content
    </a>

    {{-- Reading progress (opt-in per page) --}}
    @if($progress ?? false)
        <div id="reading-progress" class="fixed top-0 left-0 z-[60] h-0.5 w-0 bg-accent" style="transition: width 120ms linear;"></div>
    @endif

    {{-- Ambient background --}}
    <div class="fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div class="grain absolute inset-0 opacity-[0.025] dark:opacity-[0.04] mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute -top-40 left-1/2 h-[36rem] w-[36rem] -translate-x-1/2 rounded-full bg-accent-soft blur-[120px] opacity-60"></div>
    </div>

    {{-- ─── Header ─────────────────────────────────────────────────────────── --}}
    <header id="site-header"
            class="sticky top-0 z-50 border-b border-transparent transition-colors duration-300"
            data-header>
        <div class="absolute inset-0 -z-10 bg-paper/70 backdrop-blur-xl opacity-0 transition-opacity duration-300" data-header-bg></div>
        <div class="mx-auto flex h-16 max-w-5xl items-center justify-between gap-4 px-4 sm:px-6">

            {{-- Brand --}}
            <a href="{{ route('blog.index') }}" class="group flex items-center gap-2.5 focus:outline-none">
                <img src="{{ asset('favicon.ico') }}" alt="{{ config('site.name') }}"
                     class="h-9 w-9 rounded-xl border border-line bg-surface object-cover p-1 transition-transform duration-300 group-hover:-rotate-6">
                <span class="flex flex-col leading-none">
                    <span class="text-[0.95rem] font-semibold tracking-tight text-ink">{{ config('site.author.name') }}</span>
                    <span class="mt-0.5 font-mono text-[0.62rem] uppercase tracking-[0.18em] text-faint">Engineer · Writer</span>
                </span>
            </a>

            {{-- Desktop nav --}}
            @php $onWriting = request()->routeIs('blog.index', 'blog.show', 'blog.tag', 'blog.search'); @endphp
            <nav class="hidden items-center gap-1 md:flex" aria-label="Primary">
                <a href="{{ route('blog.index') }}" @if($onWriting) aria-current="page" @endif
                   @class([
                       'rounded-lg px-3 py-2 text-sm transition-colors',
                       'bg-surface-2 text-ink' => $onWriting,
                       'text-muted hover:bg-surface-2 hover:text-ink' => ! $onWriting,
                   ])>Writing</a>
                <a href="{{ config('site.socials.portfolio') }}" class="rounded-lg px-3 py-2 text-sm text-muted transition-colors hover:bg-surface-2 hover:text-ink">About</a>
                @auth
                    <a href="{{ route('admin.posts.index') }}" class="rounded-lg px-3 py-2 text-sm text-muted transition-colors hover:bg-surface-2 hover:text-ink">Dashboard</a>
                @endauth
            </nav>

            {{-- Actions --}}
            <div class="flex items-center gap-1">
                <button type="button" data-command-open
                        class="pressable group hidden items-center gap-2 rounded-lg border border-line bg-surface px-2.5 py-1.5 text-xs text-muted transition-colors hover:border-muted hover:text-ink sm:flex"
                        aria-label="Search (Ctrl+K)">
                    <x-icon name="search" class="w-3.5 h-3.5" />
                    <span>Search</span>
                    <kbd class="ml-1 rounded border border-line bg-surface-2 px-1.5 py-0.5 font-mono text-[0.6rem] text-faint">⌘K</kbd>
                </button>

                <button type="button" data-command-open
                        class="pressable grid h-9 w-9 place-items-center rounded-lg text-muted transition-colors hover:bg-surface-2 hover:text-ink sm:hidden"
                        aria-label="Search">
                    <x-icon name="search" class="w-4 h-4" />
                </button>

                <a href="{{ url('/feed') }}" class="pressable hidden h-9 w-9 place-items-center rounded-lg text-muted transition-colors hover:bg-surface-2 hover:text-ink sm:grid" aria-label="RSS feed">
                    <x-icon name="rss" class="w-4 h-4" />
                </a>

                <a href="{{ config('site.socials.github') }}" target="_blank" rel="noopener" class="pressable hidden h-9 w-9 place-items-center rounded-lg text-muted transition-colors hover:bg-surface-2 hover:text-ink sm:grid" aria-label="GitHub">
                    <x-icon name="github" class="w-4 h-4" />
                </a>

                {{-- Theme toggle --}}
                <button type="button" data-theme-toggle
                        class="pressable grid h-9 w-9 place-items-center rounded-lg text-muted transition-colors hover:bg-surface-2 hover:text-ink"
                        aria-label="Toggle theme">
                    <x-icon name="sun" class="hidden w-4 h-4 dark:block" />
                    <x-icon name="moon" class="block w-4 h-4 dark:hidden" />
                </button>

                {{-- Mobile menu --}}
                <button type="button" data-menu-toggle
                        class="pressable grid h-9 w-9 place-items-center rounded-lg text-muted transition-colors hover:bg-surface-2 hover:text-ink md:hidden"
                        aria-label="Menu" aria-expanded="false">
                    <x-icon name="menu" class="w-5 h-5" />
                </button>
            </div>
        </div>

        {{-- Mobile menu panel --}}
        <div data-menu-panel class="hidden border-t border-line bg-paper/95 backdrop-blur-xl md:hidden">
            <nav class="mx-auto flex max-w-5xl flex-col gap-1 px-4 py-4 text-sm" aria-label="Mobile">
                <a href="{{ route('blog.index') }}" class="rounded-lg px-3 py-2.5 text-ink hover:bg-surface-2">Writing</a>
                <a href="{{ config('site.socials.portfolio') }}" class="rounded-lg px-3 py-2.5 text-ink hover:bg-surface-2">About</a>
                <a href="{{ url('/feed') }}" class="rounded-lg px-3 py-2.5 text-ink hover:bg-surface-2">RSS feed</a>
                <a href="{{ config('site.socials.github') }}" target="_blank" rel="noopener" class="rounded-lg px-3 py-2.5 text-ink hover:bg-surface-2">GitHub</a>
                @auth
                    <a href="{{ route('admin.posts.index') }}" class="rounded-lg px-3 py-2.5 text-ink hover:bg-surface-2">Dashboard</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ─── Main ───────────────────────────────────────────────────────────── --}}
    <main id="main" class="w-full grow {{ $maxWidth ?? 'max-w-5xl' }} mx-auto px-4 sm:px-6 {{ $py ?? 'py-12 sm:py-16' }}">
        @yield('content')
    </main>

    {{-- ─── Footer ─────────────────────────────────────────────────────────── --}}
    <footer class="mt-auto border-t border-line">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 py-12">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2 max-w-sm">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('favicon.ico') }}" alt="{{ config('site.name') }}"
                             class="h-8 w-8 rounded-lg border border-line bg-surface object-cover p-1">
                        <span class="font-semibold tracking-tight text-ink">{{ config('site.author.name') }}</span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-muted">{{ config('site.tagline') }}</p>
                </div>

                <div>
                    <h3 class="font-mono text-[0.7rem] uppercase tracking-wider text-faint">Explore</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ route('blog.index') }}" class="text-muted transition-colors hover:text-ink">Writing</a></li>
                        <li><a href="{{ config('site.socials.portfolio') }}" class="text-muted transition-colors hover:text-ink">About</a></li>
                        <li><a href="{{ url('/feed') }}" class="text-muted transition-colors hover:text-ink">RSS feed</a></li>
                        <li><a href="{{ url('/sitemap.xml') }}" class="text-muted transition-colors hover:text-ink">Sitemap</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-mono text-[0.7rem] uppercase tracking-wider text-faint">Elsewhere</h3>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li><a href="{{ config('site.socials.github') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition-colors hover:text-ink"><x-icon name="github" class="w-4 h-4" /> GitHub</a></li>
                        <li><a href="{{ config('site.socials.twitter') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition-colors hover:text-ink"><x-icon name="twitter" class="w-3.5 h-3.5" /> Twitter / X</a></li>
                        <li><a href="{{ config('site.socials.linkedin') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-muted transition-colors hover:text-ink"><x-icon name="linkedin" class="w-4 h-4" /> LinkedIn</a></li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-3 border-t border-line pt-6 sm:flex-row">
                <p class="font-mono text-xs text-faint">© {{ date('Y') }} {{ config('site.author.name') }}. All rights reserved.</p>
                <p class="font-mono text-xs text-faint">Built with <span class="text-accent">Laravel</span> &amp; caffeine.</p>
            </div>
        </div>
    </footer>

    {{-- ─── Command palette ────────────────────────────────────────────────── --}}
    <div data-command-palette class="fixed inset-0 z-[80] hidden" role="dialog" aria-modal="true" aria-label="Search">
        <div data-command-backdrop class="absolute inset-0 bg-paper/70 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>
        <div class="relative mx-auto mt-[12vh] w-[92%] max-w-xl">
            <div data-command-card
                 class="overflow-hidden rounded-2xl border border-line bg-surface shadow-2xl opacity-0 scale-95 transition-all duration-200"
                 style="transform-origin: top center;">
                <div class="flex items-center gap-3 border-b border-line px-4">
                    <x-icon name="search" class="w-4 h-4 text-faint" />
                    <input type="text" data-command-input
                           class="w-full bg-transparent py-4 text-sm text-ink placeholder:text-faint focus:outline-none"
                           placeholder="Search posts…" autocomplete="off" spellcheck="false">
                    <kbd class="rounded border border-line bg-surface-2 px-1.5 py-0.5 font-mono text-[0.6rem] text-faint">ESC</kbd>
                </div>
                <div data-command-results class="max-h-[52vh] overflow-y-auto p-2">
                    <p class="px-3 py-6 text-center text-sm text-faint">Start typing to search…</p>
                </div>
                <div class="flex items-center justify-between border-t border-line px-4 py-2.5 font-mono text-[0.65rem] text-faint">
                    <span class="inline-flex items-center gap-1.5"><x-icon name="sparkles" class="w-3 h-3 text-accent" /> Search the blog</span>
                    <span>↵ to open · ↑↓ to navigate</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Back to top ────────────────────────────────────────────────────── --}}
    <button type="button" data-back-to-top
            class="pressable fixed bottom-6 right-6 z-40 grid h-11 w-11 translate-y-4 place-items-center rounded-full border border-line bg-surface text-muted opacity-0 shadow-lg transition-all duration-300 hover:text-ink"
            aria-label="Back to top">
        <x-icon name="arrow-up" class="w-4 h-4" />
    </button>

    {{-- Site behaviour analytics --}}
    <script>
      (function() {
        try {
          if (window.location && window.location.search && window.location.search.indexOf('capture-sitebehaviour-heatmap') !== -1) {
            sessionStorage.setItem('capture-sitebehaviour-heatmap', '_');
          }
          var sbSiteSecret = 'dbfebdb2-f19c-41bb-857e-745a2328cb57';
          window.sitebehaviourTrackingSecret = sbSiteSecret;
          var s = document.createElement('script');
          s.defer = true;
          s.id = 'site-behaviour-script-v2';
          s.src = 'https://sitebehaviour-cdn.fra1.cdn.digitaloceanspaces.com/index.min.js?sitebehaviour-secret=' + sbSiteSecret;
          document.head.appendChild(s);
        } catch (e) { console.error(e); }
      })();
    </script>

    @if(session('error') === 'Too many requests. Please slow down.')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof window.showRateLimitPopup === 'function') window.showRateLimitPopup();
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
