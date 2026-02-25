<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CMD_CENTER // @yield('title')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
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
                        dark: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            800: '#1f2937',
                            900: '#111827',
                            950: '#030712', // Deepest black
                        },
                        primary: {
                            400: '#60a5fa', // Blue
                            500: '#3b82f6',
                            600: '#2563eb',
                        },
                        accent: {
                            500: '#10b981', // Emerald
                        }
                    },
                    animation: {
                        'fade-in-down': 'fadeInDown 0.5s ease-out',
                    },
                    keyframes: {
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050505;
            color: #d1d5db; /* gray-300 */
        }
        .sidebar-link.active {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
            border-right: 2px solid #60a5fa;
        }
        .glass-panel {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(75, 85, 99, 0.4);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #111827;
        }
        ::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>
</head>
<body class="font-sans antialiased h-screen flex overflow-hidden selection:bg-primary-500 selection:text-white">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-dark-950 border-r border-gray-800 flex-col justify-between hidden md:flex md:w-64">
        <div>
            <div class="h-16 flex items-center px-6 border-b border-gray-800">
                <span class="font-mono text-xl font-bold tracking-tighter text-white">
                    <span class="text-primary-400">></span> SYS_ADMIN
                </span>
            </div>

            <nav class="mt-6 px-3 space-y-1">
                {{-- Posts --}}
                <a href="{{ route('admin.posts.index') }}" 
                   class="sidebar-link group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition-all {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Transmissions (Posts)
                </a>

                {{-- Comments --}}
                @php $pendingCount = \App\Models\Comment::where('is_approved', false)->count(); @endphp
                <a href="{{ route('admin.comments.index') }}"
                   class="sidebar-link group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition-all {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Comments
                    @if($pendingCount > 0)
                        <span class="ml-auto text-[10px] font-bold bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </a>

                {{-- Analytics --}}
                <a href="{{ route('admin.analytics.index') }}"
                   class="sidebar-link group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition-all {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics
                </a>

                <div class="pt-2 pb-1 border-t border-gray-800/60 mt-2">
                    <p class="px-3 text-[10px] uppercase tracking-widest text-gray-700 font-mono mb-1">Coming Soon</p>
                </div>

                <a href="#" class="sidebar-link group flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-400 hover:text-white hover:bg-gray-800 transition-all cursor-not-allowed opacity-40">
                    <svg class="mr-3 h-5 w-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Operatives (Users) <span class="ml-auto text-[10px] bg-gray-800 px-1 rounded">LOCKED</span>
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-800 bg-gray-900/20">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded bg-gray-800 border border-gray-700 flex items-center justify-center text-xs font-mono font-bold text-white">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-gray-300 uppercase truncate font-mono">CMD: {{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-500 font-mono truncate">ID: {{ Auth::id() }} // ROOT</p>
                </div>
            </div>
             <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full group flex items-center justify-center px-3 py-2 text-xs font-mono font-bold rounded border border-red-900/40 text-red-500 hover:bg-red-900/20 hover:text-red-400 transition-all uppercase tracking-wider">
                    [ TERMINATE SESSION ]
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT AREA --}}
    <div class="flex-1 flex flex-col min-w-0 bg-[#050505] relative">
        
        {{-- TOP BAR --}}
        <header class="h-16 flex items-center justify-between px-6 border-b border-gray-800 bg-[#0a0a0a]/80 backdrop-blur sticky top-0 z-30">
            
            {{-- Mobile Menu Trigger (Visual Only) --}}
            <div class="md:hidden text-gray-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </div>

            <div class="flex items-center gap-4">
                <span id="live-clock" class="text-gray-600 text-xs font-mono hidden sm:inline-block min-w-45">
                    {{-- JS populates this --}}
                    SYNCING...
                </span>
                 <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-900/20 text-green-500 border border-green-900/50">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    ONLINE
                </span>
            </div>
            
            <a href="{{ route('blog.index') }}" target="_blank" class="text-xs text-gray-400 hover:text-white font-mono flex items-center gap-2 group transition-colors border border-gray-800 px-3 py-1.5 rounded hover:bg-gray-800 hover:border-gray-700">
                <span>OPEN_FRONTEND_PORT</span>
                <svg class="w-3 h-3 text-gray-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </header>

        {{-- MAIN --}}
        <main class="flex-1 overflow-auto p-6 md:p-8 relative">
            
            {{-- Background Grid --}}
            <div class="absolute inset-0 z-0 pointer-events-none opacity-[0.03]" 
                 style="background-image: linear-gradient(#333 1px, transparent 1px), linear-gradient(90deg, #333 1px, transparent 1px); background-size: 20px 20px;">
            </div>

            <div class="relative z-10 max-w-7xl mx-auto">
                {{-- Flash Message --}}
                @if (session('success'))
                    <div class="mb-6 bg-green-900/10 border border-green-500/30 text-green-400 px-4 py-3 rounded flex items-center gap-3 shadow-lg shadow-green-900/5 animate-fade-in-down">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="font-mono text-xs md:text-sm">
                            <span class="font-bold">SUCCESS:</span> {{ session('success') }}
                        </div>
                    </div>
                @endif
    
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Format: YYYY-MM-DD HH:MM:SS
            const timeString = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            document.getElementById('live-clock').innerText = 'LOCAL_TIME: ' + timeString;
        }
        
        // Update immediately then every second
        updateClock();
        setInterval(updateClock, 1000);
    </script>
</body>
</html>