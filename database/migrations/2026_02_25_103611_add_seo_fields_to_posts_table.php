<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // SEO Meta Tags
            $table->string('meta_title')->nullable()->after('slug');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->string('canonical_url')->nullable()->after('meta_keywords');

            // Social Media (Open Graph / Twitter Card)
            $table->string('og_title')->nullable()->after('canonical_url');
            $table->text('og_description')->nullable()->after('og_title');
            // og_image & og_image_r2_key already exist from create_posts_table

            // Image Metadata (to go with cover_image)
            $table->string('cover_image_alt')->nullable()->after('cover_image'); 
            $table->string('cover_image_caption')->nullable()->after('cover_image_alt'); 

            // Additional Classification
            $table->json('tags')->nullable()->after('cover_image_caption'); 
            $table->longText('structured_data')->nullable()->after('tags'); // Custom JSON-LD
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title', 
                'meta_description', 
                'meta_keywords', 
                'canonical_url',
                'og_title',
                'og_description',
                'cover_image_alt',
                'cover_image_caption',
                'tags',
                'structured_data'
            ]);
        });
    }
};
