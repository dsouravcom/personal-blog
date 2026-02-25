@extends('layouts.admin')

@section('title', 'COMMENTS')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold font-mono text-white tracking-tight">
                <span class="text-primary-400">$</span> comments.log
            </h1>
            <p class="mt-1 text-sm text-gray-500 font-mono">
                // Moderation queue — {{ $pendingCount > 0 ? $pendingCount . ' awaiting approval' : 'all clear' }}
            </p>
        </div>
        @if($pendingCount > 0)
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold font-mono bg-yellow-900/20 text-yellow-400 border border-yellow-900/50 self-start">
                <span class="w-2 h-2 rounded-full bg-yellow-400 animate-pulse"></span>
                {{ $pendingCount }} PENDING
            </span>
        @endif
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-6 flex gap-1 font-mono text-sm border-b border-gray-800 pb-0">
        <a href="{{ route('admin.comments.index') }}"
           class="px-4 py-2 rounded-t border-b-2 transition-all {{ !request('status') || request('status') === 'pending' ? 'border-primary-400 text-primary-400 bg-gray-900' : 'border-transparent text-gray-500 hover:text-gray-300' }}">
            pending
            @if($pendingCount > 0)
                <span class="ml-2 px-1.5 py-0.5 text-[10px] rounded-full bg-yellow-900/40 text-yellow-400">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}"
           class="px-4 py-2 rounded-t border-b-2 transition-all {{ request('status') === 'approved' ? 'border-primary-400 text-primary-400 bg-gray-900' : 'border-transparent text-gray-500 hover:text-gray-300' }}">
            approved
        </a>
    </div>

    {{-- Comments Table --}}
    <div class="glass-panel rounded-lg overflow-hidden">
        @if($comments->isEmpty())
            <div class="py-16 text-center font-mono">
                <p class="text-gray-500 text-sm">// No comments in this queue.</p>
                <p class="text-gray-700 text-xs mt-1">$ queue.length === 0</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-left text-xs text-gray-500 font-mono uppercase tracking-wider">
                            <th class="px-6 py-4">Commenter</th>
                            <th class="px-6 py-4">Post</th>
                            <th class="px-6 py-4">Comment</th>
                            <th class="px-6 py-4 hidden lg:table-cell">Date</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/50">
                        @foreach($comments as $comment)
                            <tr class="hover:bg-gray-900/40 transition-colors group">
                                {{-- Commenter --}}
                                <td class="px-6 py-4 align-top">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded bg-gray-800 border border-gray-700 flex items-center justify-center text-[11px] font-mono font-bold text-gray-300 shrink-0">
                                            {{ strtoupper(substr($comment->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-200 font-mono text-xs">{{ $comment->name }}</p>
                                            <p class="text-gray-500 text-[11px] font-mono">{{ $comment->email }}</p>
                                            <p class="text-gray-700 text-[10px] font-mono mt-0.5">{{ $comment->ip_address }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Post --}}
                                <td class="px-6 py-4 align-top max-w-40">
                                    <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank"
                                       class="text-primary-400 hover:text-primary-300 font-mono text-xs line-clamp-2 transition-colors">
                                        {{ $comment->post->title }}
                                    </a>
                                </td>

                                {{-- Body --}}
                                <td class="px-6 py-4 align-top max-w-xs">
                                    <p class="text-gray-300 text-xs line-clamp-3 leading-relaxed">{{ $comment->body }}</p>
                                </td>

                                {{-- Date --}}
                                <td class="px-6 py-4 align-top hidden lg:table-cell">
                                    <time class="text-gray-500 font-mono text-[11px] whitespace-nowrap">
                                        {{ $comment->created_at->format('M d, Y') }}
                                    </time>
                                    <p class="text-gray-700 text-[10px] font-mono">{{ $comment->created_at->format('H:i') }}</p>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 align-top text-right">
                                    <div class="flex items-center justify-end gap-1.5 font-mono">
                                        @if(!$comment->is_approved)
                                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Approve"
                                                    class="px-2.5 py-1.5 rounded text-[11px] bg-green-900/20 text-green-400 border border-green-900/50 hover:bg-green-900/40 transition-colors">
                                                    ✓ approve
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.comments.disapprove', $comment) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" title="Disapprove"
                                                    class="px-2.5 py-1.5 rounded text-[11px] bg-yellow-900/20 text-yellow-400 border border-yellow-900/50 hover:bg-yellow-900/40 transition-colors">
                                                    ✗ hide
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline"
                                              onsubmit="return confirm('Delete this comment?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Delete"
                                                class="px-2.5 py-1.5 rounded text-[11px] bg-red-900/20 text-red-500 border border-red-900/50 hover:bg-red-900/40 transition-colors">
                                                rm
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($comments->hasPages())
                <div class="px-6 py-4 border-t border-gray-800 flex items-center justify-between text-xs text-gray-500 font-mono">
                    <span>Showing {{ $comments->firstItem() }}–{{ $comments->lastItem() }} of {{ $comments->total() }}</span>
                    <div class="flex items-center gap-2">
                        @if($comments->onFirstPage())
                            <span class="opacity-30">← prev</span>
                        @else
                            <a href="{{ $comments->previousPageUrl() }}" class="hover:text-white transition-colors">← prev</a>
                        @endif
                        @if($comments->hasMorePages())
                            <a href="{{ $comments->nextPageUrl() }}" class="hover:text-white transition-colors">next →</a>
                        @else
                            <span class="opacity-30">next →</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

@endsection
