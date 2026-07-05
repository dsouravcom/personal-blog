@extends('layouts.admin')

@section('title', 'New post')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.posts.index') }}" class="inline-flex items-center gap-1.5 text-xs text-zinc-500 transition-colors hover:text-white">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Back to posts
    </a>
    <h1 class="mt-3 text-2xl font-semibold tracking-tight text-white">New post</h1>
    <p class="mt-1 text-sm text-zinc-500">Draft, refine and publish when ready.</p>
</div>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.posts._form', ['post' => new \App\Models\Post(), 'method' => 'POST'])
</form>
@endsection
