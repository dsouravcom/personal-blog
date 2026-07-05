@extends('layouts.admin')

@section('title', 'Edit post')

@section('content')
<div class="mb-6 flex flex-wrap items-start justify-between gap-3">
    <div>
        <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-500 transition-colors hover:text-white">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to posts
        </a>
        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-white">Edit post</h1>
        <p class="mt-1 text-sm text-zinc-500">{{ $post->title }}</p>
    </div>
    @if($post->is_published)
        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg border border-[#2a2622] px-3 py-1.5 text-xs text-zinc-400 transition-colors hover:border-zinc-600 hover:text-white">
            View live
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 17 17 7M7 7h10v10"/></svg>
        </a>
    @endif
</div>

<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.posts._form', ['post' => $post, 'method' => 'PUT'])
</form>
@endsection
