@props(['name'])
@php
    $base = 'width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"';
    $attrs = $attributes->merge(['class' => 'shrink-0'])->toHtml();
@endphp
@switch($name)
    @case('sun')
        <svg {!! $base !!} {!! $attrs !!}><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
        @break
    @case('moon')
        <svg {!! $base !!} {!! $attrs !!}><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        @break
    @case('arrow-left')
        <svg {!! $base !!} {!! $attrs !!}><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        @break
    @case('arrow-right')
        <svg {!! $base !!} {!! $attrs !!}><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        @break
    @case('arrow-up-right')
        <svg {!! $base !!} {!! $attrs !!}><path d="M7 17 17 7M7 7h10v10"/></svg>
        @break
    @case('arrow-up')
        <svg {!! $base !!} {!! $attrs !!}><path d="M12 19V5M5 12l7-7 7 7"/></svg>
        @break
    @case('chevron-right')
        <svg {!! $base !!} {!! $attrs !!}><path d="m9 18 6-6-6-6"/></svg>
        @break
    @case('heart')
        <svg {!! $base !!} {!! $attrs !!}><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"/></svg>
        @break
    @case('comment')
        <svg {!! $base !!} {!! $attrs !!}><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/></svg>
        @break
    @case('eye')
        <svg {!! $base !!} {!! $attrs !!}><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
        @break
    @case('clock')
        <svg {!! $base !!} {!! $attrs !!}><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
        @break
    @case('calendar')
        <svg {!! $base !!} {!! $attrs !!}><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        @break
    @case('link')
        <svg {!! $base !!} {!! $attrs !!}><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1 1"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1-1"/></svg>
        @break
    @case('check')
        <svg {!! $base !!} {!! $attrs !!}><path d="M20 6 9 17l-5-5"/></svg>
        @break
    @case('copy')
        <svg {!! $base !!} {!! $attrs !!}><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        @break
    @case('search')
        <svg {!! $base !!} {!! $attrs !!}><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
        @break
    @case('command')
        <svg {!! $base !!} {!! $attrs !!}><path d="M15 6a3 3 0 1 1 3 3h-3V6ZM9 6a3 3 0 1 0-3 3h3V6ZM9 18a3 3 0 1 1-3-3h3v3ZM15 18a3 3 0 1 0 3-3h-3v3Z"/><rect x="9" y="9" width="6" height="6"/></svg>
        @break
    @case('menu')
        <svg {!! $base !!} {!! $attrs !!}><path d="M3 6h18M3 12h18M3 18h18"/></svg>
        @break
    @case('close')
        <svg {!! $base !!} {!! $attrs !!}><path d="M18 6 6 18M6 6l12 12"/></svg>
        @break
    @case('list')
        <svg {!! $base !!} {!! $attrs !!}><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
        @break
    @case('rss')
        <svg {!! $base !!} {!! $attrs !!}><path d="M4 11a9 9 0 0 1 9 9M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1.5" fill="currentColor" stroke="none"/></svg>
        @break
    @case('mail')
        <svg {!! $base !!} {!! $attrs !!}><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6 10-6"/></svg>
        @break
    @case('sparkles')
        <svg {!! $base !!} {!! $attrs !!}><path d="M12 3v4M12 17v4M3 12h4M17 12h4"/><path d="M12 8.5 13.2 11l2.5 1-2.5 1L12 15.5 10.8 13l-2.5-1 2.5-1Z" fill="currentColor" stroke="none"/></svg>
        @break
    @case('pen')
        <svg {!! $base !!} {!! $attrs !!}><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
        @break
    @case('github')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" {!! $attrs !!}><path d="M12 1.5a10.5 10.5 0 0 0-3.32 20.47c.53.1.72-.23.72-.5v-1.75c-2.92.64-3.54-1.4-3.54-1.4-.48-1.22-1.17-1.54-1.17-1.54-.96-.65.07-.64.07-.64 1.06.08 1.62 1.09 1.62 1.09.94 1.62 2.47 1.15 3.07.88.1-.68.37-1.15.67-1.42-2.33-.27-4.78-1.17-4.78-5.19 0-1.15.41-2.09 1.09-2.82-.11-.27-.47-1.34.1-2.8 0 0 .89-.28 2.9 1.08a10 10 0 0 1 5.28 0c2.01-1.36 2.9-1.08 2.9-1.08.57 1.46.21 2.53.1 2.8.68.73 1.09 1.67 1.09 2.82 0 4.03-2.46 4.92-4.8 5.18.38.33.71.97.71 1.96v2.9c0 .28.19.61.73.5A10.5 10.5 0 0 0 12 1.5Z"/></svg>
        @break
    @case('twitter')
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" {!! $attrs !!}><path d="M18.9 2H22l-7.1 8.1L23 22h-6.6l-5.2-6.8L5.3 22H2.2l7.6-8.7L1.7 2h6.8l4.7 6.2L18.9 2Zm-1.2 18h1.8L7.3 3.8H5.4L17.7 20Z"/></svg>
        @break
    @case('linkedin')
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" {!! $attrs !!}><path d="M4.98 3.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5ZM3 9h4v12H3V9Zm6 0h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.6 8.65 22 10.3 22 13.6V21h-4v-6.6c0-1.57-.03-3.6-2.2-3.6-2.2 0-2.54 1.72-2.54 3.49V21H9V9Z"/></svg>
        @break
    @default
        <svg {!! $base !!} {!! $attrs !!}><circle cx="12" cy="12" r="9"/></svg>
@endswitch
