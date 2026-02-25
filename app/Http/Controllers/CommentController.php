<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Honeypot: bots fill this hidden field, humans don't
        if ($request->filled('website')) {
            Log::info('Honeypot triggered (comment)', ['ip' => $request->ip()]);
            return back(); // Silently discard
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email:rfc|max:255',
            'body'    => 'required|string|min:3|max:2000',
            'website' => 'nullable|max:0', // extra honeypot validation
        ]);

        // Strip all HTML/JS tags to prevent stored XSS
        $cleanName = strip_tags(trim($validated['name']));
        $cleanBody = strip_tags(trim($validated['body']));

        // If stripping removed all content it was likely a pure injection attempt
        if (empty($cleanName) || empty($cleanBody)) {
            return back()->withErrors(['body' => 'Invalid input detected.'])->withInput();
        }

        $post->comments()->create([
            'name'        => $cleanName,
            'email'       => $validated['email'],
            'body'        => $cleanBody,
            'is_approved' => false,
            'ip_address'  => $request->ip(),
        ]);

        return back()->with('comment_submitted', 'Your comment is awaiting moderation. Thank you!');
    }
}
