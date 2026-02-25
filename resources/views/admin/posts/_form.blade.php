@if($errors->any())
    <div class="mb-6 bg-red-950/40 border border-red-500/40 text-red-300 rounded-lg p-4 font-mono text-xs">
        <p class="font-bold text-red-400 mb-2 flex items-center gap-2">
            <span class="text-red-500 text-base">✗</span> VALIDATION_ERRORS — fix the following before resubmitting:
        </p>
        <ul class="list-disc list-inside space-y-1 text-red-300/80">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Left Column: Editor --}}
    <div class="xl:col-span-2 space-y-6">
        
        {{-- Title Input --}}
        <div class="group">
            <label for="title" class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">>> TITLE_STRING <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="text" 
                       name="title" 
                       id="title" 
                       value="{{ old('title', $post->title ?? '') }}"
                       class="w-full bg-[#0a0a0a] border border-gray-800 rounded p-4 text-white placeholder-gray-700 font-mono focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all outline-none"
                       placeholder="Enter_Transmission_Title..."
                       required>
                <div class="absolute right-3 top-4 text-xs text-gray-700 font-mono hidden group-focus-within:block animate-pulse">
                    INPUT_ACTIVE
                </div>
            </div>
            @error('title') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
        </div>

        {{-- Slug Input --}}
        <div class="group">
            <label for="slug" class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">>> URL_SLUG_IDENTIFIER</label>
            <div class="relative">
                <span class="absolute left-4 top-3.5 text-gray-600 font-mono text-sm">/</span>
                <input type="text" 
                       name="slug" 
                       id="slug" 
                       value="{{ old('slug', $post->slug ?? '') }}"
                       class="w-full bg-[#0a0a0a] border border-gray-800 rounded py-3 pr-4 pl-8 text-gray-300 placeholder-gray-700 font-mono text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all outline-none"
                       placeholder="auto-generated-if-empty">
            </div>
            @error('slug') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
        </div>

        {{-- Excerpt Input --}}
        <div class="group">
             <label for="excerpt" class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">>> SUMMARY_BUFFER</label>
             <textarea name="excerpt" 
                       id="excerpt" 
                       rows="3" 
                       class="w-full bg-[#0a0a0a] border border-gray-800 rounded p-4 text-gray-400 placeholder-gray-700 font-mono text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all outline-none resize-none"
                       placeholder="Brief transmission summary...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
             @error('excerpt') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
        </div>

        {{-- Content Editor --}}
        <div class="group">
            <div class="flex items-center justify-between mb-1 ml-1">
                <label for="content" class="block text-xs font-mono text-gray-500 group-focus-within:text-primary-400 transition-colors">>> MAIN_CONTENT_BLOCK <span class="text-red-500">*</span></label>
                <div class="text-[10px] text-gray-600 font-mono border border-gray-800 px-2 py-0.5 rounded">
                    MODE: MARKDOWN/HTML
                </div>
            </div>
            <div class="relative">
                <div class="absolute left-0 top-0 bottom-0 w-8 bg-[#050505] border-r border-gray-800 flex flex-col items-center pt-4 text-[10px] text-gray-700 font-mono select-none overflow-hidden">
                    <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>...</span>
                </div>
                <textarea name="content" 
                          id="content" 
                          rows="20" 
                          class="w-full bg-[#0a0a0a] border border-gray-800 rounded p-4 pl-10 text-gray-300 placeholder-gray-800 font-mono text-sm leading-relaxed focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-all outline-none"
                          placeholder="Initialize transmission content..."
                          required>{{ old('content', $post->content ?? '') }}</textarea>
            </div>
            @error('content') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
        </div>

        {{-- ADVANCED CONFIGURATION SECTION --}}
        <div class="border-t-2 border-dashed border-gray-800 pt-8 mt-12 mb-8">
            <h3 class="text-lg font-mono text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                <span class="text-primary-500">>></span> CONFIGURATION_PROTOCOLS
            </h3>
            
            <div class="space-y-4">
                {{-- SEO Accordion --}}
                <details class="group bg-[#0a0a0a] border border-gray-800 rounded overflow-hidden open:ring-1 open:ring-primary-500/50">
                    <summary class="flex items-center justify-between p-4 cursor-pointer select-none bg-gray-900/30 hover:bg-gray-900/50 transition-colors">
                        <span class="font-mono text-sm text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            SEO_OPTIMIZATION
                        </span>
                        <svg class="w-4 h-4 text-gray-500 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="p-6 border-t border-gray-800 grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">META_TITLE <span class="text-gray-600">(Title displayed in search engine results)</span> <span id="meta-title-count" class="float-right text-[10px] text-gray-600">{{ strlen(old('meta_title', $post->meta_title ?? '')) }}/60</span></label>
                            <input type="text" name="meta_title" id="meta_title" maxlength="60" value="{{ old('meta_title', $post->meta_title ?? '') }}" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Default: Same as transmission title">
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">META_DESCRIPTION <span class="text-gray-600">(Summary for search snippets)</span> <span id="meta-desc-count" class="float-right text-[10px] text-gray-600">{{ strlen(old('meta_description', $post->meta_description ?? '')) }}/160</span></label>
                            <textarea name="meta_description" id="meta_description" rows="2" maxlength="160" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Default: Same as transmission summary">{{ old('meta_description', $post->meta_description ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">META_KEYWORDS</label>
                                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords ?? '') }}" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="e.g. laravel, php, code">
                            </div>
                            <div>
                                <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">
                                    CANONICAL_URL
                                    <span id="canonical-lock-badge" class="ml-1 text-[10px] text-green-500">[AUTO]</span>
                                </label>
                                <div class="flex gap-1">
                                    <input type="text"
                                           name="canonical_url"
                                           id="canonical_url"
                                           value="{{ old('canonical_url', $post->canonical_url ?? '') }}"
                                           class="flex-1 bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none"
                                           placeholder="https://blog.sourav.dev/posts/slug"
                                           readonly>
                                    <button type="button" id="canonical-unlock-btn"
                                            onclick="toggleCanonicalLock()"
                                            class="px-2 py-1 text-[11px] font-mono border border-gray-700 rounded text-gray-500 hover:text-yellow-400 hover:border-yellow-600 transition-colors"
                                            title="Unlock to edit manually">
                                        🔒
                                    </button>
                                </div>
                                <p class="text-[10px] text-gray-700 mt-1 font-mono">Auto-synced from slug. Click 🔒 to override manually.</p>
                                @error('canonical_url') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </details>

                {{-- Social Accordion --}}
                <details class="group bg-[#0a0a0a] border border-gray-800 rounded overflow-hidden open:ring-1 open:ring-primary-500/50">
                    <summary class="flex items-center justify-between p-4 cursor-pointer select-none bg-gray-900/30 hover:bg-gray-900/50 transition-colors">
                        <span class="font-mono text-sm text-gray-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                            SOCIAL_GRAPH_PROTOCOL (OG)
                        </span>
                        <svg class="w-4 h-4 text-gray-500 group-open:rotate-180 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </summary>
                    <div class="p-6 border-t border-gray-800 grid grid-cols-1 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">OG_TITLE <span class="text-gray-600">(Social Media Title)</span> <span id="og-title-count" class="float-right text-[10px] text-gray-600">{{ strlen(old('og_title', $post->og_title ?? '')) }}/60</span></label>
                                <input type="text" name="og_title" id="og_title" maxlength="60" value="{{ old('og_title', $post->og_title ?? '') }}" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Override for social cards">
                            </div>
                            <div>
                                <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">OG_IMAGE (Social Card Image)</label>

                                {{-- Hidden inputs: populated by JS after AJAX upload --}}
                                <input type="hidden" name="og_image"       id="og_image_value"   value="{{ old('og_image',       $post->og_image       ?? '') }}">
                                <input type="hidden" name="og_image_r2_key" id="og_image_r2_key" value="{{ old('og_image_r2_key', $post->og_image_r2_key ?? '') }}">

                                {{-- Preview: visible when an image exists --}}
                                <div id="og-preview" class="{{ (isset($post->og_image) && $post->og_image) ? '' : 'hidden' }} mb-2 relative">
                                    <img id="og-preview-img" src="{{ $post->og_image ?? '' }}"
                                         class="w-full h-20 object-cover rounded border border-gray-700 opacity-80">
                                    <div class="absolute bottom-0 left-0 bg-black/70 px-2 py-0.5 text-[10px] text-white font-mono w-full truncate" id="og-preview-name">
                                        {{ $post->og_image ? basename($post->og_image) : '' }}
                                    </div>
                                    <button type="button" onclick="clearOgImage()"
                                            class="absolute top-1 right-1 w-5 h-5 bg-red-700 hover:bg-red-500 rounded text-white text-xs flex items-center justify-center font-bold leading-none">×</button>
                                </div>

                                {{-- Upload zone --}}
                                <div class="relative">
                                    <label for="og_image_file"
                                           class="flex items-center gap-2 w-full px-3 py-2 bg-[#050505] border border-dashed border-gray-700 rounded cursor-pointer hover:border-primary-500 hover:bg-[#111] transition-colors text-gray-500 text-xs font-mono">
                                        <span id="og-upload-icon">▲</span>
                                        <span id="og-upload-label">ATTACH_OG_IMAGE</span>
                                    </label>
                                    <input type="file" id="og_image_file" class="sr-only" accept="image/*">
                                    {{-- Spinner --}}
                                    <div id="og-upload-loader" class="absolute inset-0 bg-[#050505]/90 rounded flex items-center justify-center gap-2">
                                        <svg class="animate-spin w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <span class="text-xs font-mono text-primary-400">UPLOADING...</span>
                                    </div>
                                </div>
                                <p id="og-upload-status" class="hidden mt-1 text-xs font-mono"></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-mono text-gray-500 mb-1 ml-1">OG_DESCRIPTION <span class="text-gray-600">(Social Media Description)</span> <span id="og-desc-count" class="float-right text-[10px] text-gray-600">{{ strlen(old('og_description', $post->og_description ?? '')) }}/160</span></label>
                            <textarea name="og_description" id="og_description" rows="2" maxlength="160" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Override for social cards">{{ old('og_description', $post->og_description ?? '') }}</textarea>
                        </div>
                    </div>
                </details>
            </div>
        </div>

    </div>

    {{-- Right Column: Settings --}}
    <div class="xl:col-span-1 space-y-6">
        
        {{-- Status Card --}}
        <div class="bg-[#0a0a0a]/80 backdrop-blur border border-gray-800 rounded-lg p-5">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-800 pb-2">
                // SYSTEM_CONTROLS
            </h3>
            
            {{-- Publish Toggle --}}
            <div class="flex items-center justify-between mb-6">
                <span class="text-sm text-gray-300 font-mono">BROADCAST_STATUS</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-800 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-gray-400 after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600 peer-checked:after:bg-white"></div>
                </label>
            </div>

            <button type="submit" class="w-full group relative inline-flex items-center justify-center px-4 py-3 font-mono text-sm font-bold text-black transition-all duration-200 bg-primary-500 rounded hover:bg-primary-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 focus:ring-offset-[#050505]">
                <span class="absolute right-3 opacity-0 group-hover:opacity-100 transition-opacity">-></span>
                {{ $method === 'POST' ? 'INITIATE_UPLOAD' : 'UPDATE_TRANSMISSION' }}
            </button>
            
            @if(isset($post) && $post->exists)
                 <div class="mt-4 pt-4 border-t border-gray-800 text-center">
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-red-500 hover:text-red-400 font-mono hover:underline">
                        [ ABORT_EDIT ]
                    </a>
                </div>
            @endif
        </div>

        {{-- Classifications --}}
        <div class="bg-[#0a0a0a] border border-gray-800 rounded-lg p-5">
             <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-800 pb-2 flex justify-between items-center">
                <span>// CLASSIFICATION</span>
                <span class="text-[10px] text-gray-600 font-mono">01</span>
            </h3>
            
            <div class="group">
                <label class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">TAG_INDEX</label>
                
                {{-- Hidden input stores the actual comma-separated values sent to server --}}
                <input type="hidden" name="tags" id="tags_hidden" 
                       value="{{ old('tags', isset($post) && $post->exists ? $post->tags->pluck('name')->implode(',') : '') }}">

                {{-- Visual container for selected tags --}}
                <div id="tags_container" class="flex flex-wrap gap-2 mb-3 min-h-[30px] p-2 bg-[#050505] border border-gray-800 rounded">
                    {{-- Tags will be injected here by JS --}}
                </div>

                <div class="flex flex-col gap-2">
                    {{-- Dropdown for existing tags --}}
                    <select id="tags_select" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none">
                        <option value="">[ SELECT_EXISTING_TAG ]</option>
                        @foreach($allTags as $tag)
                            <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>

                    {{-- Manual entry + Add Button --}}
                    <div class="flex gap-2">
                        <input type="text" id="tags_input" 
                               class="flex-1 bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none placeholder-gray-700"
                               placeholder="Or type new tag..."
                               onkeydown="if(event.key === 'Enter'){ event.preventDefault(); addTag(); }">
                        
                        <button type="button" onclick="addTag()" 
                                class="px-3 py-1 bg-gray-800 hover:bg-primary-500 hover:text-black text-gray-300 text-xs font-mono rounded transition-colors border border-gray-700">
                           + ADD
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Media Assets --}}
        <div class="bg-[#0a0a0a] border border-gray-800 rounded-lg p-5">
             <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 border-b border-gray-800 pb-2 flex justify-between items-center">
                <span>// MEDIA_ASSETS</span>
                <span class="text-[10px] text-gray-600 font-mono">ATTACH</span>
            </h3>
            
            <div class="space-y-4">
                <div class="group">
                    <label class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">PRIMARY_COVER_IMAGE</label>
                    
                    {{-- Hidden inputs: JS fills these after the AJAX upload completes --}}
                    <input type="hidden" name="cover_image"      id="cover_image_value"   value="{{ old('cover_image',       $post->cover_image       ?? '') }}">
                    <input type="hidden" name="cover_image_r2_key" id="cover_image_r2_key" value="{{ old('cover_image_r2_key', $post->cover_image_r2_key ?? '') }}">

                    {{-- Preview: visible while an image URL is stored --}}
                    <div id="cover-preview" class="{{ (isset($post->cover_image) && $post->cover_image) ? '' : 'hidden' }} mb-2 relative group/img overflow-hidden rounded border border-gray-700">
                        {{-- cover_image is a full public R2 URL —  no asset() wrapper needed --}}
                        <img id="cover-preview-img" src="{{ $post->cover_image ?? '' }}"
                             class="w-full h-32 object-cover opacity-70 group-hover/img:opacity-100 transition-opacity">
                        <div class="absolute bottom-0 left-0 bg-black/70 px-2 py-0.5 text-[10px] text-white font-mono w-full truncate" id="cover-preview-name">
                            {{ $post->cover_image ? basename($post->cover_image) : '' }}
                        </div>
                        <button type="button" onclick="clearCoverImage()"
                                class="absolute top-1 right-1 w-5 h-5 bg-red-700 hover:bg-red-500 rounded text-white text-xs flex items-center justify-center font-bold leading-none">×</button>
                    </div>

                    {{-- Upload zone: click to select, progress shown inline --}}
                    <div class="relative">
                        <label for="cover_image_file"
                               class="flex items-center gap-2 w-full px-3 py-2.5 bg-[#050505] border border-dashed border-gray-700 rounded cursor-pointer hover:border-primary-500 hover:bg-[#111] transition-colors text-gray-500 text-xs font-mono">
                            <span id="cover-upload-icon">▲</span>
                            <span id="cover-upload-label">ATTACH_IMAGE_FILE</span>
                        </label>
                        <input type="file" id="cover_image_file" class="sr-only" accept="image/*">

                        {{-- Loader overlay shown while the file is uploading to R2 --}}
                        <div id="cover-upload-loader" class="hidden absolute inset-0 bg-[#050505]/90 rounded flex items-center justify-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <span class="text-xs font-mono text-primary-400">UPLOADING_TO_R2...</span>
                        </div>
                    </div>
                    {{-- Upload result: checkmark on success, red message on error --}}
                    <p id="cover-upload-status" class="hidden mt-1 text-xs font-mono"></p>
                    <label class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">IMAGE_ALT_TEXT</label>
                    <input type="text" name="cover_image_alt" value="{{ old('cover_image_alt', $post->cover_image_alt ?? '') }}" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Description for screen readers">
                    @error('cover_image_alt') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
                </div>
                
                 <div class="group">
                    <label class="block text-xs font-mono text-gray-500 mb-1 ml-1 group-focus-within:text-primary-400 transition-colors">IMAGE_CAPTION</label>
                    <input type="text" name="cover_image_caption" value="{{ old('cover_image_caption', $post->cover_image_caption ?? '') }}" class="w-full bg-[#050505] border border-gray-800 rounded p-2 text-gray-300 text-xs font-mono focus:border-primary-500 outline-none" placeholder="Visible caption text">
                    @error('cover_image_caption') <p class="text-red-500 text-xs mt-1 font-mono">{{ $message }}</p> @enderror
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
        lockBadge.textContent   = canonicalLocked ? '[AUTO]' : '[MANUAL]';
        lockBadge.className     = 'ml-1 text-[10px] ' + (canonicalLocked ? 'text-green-500' : 'text-yellow-400');
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
            lockBadge.textContent   = '[MANUAL]';
            lockBadge.className     = 'ml-1 text-[10px] text-yellow-400';
        }
    })();

    // ─── Image Upload via AJAX ───────────────────────────────────────────────
    //
    // How it works:
    //  1. User clicks the upload zone → file picker opens
    //  2. On file selection → spinner appears over the upload zone
    //  3. JS sends the file to POST /admin/posts/upload-image via fetch()
    //  4. Server (PostController@uploadImage) → R2ImageService::upload()
    //     → Cloudflare R2 bucket → returns { url, key }
    //  5. JS hides the spinner, shows a preview image + ✓ checkmark
    //  6. The returned URL and key are stored in hidden inputs
    //  7. When the form is submitted, the hidden inputs are sent (not a file)
    //     → PostController@store/update read the URL+key directly from $request

    const UPLOAD_URL = '{{ route("admin.posts.upload-image") }}';

    /**
     * Upload a single image file to R2 via AJAX, then update the UI.
     *
     * @param {object} opts
     *   file         — the File object from the <input type="file">
     *   imageType    — 'cover_image' or 'og_image' (tells server which R2 folder)
     *   loaderEl     — the spinner overlay element
     *   statusEl     — the small text element below the upload zone
     *   previewEl    — the preview container element
     *   previewImgEl — the <img> inside the preview
     *   previewNameEl— the filename label inside the preview
     *   urlInput     — hidden <input> that will receive the public URL
     *   keyInput     — hidden <input> that will receive the R2 bucket key
     */
    function uploadImageToR2(opts) {
        const { file, imageType, loaderEl, statusEl, previewEl,
                previewImgEl, previewNameEl, urlInput, keyInput } = opts;

        // Show spinner, hide previous status
        loaderEl.classList.remove('hidden');
        statusEl.classList.add('hidden');

        const fd = new FormData();
        fd.append('file', file);
        fd.append('type', imageType);
        // Grab CSRF token from the @csrf hidden field that wraps this form
        fd.append('_token', document.querySelector('input[name="_token"]').value);

        fetch(UPLOAD_URL, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (!data.url) throw new Error(data.error || 'Unknown error');

                // ✅ Success — populate hidden inputs so form submission carries the data
                urlInput.value = data.url;
                keyInput.value = data.key;

                // Show preview image immediately
                previewImgEl.src = data.url;
                previewNameEl.textContent = data.key.split('/').pop();
                previewEl.classList.remove('hidden');

                // Show success badge
                statusEl.textContent  = '✓ UPLOAD_COMPLETE';
                statusEl.className    = 'mt-1 text-xs font-mono text-green-500';
                statusEl.classList.remove('hidden');
                // Auto-hide after 3 seconds
                setTimeout(() => statusEl.classList.add('hidden'), 3000);
            })
            .catch(err => {
                // ✗ Failure — show red error, keep hidden inputs unchanged
                statusEl.textContent  = '✗ ERROR: ' + err.message;
                statusEl.className    = 'mt-1 text-xs font-mono text-red-500';
                statusEl.classList.remove('hidden');
            })
            .finally(() => loaderEl.classList.add('hidden'));
    }

    // Bind cover image file input
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

    // Bind OG image file input
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
                    (len >= max ? 'text-red-500' : len >= max * 0.9 ? 'text-yellow-500' : 'text-gray-600');
            });
        });
    })();

    // Clear cover image (removes preview + empties hidden inputs)
    function clearCoverImage() {
        document.getElementById('cover_image_value').value  = '';
        document.getElementById('cover_image_r2_key').value = '';
        document.getElementById('cover-preview').classList.add('hidden');
        document.getElementById('cover_image_file').value   = '';
        document.getElementById('cover-upload-status').classList.add('hidden');
    }

    // Clear OG image
    function clearOgImage() {
        document.getElementById('og_image_value').value   = '';
        document.getElementById('og_image_r2_key').value  = '';
        document.getElementById('og-preview').classList.add('hidden');
        document.getElementById('og_image_file').value    = '';
        document.getElementById('og-upload-status').classList.add('hidden');
    }

    // ─── Tag Management Logic ───────────────────────────────────────────────
    
    // State: Set of unique tag names
    const tagsSet = new Set();
    const tagsHiddenInput = document.getElementById('tags_hidden');
    const tagsContainer   = document.getElementById('tags_container');
    const tagsSelect      = document.getElementById('tags_select');
    const tagsInput       = document.getElementById('tags_input');

    function renderTags() {
        tagsContainer.innerHTML = '';
        if (tagsSet.size === 0) {
            tagsContainer.innerHTML = '<span class="text-gray-600 text-xs italic p-1 font-mono">// NO_TAGS_SELECTED</span>';
        } else {
            tagsSet.forEach(tag => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center gap-1 px-2 py-1 bg-gray-900 text-primary-400 text-xs font-mono rounded border border-gray-700 hover:border-red-500 transition-colors group cursor-pointer';
                badge.title = 'Click to remove';
                badge.onclick = () => removeTag(tag);
                badge.innerHTML = `
                    <span class="text-gray-500">#</span>${tag} 
                    <span class="text-gray-600 group-hover:text-red-500 ml-1">×</span>
                `;
                tagsContainer.appendChild(badge);
            });
        }

        // Update hidden input sent to server
        tagsHiddenInput.value = Array.from(tagsSet).join(',');
    }

    function addTag() {
        // Try getting value from select first, then text input
        const selectVal = tagsSelect.value;
        const textVal   = tagsInput.value;
        
        let val = selectVal || textVal;
        
        if (val) {
            // Split by comma in case user pasted multiple
            val.split(',').forEach(v => {
                const clean = v.trim();
                if (clean) tagsSet.add(clean);
            });
            
            renderTags();
            
            // Reset inputs
            tagsSelect.value = '';
            tagsInput.value = '';
            if (textVal) tagsInput.focus(); // Keep focus if user was typing
        }
    }

    // Initialize from hidden input (populated by PHP)
    if (tagsHiddenInput.value) {
        tagsHiddenInput.value.split(',').forEach(t => {
            const clean = t.trim();
            if (clean) tagsSet.add(clean);
        });
        renderTags();
    } else {
        renderTags(); // Show empty state
    }

    // Expose remove function to global scope
    window.removeTag = function(tag) {
        tagsSet.delete(tag);
        renderTags();
    };

    // Auto-add when dropdown changes
    if (tagsSelect) {
        tagsSelect.addEventListener('change', function() {
            if (this.value) {
                addTag();
            }
        });
    }
    
    // Allow pressing Enter in text input to add
    if (tagsInput) {
        tagsInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent form submission
                addTag();
            }
        });
    }

</script>