<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>SECURELINK // OTP_REQ</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=IBM+Plex+Mono:ital,wght@0,100;0,400;0,700;1,100&display=swap" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --bg-dark: #0a0a0a;
        }

        body {
            background-color: var(--bg-dark);
            color: #dcdcdc;
            font-family: 'IBM Plex Mono', monospace;
            overflow: hidden;
            user-select: none;
        }
        
        .crt-effect {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06));
            background-size: 100% 2px, 3px 100%;
            z-index: 999;
            pointer-events: none;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative bg-black">

    <div class="crt-effect"></div>

    <div class="w-full max-w-md bg-[#111] border border-[#333] shadow-2xl p-8 relative z-50">
        <div class="absolute -top-3 left-4 bg-black px-2 text-yellow-500 text-xs font-bold border border-yellow-900">
            SECURITY CHECKPOINT
        </div>

        <div class="mb-6 text-center">
            <p class="text-yellow-500 font-bold text-2xl mb-2">2FA REQUIRED</p>
            <p class="text-xs text-gray-500 font-mono tracking-widest mt-2">
                A verification code has been sent to your secure channel.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.otp.verify') }}" class="space-y-5">
            @csrf
            
            <div class="group">
                <label class="block text-[10px] uppercase text-gray-600 mb-1">Enter One-Time Password</label>
                <input type="text" name="otp" required autofocus
                    class="w-full bg-black border border-gray-800 text-white p-2 font-mono text-center text-xl tracking-[0.5em] focus:border-yellow-600 focus:outline-none transition-colors"
                    placeholder="______" maxlength="6" pattern="[0-9]*" inputmode="numeric" api-otp>
            </div>

            <button type="submit" class="w-full bg-[#222] text-gray-400 hover:bg-yellow-900 hover:text-white border border-[#333] py-3 text-xs uppercase font-bold tracking-widest transition-all duration-200">
                Authenticate
            </button>

            @if($errors->any())
                <div class="mt-4 text-red-500 text-xs text-center border border-red-900/50 bg-red-900/10 p-2 font-mono">
                    > ERROR: {{ $errors->first() }}
                </div>
            @endif
        </form>
    </div>

</body>
</html>
