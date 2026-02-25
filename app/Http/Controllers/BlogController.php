<?php

namespace App\Http\Controllers;

use App\Models\Post;
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

        return view('blog.index', compact('posts'));
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

        // Live view count after tracking
        $viewCount  = $post->views()->count();

        // Has this visitor already liked the post?
        $likeHash  = hash('sha256', $request->ip() . ($request->userAgent() ?? '') . $post->id);
        $userLiked = $post->likes()->where('like_hash', $likeHash)->exists();

        return view('blog.show', compact('post', 'viewCount', 'userLiked'));
    }
}
