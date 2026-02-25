<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add two R2 object-key columns to the posts table.
 *
 * Why separate key columns?
 * ─────────────────────────
 * When we upload to Cloudflare R2 we get back two pieces of information:
 *   1. The *public URL*  → written into cover_image / og_image so views can
 *      render the image with a plain <img src="{{ $post->cover_image }}">.
 *   2. The *object key*  → the path inside the bucket (e.g. "posts/covers/abc.jpg").
 *      We ONLY need this to call Storage::disk('r2')->delete($key) later.
 *
 * Storing both lets us show images instantly without extra look-ups, and
 * delete them from R2 without parsing the URL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // R2 object key for the main cover image  (nullable = no image yet)
            $table->string('cover_image_r2_key')->nullable()->after('cover_image');

            // R2 object key for the OpenGraph / social-share image
            $table->string('og_image_r2_key')->nullable()->after('og_image');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['cover_image_r2_key', 'og_image_r2_key']);
        });
    }
};
