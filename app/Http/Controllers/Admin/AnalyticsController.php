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
    public function index()
    {
        // ── Overview Stats ──────────────────────────────────────────────────
        $totalViews    = PostView::count();
        $totalLikes    = PostLike::count();
        $totalComments = Comment::count();
        $totalPosts    = Post::where('is_published', true)->count();

        // ── Views over last 30 days (for line chart) ─────────────────────────
        $viewsByDay = PostView::select(
                DB::raw("to_char(viewed_at, 'YYYY-MM-DD') as date"),
                DB::raw('count(*) as total')
            )
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing days with zero
        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last30Days->push([
                'date'  => $date,
                'total' => $viewsByDay[$date]->total ?? 0,
            ]);
        }

        // ── Device Breakdown ─────────────────────────────────────────────────
        $deviceStats = PostView::select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        // ── Browser Breakdown ─────────────────────────────────────────────────
        $browserStats = PostView::select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── OS Breakdown ─────────────────────────────────────────────────────
        $osStats = PostView::select('os', DB::raw('count(*) as total'))
            ->groupBy('os')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── Traffic Sources ──────────────────────────────────────────────────
        $sourceStats = PostView::select('referrer_domain', DB::raw('count(*) as total'))
            ->whereNotNull('referrer_domain')
            ->groupBy('referrer_domain')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Direct traffic
        $directViews = PostView::whereNull('referrer_domain')->count();

        // ── UTM Sources ───────────────────────────────────────────────────────
        $utmStats = PostView::select('utm_source', 'utm_medium', 'utm_campaign', DB::raw('count(*) as total'))
            ->whereNotNull('utm_source')
            ->groupBy('utm_source', 'utm_medium', 'utm_campaign')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Top Posts ─────────────────────────────────────────────────────────
        $topPosts = Post::withCount(['views', 'likes', 'approvedComments as comments_count'])
            ->where('is_published', true)
            ->orderByDesc('views_count')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'totalViews', 'totalLikes', 'totalComments', 'totalPosts',
            'last30Days', 'deviceStats', 'browserStats', 'osStats',
            'sourceStats', 'directViews', 'utmStats', 'topPosts'
        ));
    }

    public function post(Post $post)
    {
        // ── Post-Specific Stats ───────────────────────────────────────────────
        $totalViews    = $post->views()->count();
        $totalLikes    = $post->likes()->count();
        $totalComments = $post->comments()->count();

        // Views over last 30 days
        $viewsByDay = $post->views()
            ->select(DB::raw("to_char(viewed_at, 'YYYY-MM-DD') as date"), DB::raw('count(*) as total'))
            ->where('viewed_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $last30Days = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $last30Days->push([
                'date'  => $date,
                'total' => $viewsByDay[$date]->total ?? 0,
            ]);
        }

        $deviceStats  = $post->views()->select('device_type', DB::raw('count(*) as total'))->groupBy('device_type')->orderByDesc('total')->get();
        $browserStats = $post->views()->select('browser', DB::raw('count(*) as total'))->groupBy('browser')->orderByDesc('total')->get();
        $osStats      = $post->views()->select('os', DB::raw('count(*) as total'))->groupBy('os')->orderByDesc('total')->get();
        $sourceStats  = $post->views()->select('referrer_domain', DB::raw('count(*) as total'))->whereNotNull('referrer_domain')->groupBy('referrer_domain')->orderByDesc('total')->limit(10)->get();
        $directViews  = $post->views()->whereNull('referrer_domain')->count();

        return view('admin.analytics.post', compact(
            'post', 'totalViews', 'totalLikes', 'totalComments',
            'last30Days', 'deviceStats', 'browserStats', 'osStats',
            'sourceStats', 'directViews'
        ));
    }
}
