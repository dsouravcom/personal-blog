@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-mono font-bold text-white tracking-tight">SUBSCRIBERS_REGISTRY</h1>
            <p class="text-sm text-gray-400 font-mono mt-1">Total Audience Reach: {{ \App\Models\Subscriber::count() }} records found</p>
        </div>
        
        <button onclick="document.getElementById('exportModal').classList.remove('hidden')" class="bg-primary-600 hover:bg-primary-500 text-white px-4 py-2 text-sm font-mono font-bold rounded flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            EXPORT_DATA
        </button>
    </div>

    <!-- Export Modal popup -->
    <div id="exportModal" class="fixed inset-0 z-50 hidden bg-[#050505]/90 backdrop-blur flex items-center justify-center p-4">
        <div class="bg-dark-950 border border-gray-800 w-full max-w-md relative animate-fade-in-down">
            
            <!-- Terminal-like Header -->
            <div class="px-4 py-3 border-b border-gray-800 bg-gray-900/40 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 bg-primary-500 animate-pulse"></span>
                    <h3 class="font-mono text-xs font-bold text-gray-300 tracking-widest">SYS.EXPORT_MODULE</h3>
                </div>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="text-gray-500 hover:text-red-400 transition-colors focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="p-6 space-y-4 font-mono">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-4">
                    <span class="text-primary-500">></span> <span>SELECT_OUTPUT_FORMAT:</span>
                </div>
                
                <a href="{{ route('admin.subscribers.export.csv') }}" class="block w-full text-left bg-gray-900/20 border border-gray-800 hover:border-primary-500 hover:bg-primary-900/10 p-4 transition-all group relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-3">
                            <span class="text-primary-500 font-bold text-sm">.CSV</span>
                            <span class="text-gray-400 text-xs hidden sm:inline-block group-hover:text-gray-300">Raw Comma-Separated_Values</span>
                        </div>
                        <span class="text-[10px] text-gray-600 group-hover:text-primary-400 tracking-widest">[ EXECUTE ]</span>
                    </div>
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>

                <a href="{{ route('admin.subscribers.export.pdf') }}" class="block w-full text-left bg-gray-900/20 border border-gray-800 hover:border-red-500 hover:bg-red-900/10 p-4 transition-all group relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-3">
                            <span class="text-red-500 font-bold text-sm">.PDF</span>
                            <span class="text-gray-400 text-xs hidden sm:inline-block group-hover:text-gray-300">Portable_Document_Format</span>
                        </div>
                        <span class="text-[10px] text-gray-600 group-hover:text-red-400 tracking-widest">[ EXECUTE ]</span>
                    </div>
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>

                 <a href="{{ route('admin.subscribers.export.html') }}" class="block w-full text-left bg-gray-900/20 border border-gray-800 hover:border-green-500 hover:bg-green-900/10 p-4 transition-all group relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex items-center gap-3">
                            <span class="text-green-500 font-bold text-sm">.HTML</span>
                            <span class="text-gray-400 text-xs hidden sm:inline-block group-hover:text-gray-300">Hypertext_Markup_Language</span>
                        </div>
                        <span class="text-[10px] text-gray-600 group-hover:text-green-400 tracking-widest">[ EXECUTE ]</span>
                    </div>
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </a>
            </div>
            
            <div class="px-6 py-3 border-t border-gray-800 bg-[#030712] flex justify-end">
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="text-[10px] font-mono font-bold text-gray-500 hover:text-red-600 transition-colors tracking-widest focus:outline-none">
                    [ ABORT_OPERATION ]
                </button>
            </div>
        </div>
    </div>


    <div class="glass-panel rounded-lg overflow-hidden border border-gray-800 shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-900/50 border-b border-gray-800 font-mono text-[10px] uppercase tracking-wider text-gray-400">
                        <th class="px-6 py-4 font-medium">ID</th>
                        <th class="px-6 py-4 font-medium">EMAIL_ADDRESS</th>
                        <th class="px-6 py-4 font-medium">SUBSCRIBED_AT</th>
                        <th class="px-6 py-4 font-medium">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/50 font-mono text-sm">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-gray-800/20 transition-colors group">
                            <td class="px-6 py-4 text-gray-500 group-hover:text-gray-400">{{ $sub->id }}</td>
                            <td class="px-6 py-4 text-gray-300 font-medium">{{ $sub->email }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $sub->created_at ? $sub->created_at->format('Y-m-d H:i') : '--' }}</td>
                            
                            <td class="px-6 py-4">
                                @if($sub->unsubscribed_at)
                                     <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-red-900/20 text-red-500 border border-red-900/50 tracking-wider">
                                        UNSUBSCRIBED
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-bold bg-green-900/20 text-green-500 border border-green-900/50 tracking-wider">
                                        ACTIVE
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-mono text-sm">
                                [ NO_SUBSCRIBER_DATA_FOUND ]
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subscribers->hasPages())
        <div class="mt-6 border-t border-gray-800 pt-6">
            {{ $subscribers->links('pagination::tailwind') }}
        </div>
    @endif

</div>
@endsection