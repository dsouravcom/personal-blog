<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * R2ImageService
 * ──────────────
 * Single-responsibility service that handles ALL Cloudflare R2 image
 * operations for this blog.  Everything goes through two public methods:
 *
 *   upload()  → stores a file on R2 and returns the public URL + object key
 *   delete()  → removes a file from R2 using its object key
 *
 * Data-flow overview
 * ──────────────────
 *
 *  [Browser] ──(multipart/form-data)──▶ [PostController]
 *                                              │
 *                                    $this->r2->upload($file, 'posts/covers')
 *                                              │
 *                                              ▼
 *                                     [R2ImageService::upload()]
 *                                       1. Build a unique file name
 *                                       2. Stream the file to R2 via
 *                                          Storage::disk('r2')->put()
 *                                       3. Return [ 'url' => ..., 'key' => ... ]
 *                                              │
 *                                              ▼
 *                          url  ──▶  cover_image  column  (shown in <img>)
 *                          key  ──▶  cover_image_r2_key   (used to delete later)
 *
 *  When the post is deleted / image replaced:
 *
 *   [PostController::destroy() / update()]
 *     │
 *     └─▶ $this->r2->delete($post->cover_image_r2_key)
 *           │
 *           ▼
 *        Storage::disk('r2')->delete($key)   → file removed from bucket
 *
 * Configuration
 * ─────────────
 * The 'r2' disk is defined in config/filesystems.php and reads these .env vars:
 *   R2_ACCOUNT_ID, R2_ACCESS_KEY_ID, R2_SECRET_ACCESS_KEY, R2_BUCKET, R2_PUBLIC_URL
 */
class R2ImageService
{
    // The Laravel filesystem disk name that points to Cloudflare R2
    private const DISK = 'r2';

    /**
     * Upload an image to Cloudflare R2.
     *
     * @param  UploadedFile  $file       The uploaded file from the request.
     * @param  string        $directory  Sub-folder inside the bucket, e.g. "posts/covers".
     *                                  This keeps different image types organised.
     * @return array{url: string, key: string}
     *              url → fully-qualified public URL  (stored in cover_image / og_image)
     *              key → object key inside the bucket (stored in *_r2_key columns)
     */
    public function upload(UploadedFile $file, string $directory): array
    {
        // Build a unique, safe file name so concurrent uploads never collide.
        // Pattern: posts/covers/a1b2c3d4_1700000000.webp
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename  = Str::random(8) . '_' . time() . '.' . $extension;

        // The full path (key) inside the R2 bucket, e.g. "posts/covers/a1b2c3d4_1700000000.jpg"
        $key = trim($directory, '/') . '/' . $filename;

        // Stream the file to R2.
        // Do NOT pass a visibility/ACL string — R2 does not support per-object ACLs.
        // Access is controlled at bucket level via Cloudflare's "Public Access" toggle.
        try {
            Storage::disk(self::DISK)->put($key, file_get_contents($file->getRealPath()));
        } catch (\Throwable $e) {
            // Unwrap the chain to get the real AWS/HTTP error message
            // e.g. "403 Forbidden", "InvalidAccessKeyId", "NoSuchBucket"
            $cause = $e;
            while ($cause->getPrevious()) {
                $cause = $cause->getPrevious();
            }
            throw new \RuntimeException(
                'R2 upload failed: ' . $cause->getMessage() . ' [key: ' . $key . ']',
                0,
                $e
            );
        }

        // Build the public URL from R2_PUBLIC_URL + object key.
        // e.g. https://assets.sourav.dev/posts/covers/a1b2c3d4_1700000000.jpg
        $publicUrl = rtrim(config('filesystems.disks.r2.url'), '/') . '/' . $key;

        return [
            'url' => $publicUrl,   // → saved to cover_image / og_image
            'key' => $key,         // → saved to cover_image_r2_key / og_image_r2_key
        ];
    }

    /**
     * Delete an image from Cloudflare R2 using its object key.
     *
     * Safe to call with a null/empty key — it will simply do nothing,
     * which avoids scattered null-checks in the controller.
     *
     * @param  string|null  $key  The R2 object key stored in *_r2_key column.
     */
    public function delete(?string $key): void
    {
        // Guard: if no key is stored there's nothing to delete
        if (empty($key)) {
            return;
        }

        // Remove the file from the R2 bucket
        Storage::disk(self::DISK)->delete($key);
    }
}
