@extends('layouts.admin')

@section('title', 'POST ANALYTICS')

@section('content')

    {{-- Back + Title --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-start justify-between gap-4">
        <div>
            <a href="{{ route('admin.analytics.index') }}" class="text-xs font-mono text-gray-500 hover:text-white transition-colors flex items-center gap-1 mb-3">
                ← back to analytics
            </a>
            <h1 class="text-2xl font-bold font-mono text-white tracking-tight">
                <span class="text-primary-400">$</span> analytics.post
            </h1>
            <p class="mt-1 text-sm text-gray-500 font-mono line-clamp-1">
                // <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="hover:text-primary-400 transition-colors">{{ $post->title }}</a> ({{ $periodLabel ?? 'Last 30 days' }})
            </p>
        </div>
        <div class="mt-2 md:mt-0">
            <form method="GET" action="{{ route('admin.analytics.post', $post) }}">
                <select name="period" onchange="this.form.submit()" class="bg-gray-800 border border-gray-700 text-sm font-mono text-gray-300 rounded px-3 py-1.5 focus:ring-primary-500 focus:border-primary-500">
                    <option value="thisyear" {{ request('period') === 'thisyear' ? 'selected' : '' }}>This year</option>
                    <option value="6months" {{ request('period') === '6months' ? 'selected' : '' }}>Past 6 months</option>
                    <option value="30days" {{ request('period') === '30days' || !request('period') ? 'selected' : '' }}>Past 30 days</option>
                    <option value="7days" {{ request('period') === '7days' ? 'selected' : '' }}>7 days</option>
                    <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-3 gap-4 mb-8">
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Total Views</p>
            <p class="text-3xl font-bold text-blue-400 font-mono">{{ number_format($totalViews) }}</p>
        </div>
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Likes</p>
            <p class="text-3xl font-bold text-red-400 font-mono">{{ number_format($totalLikes) }}</p>
        </div>
        <div class="glass-panel rounded-lg p-5">
            <p class="text-xs text-gray-500 font-mono uppercase tracking-wider mb-1">Comments</p>
            <p class="text-3xl font-bold text-green-400 font-mono">{{ number_format($totalComments) }}</p>
        </div>
    </div>

    {{-- Views Over Time --}}
    <div class="glass-panel rounded-lg p-6 mb-8">
        <h2 class="text-sm font-bold font-mono text-gray-300 mb-4">
            <span class="text-primary-400">></span> views_per_day <span class="text-gray-600">// {{ strtolower($periodLabel ?? 'last 30 days') }}</span>
        </h2>
        <div class="relative h-64">
            <canvas id="viewsChart"></canvas>
        </div>
    </div>

    {{-- Breakdown --}}
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
                @foreach($browserStats as $row)
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
                @foreach($osStats as $row)
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
                @foreach($countryStats as $row)
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

    {{-- Traffic Sources --}}
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
                @foreach($sourceStats as $row)
                    <tr class="text-gray-300 hover:bg-gray-800/30">
                        <td class="py-2 truncate max-w-50">{{ $row->referrer_domain === 'blog.sourav.dev' ? 'home' : $row->referrer_domain }}</td>
                        <td class="py-2 text-right">{{ $row->total }}</td>
                        <td class="py-2 text-right text-gray-500">{{ $totalViews > 0 ? round(($row->total / $totalViews) * 100) : 0 }}%</td>
                    </tr>
                @endforeach
                @if($sourceStats->isEmpty() && $directViews === 0)
                    <tr><td colspan="3" class="py-4 text-gray-600">// No referrer data yet</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    (function() {
        const raw = @json($chartData ?? []);
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
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        titleColor: '#9ca3af',
                        bodyColor: '#e5e7eb',
                        titleFont: { family: 'JetBrains Mono', size: 12 },
                        bodyFont: { family: 'JetBrains Mono', size: 12 },
                        padding: 10,
                        cornerRadius: 4,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ' Views: ' + context.parsed.y;
                            }
                        }
                    }
                },
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
