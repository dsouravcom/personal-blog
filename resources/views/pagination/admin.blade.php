@if ($paginator->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between bg-[#050505] border-t border-gray-800 px-4 py-3 md:px-6 select-none">

        {{-- Left Info Block --}}
        <div class="flex items-center gap-4 text-[10px] md:text-xs font-mono uppercase tracking-widest text-gray-500 mb-4 md:mb-0 w-full md:w-auto justify-between md:justify-start">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-primary-500/50 animate-pulse rounded-full"></span>
                <span>DATA_STREAM</span>
            </div>
            <div class="flex items-center gap-2 text-gray-600">
                <span>[ SEQ: <span class="text-white">{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</span> ]</span>
                <span class="hidden sm:inline text-gray-700">// MAX: {{ $paginator->total() }}</span>
            </div>
        </div>

        {{-- Pagination Controls --}}
        <nav class="flex items-center gap-1 font-mono text-xs">

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-gray-700 border border-gray-800 bg-gray-900/20 cursor-not-allowed opacity-50 font-bold">
                    &lt; PREV
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="group px-3 py-1.5 text-gray-400 border border-gray-700 hover:border-primary-500 hover:text-primary-400 hover:bg-primary-500/10 transition-all font-bold">
                    &lt; PREV
                </a>
            @endif

            {{-- Numbers (Hidden on very small screens) --}}
            <div class="hidden sm:flex items-center mx-2 gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-2 text-gray-600">...</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="px-3 py-1.5 bg-primary-500 text-black font-bold border border-primary-400 shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                                    {{ sprintf('%02d', $page) }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1.5 text-gray-500 hover:text-white border border-transparent hover:border-gray-600 transition-colors">
                                    {{ sprintf('%02d', $page) }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Mobile Current Indicator --}}
            <span class="sm:hidden px-3 py-1.5 text-primary-400 border border-primary-500/20 bg-primary-900/10 font-bold">
                {{ sprintf('%02d', $paginator->currentPage()) }}
            </span>

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="group px-3 py-1.5 text-gray-400 border border-gray-700 hover:border-primary-500 hover:text-primary-400 hover:bg-primary-500/10 transition-all font-bold">
                    NEXT &gt;
                </a>
            @else
                <span class="px-3 py-1.5 text-gray-700 border border-gray-800 bg-gray-900/20 cursor-not-allowed opacity-50 font-bold">
                    NEXT &gt;
                </span>
            @endif
            
        </nav>
    </div>
@endif
