<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>CRIT_PROCESS_DIED // 0xDEADBEEF</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Mono:ital,wght@0,100;0,400;0,700;1,100&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --bsod-blue: #0078d7; /* Standard BSOD */
            --term-green: #00ff00;
            --err-red: #ff3333;
            --bg-dark: #0a0a0a;
        }

        body {
            background-color: var(--bg-dark);
            color: #dcdcdc;
            font-family: 'IBM Plex Mono', monospace;
            overflow: hidden;
            cursor: progress; /* Feeling of stuck loading */
            user-select: none;
        }

        /* Glitch Animation */
        @keyframes glitch-skew {
            0% { transform: skew(0deg); }
            10% { transform: skew(-20deg); }
            20% { transform: skew(20deg); }
            30% { transform: skew(-5deg); }
            40% { transform: skew(5deg); }
            50% { transform: skew(0deg); }
            100% { transform: skew(0deg); }
        }
        .glitch-block {
            animation: glitch-skew 0.3s infinite;
            display: inline-block;
            background: red;
            color: black;
            padding: 0 4px;
        }

        /* Screen distortion */
        .crt-effect {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 2px, 3px 100%;
            z-index: 999;
            pointer-events: none;
        }

        /* Terminal Blink */
        .blink { animation: blinker 1s linear infinite; }
        @keyframes blinker { 50% { opacity: 0; } }

        /* Fake Console Overlay */
        #console-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 50;
            padding: 20px;
            overflow: hidden;
            border-bottom: 2px solid #333;
        }

        /* Login Modal (Hidden) */
        #login-modal {
            display: none; /* JS will reveal */
            z-index: 100;
        }

        /* Panic Kernel Dump Styling */
        .kernel-panic {
            color: #ccc;
            font-size: 12px;
            line-height: 1.4;
            opacity: 0.7;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 10px;
            z-index: 0;
            pointer-events: none;
            filter: blur(0.5px);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative bg-black">

    <div class="crt-effect"></div>

    {{-- KERNEL PANIC BACKGROUND --}}
    <div class="kernel-panic" id="kernel-bg">
        {{-- JS will fill this with random hex dump --}}
    </div>

    {{-- FOREGROUND CONSOLE --}}
    <div id="console-overlay" class="flex flex-col justify-end pb-20 font-mono text-sm md:text-base text-gray-400">
        <div id="log-container" class="space-y-1">
            <p>Initializing boot sequence...</p>
        </div>
        <div class="mt-2 border-t border-gray-700 pt-2 text-white">
            <span class="text-green-500">root@sys-admin:~#</span> <span id="typing-command" class="animate-pulse">_</span>
        </div>
    </div>

    {{-- HIDDEN LOGIN (Reveals after "crash") --}}
    <div id="login-modal" class="w-full max-w-md bg-[#111] border border-[#333] shadow-2xl p-8 relative">
        <div class="absolute -top-3 left-4 bg-black px-2 text-red-600 text-xs font-bold border border-red-900">
            SYSTEM HALTED
        </div>

        <div class="mb-6 text-center">
            <p class="text-red-600 font-bold text-4xl mb-2 glitch-block">FATAL ERROR</p>
            <p class="text-xs text-gray-500 font-mono tracking-widest mt-2">
                UNAUTHORIZED KERNEL ACCESS ATTEMPT DETECTED FROM <span class="text-white">{{ request()->ip() }}</span>
            </p>
            <p class="text-[10px] text-gray-600 mt-1">
                SESSION ID: {{ bin2hex(random_bytes(8)) }}
            </p>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf
            
            <div class="group">
                <label class="block text-[10px] uppercase text-gray-600 mb-1">Administrator Override</label>
                <input type="email" name="email" required autofocus
                    class="w-full bg-black border border-gray-800 text-white p-2 font-mono text-sm focus:border-red-600 focus:outline-none transition-colors"
                    placeholder="root">
            </div>

            <div class="group">
                <label class="block text-[10px] uppercase text-gray-600 mb-1">Encryption Key</label>
                <input type="password" name="password" required 
                    class="w-full bg-black border border-gray-800 text-white p-2 font-mono text-sm focus:border-red-600 focus:outline-none transition-colors"
                    placeholder="••••••">
            </div>

            <button type="submit" class="w-full bg-[#222] text-gray-400 hover:bg-red-900 hover:text-white border border-[#333] py-3 text-xs uppercase font-bold tracking-widest transition-all duration-200">
                Execute Recovery
            </button>

            <div class="flex justify-between mt-4 border-t border-gray-900 pt-4">
                 <a href="{{ route('blog.index') }}" class="text-[10px] text-gray-600 hover:text-white underline decoration-dotted">
                    > Abort Sequence
                </a>
                <span class="text-[10px] text-red-900 font-bold animate-pulse">
                    LOGGING ACTIVE
                </span>
            </div>
            @error('email')
                <div class="mt-2 text-red-500 text-xs text-center border border-red-900/50 bg-red-900/10 p-2 font-mono">
                    > ACCESS_DENIED: PROHIBITED
                </div>
            @enderror
        </form>
    </div>

    {{-- AUDIO ELEMENT (Optional simulated sound effects via JS visual cues) --}}
    
    <script>
        // --- 1. GENERATE BACKGROUND HEX DUMP ---
        const kernelBg = document.getElementById('kernel-bg');
        let hexContent = "";
        for(let i=0; i<400; i++) {
            hexContent += `0x${Math.floor(Math.random()*16777215).toString(16).toUpperCase().padStart(8, '0')} `;
            if(i % 8 === 0) hexContent += "<br>";
        }
        kernelBg.innerHTML = hexContent;


        // --- 2. TERMINAL SIMULATION ---
        const logContainer = document.getElementById('log-container');
        const typingCommand = document.getElementById('typing-command');
        const consoleOverlay = document.getElementById('console-overlay');
        const loginModal = document.getElementById('login-modal');

        const commands = [
            { text: "Reading memory address 0x00400000...", delay: 200 },
            { text: "Checking user privileges...", delay: 800 },
            { text: "User 'guest' found. Elevating...", delay: 1500 },
            { text: "ELEVATION FAILED. PERMISSION DENIED.", delay: 2500, class: "text-red-500 font-bold" },
            { text: "Alert: Intrusion detection system triggered.", delay: 3500, class: "text-yellow-500" },
            { text: "Tracing source IP: {{ request()->ip() }}...", delay: 4500 },
            { text: "Source identified. Logging MAC address...", delay: 6000 },
            { text: "Sending report to admin@secure-net...", delay: 7500 },
            { text: "Locking terminal session...", delay: 9000 },
            { text: "CRIT_PROCESS_DIED. SYSTEM HALTED.", delay: 10500, class: "text-red-600 bg-red-900/20" }
        ];

        // Typewriter effect
        commands.forEach((cmd, index) => {
            setTimeout(() => {
                const p = document.createElement('div');
                p.innerHTML = `<span class="opacity-50">[${(index * 0.452).toFixed(4)}]</span> ${cmd.text}`;
                if(cmd.class) p.className = cmd.class;
                logContainer.appendChild(p);
                
                // Keep scrolled to bottom
                consoleOverlay.scrollTop = consoleOverlay.scrollHeight;

                // Glitch effect on last message
                if(index === commands.length - 1) {
                    document.body.style.filter = "invert(1)";
                    setTimeout(() => { document.body.style.filter = "none"; }, 100);
                    
                    // Hide console and show login
                    setTimeout(() => {
                        consoleOverlay.style.display = 'none';
                        loginModal.style.display = 'block';
                        document.body.classList.add('bg-black'); // Ensure background is black
                        // Add shake to body
                         document.body.style.animation = "shake 0.5s cubic-bezier(.36,.07,.19,.97) both";
                    }, 2000);
                }
            }, cmd.delay);
        });

        // Typing cursor effect
        let curs = true;
        setInterval(() => {
            curs = !curs;
            typingCommand.style.opacity = curs ? 1 : 0;
        }, 500);

    </script>
</body>
</html>