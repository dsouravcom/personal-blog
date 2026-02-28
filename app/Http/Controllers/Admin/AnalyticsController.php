<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostView;
use App\Models\PostLike;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '30days');
        
        $startDate = match ($period) {
            'thisyear' => now()->startOfYear(),
            '6months'  => now()->subMonths(6),
            '7days'    => now()->subDays(6)->startOfDay(),
            'today'    => now()->startOfDay(),
            default    => now()->subDays(29)->startOfDay(), // 30days default
        };

        $periodLabel = match ($period) {
            'thisyear' => 'This year',
            '6months'  => 'Past 6 months',
            '7days'    => 'Last 7 days',
            'today'    => 'Today',
            default    => 'Last 30 days',
        };

        // ── Overview Stats ──────────────────────────────────────────────────
        $totalViews    = PostView::where('viewed_at', '>=', $startDate)->count();
        $totalLikes    = PostLike::where('liked_at', '>=', $startDate)->count();
        $totalComments = Comment::where('created_at', '>=', $startDate)->count();
        $totalPosts    = Post::where('is_published', true)->where('created_at', '>=', $startDate)->count();

        // ── Views over time (for line chart) ─────────────────────────
        $viewsByDay = PostView::select(
                DB::raw("to_char(viewed_at, 'YYYY-MM-DD') as date"),
                DB::raw('count(*) as total')
            )
            ->where('viewed_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing days with zero
        $chartData = collect();
        $daysCount = \Carbon\Carbon::parse($startDate)->diffInDays(now());
        if ($period === 'today') $daysCount = 0;

        for ($i = $daysCount; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData->push([
                'date'  => $date,
                'total' => $viewsByDay[$date]->total ?? 0,
            ]);
        }

        // ── Device Breakdown ─────────────────────────────────────────────────
        $deviceStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        // ── Browser Breakdown ─────────────────────────────────────────────────
        $browserStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── OS Breakdown ─────────────────────────────────────────────────────
        $osStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('os', DB::raw('count(*) as total'))
            ->groupBy('os')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── Country Breakdown ────────────────────────────────────────────────
        $countryStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('country_code', DB::raw('count(*) as total'))
            ->groupBy('country_code')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── Traffic Sources ──────────────────────────────────────────────────
        $sourceStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('referrer_domain', DB::raw('count(*) as total'))
            ->whereNotNull('referrer_domain')
            ->groupBy('referrer_domain')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Direct traffic
        $directViews = PostView::where('viewed_at', '>=', $startDate)
            ->whereNull('referrer_domain')->count();

        // ── UTM Sources ───────────────────────────────────────────────────────
        $utmStats = PostView::where('viewed_at', '>=', $startDate)
            ->select('utm_source', 'utm_medium', 'utm_campaign', DB::raw('count(*) as total'))
            ->whereNotNull('utm_source')
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Top Posts ─────────────────────────────────────────────────────────
        $topPosts = Post::withCount([
                'views' => fn($q) => $q->where('viewed_at', '>=', $startDate),
                'likes' => fn($q) => $q->where('liked_at', '>=', $startDate),
                'approvedComments as comments_count' => fn($q) => $q->where('created_at', '>=', $startDate)
            ])
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'periodLabel', 'chartData',
            'totalViews', 'totalLikes', 'totalComments', 'totalPosts',
            'deviceStats', 'browserStats', 'osStats', 'countryStats',
            'sourceStats', 'directViews', 'utmStats', 'topPosts'
        ));
    }

    public function post(Request $request, Post $post)
    {
        $period = $request->input('period', '30days');
        
        $startDate = match ($period) {
            'thisyear' => now()->startOfYear(),
            '6months'  => now()->subMonths(6),
            '7days'    => now()->subDays(6)->startOfDay(),
            'today'    => now()->startOfDay(),
            default    => now()->subDays(29)->startOfDay(),
        };

        $periodLabel = match ($period) {
            'thisyear' => 'This year',
            '6months'  => 'Past 6 months',
            '7days'    => 'Last 7 days',
            'today'    => 'Today',
            default    => 'Last 30 days',
        };

        // ── Post-Specific Stats ───────────────────────────────────────────────
        $totalViews    = $post->views()->where('viewed_at', '>=', $startDate)->count();
        $totalLikes    = $post->likes()->where('liked_at', '>=', $startDate)->count();
        $totalComments = $post->comments()->where('created_at', '>=', $startDate)->count();

        // Views over time
        $viewsByDay = $post->views()
            ->select(DB::raw("to_char(viewed_at, 'YYYY-MM-DD') as date"), DB::raw('count(*) as total'))
            ->where('viewed_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = collect();
        $daysCount = \Carbon\Carbon::parse($startDate)->diffInDays(now());
        if ($period === 'today') $daysCount = 0;

        for ($i = $daysCount; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData->push([
                'date'  => $date,
                'total' => $viewsByDay[$date]->total ?? 0,
            ]);
        }

        $deviceStats  = $post->views()->where('viewed_at', '>=', $startDate)->select('device_type', DB::raw('count(*) as total'))->groupBy('device_type')->orderByDesc('total')->get();
        $browserStats = $post->views()->where('viewed_at', '>=', $startDate)->select('browser', DB::raw('count(*) as total'))->groupBy('browser')->orderByDesc('total')->get();
        $osStats      = $post->views()->where('viewed_at', '>=', $startDate)->select('os', DB::raw('count(*) as total'))->groupBy('os')->orderByDesc('total')->get();
        $countryStats = $post->views()->where('viewed_at', '>=', $startDate)->select('country_code', DB::raw('count(*) as total'))->groupBy('country_code')->orderByDesc('total')->limit(8)->get();
        $sourceStats  = $post->views()->where('viewed_at', '>=', $startDate)->select('referrer_domain', DB::raw('count(*) as total'))->whereNotNull('referrer_domain')->groupBy('referrer_domain')->orderByDesc('total')->limit(10)->get();
        $directViews  = $post->views()->where('viewed_at', '>=', $startDate)->whereNull('referrer_domain')->count();

        return view('admin.analytics.post', compact(
            'post', 'periodLabel', 'chartData', 
            'totalViews', 'totalLikes', 'totalComments',
            'deviceStats', 'browserStats', 'osStats', 'countryStats',
            'sourceStats', 'directViews'
        ));
    }
}
