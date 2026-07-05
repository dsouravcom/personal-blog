<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Studio · @yield('title', 'Dashboard') — {{ config('site.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        // Amber brand (aliased to primary for any legacy classes)
                        brand:   { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309', DEFAULT: '#f59e0b' },
                        primary: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309' },
                    },
                    borderRadius: { xl: '0.9rem' },
                }
            }
        }
    </script>
    <style>
        body { background:#0a0a0a; color:#d4d0cb; }
        ::selection { background:#f59e0b; color:#1a1206; }

        .panel, .glass-panel {
            background:#141312;
            border:1px solid #272320;
            border-radius:0.9rem;
        }
        .sidebar-link {
            position:relative;
            transition:background-color .18s ease, color .18s ease;
        }
        .sidebar-link.active {
            background:rgba(245,158,11,0.10);
            color:#fbbf24;
        }
        .sidebar-link.active::before {
            content:''; position:absolute; left:0; top:20%; bottom:20%;
            width:2px; border-radius:2px; background:#f59e0b;
        }
        .btn-brand {
            background:#f59e0b; color:#1a1206;
            transition:transform .16s cubic-bezier(0.23,1,0.32,1), background-color .2s ease;
        }
        .btn-brand:hover { background:#fbbf24; }
        .btn-brand:active { transform:scale(0.97); }

        ::-webkit-scrollbar { width:10px; height:10px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#33302c; border-radius:99px; border:3px solid transparent; background-clip:content-box; }
        ::-webkit-scrollbar-thumb:hover { background:#4a453f; background-clip:content-box; }

        @keyframes admin-in { from { opacity:0; transform:translateY(-6px);} to { opacity:1; transform:translateY(0);} }
        .animate-fade-in-down { animation:admin-in .4s cubic-bezier(0.23,1,0.32,1); }
    </style>
</head>
<body class="font-sans antialiased h-screen flex overflow-hidden">

    {{-- Mobile overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 z-30 hidden bg-black/60 backdrop-blur-sm md:hidden"></div>

    {{-- ── Sidebar ─────────────────────────────────────────────────────────── --}}
    <aside id="sidebar"
           class="fixed z-40 flex h-full w-64 -translate-x-full flex-col justify-between border-r border-[#221f1c] bg-[#0d0c0b] transition-transform duration-300 md:static md:translate-x-0"
           style="transition-timing-function:cubic-bezier(0.32,0.72,0,1);">
        <div>
            <div class="flex h-16 items-center gap-2.5 border-b border-[#221f1c] px-5">
                <span class="grid h-9 w-9 place-items-center rounded-xl bg-white font-mono text-sm font-bold text-black">{{ config('site.author.monogram') }}</span>
                <div class="leading-tight">
                    <p class="text-sm font-semibold text-white">Studio</p>
                    <p class="font-mono text-[0.6rem] uppercase tracking-[0.16em] text-zinc-500">Content admin</p>
                </div>
            </div>

            <nav class="mt-5 space-y-1 px-3">
                @php $pendingCount = \App\Models\Comment::where('is_approved', false)->count(); @endphp
                <a href="{{ route('admin.posts.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-400 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h11l5 5v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M14 4v6h6"/></svg>
                    Posts
                </a>
                <a href="{{ route('admin.comments.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-400 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>
                    Comments
                    @if($pendingCount > 0)
                        <span class="ml-auto rounded-full border border-amber-500/40 bg-amber-500/15 px-1.5 py-0.5 font-mono text-[10px] font-bold text-amber-400">{{ $pendingCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.analytics.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-400 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg>
                    Analytics
                </a>
                <a href="{{ route('admin.subscribers.index') }}" class="sidebar-link flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-zinc-400 hover:bg-white/5 hover:text-white {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}">
                    <svg class="h-4.5 w-4.5" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13A4 4 0 0 1 16 11"/></svg>
                    Subscribers
                </a>
            </nav>
        </div>

        <div class="border-t border-[#221f1c] p-4">
            <div class="mb-3 flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-[#1c1a17] font-mono text-xs font-bold text-zinc-300">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-medium text-zinc-200">{{ Auth::user()->name }}</p>
                    <p class="truncate font-mono text-[0.65rem] text-zinc-500">Signed in</p>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#2a2622] px-3 py-2 text-xs font-medium text-zinc-400 transition-colors hover:border-red-900/60 hover:bg-red-950/30 hover:text-red-400">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main ────────────────────────────────────────────────────────────── --}}
    <div class="relative flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-[#221f1c] bg-[#0a0a0a]/85 px-4 backdrop-blur md:px-8">
            <div class="flex items-center gap-3">
                <button id="sidebar-toggle" class="grid h-9 w-9 place-items-center rounded-lg text-zinc-400 hover:bg-white/5 hover:text-white md:hidden" aria-label="Menu">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
                </button>
                <h2 class="font-mono text-sm uppercase tracking-wider text-zinc-300">@yield('title', 'Dashboard')</h2>
            </div>
            <a href="{{ route('blog.index') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-[#2a2622] px-3 py-1.5 text-xs text-zinc-400 transition-colors hover:border-zinc-600 hover:text-white">
                View site
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M7 7h10v10"/></svg>
            </a>
        </header>

        <main class="flex-1 overflow-auto p-4 md:p-8">
            <div class="mx-auto max-w-7xl">
                @if (session('success'))
                    <div class="animate-fade-in-down mb-6 flex items-center gap-3 rounded-xl border border-emerald-800/50 bg-emerald-950/30 px-4 py-3 text-sm text-emerald-300">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 6 9 17l-5-5"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        (function () {
            var btn = document.getElementById('sidebar-toggle');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            function open() { sidebar.classList.remove('-translate-x-full'); overlay.classList.remove('hidden'); }
            function close() { sidebar.classList.add('-translate-x-full'); overlay.classList.add('hidden'); }
            btn && btn.addEventListener('click', open);
            overlay && overlay.addEventListener('click', close);
        })();
    </script>
</body>
</html>
