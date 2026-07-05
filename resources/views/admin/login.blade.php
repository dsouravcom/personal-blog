<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in · Studio — {{ config('site.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans:['Inter','sans-serif'], mono:['JetBrains Mono','monospace'] } } } };
    </script>
    <style>
        body { background:#0a0a0a; color:#d4d0cb; }
        ::selection { background:#f59e0b; color:#1a1206; }
        .grain { background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); }
    </style>
</head>
<body class="relative flex min-h-screen items-center justify-center overflow-hidden p-4 font-sans antialiased">

    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="grain absolute inset-0 opacity-[0.04] mix-blend-screen"></div>
        <div class="absolute -top-40 left-1/2 h-[32rem] w-[32rem] -translate-x-1/2 rounded-full bg-amber-500/10 blur-[120px]"></div>
    </div>

    <div class="relative w-full max-w-sm">
        <div class="mb-8 flex flex-col items-center text-center">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-white font-mono text-base font-bold text-black">{{ config('site.author.monogram') }}</span>
            <h1 class="mt-5 text-xl font-semibold tracking-tight text-white">Sign in to Studio</h1>
            <p class="mt-1 text-sm text-zinc-500">Manage {{ config('site.name') }}’s blog</p>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}" class="rounded-2xl border border-[#272320] bg-[#131211] p-6 shadow-2xl">
            @csrf

            @if($errors->any())
                <div class="mb-5 rounded-lg border border-red-900/50 bg-red-950/30 px-4 py-3 text-center text-sm text-red-400">
                    Those credentials don’t match our records.
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="email" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Email</label>
                    <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}"
                           class="w-full rounded-lg border border-[#2a2622] bg-[#0d0c0b] px-4 py-2.5 text-sm text-white placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-600/20"
                           placeholder="you@example.com">
                </div>
                <div>
                    <label for="password" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border border-[#2a2622] bg-[#0d0c0b] px-4 py-2.5 text-sm text-white placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-600/20"
                           placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="mt-6 w-full rounded-lg bg-amber-500 py-2.5 text-sm font-semibold text-[#1a1206] transition-all hover:bg-amber-400 active:scale-[0.98]">
                Continue
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('blog.index') }}" class="text-xs text-zinc-500 transition-colors hover:text-zinc-300">← Back to site</a>
        </p>
    </div>
</body>
</html>
