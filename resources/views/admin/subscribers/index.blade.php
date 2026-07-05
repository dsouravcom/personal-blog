@extends('layouts.admin')

@section('title', 'Subscribers')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-white">Subscribers</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ \App\Models\Subscriber::count() }} total records</p>
        </div>
        <button onclick="document.getElementById('exportModal').classList.remove('hidden')"
                class="btn-brand inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
            Export
        </button>
    </div>

    {{-- Export modal --}}
    <div id="exportModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm flex">
        <div class="panel animate-fade-in-down w-full max-w-md overflow-hidden">
            <div class="flex items-center justify-between border-b border-[#221f1c] px-5 py-4">
                <h3 class="text-sm font-semibold text-white">Export subscribers</h3>
                <button onclick="document.getElementById('exportModal').classList.add('hidden')" class="grid h-8 w-8 place-items-center rounded-lg text-zinc-500 hover:bg-white/5 hover:text-white">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="space-y-2.5 p-5">
                <p class="mb-1 text-xs text-zinc-500">Choose a format</p>
                @foreach([['csv','CSV','Comma-separated values'],['pdf','PDF','Portable document'],['html','HTML','Web page']] as [$fmt, $label, $desc])
                    <a href="{{ route('admin.subscribers.export.' . $fmt) }}"
                       class="group flex items-center justify-between rounded-xl border border-[#272320] bg-[#111010] px-4 py-3 transition-colors hover:border-amber-600/60 hover:bg-amber-950/10">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-sm font-bold text-amber-400">.{{ strtoupper($fmt) }}</span>
                            <span class="text-xs text-zinc-500">{{ $desc }}</span>
                        </div>
                        <svg class="text-zinc-600 transition-transform group-hover:translate-x-0.5 group-hover:text-amber-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="panel overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-[#221f1c] text-xs font-medium uppercase tracking-wider text-zinc-500">
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">Subscribed</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1c1a17]">
                    @forelse($subscribers as $sub)
                        <tr class="transition-colors hover:bg-white/[0.02]">
                            <td class="px-6 py-4 font-mono text-xs text-zinc-500">{{ $sub->id }}</td>
                            <td class="px-6 py-4 font-medium text-zinc-200">{{ $sub->email }}</td>
                            <td class="px-6 py-4 font-mono text-xs text-zinc-500">{{ $sub->created_at ? $sub->created_at->format('M j, Y · H:i') : '—' }}</td>
                            <td class="px-6 py-4">
                                @if($sub->unsubscribed_at)
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-red-900/50 bg-red-950/20 px-2.5 py-1 text-[11px] font-medium text-red-400">Unsubscribed</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-800/50 bg-emerald-950/30 px-2.5 py-1 text-[11px] font-medium text-emerald-400"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Active</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-zinc-500">No subscribers yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($subscribers->hasPages())
        <div>{{ $subscribers->links('pagination.admin') }}</div>
    @endif
</div>
@endsection
