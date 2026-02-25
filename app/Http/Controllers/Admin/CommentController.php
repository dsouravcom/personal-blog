<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $commentsQuery = Comment::with('post')
            ->when($status === 'pending', fn($q) => $q->pending())
            ->when($status === 'approved', fn($q) => $q->approved())
            ->latest();

        $comments    = $commentsQuery->paginate(20)->withQueryString();
        $pendingCount = Comment::pending()->count();

        return view('admin.comments.index', compact('comments', 'status', 'pendingCount'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['is_approved' => true]);
        return back()->with('success', 'Comment approved.');
    }

    public function disapprove(Comment $comment)
    {
        $comment->update(['is_approved' => false]);
        return back()->with('success', 'Comment hidden.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment permanently deleted.');
    }
}
