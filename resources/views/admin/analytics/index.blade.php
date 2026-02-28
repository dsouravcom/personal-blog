@extends('layouts.admin')

@section('title', 'ANALYTICS')

@section('content')

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold font-mono text-white tracking-tight">
            <span class="text-primary-400">$</span> analytics.report
        </h1>
        <p class="mt-1 text-sm text-gray-500 font-mono">// Last 30 days — real-time stats</p>
    </div>

    {{-- Top Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['label' => 'Total Views', 'value' => number_format($totalViews), 'color' => 'text-blue-400', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5...'],
            ['label' => 'Total Likes', 'value' => number_format($totalLikes), 'color' => 'text-red-400'],
            ['label' => 'Comments', 'value' => number_format($totalComments), 'color' => 'text-green-400'],
            ['label' => 'Posts', 'value' => number_format($totalPosts), 'color' => 'text-yellow-400'],
        ] as $idx => $card)
        @endforeach

        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Total Views</p>
            <p class="text-3xl font-bold text-blue-400 font-mono">{{ number_format($totalViews) }}</p>
        </div>
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Total Likes</p>
            <p class="text-3xl font-bold text-red-400 font-mono">{{ number_format($totalLikes) }}</p>
        </div>
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Comments</p>
            <p class="text-3xl font-bold text-green-400 font-mono">{{ number_format($totalComments) }}</p>
        </div>
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Published Posts</p>
            <p class="text-3xl font-bold text-yellow-400 font-mono">{{ number_format($totalPosts) }}</p>
        </div>
    </div>

    {{-- Views Over Time Chart --}}
    <div class="glass-panel rounded-lg p-6 mb-8">
        <h2 class="text-sm font-bold font-mono text-gray-300 mb-4">
            <span class="text-primary-400">></span> views_per_day <span class="text-gray-600">// last 30 days</span>
        </h2>
        <div class="relative h-64">
            <canvas id="viewsChart"></canvas>
        </div>
    </div>

    {{-- Breakdown Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Device --}}
        <div class="glass-panel rounded-lg p-5 self-start">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">Device Type</h3>
            <div class="space-y-3">
                @foreach($deviceStats as $row)
                    @php $pct = $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-gray-300">{{ $row->device_type ?: 'unknown' }}</span>
                            <span class="text-gray-500">{{ $row->total }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($deviceStats->isEmpty())
                    <p class="text-gray-600 text-xs font-mono">// No data yet</p>
                @endif
            </div>
        </div>

        {{-- Browser --}}
        <div class="glass-panel rounded-lg p-5">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">Browser</h3>
            <div class="space-y-3">
                @foreach($browserStats->take(6) as $row)
                    @php $pct = $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-gray-300">{{ $row->browser ?: 'unknown' }}</span>
                            <span class="text-gray-500">{{ $row->total }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-purple-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($browserStats->isEmpty())
                    <p class="text-gray-600 text-xs font-mono">// No data yet</p>
                @endif
            </div>
        </div>

        {{-- OS --}}
        <div class="glass-panel rounded-lg p-5">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">Operating System</h3>
            <div class="space-y-3">
                @foreach($osStats->take(6) as $row)
                    @php $pct = $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-gray-300">{{ $row->os ?: 'unknown' }}</span>
                            <span class="text-gray-500">{{ $row->total }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($osStats->isEmpty())
                    <p class="text-gray-600 text-xs font-mono">// No data yet</p>
                @endif
            </div>
        </div>

        {{-- Country --}}
        <div class="glass-panel rounded-lg p-5">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">Country</h3>
            <div class="space-y-3">
                @foreach($countryStats->take(6) as $row)
                    @php $pct = $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-xs font-mono mb-1">
                            <span class="text-gray-300">{{ $row->country_code ?: 'unknown' }}</span>
                            <span class="text-gray-500">{{ $row->total }} ({{ $pct }}%)</span>
                        </div>
                        <div class="h-1.5 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                @if($countryStats->isEmpty())
                    <p class="text-gray-600 text-xs font-mono">// No data yet</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Traffic Sources + UTM Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Referrers --}}
        <div class="glass-panel rounded-lg p-5">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">Traffic Sources</h3>
            <table class="w-full text-xs font-mono">
                <thead>
                    <tr class="text-gray-600 border-b border-gray-800">
                        <th class="text-left py-2">Source</th>
                        <th class="text-right py-2">Views</th>
                        <th class="text-right py-2">%</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800/40">
                    <tr class="text-gray-300">
                        <td class="py-2">Direct / Unknown</td>
                        <td class="py-2 text-right">{{ $directViews }}</td>
                        <td class="py-2 text-right text-gray-500">{{ $totalViews > 0 ? round(($directViews / $totalViews) * 100) : 0 }}%</td>
                    </tr>
                    @foreach($sourceStats->take(10) as $row)
                        <tr class="text-gray-300 hover:bg-gray-800/30">
                            <td class="py-2 truncate max-w-40">{{ $row->referrer_domain }}</td>
                        <td class="py-2 text-right">{{ $row->total }}</td>
                        <td class="py-2 text-right text-gray-500">{{ $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0 }}%</td>
                        </tr>
                    @endforeach
                    @if($sourceStats->isEmpty())
                        <tr><td colspan="3" class="py-4 text-gray-600">// No referrers logged yet</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- UTM --}}
        <div class="glass-panel rounded-lg p-5">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider mb-4">UTM Campaigns</h3>
            @if($utmStats->isEmpty())
                <p class="text-gray-600 text-xs font-mono py-4">// No UTM-tagged traffic yet.<br>// Use ?utm_source=... params on your links.</p>
            @else
                <table class="w-full text-xs font-mono">
                    <thead>
                        <tr class="text-gray-600 border-b border-gray-800">
                            <th class="text-left py-2">Source</th>
                            <th class="text-left py-2">Medium</th>
                            <th class="text-left py-2">Campaign</th>
                            <th class="text-right py-2">Views</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800/40">
                        @foreach($utmStats->take(10) as $row)
                            <tr class="text-gray-300 hover:bg-gray-800/30">
                                <td class="py-2 text-blue-400">{{ $row->utm_source ?? '—' }}</td>
                                <td class="py-2">{{ $row->utm_medium ?? '—' }}</td>
                                <td class="py-2 truncate max-w-30">{{ $row->utm_campaign ?? '—' }}</td>
                                <td class="py-2 text-right">{{ $row->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- Top Posts --}}
    <div class="glass-panel rounded-lg overflow-hidden">
        <div class="p-5 border-b border-gray-800">
            <h3 class="text-xs font-mono text-gray-500 uppercase tracking-wider">Top Posts by Views</h3>
        </div>
        <table class="w-full text-sm font-mono">
            <thead>
                <tr class="text-left text-xs text-gray-600 uppercase tracking-wider border-b border-gray-800">
                    <th class="px-6 py-3">Post</th>
                    <th class="px-6 py-3 text-right">Views</th>
                    <th class="px-6 py-3 text-right hidden sm:table-cell">Likes</th>
                    <th class="px-6 py-3 text-right hidden md:table-cell">Comments</th>
                    <th class="px-6 py-3 text-right">Analytics</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50">
                @forelse($topPosts as $post)
                    <tr class="hover:bg-gray-900/40 transition-colors">
                        <td class="px-6 py-3">
                            <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                               class="text-gray-200 hover:text-primary-400 transition-colors line-clamp-1">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="px-6 py-3 text-right text-blue-400">{{ number_format($post->views_count) }}</td>
                        <td class="px-6 py-3 text-right text-red-400 hidden sm:table-cell">{{ $post->likes_count }}</td>
                        <td class="px-6 py-3 text-right text-green-400 hidden md:table-cell">{{ $post->comments_count }}</td>
                        <td class="px-6 py-3 text-right">
                            <a href="{{ route('admin.analytics.post', $post) }}"
                               class="text-xs px-2.5 py-1.5 rounded border border-gray-700 text-gray-400 hover:text-white hover:border-primary-500 transition-colors">
                                details →
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-600 text-xs">// No views tracked yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        const raw = @json($last30Days);
        const labels = raw.map(d => d.date);
        const data   = raw.map(d => d.total);

        const ctx = document.getElementById('viewsChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Views',
                    data,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#3b82f6',
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: { color: '#4b5563', font: { family: 'JetBrains Mono', size: 11 }, maxTicksLimit: 10 },
                        grid: { color: 'rgba(255,255,255,0.03)' },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#4b5563', font: { family: 'JetBrains Mono', size: 11 }, stepSize: 1 },
                        grid: { color: 'rgba(255,255,255,0.05)' },
                    }
                }
            }
        });
    })();
    </script>

@endsection
