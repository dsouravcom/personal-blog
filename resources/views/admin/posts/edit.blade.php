@extends('layouts.admin')

@section('title', 'EDIT_TRANSMISSION')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
         <h1 class="text-xl font-mono text-white tracking-widest uppercase">
            // MODIFY_TRANSMISSION: {{ $post->id }}
        </h1>
        <p class="text-gray-500 text-xs font-mono mt-1">
            Revising existing data node...
        </p>
    </div>
</div>

<form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.posts._form', ['post' => $post, 'method' => 'PUT'])
</form>

@endsection