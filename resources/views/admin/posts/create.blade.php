@extends('layouts.admin')

@section('title', 'INIT_TRANSMISSION')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div>
         <h1 class="text-xl font-mono text-white tracking-widest uppercase">
            // INITIATE_NEW_TRANSMISSION
        </h1>
        <p class="text-gray-500 text-xs font-mono mt-1">
            Preparing new data stream...
        </p>
    </div>
</div>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.posts._form', ['post' => new \App\Models\Post(), 'method' => 'POST'])
</form>

@endsection