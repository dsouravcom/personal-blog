@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-white">Comments</h1>
        <p class="mt-1 text-sm text-zinc-500">
            Moderation queue — {{ $pendingCount > 0 ? $pendingCount . ' awaiting review' : 'all clear' }}
        </p>
    </div>
    @if($pendingCount > 0)
        <span class="inline-flex items-center gap-1.5 self-start rounded-full border border-amber-800/50 bg-amber-950/30 px-3 py-1.5 text-xs font-medium text-amber-400">
            <span class="h-2 w-2 rounded-full bg-amber-400"></span> {{ $pendingCount }} pending
        </span>
    @endif
</div>

{{-- Tabs --}}
<div class="mb-6 flex gap-1 border-b border-[#221f1c] text-sm">
    <a href="{{ route('admin.comments.index') }}"
       class="-mb-px border-b-2 px-4 py-2.5 transition-colors {{ !request('status') || request('status') === 'pending' ? 'border-amber-500 text-amber-400' : 'border-transparent text-zinc-500 hover:text-zinc-300' }}">
        Pending
        @if($pendingCount > 0)<span class="ml-1.5 rounded-full bg-amber-500/15 px-1.5 py-0.5 text-[10px] text-amber-400">{{ $pendingCount }}</span>@endif
    </a>
    <a href="{{ route('admin.comments.index', ['status' => 'approved']) }}"
       class="-mb-px border-b-2 px-4 py-2.5 transition-colors {{ request('status') === 'approved' ? 'border-amber-500 text-amber-400' : 'border-transparent text-zinc-500 hover:text-zinc-300' }}">
        Approved
    </a>
</div>

<div class="panel overflow-hidden">
    @if($comments->isEmpty())
        <div class="py-16 text-center">
            <p class="text-sm text-zinc-400">No comments in this queue.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#221f1c] text-left text-xs font-medium uppercase tracking-wider text-zinc-500">
                        <th class="px-6 py-4">Author</th>
                        <th class="px-6 py-4">Post</th>
                        <th class="px-6 py-4">Comment</th>
                        <th class="hidden px-6 py-4 lg:table-cell">Date</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1c1a17]">
                    @foreach($comments as $comment)
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-6 py-4 align-top">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-[#1c1a17] font-mono text-xs font-bold text-zinc-300">{{ strtoupper(substr($comment->name, 0, 1)) }}</span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-zinc-200">{{ $comment->name }}</p>
                                        <p class="font-mono text-[11px] text-zinc-500">{{ $comment->email }}</p>
                                        <p class="font-mono text-[10px] text-zinc-600">{{ $comment->ip_address }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="max-w-40 px-6 py-4 align-top">
                                <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank" class="line-clamp-2 text-xs text-amber-400 transition-colors hover:text-amber-300">{{ $comment->post->title }}</a>
                            </td>
                            <td class="max-w-xs px-6 py-4 align-top">
                                <p class="line-clamp-3 text-xs leading-relaxed text-zinc-300">{{ $comment->body }}</p>
                            </td>
                            <td class="hidden px-6 py-4 align-top lg:table-cell">
                                <time class="whitespace-nowrap font-mono text-[11px] text-zinc-500">{{ $comment->created_at->format('M j, Y') }}</time>
                                <p class="font-mono text-[10px] text-zinc-600">{{ $comment->created_at->format('H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 align-top text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if(!$comment->is_approved)
                                        <form method="POST" action="{{ route('admin.comments.approve', $comment) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="rounded-lg border border-emerald-800/50 bg-emerald-950/30 px-2.5 py-1.5 text-[11px] text-emerald-400 transition-colors hover:bg-emerald-950/60">Approve</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.comments.disapprove', $comment) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="rounded-lg border border-amber-800/50 bg-amber-950/30 px-2.5 py-1.5 text-[11px] text-amber-400 transition-colors hover:bg-amber-950/60">Hide</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-900/50 bg-red-950/20 px-2.5 py-1.5 text-[11px] text-red-500 transition-colors hover:bg-red-950/50">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($comments->hasPages())
            <div class="flex items-center justify-between border-t border-[#221f1c] px-6 py-4 font-mono text-xs text-zinc-500">
                <span>Showing {{ $comments->firstItem() }}–{{ $comments->lastItem() }} of {{ $comments->total() }}</span>
                <div class="flex items-center gap-3">
                    @if($comments->onFirstPage())
                        <span class="text-zinc-700">← Prev</span>
                    @else
                        <a href="{{ $comments->previousPageUrl() }}" class="transition-colors hover:text-white">← Prev</a>
                    @endif
                    @if($comments->hasMorePages())
                        <a href="{{ $comments->nextPageUrl() }}" class="transition-colors hover:text-white">Next →</a>
                    @else
                        <span class="text-zinc-700">Next →</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
