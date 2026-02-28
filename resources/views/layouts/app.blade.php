<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Personal blog of Sourav Dutta - Thoughts on technology, design, and software engineering.')">
    
    {{-- SEO Meta Tags --}}
    <meta name="author" content="Sourav Dutta">
    <meta name="robots" content="index, follow">
    
    {{-- Favicon --}}
    {{-- <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"> --}}
    {{-- <link rel="manifest" href="/site.webmanifest"> --}}

    <title>@yield('title', 'Sourav Dutta')</title>

    {{-- Additional Meta from Views --}}
    @yield('meta')

    {{-- Fonts: Inter (Google Fonts) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        ::selection { background-color: #000000; color: #ffffff; }
        .dark ::selection { background-color: #ffffff; color: #000000; }
    </style>
    
    <script>
        // Check for saved theme preference or system preference immediately to avoid flash
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans text-gray-900 bg-white dark:bg-black dark:text-zinc-200 antialiased min-h-screen flex flex-col transition-colors duration-300">
    
    {{-- Clean Minimal Noise Texture + Spotlight --}}
    <div class="fixed inset-0 -z-10 w-full h-full bg-zinc-50 dark:bg-black">
        {{-- 1. Noise Filter (creates paper/analog feel) --}}
        <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none mix-blend-multiply dark:mix-blend-overlay" 
             style="background-image: url(&quot;data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E&quot;);">
        </div>

        {{-- 2. Subtle Glow Orbs (Soft ambient light) --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-200/40 dark:bg-purple-900/10 rounded-full blur-3xl opacity-50 dark:opacity-20 animate-pulse delay-75 mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute top-1/4 -right-24 w-80 h-80 bg-blue-200/40 dark:bg-blue-900/10 rounded-full blur-3xl opacity-50 dark:opacity-20 animate-pulse mix-blend-multiply dark:mix-blend-screen"></div>
        <div class="absolute bottom-0 left-1/3 w-full h-64 bg-linear-to-t from-white via-white/0 to-transparent dark:from-black dark:via-black/0 dark:to-transparent"></div>
    </div>

    {{-- Navigation --}}
    <header class="w-full transition-colors duration-300 bg-white/80 dark:bg-black/80 backdrop-blur-md border-b border-gray-100 dark:border-white/10 sticky top-0 z-50 supports-backdrop-filter:bg-white/60 font-mono">
        <div class="{{ $maxWidth ?? 'max-w-6xl' }} mx-auto px-4 sm:px-6 h-16 flex justify-between items-center">
            
            {{-- Brand / Terminal --}}
            <a href="{{ route('blog.index') }}" class="group flex items-center gap-2 focus:outline-none">
                <span class="text-zinc-400 dark:text-zinc-500 font-bold select-none">~/blog</span>
                <span class="text-zinc-300 dark:text-zinc-600 select-none">$</span>
                <span class="text-zinc-900 dark:text-white font-bold group-hover:underline decoration-2 underline-offset-4 decoration-zinc-400">./index.sh</span>
                <span class="w-2 h-4 bg-zinc-900 dark:bg-white animate-pulse"></span>
            </a>

            <div class="flex items-center gap-6 text-sm">
                <nav class="hidden sm:flex items-center gap-6">
                    {{-- Portfolio Link (Go Back) --}}
                    <a href="https://sourav.dev" class="group flex items-center gap-1.5 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        <span class="text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400 transition-colors">cd</span>
                        <span class="font-medium">..</span>
                    </a>

                    {{-- Newsletter (Subscribe) --}}
                    <a href="#subscribe" class="group flex items-center gap-1.5 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        <span class="text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400 transition-colors">./</span>
                        <span class="font-medium">subscribe.sh</span>
                    </a>

                    {{-- GitHub (Source) --}}
                    <a href="https://github.com/dsouravcom" target="_blank" class="group flex items-center gap-1.5 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                        <span class="text-green-500/80 group-hover:text-green-500 transition-colors">git</span>
                        <span class="font-medium">checkout</span>
                    </a>

                    @auth
                        <a href="{{ route('admin.posts.index') }}" class="group flex items-center gap-1.5 text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition-colors">
                             <span class="text-zinc-300 dark:text-zinc-600 group-hover:text-zinc-400 transition-colors">sudo</span>
                             <span class="font-medium">dashboard</span>
                        </a>
                    @endauth
                </nav>
                
                <div class="w-px h-4 bg-zinc-200 dark:bg-zinc-800 hidden sm:block"></div>


                {{-- Theme Toggle (Terminal Style) --}}
                <button id="theme-toggle" type="button" class="group flex items-center gap-2 px-3 py-1.5 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-900 focus:outline-none transition-all duration-300 font-mono text-xs" aria-label="Toggle Dark Mode">
                    <span class="text-zinc-400 dark:text-zinc-500 select-none">--theme=</span>
                    
                    {{-- Shows when in Dark Mode --}}
                    <span id="theme-toggle-dark-icon" class="hidden font-bold text-zinc-900 dark:text-white">
                        dark
                    </span>
                    
                    {{-- Shows when in Light Mode --}}
                    <span id="theme-toggle-light-icon" class="hidden font-bold text-zinc-900 dark:text-white">
                        light
                    </span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="w-full {{ $maxWidth ?? 'max-w-6xl' }} mx-auto grow px-4 sm:px-6 {{ $py ?? 'py-12' }} animate-fade-in">
        @yield('content')
    </main>

    {{-- Clean Footer --}}
    <footer class="w-full border-t border-zinc-200 dark:border-zinc-800 py-8 bg-zinc-50/50 dark:bg-black/90 transition-colors duration-300 mt-auto backdrop-blur-sm font-mono text-xs">
        <div class="{{ $maxWidth ?? 'max-w-6xl' }} mx-auto px-4 sm:px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-zinc-500 dark:text-zinc-500 flex items-center gap-2">
                <span class="text-green-500">➜</span>
                <span>// {{ date('Y') }} Sourav Dutta. Built with Laravel & Caffeine.</span>
            </div>

            <div class="flex gap-6">
                <a href="https://x.com/souravdotdev" class="group text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-300 transition-colors">
                    <span class="text-purple-500 group-hover:underline">man</span> twitter
                </a>
                <a href="https://github.com/dsouravcom" class="group text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-300 transition-colors">
                    <span class="text-purple-500 group-hover:underline">man</span> github
                </a>
                <a href="https://www.linkedin.com/in/souravdotdev" class="group text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-300 transition-colors">
                    <span class="text-purple-500 group-hover:underline">man</span> linkedin
                </a>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle logic
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Set initial icon state based on current theme
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleDarkIcon.classList.remove('hidden');
        } else {
            themeToggleLightIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // If is set in localstorage
            if (localStorage.theme === 'dark') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        });
    </script>

    <script type="text/javascript">
      (
        function() {
            try {
              if(window.location && window.location.search && window.location.search.indexOf('capture-sitebehaviour-heatmap') !== -1) {
                sessionStorage.setItem('capture-sitebehaviour-heatmap', '_');
              }
         
              var sbSiteSecret = 'dbfebdb2-f19c-41bb-857e-745a2328cb57';
              window.sitebehaviourTrackingSecret = sbSiteSecret;
              var scriptElement = document.createElement('script');
              scriptElement.defer = true;
              scriptElement.id = 'site-behaviour-script-v2';
              scriptElement.src = 'https://sitebehaviour-cdn.fra1.cdn.digitaloceanspaces.com/index.min.js?sitebehaviour-secret=' + sbSiteSecret;
              document.head.appendChild(scriptElement); 
            }
            catch (e) {console.error(e)}
        }
      )()
</script>

    @if(session('error') === 'Too many requests. Please slow down.')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof window.showRateLimitPopup === 'function') {
                    window.showRateLimitPopup();
                }
            });
        </script>
    @endif
</body>
</html>
