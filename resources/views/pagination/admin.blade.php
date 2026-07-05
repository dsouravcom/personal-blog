@if ($paginator->hasPages())
    <div class="flex items-center justify-between gap-4 px-6 py-4">
        <p class="font-mono text-xs text-zinc-500">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}
        </p>

        <nav class="flex items-center gap-1 font-mono text-xs">
            @if ($paginator->onFirstPage())
                <span class="cursor-not-allowed rounded-lg border border-[#221f1c] px-3 py-1.5 text-zinc-700">Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="rounded-lg border border-[#2a2622] px-3 py-1.5 text-zinc-400 transition-colors hover:border-amber-600 hover:text-amber-400">Prev</a>
            @endif

            <div class="mx-1 hidden items-center gap-1 sm:flex">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="px-2 text-zinc-600">…</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="rounded-lg bg-amber-500 px-3 py-1.5 font-bold text-[#1a1206]">{{ sprintf('%02d', $page) }}</span>
                            @else
                                <a href="{{ $url }}" class="rounded-lg px-3 py-1.5 text-zinc-500 transition-colors hover:bg-white/5 hover:text-white">{{ sprintf('%02d', $page) }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <span class="rounded-lg border border-amber-500/20 bg-amber-500/10 px-3 py-1.5 font-bold text-amber-400 sm:hidden">{{ sprintf('%02d', $paginator->currentPage()) }}</span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="rounded-lg border border-[#2a2622] px-3 py-1.5 text-zinc-400 transition-colors hover:border-amber-600 hover:text-amber-400">Next</a>
            @else
                <span class="cursor-not-allowed rounded-lg border border-[#221f1c] px-3 py-1.5 text-zinc-700">Next</span>
            @endif
        </nav>
    </div>
@endif
