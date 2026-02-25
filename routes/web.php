<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;

// ─── Public blog ─────────────────────────────────────────────────────────────
Route::middleware('throttle:read.content')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/posts/{slug}', [BlogController::class, 'show'])->name('blog.show');
    Route::get('/tags/{slug}', [TagController::class, 'show'])->name('blog.tag');
    Route::get('/sitemap.xml', [BlogController::class, 'sitemap'])->name('blog.sitemap');
});

Route::post('/subscribe', [SubscriberController::class, 'store'])
    ->name('blog.subscribe')
    ->middleware('throttle:write.subscribe');

// Use the 'signed' middleware to ensure valid signature
Route::get('/unsubscribe/{subscriber}', [SubscriberController::class, 'destroy'])
    ->name('blog.unsubscribe')
    ->middleware(['signed', 'throttle:6,1']); // Low, since it's a one-time action

// Comments (throttled: 3 per minute per IP)
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
    ->name('blog.comments.store')
    ->middleware('throttle:write.comment');

// Likes (throttled: 20 per minute per IP)
Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])
    ->name('blog.posts.like')
    ->middleware('throttle:write.like');

// ─── Admin auth ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('throttle:auth.strict')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
        Route::get('/verify-otp', [AuthController::class, 'showOtp'])->name('otp');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ─── Protected admin routes ───────────────────────────────────────────
    Route::middleware(['auth', 'throttle:admin.action'])->group(function () {
        // Dedicated AJAX endpoint for R2 image upload — must be declared BEFORE
        // Route::resource so it takes priority over the {post} wildcard route.
        Route::post('posts/upload-image', [PostController::class, 'uploadImage'])
            ->name('posts.upload-image')
            ->middleware('throttle:admin.upload'); // More restrictive for uploads

        Route::resource('posts', PostController::class);

        // Comment moderation
        Route::get('comments', [AdminCommentController::class, 'index'])->name('comments.index');
        Route::patch('comments/{comment}/approve', [AdminCommentController::class, 'approve'])->name('comments.approve');
        Route::patch('comments/{comment}/disapprove', [AdminCommentController::class, 'disapprove'])->name('comments.disapprove');
        Route::delete('comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

        // Analytics
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/posts/{post}', [AnalyticsController::class, 'post'])->name('analytics.post');
    });
});

