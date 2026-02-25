<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        // ── Cover image ──────────────────────────────────────────────────────
        'cover_image',          // full public URL returned by R2ImageService::upload()
        'cover_image_r2_key',   // R2 object key (bucket path) used for deletion
        'cover_image_alt',
        'cover_image_caption',
        // ── Publication ──────────────────────────────────────────────────────
        'is_published',
        'published_at',
        // ── SEO ──────────────────────────────────────────────────────────────
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        // ── OpenGraph / social ───────────────────────────────────────────────
        'og_title',
        'og_description',
        'og_image',             // full public URL returned by R2ImageService::upload()
        'og_image_r2_key',      // R2 object key (bucket path) used for deletion
        'structured_data',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Hide internal R2 bucket keys from serialised/public output
    protected $hidden = [
        'cover_image_r2_key',
        'og_image_r2_key',
    ];

    // Auto-generate slug from title before creating
    protected static function booted(): void
    {
        static::creating(function (Post $post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::updating(function (Post $post) {
            if ($post->isDirty('title') && ! $post->isDirty('slug')) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // Scope published posts
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderByDesc('published_at');
    }

    // Reading time helper
    public function readingTime(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, (int) ceil($words / 200));
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->latest();
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(PostLike::class);
    }
}
