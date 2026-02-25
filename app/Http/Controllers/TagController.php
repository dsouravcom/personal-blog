<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->where('is_published', true)
            ->withCount(['views', 'likes', 'approvedComments as comments_count'])
            ->with('tags')
            ->orderByDesc('published_at')
            ->paginate(10);

        return view('blog.tag', compact('tag', 'posts'));
    }
}
