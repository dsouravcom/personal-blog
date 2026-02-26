<?php

namespace App\Jobs;

use App\Mail\NewPostPublished;
use App\Models\Post;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

/**
 * SendPostPublishedNotifications
 * ────────────────────────────────
 * Notifies all active subscribers that a new post has been published.
 *
 * Daily-limit strategy
 * ─────────────────────
 *  - The SMTP provider allows DAILY_EMAIL_LIMIT sends per day (default 300).
 *  - A counter is stored in the cache under the key:
 *      post_notifications_daily_count:YYYY-MM-DD
 *    and expires automatically at midnight (via a 24-hour TTL from first write
 *    of each day, renewed each day the job runs).
 *  - When dispatched the job:
 *      1. Calculates remaining quota for today.
 *      2. Fetches the next batch of active subscribers starting from $offset.
 *      3. Sends up to `remaining` emails, incrementing the cache counter.
 *      4. If more subscribers remain after the batch, re-dispatches itself
 *         for the start of the next calendar day with the updated $offset.
 */
class SendPostPublishedNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted before failing.
     */
    public int $tries = 3;

    /**
     * @param Post $post   The newly published post.
     * @param int  $offset How many subscribers have already been notified
     *                     (used when the job is re-queued across days).
     */
    public function __construct(
        public readonly Post $post,
        public readonly int  $offset = 0
    ) {}

    public function handle(): void
    {
        // ── 1. Determine today's remaining quota ─────────────────────────────
        $limit     = (int) config('blog.daily_email_limit', 300);
        $cacheKey  = 'post_notifications_daily_count:' . now()->toDateString();
        $sentToday = (int) Cache::get($cacheKey, 0);
        $remaining = $limit - $sentToday;

        // If today's quota is already exhausted, reschedule for tomorrow
        if ($remaining <= 0) {
            static::dispatch($this->post, $this->offset)
                ->delay(Carbon::tomorrow()->startOfDay());
            return;
        }

        // ── 2. Fetch the next batch of active subscribers ────────────────────
        $subscribers = Subscriber::active()
            ->orderBy('id')
            ->skip($this->offset)
            ->take($remaining)
            ->get();

        if ($subscribers->isEmpty()) {
            // All subscribers have been notified — nothing left to do
            return;
        }

        // ── 3. Send emails and track the count ───────────────────────────────
        $sent = 0;

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new NewPostPublished($this->post, $subscriber));
            $sent++;
        }

        // Increment the daily counter (set TTL to cover the rest of today + buffer)
        $secondsUntilMidnight = now()->secondsUntilEndOfDay() + 1;
        Cache::put($cacheKey, $sentToday + $sent, $secondsUntilMidnight);

        // ── 4. If more subscribers remain, re-queue for tomorrow ─────────────
        $newOffset      = $this->offset + $sent;
        $totalActive    = Subscriber::active()->count();

        if ($newOffset < $totalActive) {
            static::dispatch($this->post, $newOffset)
                ->delay(Carbon::tomorrow()->startOfDay());
        }
    }
}
