<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like for a post.
     * Uses IP + User-Agent fingerprint. Throttled via route middleware.
     */
    public function toggle(Request $request, Post $post)
    {
        $likeHash = hash('sha256', $request->ip() . ($request->userAgent() ?? '') . $post->id);

        $existing = PostLike::where('post_id', $post->id)
            ->where('like_hash', $likeHash)
            ->first();

        if ($existing) {
            // Unlike
            $existing->delete();
            $liked = false;
        } else {
            // Like
            PostLike::create([
                'post_id'    => $post->id,
                'like_hash'  => $likeHash,
                'ip_address' => $request->ip(),
            ]);
            $liked = true;
        }

        $likeCount = $post->likes()->count();

        if ($request->wantsJson()) {
            return response()->json(['liked' => $liked, 'count' => $likeCount]);
        }

        return back();
    }
}
