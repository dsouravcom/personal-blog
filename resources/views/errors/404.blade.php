@extends('layouts.app', ['py' => 'py-16'])

@section('title', 'Page not found — ' . config('site.name'))
@section('robots', 'noindex, follow')

@section('content')
<div class="relative flex min-h-[62vh] flex-col items-center justify-center text-center">

    {{-- Oversized ghost numerals with subtle parallax --}}
    <div class="pointer-events-none absolute inset-0 flex items-center justify-center overflow-hidden" aria-hidden="true">
        <span data-parallax class="select-none font-semibold leading-none tracking-tighter text-ink/[0.035] dark:text-ink/[0.05]"
              style="font-size: clamp(12rem, 40vw, 30rem); transition: transform 300ms var(--ease-out-strong);">
            404
        </span>
    </div>

    <div class="reveal relative">
        <p class="inline-flex items-center gap-2 rounded-full border border-line bg-surface px-3 py-1 font-mono text-xs text-muted">
            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Error 404
        </p>
        <h1 class="mt-6 text-3xl sm:text-4xl font-semibold tracking-tight text-ink">This page wandered off.</h1>
        <p class="mx-auto mt-4 max-w-md leading-relaxed text-muted">
            There’s nothing at
            <code class="rounded-md border border-line bg-surface-2 px-1.5 py-0.5 font-mono text-sm text-accent">/{{ Str::limit(request()->path(), 40) }}</code>.
            It may have moved, or never existed.
        </p>

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('blog.index') }}" class="btn btn-primary pressable">
                <x-icon name="arrow-left" class="w-4 h-4" /> Back home
            </a>
            <button type="button" data-command-open class="btn btn-ghost pressable">
                <x-icon name="search" class="w-4 h-4" /> Search posts
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        var el = document.querySelector('[data-parallax]');
        if (!el || !window.matchMedia('(pointer: fine)').matches) return;
        window.addEventListener('mousemove', function (e) {
            var x = (e.clientX / window.innerWidth - 0.5) * -30;
            var y = (e.clientY / window.innerHeight - 0.5) * -30;
            el.style.transform = 'translate(' + x + 'px,' + y + 'px)';
        }, { passive: true });
    })();
</script>
@endpush
