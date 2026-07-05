@props(['paginator'])

@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-4 pt-4" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="btn btn-ghost opacity-40 cursor-not-allowed select-none">
                <x-icon name="arrow-left" class="w-4 h-4" /> Previous
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-ghost pressable">
                <x-icon name="arrow-left" class="w-4 h-4" /> Previous
            </a>
        @endif

        <span class="font-mono text-xs text-muted tabular-nums">
            {{ str_pad($paginator->currentPage(), 2, '0', STR_PAD_LEFT) }}
            <span class="text-faint">/</span>
            {{ str_pad($paginator->lastPage(), 2, '0', STR_PAD_LEFT) }}
        </span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-ghost pressable">
                Next <x-icon name="arrow-right" class="w-4 h-4" />
            </a>
        @else
            <span class="btn btn-ghost opacity-40 cursor-not-allowed select-none">
                Next <x-icon name="arrow-right" class="w-4 h-4" />
            </span>
        @endif
    </nav>
@endif
