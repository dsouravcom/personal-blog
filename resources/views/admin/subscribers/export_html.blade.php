<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscribers Export</title>
    
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                     colors: {
                        dark: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            800: '#1f2937',
                            900: '#111827',
                            950: '#030712', // Deepest black
                        },
                     }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050505; color: #d1d5db; }
        .glass-panel { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(75, 85, 99, 0.4); }
    </style>
</head>
<body class="font-sans antialiased bg-[#050505] p-8 min-h-screen">

<div class="max-w-7xl mx-auto space-y-8">
    
    <div class="flex items-center justify-between border-b border-gray-800 pb-4">
        <div>
            <h1 class="text-3xl font-mono font-bold text-white tracking-tight">SUBSCRIBERS_EXPORT_DATA</h1>
            <p class="text-sm text-gray-400 font-mono mt-2">Generated: {{ now()->format('Y-m-d H:i:s T') }}</p>
            <p class="text-sm text-gray-400 font-mono mt-1">Total Audience Reach: {{ $subscribers->count() }} records</p>
        </div>
        
        <button onclick="window.print()" class="print:hidden bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 text-sm font-mono font-bold rounded flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            PRINT_OR_SAVE
        </button>
    </div>

    <div class="glass-panel rounded-lg overflow-hidden border border-gray-800 shadow-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-900/80 border-b border-gray-800 font-mono text-xs uppercase tracking-wider text-gray-400">
                    <th class="px-6 py-4 font-bold">ID</th>
                    <th class="px-6 py-4 font-bold">EMAIL_ADDRESS</th>
                    <th class="px-6 py-4 font-bold">SUBSCRIBED_AT</th>
                    <th class="px-6 py-4 font-bold">UNSUBSCRIBED_AT</th>
                    <th class="px-6 py-4 font-bold">STATUS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800/50 font-mono text-sm">
                @forelse($subscribers as $sub)
                    <tr class="hover:bg-gray-800/30 transition-colors">
                        <td class="px-6 py-4 text-gray-500">{{ $sub->id }}</td>
                        <td class="px-6 py-4 text-white font-medium">{{ $sub->email }}</td>
                        <td class="px-6 py-4 text-gray-400">{{ $sub->created_at ? $sub->created_at->format('Y-m-d H:i') : '--' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $sub->unsubscribed_at ? $sub->unsubscribed_at->format('Y-m-d H:i') : '--' }}</td>
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
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-mono text-sm">
                            [ NO_SUBSCRIBER_DATA_FOUND ]
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>