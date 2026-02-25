<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Honeypot: bots fill this hidden field, humans don't
        if ($request->filled('website')) {
            return back(); // Silently discard
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'body'    => 'required|string|min:3|max:2000',
            'website' => 'nullable|max:0', // extra honeypot validation
        ]);

        $comment = $post->comments()->create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'body'       => $validated['body'],
            'is_approved'=> false,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('comment_submitted', 'Your comment is awaiting moderation. Thank you!');
    }
}
