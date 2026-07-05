<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Services\ViewTrackerService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::published()
            ->withCount(['views', 'likes', 'approvedComments as comments_count'])
            ->with('tags')
            ->paginate(10);

        // Featured = newest post, only surfaced on the first page.
        $featured = $posts->currentPage() === 1 ? $posts->first() : null;

        // Popular tags for the filter strip.
        $tags = Tag::whereHas('posts', fn ($q) => $q->where('is_published', true))
            ->withCount(['posts' => fn ($q) => $q->where('is_published', true)])
            ->orderByDesc('posts_count')
            ->limit(12)
            ->get();

        return view('blog.index', compact('posts', 'featured', 'tags'));
    }

    public function show(string $slug, ViewTrackerService $tracker, Request $request)
    {
        $post = Post::where('slug', $slug)
            ->where('is_published', true)
            ->withCount(['likes', 'approvedComments as comments_count'])
            ->with(['tags', 'approvedComments'])
            ->firstOrFail();

        // Track the view (production-grade, bot-filtered, unique per day)
        $tracker->track($request, $post);

        $viewCount = $post->views()->count();

        // Has this visitor already liked the post?
        $likeHash  = hash('sha256', $request->ip() . ($request->userAgent() ?? '') . $post->id);
        $userLiked = $post->likes()->where('like_hash', $likeHash)->exists();

        // Previous / next in chronological order.
        $prev = Post::published()
            ->where('published_at', '<', $post->published_at)
            ->orderByDesc('published_at')
            ->first(['title', 'slug']);

        $next = Post::where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', $post->published_at)
            ->orderBy('published_at')
            ->first(['title', 'slug']);

        // Related posts sharing at least one tag.
        $tagIds = $post->tags->pluck('id');
        $related = Post::published()
            ->where('id', '!=', $post->id)
            ->when($tagIds->isNotEmpty(), fn ($q) => $q->whereHas('tags', fn ($t) => $t->whereIn('tags.id', $tagIds)))
            ->withCount(['views', 'likes'])
            ->with('tags')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'viewCount', 'userLiked', 'prev', 'next', 'related'));
    }

    /**
     * Full-page search results (also the SearchAction target for SEO).
     */
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $posts = Post::published()
            ->withCount(['views', 'likes', 'approvedComments as comments_count'])
            ->with('tags')
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            }))
            ->paginate(10)
            ->withQueryString();

        return view('blog.search', compact('posts', 'q'));
    }

    /**
     * Lightweight JSON endpoint powering the ⌘K command palette.
     */
    public function searchApi(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $results = Post::published()
            ->when($q !== '', fn ($query) => $query->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            }))
            ->limit(8)
            ->get(['title', 'slug', 'excerpt', 'published_at'])
            ->map(fn (Post $p) => [
                'title'   => $p->title,
                'url'     => route('blog.show', $p->slug),
                'excerpt' => $p->excerpt,
                'date'    => $p->published_at?->format('M Y'),
            ]);

        return response()->json(['results' => $results]);
    }

    /**
     * RSS 2.0 feed.
     */
    public function feed()
    {
        $posts = Post::published()->with('tags')->limit(30)->get();

        return response()
            ->view('feed', compact('posts'))
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * /llms.txt — a plain-text index for AI crawlers and agents.
     * See https://llmstxt.org
     */
    public function llms()
    {
        $posts = Post::published()->get(['title', 'slug', 'excerpt', 'published_at']);

        $lines = [];
        $lines[] = '# ' . config('site.name');
        $lines[] = '';
        $lines[] = '> ' . config('site.tagline');
        $lines[] = '';
        $lines[] = config('site.author.bio');
        $lines[] = '';
        $lines[] = 'Author: ' . config('site.author.name') . ' — ' . config('site.author.job_title');
        $lines[] = 'Website: ' . config('site.author.url');
        $lines[] = 'RSS feed: ' . route('blog.feed');
        $lines[] = '';
        $lines[] = '## Posts';
        $lines[] = '';

        foreach ($posts as $post) {
            $lines[] = '- [' . $post->title . '](' . route('blog.show', $post->slug) . ')'
                . ($post->excerpt ? ': ' . trim($post->excerpt) : '');
        }

        $lines[] = '';

        return response(implode("\n", $lines))
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function sitemap()
    {
        $posts = Post::where('is_published', true)
            ->orderByDesc('updated_at')
            ->get();

        $tags = Tag::whereHas('posts', fn ($q) => $q->where('is_published', true))->get();

        return response()
            ->view('sitemap', compact('posts', 'tags'))
            ->header('Content-Type', 'text/xml');
    }
}
