@extends('layouts.app')

@section('title', 'Too Many Requests')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4">
    <div class="max-w-lg w-full bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 dark:bg-red-900 mb-6">
                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Whoa there, slow down!
            </h1>
            
            <p class="text-gray-600 dark:text-gray-300 mb-8">
                We noticed a lot of requests coming from your connection. Please wait a moment before trying again.
            </p>

            <div class="flex justify-center space-x-4">
                <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                    Return Home
                </a>
                <button onclick="window.location.reload()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors duration-200">
                    Try Again
                </button>
            </div>
        </div>
        
        <div class="bg-gray-50 dark:bg-gray-700 px-8 py-4 border-t border-gray-100 dark:border-gray-600">
            <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                Error 429: Too Many Requests
            </p>
        </div>
    </div>
</div>
@endsection
