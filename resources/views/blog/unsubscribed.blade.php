@extends('layouts.app')

@section('title', 'Unsubscribed')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12 sm:py-20 min-h-[60vh] flex flex-col items-center justify-center text-center">
    
    <div class="mb-8">
        <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-zinc-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2 font-mono">Unsubscribed</h1>
        <p class="text-zinc-600 dark:text-zinc-400 max-w-md mx-auto">
            You have been successfully removed from the newsletter. <br class="hidden sm:block">
            You won't receive any more emails from me.
        </p>
    </div>

    <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-lg p-6 max-w-sm w-full">
        <p class="text-sm text-zinc-500 dark:text-zinc-500 mb-4">
            Made a mistake? You can always resubscribe from the home page or click below.
        </p>
        <a href="{{ route('blog.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-medium rounded-md hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors">
            Return to Home
        </a>
    </div>

</div>
@endsection
