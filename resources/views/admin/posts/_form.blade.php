@if($errors->any())
    <div class="mb-6 rounded-xl border border-red-900/50 bg-red-950/30 p-4 text-sm">
        <p class="mb-2 flex items-center gap-2 font-medium text-red-400">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
            Please fix the following before saving:
        </p>
        <ul class="list-inside list-disc space-y-1 text-red-300/80">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
    {{-- ── Left: editor ─────────────────────────────────────────────────────── --}}
    <div class="space-y-6 xl:col-span-2">

        {{-- Title --}}
        <div>
            <label for="title" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Title <span class="text-amber-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $post->title ?? '') }}" required
                   class="w-full rounded-lg border border-[#2a2622] bg-[#0d0c0b] p-3.5 text-white placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-600/20"
                   placeholder="An unmissable headline">
            @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Slug --}}
        <div>
            <label for="slug" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">URL slug</label>
            <div class="relative">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-mono text-sm text-zinc-600">/</span>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $post->slug ?? '') }}"
                       class="w-full rounded-lg border border-[#2a2622] bg-[#0d0c0b] py-3 pl-7 pr-4 font-mono text-sm text-zinc-300 placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-600/20"
                       placeholder="auto-generated-from-title">
            </div>
            @error('slug') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Excerpt --}}
        <div>
            <label for="excerpt" class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Excerpt</label>
            <textarea name="excerpt" id="excerpt" rows="3"
                      class="w-full resize-none rounded-lg border border-[#2a2622] bg-[#0d0c0b] p-3.5 text-sm text-zinc-300 placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-600/20"
                      placeholder="A one or two sentence summary shown in listings and previews.">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
            @error('excerpt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Content --}}
        <div>
            <div class="mb-1.5 flex items-center justify-between">
                <label for="content" class="block font-mono text-xs uppercase tracking-wider text-zinc-500">Content <span class="text-amber-500">*</span></label>
                <span class="rounded border border-[#2a2622] px-2 py-0.5 font-mono text-[10px] text-zinc-600">Rich text</span>
            </div>

            <div class="overflow-hidden rounded-lg border border-[#2a2622] bg-[#0d0c0b] focus-within:border-amber-600 focus-within:ring-2 focus-within:ring-amber-600/20">
                <div id="tiptap-toolbar" class="flex flex-wrap gap-1 border-b border-[#221f1c] bg-[#111010] p-2"></div>
                <div id="tiptap-editor" class="min-h-[500px] font-mono text-zinc-300 focus:outline-none"></div>
                <input type="hidden" name="content" id="content" value="{{ old('content', $post->content ?? '') }}" required>
            </div>
            @error('content') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <script>
            window.UPLOAD_URL = "{{ route('admin.posts.upload-image') }}";
        </script>
        @vite('resources/js/admin/post-editor.js')

        <style>
            .ProseMirror { outline: none !important; min-height: 500px; padding: 1rem; }
            .ProseMirror p.is-editor-empty:first-child::before {
                content: attr(data-placeholder); float: left; color: #4b463f; pointer-events: none; height: 0;
            }
            .ProseMirror pre {
                background: #17150f; border-radius: 0.6rem; color: #e5e7eb;
                font-family: 'JetBrains Mono', monospace; padding: 0.75rem 1rem; border: 1px solid #2a2622; overflow-x: auto;
            }
            .ProseMirror code { color: #fbbf24; background-color: rgba(255,255,255,0.06); padding: 0.1em 0.3em; border-radius: 0.2em; font-family: 'JetBrains Mono', monospace; }
            .ProseMirror pre code { color: inherit; background-color: transparent; padding: 0; }
            .ProseMirror blockquote { border-left: 2px solid #f59e0b; padding-left: 1rem; font-style: italic; color: #a1998f; }
            .ProseMirror img { max-width: 100%; height: auto; border-radius: 0.5rem; border: 1px solid #2a2622; }
            .ProseMirror img.ProseMirror-selectednode { outline: 2px solid #f59e0b; }
            .ProseMirror h1 { font-size: 2em; font-weight: 800; margin-top: 1.5em; margin-bottom: 0.5em; color: white; line-height: 1.2; }
            .ProseMirror h2 { font-size: 1.5em; font-weight: 700; margin-top: 1.5em; margin-bottom: 0.5em; color: #f3f4f6; line-height: 1.3; }
            .ProseMirror h3 { font-size: 1.25em; font-weight: 600; margin-top: 1em; margin-bottom: 0.5em; color: #e5e7eb; line-height: 1.4; }
            .ProseMirror ul, .ProseMirror ol { padding-left: 1.5rem; margin-bottom: 1rem; list-style-position: outside; }
            .ProseMirror ul { list-style-type: disc; }
            .ProseMirror ol { list-style-type: decimal; }
            .ProseMirror li { margin-bottom: 0.25rem; }
            .ProseMirror strong, .ProseMirror b { font-weight: 700; color: #fff; }
            .ProseMirror em, .ProseMirror i { font-style: italic; color: #d1d5db; }
            .ProseMirror p { margin-bottom: 1rem; line-height: 1.75; }
            .ProseMirror a { color: #fbbf24; text-decoration: underline; text-underline-offset: 4px; }
            .ProseMirror hr { margin: 2rem 0; border: 0; border-top: 1px solid #2a2622; }
        </style>

        {{-- Advanced --}}
        <div class="space-y-3 border-t border-[#221f1c] pt-8">
            <h3 class="mb-2 text-sm font-semibold text-white">Search &amp; social</h3>

            {{-- SEO --}}
            <details class="group overflow-hidden rounded-xl border border-[#272320] bg-[#111010]">
                <summary class="flex cursor-pointer select-none items-center justify-between p-4 text-sm text-zinc-300 [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center gap-2">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m21 21-4.3-4.3"/></svg>
                        SEO optimization
                    </span>
                    <svg class="text-zinc-500 transition-transform group-open:rotate-180" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="grid grid-cols-1 gap-5 border-t border-[#221f1c] p-5">
                    <div>
                        <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Meta title <span class="normal-case text-zinc-600">(search result title)</span> <span id="meta-title-count" class="float-right text-[10px] text-zinc-600">{{ strlen(old('meta_title', $post->meta_title ?? '')) }}/60</span></label>
                        <input type="text" name="meta_title" id="meta_title" maxlength="60" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Defaults to the post title">
                    </div>
                    <div>
                        <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Meta description <span class="normal-case text-zinc-600">(search snippet)</span> <span id="meta-desc-count" class="float-right text-[10px] text-zinc-600">{{ strlen(old('meta_description', $post->meta_description ?? '')) }}/160</span></label>
                        <textarea name="meta_description" id="meta_description" rows="2" maxlength="160" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Defaults to the excerpt">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Meta keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="laravel, php, databases">
                        </div>
                        <div>
                            <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">
                                Canonical URL
                                <span id="canonical-lock-badge" class="ml-1 text-[10px] text-emerald-500">[auto]</span>
                            </label>
                            <div class="flex gap-1.5">
                                <input type="text" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $post->canonical_url ?? '') }}" readonly
                                       class="flex-1 rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none"
                                       placeholder="https://blog.sourav.dev/posts/slug">
                                <button type="button" id="canonical-unlock-btn" onclick="toggleCanonicalLock()"
                                        class="rounded-lg border border-[#2a2622] px-2.5 py-1 text-[11px] text-zinc-500 transition-colors hover:border-amber-600 hover:text-amber-400" title="Unlock to edit">🔒</button>
                            </div>
                            <p class="mt-1 text-[10px] text-zinc-600">Auto-synced from the slug. Unlock to override.</p>
                            @error('canonical_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </details>

            {{-- Social --}}
            <details class="group overflow-hidden rounded-xl border border-[#272320] bg-[#111010]">
                <summary class="flex cursor-pointer select-none items-center justify-between p-4 text-sm text-zinc-300 [&::-webkit-details-marker]:hidden">
                    <span class="flex items-center gap-2">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path stroke-linecap="round" d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></svg>
                        Social share card (Open Graph)
                    </span>
                    <svg class="text-zinc-500 transition-transform group-open:rotate-180" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </summary>
                <div class="grid grid-cols-1 gap-5 border-t border-[#221f1c] p-5">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">OG title <span id="og-title-count" class="float-right text-[10px] text-zinc-600">{{ strlen(old('og_title', $post->og_title ?? '')) }}/60</span></label>
                            <input type="text" name="og_title" id="og_title" maxlength="60" value="{{ old('og_title', $post->og_title ?? '') }}" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Override for social cards">
                        </div>
                        <div>
                            <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">OG image</label>

                            <input type="hidden" name="og_image" id="og_image_value" value="{{ old('og_image', $post->og_image ?? '') }}">
                            <input type="hidden" name="og_image_r2_key" id="og_image_r2_key" value="{{ old('og_image_r2_key', $post->og_image_r2_key ?? '') }}">

                            <div id="og-preview" class="{{ (isset($post->og_image) && $post->og_image) ? '' : 'hidden' }} relative mb-2">
                                <img id="og-preview-img" src="{{ $post->og_image ?? '' }}" class="h-20 w-full rounded-lg border border-[#2a2622] object-cover opacity-90">
                                <div class="absolute bottom-0 left-0 w-full truncate bg-black/70 px-2 py-0.5 font-mono text-[10px] text-white" id="og-preview-name">{{ $post->og_image ? basename($post->og_image) : '' }}</div>
                                <button type="button" onclick="clearOgImage()" class="absolute right-1 top-1 grid h-5 w-5 place-items-center rounded bg-red-700 text-xs font-bold leading-none text-white hover:bg-red-500">×</button>
                            </div>

                            <div class="relative">
                                <label for="og_image_file" class="flex w-full cursor-pointer items-center gap-2 rounded-lg border border-dashed border-[#2a2622] bg-[#0a0a0a] px-3 py-2 font-mono text-xs text-zinc-500 transition-colors hover:border-amber-600 hover:bg-[#131211]">
                                    <span id="og-upload-icon">↑</span>
                                    <span id="og-upload-label">Upload OG image</span>
                                </label>
                                <input type="file" id="og_image_file" class="sr-only" accept="image/*">
                                <div id="og-upload-loader" class="absolute inset-0 hidden items-center justify-center gap-2 rounded-lg bg-[#0a0a0a]/90 flex">
                                    <svg class="h-4 w-4 animate-spin text-amber-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span class="font-mono text-xs text-amber-400">Uploading…</span>
                                </div>
                            </div>
                            <p id="og-upload-status" class="mt-1 hidden font-mono text-xs"></p>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">OG description <span id="og-desc-count" class="float-right text-[10px] text-zinc-600">{{ strlen(old('og_description', $post->og_description ?? '')) }}/160</span></label>
                        <textarea name="og_description" id="og_description" rows="2" maxlength="160" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Override for social cards">{{ old('og_description', $post->og_description ?? '') }}</textarea>
                    </div>
                </div>
            </details>
        </div>
    </div>

    {{-- ── Right: settings ──────────────────────────────────────────────────── --}}
    <div class="space-y-6 xl:col-span-1">

        {{-- Publish --}}
        <div class="panel p-5">
            <h3 class="mb-4 border-b border-[#221f1c] pb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Publish</h3>
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <span class="block text-sm text-zinc-200">Published</span>
                    <span class="text-xs text-zinc-500">Visible to everyone</span>
                </div>
                <label class="relative inline-flex cursor-pointer items-center">
                    <input type="checkbox" name="is_published" value="1" class="peer sr-only" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
                    <div class="h-6 w-11 rounded-full bg-[#2a2622] after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-zinc-400 after:transition-all after:content-[''] peer-checked:bg-emerald-600 peer-checked:after:translate-x-full peer-checked:after:bg-white peer-focus:ring-2 peer-focus:ring-emerald-600/40"></div>
                </label>
            </div>
            <button type="submit" class="btn-brand flex w-full items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-semibold">
                {{ $method === 'POST' ? 'Create post' : 'Save changes' }}
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
            @if(isset($post) && $post->exists)
                <div class="mt-4 border-t border-[#221f1c] pt-4 text-center">
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-zinc-500 transition-colors hover:text-white">Cancel</a>
                </div>
            @endif
        </div>

        {{-- Tags --}}
        <div class="panel p-5">
            <h3 class="mb-4 border-b border-[#221f1c] pb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Tags</h3>

            <input type="hidden" name="tags" id="tags_hidden" value="{{ old('tags', isset($post) && $post->exists ? $post->tags->pluck('name')->implode(',') : '') }}">

            <div id="tags_container" class="mb-3 flex min-h-8 flex-wrap gap-2 rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2"></div>

            <div class="flex flex-col gap-2">
                <select id="tags_select" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none">
                    <option value="">Select an existing tag…</option>
                    @foreach($allTags as $tag)
                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <input type="text" id="tags_input" class="flex-1 rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 placeholder:text-zinc-600 focus:border-amber-600 focus:outline-none" placeholder="Or type a new tag…"
                           onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addTag(); }">
                    <button type="button" onclick="addTag()" class="rounded-lg border border-[#2a2622] bg-[#1a1815] px-3 py-1 text-xs text-zinc-300 transition-colors hover:bg-amber-500 hover:text-black">Add</button>
                </div>
            </div>
        </div>

        {{-- Cover image --}}
        <div class="panel p-5">
            <h3 class="mb-4 border-b border-[#221f1c] pb-3 text-xs font-semibold uppercase tracking-widest text-zinc-400">Cover image</h3>

            <div class="space-y-4">
                <div>
                    <input type="hidden" name="cover_image" id="cover_image_value" value="{{ old('cover_image', $post->cover_image ?? '') }}">
                    <input type="hidden" name="cover_image_r2_key" id="cover_image_r2_key" value="{{ old('cover_image_r2_key', $post->cover_image_r2_key ?? '') }}">

                    <div id="cover-preview" class="{{ (isset($post->cover_image) && $post->cover_image) ? '' : 'hidden' }} group/img relative mb-2 overflow-hidden rounded-lg border border-[#2a2622]">
                        <img id="cover-preview-img" src="{{ $post->cover_image ?? '' }}" class="h-32 w-full object-cover opacity-80 transition-opacity group-hover/img:opacity-100">
                        <div class="absolute bottom-0 left-0 w-full truncate bg-black/70 px-2 py-0.5 font-mono text-[10px] text-white" id="cover-preview-name">{{ $post->cover_image ? basename($post->cover_image) : '' }}</div>
                        <button type="button" onclick="clearCoverImage()" class="absolute right-1 top-1 grid h-5 w-5 place-items-center rounded bg-red-700 text-xs font-bold leading-none text-white hover:bg-red-500">×</button>
                    </div>

                    <div class="relative">
                        <label for="cover_image_file" class="flex w-full cursor-pointer items-center gap-2 rounded-lg border border-dashed border-[#2a2622] bg-[#0a0a0a] px-3 py-2.5 font-mono text-xs text-zinc-500 transition-colors hover:border-amber-600 hover:bg-[#131211]">
                            <span id="cover-upload-icon">↑</span>
                            <span id="cover-upload-label">Upload cover image</span>
                        </label>
                        <input type="file" id="cover_image_file" class="sr-only" accept="image/*">
                        <div id="cover-upload-loader" class="absolute inset-0 hidden items-center justify-center gap-2 rounded-lg bg-[#0a0a0a]/90 flex">
                            <svg class="h-4 w-4 animate-spin text-amber-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            <span class="font-mono text-xs text-amber-400">Uploading…</span>
                        </div>
                    </div>
                    <p id="cover-upload-status" class="mt-1 hidden font-mono text-xs"></p>

                    <label class="mb-1.5 mt-3 block font-mono text-xs uppercase tracking-wider text-zinc-500">Alt text</label>
                    <input type="text" name="cover_image_alt" value="{{ old('cover_image_alt', $post->cover_image_alt ?? '') }}" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Describe the image for screen readers">
                    @error('cover_image_alt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block font-mono text-xs uppercase tracking-wider text-zinc-500">Caption</label>
                    <input type="text" name="cover_image_caption" value="{{ old('cover_image_caption', $post->cover_image_caption ?? '') }}" class="w-full rounded-lg border border-[#2a2622] bg-[#0a0a0a] p-2.5 text-xs text-zinc-300 focus:border-amber-600 focus:outline-none" placeholder="Visible caption text">
                    @error('cover_image_caption') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const BLOG_DOMAIN    = 'https://blog.sourav.dev';
    const titleInput     = document.getElementById('title');
    const slugInput      = document.getElementById('slug');
    const canonicalInput = document.getElementById('canonical_url');
    const unlockBtn      = document.getElementById('canonical-unlock-btn');
    const lockBadge      = document.getElementById('canonical-lock-badge');
    let canonicalLocked  = true;

    function slugify(str) {
        return str.toLowerCase().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
    }

    function syncCanonical(slug) {
        if (canonicalLocked && slug) {
            canonicalInput.value = BLOG_DOMAIN + '/posts/' + slug;
        }
    }

    function toggleCanonicalLock() {
        canonicalLocked = !canonicalLocked;
        canonicalInput.readOnly = canonicalLocked;
        unlockBtn.textContent   = canonicalLocked ? '🔒' : '🔓';
        lockBadge.textContent   = canonicalLocked ? '[auto]' : '[manual]';
        lockBadge.className     = 'ml-1 text-[10px] ' + (canonicalLocked ? 'text-emerald-500' : 'text-amber-400');
        if (canonicalLocked) syncCanonical(slugInput.value);
        if (!canonicalLocked) canonicalInput.focus();
    }

    // Title → slug + canonical
    titleInput.addEventListener('input', function () {
        if (!slugInput.getAttribute('data-touched')) {
            const slug = slugify(this.value);
            slugInput.value = slug;
            syncCanonical(slug);
        }
    });

    // Slug manual edit → canonical
    slugInput.addEventListener('input', function () {
        this.setAttribute('data-touched', 'true');
        syncCanonical(this.value);
    });

    // On page load: seed canonical if empty, or detect manual override
    (function () {
        const currentSlug = slugInput.value;
        const currentCanonical = canonicalInput.value;
        const expected = BLOG_DOMAIN + '/posts/' + currentSlug;

        if (!currentCanonical && currentSlug) {
            canonicalInput.value = expected;
        } else if (currentCanonical && currentCanonical !== expected) {
            canonicalLocked = false;
            canonicalInput.readOnly = false;
            unlockBtn.textContent   = '🔓';
            lockBadge.textContent   = '[manual]';
            lockBadge.className     = 'ml-1 text-[10px] text-amber-400';
        }
    })();

    // ─── Image Upload via AJAX ───────────────────────────────────────────────
    const UPLOAD_URL = '{{ route("admin.posts.upload-image") }}';

    function uploadImageToR2(opts) {
        const { file, imageType, loaderEl, statusEl, previewEl,
                previewImgEl, previewNameEl, urlInput, keyInput } = opts;

        loaderEl.classList.remove('hidden');
        statusEl.classList.add('hidden');

        const fd = new FormData();
        fd.append('file', file);
        fd.append('type', imageType);
        fd.append('_token', document.querySelector('input[name="_token"]').value);

        fetch(UPLOAD_URL, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.url) throw new Error(data.error || 'Unknown error');

                urlInput.value = data.url;
                keyInput.value = data.key;

                previewImgEl.src = data.url;
                previewNameEl.textContent = data.key.split('/').pop();
                previewEl.classList.remove('hidden');

                statusEl.textContent  = '✓ Uploaded';
                statusEl.className    = 'mt-1 text-xs font-mono text-emerald-500';
                statusEl.classList.remove('hidden');
                setTimeout(() => statusEl.classList.add('hidden'), 3000);
            })
            .catch(err => {
                statusEl.textContent  = '✗ Error: ' + err.message;
                statusEl.className    = 'mt-1 text-xs font-mono text-red-500';
                statusEl.classList.remove('hidden');
            })
            .finally(() => loaderEl.classList.add('hidden'));
    }

    document.getElementById('cover_image_file').addEventListener('change', function () {
        if (!this.files[0]) return;
        uploadImageToR2({
            file:         this.files[0],
            imageType:    'cover_image',
            loaderEl:     document.getElementById('cover-upload-loader'),
            statusEl:     document.getElementById('cover-upload-status'),
            previewEl:    document.getElementById('cover-preview'),
            previewImgEl: document.getElementById('cover-preview-img'),
            previewNameEl:document.getElementById('cover-preview-name'),
            urlInput:     document.getElementById('cover_image_value'),
            keyInput:     document.getElementById('cover_image_r2_key'),
        });
    });

    document.getElementById('og_image_file').addEventListener('change', function () {
        if (!this.files[0]) return;
        uploadImageToR2({
            file:         this.files[0],
            imageType:    'og_image',
            loaderEl:     document.getElementById('og-upload-loader'),
            statusEl:     document.getElementById('og-upload-status'),
            previewEl:    document.getElementById('og-preview'),
            previewImgEl: document.getElementById('og-preview-img'),
            previewNameEl:document.getElementById('og-preview-name'),
            urlInput:     document.getElementById('og_image_value'),
            keyInput:     document.getElementById('og_image_r2_key'),
        });
    });

    // ─── Char counters for SEO/OG fields ─────────────────────────────────────
    (function () {
        const fields = [
            { id: 'meta_title',       countId: 'meta-title-count', max: 60  },
            { id: 'meta_description', countId: 'meta-desc-count',  max: 160 },
            { id: 'og_title',         countId: 'og-title-count',   max: 60  },
            { id: 'og_description',   countId: 'og-desc-count',    max: 160 },
        ];
        fields.forEach(({ id, countId, max }) => {
            const el    = document.getElementById(id);
            const badge = document.getElementById(countId);
            if (!el || !badge) return;
            el.addEventListener('input', function () {
                const len = this.value.length;
                badge.textContent = len + '/' + max;
                badge.className   = 'float-right text-[10px] ' +
                    (len >= max ? 'text-red-500' : len >= max * 0.9 ? 'text-amber-500' : 'text-zinc-600');
            });
        });
    })();

    function clearCoverImage() {
        document.getElementById('cover_image_value').value  = '';
        document.getElementById('cover_image_r2_key').value = '';
        document.getElementById('cover-preview').classList.add('hidden');
        document.getElementById('cover_image_file').value   = '';
        document.getElementById('cover-upload-status').classList.add('hidden');
    }

    function clearOgImage() {
        document.getElementById('og_image_value').value   = '';
        document.getElementById('og_image_r2_key').value  = '';
        document.getElementById('og-preview').classList.add('hidden');
        document.getElementById('og_image_file').value    = '';
        document.getElementById('og-upload-status').classList.add('hidden');
    }

    // ─── Tag Management Logic ───────────────────────────────────────────────
    const tagsSet = new Set();
    const tagsHiddenInput = document.getElementById('tags_hidden');
    const tagsContainer   = document.getElementById('tags_container');
    const tagsSelect      = document.getElementById('tags_select');
    const tagsInput       = document.getElementById('tags_input');

    function renderTags() {
        tagsContainer.innerHTML = '';
        if (tagsSet.size === 0) {
            tagsContainer.innerHTML = '<span class="p-1 text-xs italic text-zinc-600">No tags yet</span>';
        } else {
            tagsSet.forEach(tag => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex cursor-pointer items-center gap-1 rounded-md border border-[#2a2622] bg-[#1a1815] px-2 py-1 font-mono text-xs text-amber-400 transition-colors hover:border-red-500 group';
                badge.title = 'Click to remove';
                badge.onclick = () => removeTag(tag);
                badge.innerHTML = `<span class="text-zinc-500">#</span>${tag}<span class="ml-1 text-zinc-600 group-hover:text-red-500">×</span>`;
                tagsContainer.appendChild(badge);
            });
        }
        tagsHiddenInput.value = Array.from(tagsSet).join(',');
    }

    function addTag() {
        const selectVal = tagsSelect.value;
        const textVal   = tagsInput.value;
        let val = selectVal || textVal;

        if (val) {
            val.split(',').forEach(v => {
                const clean = v.trim();
                if (clean) tagsSet.add(clean);
            });
            renderTags();
            tagsSelect.value = '';
            tagsInput.value = '';
            if (textVal) tagsInput.focus();
        }
    }

    if (tagsHiddenInput.value) {
        tagsHiddenInput.value.split(',').forEach(t => {
            const clean = t.trim();
            if (clean) tagsSet.add(clean);
        });
        renderTags();
    } else {
        renderTags();
    }

    window.removeTag = function(tag) {
        tagsSet.delete(tag);
        renderTags();
    };

    if (tagsSelect) {
        tagsSelect.addEventListener('change', function() {
            if (this.value) addTag();
        });
    }

    if (tagsInput) {
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') { e.preventDefault(); addTag(); }
        });
    }
</script>
