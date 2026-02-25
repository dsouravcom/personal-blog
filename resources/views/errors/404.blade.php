@extends('layouts.app')

@section('title', '404 - Segfault')

@section('content')
<div class="relative min-h-[85vh] flex items-center justify-center overflow-hidden font-mono selection:bg-red-500 selection:text-white"
     x-data="glitchSystem"
     x-init="init()"
     @mousemove="handleMouseMove($event)">

    {{-- Background Noise / Static --}}
    <div class="fixed inset-0 opacity-[0.03] pointer-events-none z-0 mix-blend-overlay" 
         style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0IiBoZWlnaHQ9IjQiPgo8cmVjdCB3aWR0aD0iNCIgaGVpZ2h0PSI0IiBmaWxsPSIjZmZmIi8+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiMwMDAiLz4KPC9zdmc+');">
    </div>

    {{-- Large Background 404 (Parallax) --}}
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-0 overflow-hidden">
        <h1 class="text-[30vw] font-black leading-none text-zinc-100 dark:text-zinc-900/50 select-none blur-[2px]"
            :style="`transform: translate(${mouse.x * -20}px, ${mouse.y * -20}px) rotate(-5deg)`">
            404
        </h1>
    </div>

    {{-- Floating Code Fragments (Parallax Layer 2) --}}
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden hidden sm:block">
        <template x-for="n in 5">
            <div class="absolute text-xs text-zinc-300 dark:text-zinc-700 opacity-40 whitespace-pre font-mono"
                 :style="`top: ${Math.random() * 100}%; left: ${Math.random() * 100}%; transform: translate(${mouse.x * 10}px, ${mouse.y * 10}px)`">
                &lt;?php throw new NotFoundException(); ?&gt;
            </div>
        </template>
    </div>

    {{-- Main Glitch Container --}}
    <div class="relative z-10 w-full max-w-4xl px-6">
        <div class="relative group">
            
            {{-- The "Missing" Card --}}
            <div class="relative bg-white dark:bg-[#0a0a0a] border-2 border-zinc-900 dark:border-zinc-100 p-8 md:p-12 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] dark:shadow-[8px_8px_0px_0px_rgba(255,255,255,1)] transition-transform duration-100 ease-linear hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] dark:hover:shadow-[10px_10px_0px_0px_rgba(255,255,255,1)]">
                
                {{-- Decorative Corner Tags --}}
                <div class="absolute -top-3 -left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 uppercase tracking-widest border border-black dark:border-white">
                    Fatal_Error
                </div>
                <div class="absolute -bottom-3 -right-3 bg-zinc-900 dark:bg-white text-white dark:text-black text-xs font-bold px-2 py-1 font-mono border border-black dark:border-white">
                    0x404
                </div>

                <div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
                    
                    {{-- Glitch Visual --}}
                    <div class="relative shrink-0 mx-auto md:mx-0">
                        <div class="w-32 h-32 md:w-40 md:h-40 bg-zinc-100 dark:bg-zinc-900 border border-zinc-300 dark:border-zinc-700 flex items-center justify-center relative overflow-hidden">
                            <span class="text-6xl md:text-7xl font-bold text-zinc-900 dark:text-white relative z-10 mix-blend-difference">?</span>
                            
                            {{-- Animated Bars --}}
                            <div class="absolute inset-0 flex flex-col justify-between opacity-20 pointer-events-none">
                                <template x-for="i in 10">
                                    <div class="h-1 bg-black dark:bg-white w-full animate-pulse" :style="`animation-delay: ${i * 0.1}s`"></div>
                                </template>
                            </div>
                            
                            {{-- Glitch Slice --}}
                            <div class="absolute inset-0 bg-red-500/10 dark:bg-red-500/20 translate-x-2 translate-y-2 mix-blend-multiply animate-pulse"></div>
                            <div class="absolute inset-0 bg-blue-500/10 dark:bg-blue-500/20 -translate-x-2 -translate-y-2 mix-blend-multiply animate-pulse" style="animation-delay: 0.2s"></div>
                        </div>
                        
                        {{-- Connection Line --}}
                        <div class="absolute top-1/2 -right-8 w-8 h-px bg-zinc-900 dark:bg-zinc-100 hidden md:block border-t border-dashed border-zinc-400"></div>
                    </div>

                    {{-- Text Content --}}
                    <div class="space-y-6 text-center md:text-left w-full">
                        <div>
                            <h2 class="text-4xl md:text-5xl font-bold tracking-tighter mb-2 text-zinc-900 dark:text-white">
                                <span class="inline-block hover:text-red-500 transition-colors cursor-help" title="Hypertext Transfer Protocol Response Status Code">404</span> 
                                <span class="font-normal text-zinc-400 dark:text-zinc-600">/</span> 
                                MISSING
                            </h2>
                            <p class="text-lg text-zinc-600 dark:text-zinc-300 max-w-md mx-auto md:mx-0 leading-relaxed">
                                The digital coordinates <code class="bg-zinc-100 dark:bg-zinc-800 px-1 py-0.5 rounded text-red-500 text-sm border border-zinc-200 dark:border-zinc-700 break-all">{{ request()->path() }}</code> do not resolve to a valid memory address.
                            </p>
                        </div>

                        {{-- Technical Details Box --}}
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 p-4 border-l-2 border-zinc-300 dark:border-zinc-700 text-xs font-mono text-zinc-500 text-left overflow-x-auto">
                            <div class="flex gap-4 mb-2 border-b border-zinc-200 dark:border-zinc-800 pb-2">
                                <span class="font-bold text-zinc-700 dark:text-zinc-300">STACK TRACE</span>
                                <span class="ml-auto opacity-50">{{ date('Y-m-d H:i:s') }}</span>
                            </div>
                            <div class="space-y-1">
                                <p> <span class="text-blue-500">at</span> Illuminate\Routing\RouteCollection->match(Object(Illuminate\Http\Request))</p>
                                <p> <span class="text-blue-500">at</span> Illuminate\Routing\Router->findRoute(Object(Illuminate\Http\Request))</p>
                                <p> <span class="text-red-500 font-bold">at</span> User\Navigation\Error -> <span class="underline decoration-dotted">lost_direction()</span></p>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-col sm:flex-row gap-4 pt-2 justify-center md:justify-start">
                            <a href="{{ route('blog.index') }}" 
                               class="group relative inline-flex items-center justify-center px-6 py-3 bg-zinc-900 dark:bg-white text-white dark:text-black font-bold uppercase tracking-wide hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all overflow-hidden">
                                <span class="relative z-10 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    System Reboot
                                </span>
                                {{-- Button Hover Effect --}}
                                <div class="absolute inset-0 bg-red-500 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200 ease-out z-0 mix-blend-difference"></div>
                            </a>
                            
                            <button @click="history.back()" 
                                    class="px-6 py-3 border-2 border-zinc-900 dark:border-white text-zinc-900 dark:text-white font-bold uppercase tracking-wide hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                                <span>Return_Back()</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Background Shadow Card (Depth) --}}
            <div class="absolute inset-0 bg-zinc-200 dark:bg-zinc-800 -z-10 translate-x-4 translate-y-4 border-2 border-zinc-300 dark:border-zinc-700 hidden md:block"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('glitchSystem', () => ({
            mouse: { x: 0, y: 0 },
            
            init() {
                // Occasional glitch effect class toggle on body or container could go here
            },

            handleMouseMove(e) {
                // Calculate normalized mouse position (-1 to 1)
                this.mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
                this.mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
            }
        }))
    })
</script>
@endsection
