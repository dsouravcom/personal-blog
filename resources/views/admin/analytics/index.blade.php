@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')

    <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-white">Analytics</h1>
            <p class="mt-1 text-sm text-zinc-500">{{ $periodLabel ?? 'Last 30 days' }} · real-time stats</p>
        </div>
        <form method="GET" action="{{ route('admin.analytics.index') }}">
            <select name="period" onchange="this.form.submit()" class="rounded-lg border border-[#2a2622] bg-[#141312] px-3 py-2 text-sm text-zinc-300 focus:border-amber-600 focus:outline-none">
                <option value="thisyear" {{ request('period') === 'thisyear' ? 'selected' : '' }}>Past 1 year</option>
                <option value="6months" {{ request('period') === '6months' ? 'selected' : '' }}>Past 6 months</option>
                <option value="30days" {{ request('period') === '30days' || !request('period') ? 'selected' : '' }}>Past 30 days</option>
                <option value="7days" {{ request('period') === '7days' ? 'selected' : '' }}>Past 7 days</option>
                <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
            </select>
        </form>
    </div>

    {{-- Stat cards --}}
    <div class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach([
            ['Total views', number_format($totalViews), 'text-amber-400'],
            ['Total likes', number_format($totalLikes), 'text-rose-400'],
            ['Comments', number_format($totalComments), 'text-emerald-400'],
            ['Published posts', number_format($totalPosts), 'text-sky-400'],
        ] as [$label, $value, $color])
            <div class="panel p-5">
                <p class="mb-1 text-xs uppercase tracking-wider text-zinc-500">{{ $label }}</p>
                <p class="text-3xl font-semibold {{ $color }}">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- Chart --}}
    <div class="panel mb-8 p-6">
        <h2 class="mb-4 text-sm font-medium text-zinc-300">Views per day <span class="text-zinc-600">· {{ strtolower($periodLabel ?? 'last 30 days') }}</span></h2>
        <div class="relative h-64"><canvas id="viewsChart"></canvas></div>
    </div>

    {{-- Breakdown --}}
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['Device', $deviceStats, 'device_type', 'bg-amber-500'],
            ['Browser', $browserStats->take(6), 'browser', 'bg-violet-500'],
            ['Operating system', $osStats->take(6), 'os', 'bg-emerald-500'],
            ['Country', $countryStats->take(6), 'country_code', 'bg-sky-500'],
        ] as [$title, $rows, $key, $bar])
            <div class="panel self-start p-5">
                <h3 class="mb-4 text-xs uppercase tracking-wider text-zinc-500">{{ $title }}</h3>
                <div class="space-y-3">
                    @forelse($rows as $row)
                        @php $pct = $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0; @endphp
                        <div>
                            <div class="mb-1 flex justify-between text-xs">
                                <span class="text-zinc-300">{{ $row->$key ?: 'unknown' }}</span>
                                <span class="text-zinc-500">{{ $row->total }} ({{ $pct }}%)</span>
                            </div>
                            <div class="h-1.5 overflow-hidden rounded-full bg-[#26231f]">
                                <div class="h-full rounded-full {{ $bar }}" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-zinc-600">No data yet</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>

    {{-- Sources + UTM --}}
    <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="panel p-5">
            <h3 class="mb-4 text-xs uppercase tracking-wider text-zinc-500">Traffic sources</h3>
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-[#221f1c] text-zinc-600">
                        <th class="py-2 text-left font-medium">Source</th>
                        <th class="py-2 text-right font-medium">Views</th>
                        <th class="py-2 text-right font-medium">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1c1a17]">
                    <tr class="text-zinc-300">
                        <td class="py-2">Direct / unknown</td>
                        <td class="py-2 text-right">{{ $directViews }}</td>
                        <td class="py-2 text-right text-zinc-500">{{ $totalViews > 0 ? round(($directViews / $totalViews) * 100) : 0 }}%</td>
                    </tr>
                    @foreach($sourceStats->take(10) as $row)
                        <tr class="text-zinc-300">
                            <td class="max-w-40 truncate py-2">{{ $row->referrer_domain === 'blog.sourav.dev' ? 'home' : $row->referrer_domain }}</td>
                            <td class="py-2 text-right">{{ $row->total }}</td>
                            <td class="py-2 text-right text-zinc-500">{{ $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0 }}%</td>
                        </tr>
                    @endforeach
                    @if($sourceStats->isEmpty())
                        <tr><td colspan="3" class="py-4 text-zinc-600">No referrers logged yet</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="panel p-5">
            <h3 class="mb-4 text-xs uppercase tracking-wider text-zinc-500">UTM campaigns</h3>
            @if($utmStats->isEmpty())
                <p class="py-4 text-xs text-zinc-600">No UTM-tagged traffic yet. Add <span class="font-mono">?utm_source=…</span> to your links.</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr class="border-b border-[#221f1c] text-zinc-600">
                            <th class="py-2 text-left font-medium">Source</th>
                            <th class="py-2 text-left font-medium">Medium</th>
                            <th class="py-2 text-left font-medium">Campaign</th>
                            <th class="py-2 text-right font-medium">Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#1c1a17]">
                        @foreach($utmStats->take(10) as $row)
                            <tr class="text-zinc-300">
                                <td class="py-2 text-amber-400">{{ $row->utm_source ?? '—' }}</td>
                                <td class="py-2">{{ $row->utm_medium ?? '—' }}</td>
                                <td class="max-w-30 truncate py-2">{{ $row->utm_campaign ?? '—' }}</td>
                                <td class="py-2 text-right">{{ $row->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Top posts --}}
    <div class="panel overflow-hidden">
        <div class="border-b border-[#221f1c] p-5">
            <h3 class="text-xs uppercase tracking-wider text-zinc-500">Top posts by views</h3>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#221f1c] text-left text-xs uppercase tracking-wider text-zinc-600">
                    <th class="px-6 py-3">Post</th>
                    <th class="px-6 py-3 text-right">Views</th>
                    <th class="hidden px-6 py-3 text-right sm:table-cell">Likes</th>
                    <th class="hidden px-6 py-3 text-right md:table-cell">Comments</th>
                    <th class="px-6 py-3 text-right">Detail</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1c1a17]">
                @forelse($topPosts as $post)
                    <tr class="transition-colors hover:bg-white/[0.02]">
                        <td class="px-6 py-3">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="line-clamp-1 text-zinc-200 transition-colors hover:text-amber-400">{{ $post->title }}</a>
                        </td>
                        <td class="px-6 py-3 text-right text-amber-400">{{ number_format($post->views_count) }}</td>
                        <td class="hidden px-6 py-3 text-right text-rose-400 sm:table-cell">{{ $post->likes_count }}</td>
                        <td class="hidden px-6 py-3 text-right text-emerald-400 md:table-cell">{{ $post->comments_count }}</td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('admin.analytics.post', $post) }}" class="rounded-lg border border-[#2a2622] px-2.5 py-1.5 text-xs text-zinc-400 transition-colors hover:border-amber-600 hover:text-amber-400">Details →</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-xs text-zinc-600">No views tracked yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        const raw = @json($chartData ?? []);
        const ctx = document.getElementById('viewsChart').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 256);
        grad.addColorStop(0, 'rgba(245,158,11,0.22)');
        grad.addColorStop(1, 'rgba(245,158,11,0)');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: raw.map(d => d.date),
                datasets: [{
                    label: 'Views', data: raw.map(d => d.total),
                    borderColor: '#f59e0b', backgroundColor: grad, borderWidth: 2,
                    fill: true, tension: 0.4, pointRadius: 0, pointHoverRadius: 5, pointBackgroundColor: '#f59e0b',
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#141312', titleColor: '#a1a1aa', bodyColor: '#e4e4e7',
                        titleFont: { family: 'JetBrains Mono', size: 12 }, bodyFont: { family: 'JetBrains Mono', size: 12 },
                        padding: 10, cornerRadius: 8, displayColors: false, borderColor: '#272320', borderWidth: 1,
                        callbacks: { label: (c) => ' Views: ' + c.parsed.y }
                    }
                },
                scales: {
                    x: { ticks: { color: '#6b6660', font: { family: 'JetBrains Mono', size: 11 }, maxTicksLimit: 10 }, grid: { color: 'rgba(255,255,255,0.03)' } },
                    y: { beginAtZero: true, ticks: { color: '#6b6660', font: { family: 'JetBrains Mono', size: 11 }, precision: 0 }, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
    })();
    </script>
@endsection
