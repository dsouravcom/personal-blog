@props(['id' => 'subscribe'])

<section id="{{ $id }}" class="reveal">
    <div class="card relative overflow-hidden p-7 sm:p-10">
        {{-- Ambient accent glow --}}
        <div class="pointer-events-none absolute -top-24 -right-16 h-56 w-56 rounded-full bg-accent-soft blur-3xl"></div>

        <div class="relative flex flex-col gap-7 md:flex-row md:items-center md:justify-between md:gap-12">
            <div class="max-w-md">
                <div class="mb-3 inline-flex items-center gap-2 rounded-full border border-line bg-surface-2 px-3 py-1 font-mono text-[0.7rem] uppercase tracking-wider text-muted">
                    <x-icon name="mail" class="w-3.5 h-3.5 text-accent" />
                    Newsletter
                </div>
                <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-ink">
                    New writing, straight to your inbox.
                </h2>
                <p class="mt-2 text-[0.95rem] leading-relaxed text-muted">
                    Occasional deep dives on engineering and the web. No spam, unsubscribe anytime.
                </p>
            </div>

            <div class="w-full md:max-w-sm">
                @if (session('subscribed'))
                    <div class="animate-pop flex items-center gap-3 rounded-xl border border-accent/40 bg-accent-soft px-4 py-4 text-sm text-ink">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-accent text-white dark:text-[#1a1206]">
                            <x-icon name="check" class="w-4 h-4" />
                        </span>
                        <span><span class="font-semibold">You're on the list.</span> Check your inbox to confirm.</span>
                    </div>
                @else
                    <form action="{{ route('blog.subscribe') }}" method="POST" class="flex flex-col gap-2.5 sm:flex-row">
                        @csrf
                        {{-- Honeypot --}}
                        <input type="text" name="_hp_email" tabindex="-1" autocomplete="off"
                               class="hidden" aria-hidden="true">

                        <label for="{{ $id }}-email" class="sr-only">Email address</label>
                        <input id="{{ $id }}-email" type="email" name="email" required
                               value="{{ old('email') }}" placeholder="you@example.com"
                               class="flex-1 rounded-lg border border-line bg-surface px-4 py-3 text-sm text-ink
                                      placeholder:text-faint transition-colors duration-200
                                      focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/25">
                        <button type="submit" class="btn btn-accent">
                            Subscribe
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </button>
                    </form>
                    @error('email')
                        <p class="mt-2 font-mono text-xs text-red-500">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>
    </div>
</section>
