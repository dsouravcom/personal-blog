@extends('layouts.admin')

@section('title', 'TRANSMISSION_LOG')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white tracking-tight flex items-center gap-2">
            <span class="text-primary-500 font-mono">></span> TRANSMISSION_LOG
        </h1>
        <p class="text-gray-400 text-xs font-mono mt-1">
            // INDEXING ALL BROADCASTS...
        </p>
    </div>
    
    <a href="{{ route('admin.posts.create') }}"
       class="group relative inline-flex items-center justify-center px-4 py-2 font-mono text-sm font-medium text-white transition-all duration-200 bg-primary-600 border border-primary-500 rounded hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-[#050505]">
        <span class="absolute inset-0 w-full h-full -mt-1 rounded opacity-30 bg-linear-to-b from-transparent via-transparent to-black"></span>
        <span class="relative flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            INITIATE_NEW_POST
        </span>
    </a>
</div>

<div class="relative overflow-hidden rounded-lg border border-gray-800 bg-[#0a0a0a]/50 backdrop-blur-sm">
    <div class="absolute top-0 left-0 w-full h-px bg-linear-to-r from-transparent via-primary-500/50 to-transparent"></div>

    @if ($posts->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <div class="w-16 h-16 mb-4 rounded-full bg-gray-900 border border-gray-800 flex items-center justify-center text-gray-600">
                <svg class="w-8 h-8 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-gray-300 font-mono text-sm mb-1">NO_DATA_FOUND</h3>
            <p class="text-gray-500 text-xs max-w-sm mb-6">The transmission logs are empty. Begin broadcasting to populate the feed.</p>
            <a href="{{ route('admin.posts.create') }}" class="text-primary-400 hover:text-primary-300 text-xs font-mono hover:underline">
                > EXECUTE: create_first_post();
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-800 bg-gray-900/40 text-xs text-gray-500 uppercase font-mono">
                        <th class="px-6 py-4 font-medium tracking-wider w-1/2">ID // Title</th>
                        <th class="px-6 py-4 font-medium tracking-wider">State</th>
                        <th class="px-6 py-4 font-medium tracking-wider">Timestamp</th>
                        <th class="px-6 py-4 font-medium tracking-wider text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-sm">
                    @foreach ($posts as $post)
                        <tr class="group hover:bg-white/2 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-4">
                                    <span class="text-gray-600 font-mono text-xs mt-1">#{{ str_pad($post->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    <div>
                                        <div class="font-medium text-gray-200 group-hover:text-white transition-colors text-base">{{ $post->title }}</div>
                                        <div class="text-gray-500 text-xs font-mono mt-1 opacity-70 flex items-center gap-2">
                                            <span class="text-primary-500/50">/slug:</span> {{ $post->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 align-top pt-5">
                                @if ($post->is_published)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border border-green-900/50 bg-green-900/10 text-green-400 text-[10px] font-mono uppercase tracking-wide shadow-[0_0_10px_rgba(34,197,94,0.1)]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)] animate-pulse"></span>
                                        LIVE
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded border border-yellow-900/50 bg-yellow-900/10 text-yellow-500 text-[10px] font-mono uppercase tracking-wide">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                        DRAFT
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 align-top pt-5">
                                <div class="font-mono text-xs text-gray-400">
                                    {{ $post->published_at?->format('Y-m-d') ?? 'N/A' }}
                                    <span class="text-gray-600 block text-[10px] mt-0.5">{{ $post->published_at?->format('H:i:s T') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right align-top pt-5">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.posts.edit', $post) }}" 
                                       class="p-1.5 text-gray-400 hover:text-primary-400 hover:bg-primary-400/10 rounded transition-all" 
                                       title="Edit Transmission">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline-block" 
                                          onsubmit="return confirm('WARNING: Are you sure you want to purge this transmission log? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded transition-all" title="Purge Log">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
        <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/20">
            {{ $posts->links() }} 
        </div>
        @endif
    @endif
</div>
@endsection