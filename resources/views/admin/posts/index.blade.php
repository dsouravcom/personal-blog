@extends('layouts.admin')

@section('title', 'Posts')

@section('content')
<div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight text-white">Posts</h1>
        <p class="mt-1 text-sm text-zinc-500">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} · manage your writing</p>
    </div>
    <a href="{{ route('admin.posts.create') }}" class="btn-brand inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        New post
    </a>
</div>

<div class="panel overflow-hidden">
    @if ($posts->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="mb-4 grid h-14 w-14 place-items-center rounded-2xl border border-[#272320] bg-[#1a1815] text-zinc-600">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4h11l5 5v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/></svg>
            </div>
            <h3 class="text-sm font-medium text-zinc-300">No posts yet</h3>
            <p class="mt-1 max-w-xs text-xs text-zinc-500">Write your first post to get things started.</p>
            <a href="{{ route('admin.posts.create') }}" class="mt-5 text-sm font-medium text-amber-400 hover:text-amber-300">Create your first post →</a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-[#221f1c] text-xs font-medium uppercase tracking-wider text-zinc-500">
                        <th class="px-6 py-4">Title</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Published</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1c1a17] text-sm">
                    @foreach ($posts as $post)
                        <tr class="group transition-colors hover:bg-white/[0.02]">
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-3">
                                    <span class="mt-0.5 font-mono text-xs text-zinc-600">#{{ str_pad($post->id, 2, '0', STR_PAD_LEFT) }}</span>
                                    <div class="min-w-0">
                                        <div class="font-medium text-zinc-100">{{ $post->title }}</div>
                                        <div class="mt-0.5 font-mono text-xs text-zinc-500">/{{ $post->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($post->is_published)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-800/50 bg-emerald-950/40 px-2.5 py-1 text-[11px] font-medium text-emerald-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-800/50 bg-amber-950/30 px-2.5 py-1 text-[11px] font-medium text-amber-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs text-zinc-400">
                                    {{ $post->published_at?->format('M j, Y') ?? '—' }}
                                    <span class="mt-0.5 block text-[10px] text-zinc-600">{{ $post->published_at?->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="grid h-8 w-8 place-items-center rounded-lg text-zinc-500 transition-colors hover:bg-white/5 hover:text-white" title="View">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="grid h-8 w-8 place-items-center rounded-lg text-zinc-500 transition-colors hover:bg-amber-500/10 hover:text-amber-400" title="Edit">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete “{{ addslashes($post->title) }}”? This cannot be undone.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="grid h-8 w-8 place-items-center rounded-lg text-zinc-500 transition-colors hover:bg-red-500/10 hover:text-red-500" title="Delete">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1L5 6"/></svg>
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
            <div class="border-t border-[#221f1c]">
                {{ $posts->links('pagination.admin') }}
            </div>
        @endif
    @endif
</div>
@endsection
