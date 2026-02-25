<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use App\Services\R2ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Admin PostController
 * ────────────────────
 * Handles all CRUD operations for blog posts from the admin panel.
 *
 * Image storage data-flow
 * ───────────────────────
 *  1. User selects an image in the form and submits.
 *  2. store() / update() calls R2ImageService::upload()
 *     which streams the file to Cloudflare R2 and returns:
 *       [ 'url' => 'https://pub-xxx.r2.dev/posts/covers/abc.jpg',
 *         'key' => 'posts/covers/abc.jpg' ]
 *  3. The URL is stored in cover_image / og_image   → used in <img src>
 *     The key is stored in cover_image_r2_key / og_image_r2_key → used to delete
 *  4. On update: old R2 file deleted FIRST, then new file uploaded.
 *  5. On destroy: both R2 files deleted BEFORE the DB row is removed.
 */
class PostController extends Controller
{
    public function __construct(private readonly R2ImageService $r2) {}

    // ─── AJAX Image Upload ───────────────────────────────────────────────────

    /**
     * Immediately upload a single image to Cloudflare R2 and return JSON.
     *
     * Called by JavaScript in _form.blade.php the moment the user selects a file.
     * The response is used to populate two hidden inputs in the form:
     *   - name="cover_image"       (or og_image)       ← receives the public URL
     *   - name="cover_image_r2_key" (or og_image_r2_key) ← receives the bucket key
     *
     * Data-flow:
     *   User selects file → JS fetch POST here → R2ImageService::upload() →
     *   R2 bucket ← response {url, key} → JS fills hidden inputs + shows preview
     *
     * @return \Illuminate\Http\JsonResponse {url, key} on success, {error} on failure
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:4096',
            'type' => 'nullable|in:cover_image,og_image',
        ]);

        // Determine which sub-folder in the bucket to use based on image type
        $directory = $request->input('type') === 'og_image' ? 'posts/og' : 'posts/covers';

        try {
            $result = $this->r2->upload($request->file('file'), $directory);

            // Return the public URL and object key so JS can store them in hidden inputs
            return response()->json([
                'url' => $result['url'],   // → goes into name="cover_image" hidden input
                'key' => $result['key'],   // → goes into name="cover_image_r2_key" hidden input
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ─── List ────────────────────────────────────────────────────────────────

    public function index()
    {
        $posts = Post::latest()
            ->withCount(['views', 'likes', 'approvedComments as comments_count'])
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    // ─── Create ──────────────────────────────────────────────────────────────

    public function create()
    {
        return view('admin.posts.create');
    }

    // ─── Store (new post) ────────────────────────────────────────────────────

    public function store(Request $request)
    {
        // Validate all incoming fields
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'slug'                => ['nullable', 'string', 'max:255', 'unique:posts,slug'],
            'excerpt'             => 'nullable|string|max:500',
            'content'             => 'required|string',
            'is_published'        => 'nullable|boolean',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
            'meta_keywords'       => 'nullable|string|max:255',
            'canonical_url'       => 'nullable|string|max:255',
            // Images are pre-uploaded via AJAX; form sends the URL + bucket key as hidden inputs
            'cover_image'         => 'nullable|string|max:500',
            'cover_image_r2_key'  => 'nullable|string|max:255',
            'cover_image_alt'     => 'nullable|string|max:255',
            'cover_image_caption' => 'nullable|string|max:255',
            'og_image'            => 'nullable|string|max:500',
            'og_image_r2_key'     => 'nullable|string|max:255',
            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string|max:500',
            'tags'                => 'nullable|string',
        ], [
            'slug.unique' => 'This slug is already taken by another post. Please choose a different one.',
        ]);

        // Derive slug, publication state, and canonical URL
        $data['slug']         = Str::slug($data['slug'] ?? $data['title']);
        $data['is_published'] = $request->boolean('is_published');
        $data['published_at'] = $data['is_published'] ? now() : null;

        if (empty($data['canonical_url'])) {
            $data['canonical_url'] = 'https://blog.sourav.dev/posts/' . $data['slug'];
        }

        // Auto-fill SEO title from post title when left blank
        $data['meta_title'] = !empty($data['meta_title']) ? $data['meta_title'] : $data['title'];

        // Tags are handled separately via sync(); remove from mass-assignment data
        unset($data['tags']);

        // Images were already uploaded to R2 by the AJAX uploadImage() endpoint.
        // The form passes back the public URL (cover_image) and bucket key
        // (cover_image_r2_key) via hidden inputs — no file upload needed here.
        // If the user didn't select an image the hidden input is empty; clean those up.
        if (empty($data['cover_image'])) {
            unset($data['cover_image'], $data['cover_image_r2_key']);
        }
        if (empty($data['og_image'])) {
            unset($data['og_image'], $data['og_image_r2_key']);
        }

        // Persist the post row; fillable list in Post::$fillable includes the R2 columns
        $post = Post::create($data);

        // Sync tags: find or create each tag by name, then link to this post
        $post->tags()->sync($this->resolveTagIds($request->input('tags')));

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully!');
    }

    // ─── Edit ────────────────────────────────────────────────────────────────

    public function edit(Post $post)
    {
        $post->load('tags');
        return view('admin.posts.edit', compact('post'));
    }

    // ─── Update (existing post) ──────────────────────────────────────────────

    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'slug'                => ['nullable', 'string', 'max:255', 'unique:posts,slug,' . $post->id],
            'excerpt'             => 'nullable|string|max:500',
            'content'             => 'required|string',
            'is_published'        => 'nullable|boolean',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
            'meta_keywords'       => 'nullable|string|max:255',
            'canonical_url'       => 'nullable|string|max:255',
            // Images are pre-uploaded via AJAX; form sends URL + bucket key as hidden inputs
            'cover_image'         => 'nullable|string|max:500',
            'cover_image_r2_key'  => 'nullable|string|max:255',
            'cover_image_alt'     => 'nullable|string|max:255',
            'cover_image_caption' => 'nullable|string|max:255',
            'og_image'            => 'nullable|string|max:500',
            'og_image_r2_key'     => 'nullable|string|max:255',
            'og_title'            => 'nullable|string|max:255',
            'og_description'      => 'nullable|string|max:500',
            'tags'                => 'nullable|string',
        ], [
            'slug.unique' => 'This slug is already taken by another post. Please choose a different one.',
        ]);

        $data['slug']         = Str::slug($data['slug'] ?: $data['title']);
        $data['is_published'] = $request->boolean('is_published');

        // Only set published_at on first publish; clear it when unpublishing
        if ($data['is_published'] && !$post->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        if (empty($data['canonical_url'])) {
            $data['canonical_url'] = 'https://blog.sourav.dev/posts/' . $data['slug'];
        }

        // Auto-fill SEO title from post title when left blank
        $data['meta_title'] = !empty($data['meta_title']) ? $data['meta_title'] : $data['title'];

        unset($data['tags']);

        // ── Cover image ───────────────────────────────────────────────────────
        // The hidden input always carries either the NEW url (after AJAX upload)
        // or the EXISTING url (unchanged).  Compare with DB to detect a change.
        if (!empty($data['cover_image'])) {
            if ($data['cover_image'] !== $post->cover_image) {
                // A new image was uploaded; delete the old R2 file first
                $this->r2->delete($post->cover_image_r2_key);
            }
            // Either way, let the new url+key flow through $data into ->update()
        } else {
            // No image submitted at all — keep the current DB values untouched
            unset($data['cover_image'], $data['cover_image_r2_key']);
        }

        // ── OG image ─────────────────────────────────────────────────────────
        if (!empty($data['og_image'])) {
            if ($data['og_image'] !== $post->og_image) {
                $this->r2->delete($post->og_image_r2_key);
            }
        } else {
            unset($data['og_image'], $data['og_image_r2_key']);
        }

        $post->update($data);

        $post->tags()->sync($this->resolveTagIds($request->input('tags')));

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully!');
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────

    public function destroy(Post $post)
    {
        // Delete R2 files BEFORE removing the DB row so we still have the keys.
        // R2ImageService::delete() is a no-op when the key is null/empty.
        $this->r2->delete($post->cover_image_r2_key); // remove cover from R2
        $this->r2->delete($post->og_image_r2_key);    // remove OG image from R2

        // Now remove the database row (cascade deletes comments/views/likes via FK)
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Convert a comma-separated tag string into an array of Tag IDs.
     * Creates new Tag records on-the-fly for any name not yet in the DB.
     *
     * e.g. "Laravel, PHP, Deployment" → [1, 2, 5]
     */
    private function resolveTagIds(?string $tagInput): array
    {
        if (!$tagInput || !trim($tagInput)) {
            return [];
        }

        $tagNames = array_filter(array_map('trim', explode(',', $tagInput)));

        return collect($tagNames)->map(function (string $name) {
            return Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            )->id;
        })->toArray();
    }
}
