@extends('layouts.app', ['py' => 'py-16'])

@section('title', 'Unsubscribed — ' . config('site.name'))
@section('robots', 'noindex, nofollow')

@section('content')
<div class="flex min-h-[55vh] items-center justify-center">
    <div class="reveal w-full max-w-md text-center">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl border border-line bg-surface">
            <x-icon name="mail" class="w-6 h-6 text-muted" />
        </div>
        <h1 class="mt-6 text-2xl sm:text-3xl font-semibold tracking-tight text-ink">You’re unsubscribed</h1>
        <p class="mx-auto mt-3 max-w-sm leading-relaxed text-muted">
            You’ve been removed from the newsletter and won’t receive any more emails. No hard feelings — the door’s always open.
        </p>

        <div class="mt-8 rounded-2xl border border-line bg-surface p-6">
            <p class="text-sm text-muted">Changed your mind? You can resubscribe any time from the home page.</p>
            <a href="{{ route('blog.index') }}" class="mt-4 inline-flex btn btn-primary pressable">
                <x-icon name="arrow-left" class="w-4 h-4" /> Return home
            </a>
        </div>
    </div>
</div>
@endsection
