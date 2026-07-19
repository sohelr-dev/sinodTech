<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Denied | FnFTrip</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .floating {
            animation: floating 3.5s ease-in-out infinite;
        }

        @keyframes floating {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }

        .glow {
            filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.3));
        }

        /* Custom scrollbar for a cleaner look */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #111827;
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#0f172a] text-gray-200 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-xl w-full">
        <div class="text-center mb-10">
            <div class="inline-block floating glow">
                <div class="p-5 bg-gray-800/50 rounded-full border border-gray-700">
                    <svg class="w-16 h-16 md:w-20 md:h-20 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
            <p class="mt-4 text-indigo-400 font-bold tracking-widest uppercase text-sm">FnFTrip Security</p>
        </div>

        <div class="bg-gray-800/40 backdrop-blur-md rounded-[2.5rem] shadow-2xl border border-white/5 p-8 md:p-12 text-center">
            <h1 class="text-6xl md:text-7xl font-black gradient-text mb-2">403</h1>

            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">
                Access Denied
            </h2>

            <p class="text-gray-400 text-base md:text-lg mb-8 leading-relaxed">
                You don't have permission to access this page. This might be because your session expired or your account role is restricted.
            </p>

            {{-- @auth
            <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-2xl px-5 py-3 mb-10 inline-flex items-center gap-3">
                <div class="w-2 h-2 bg-indigo-500 rounded-full animate-pulse"></div>
                <p class="text-sm font-medium text-indigo-300">
                    Current Role: <span class="font-bold text-white uppercase">{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</span>
                </p>
            </div>
            @endauth --}}

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button
                    onclick="window.history.back()"
                    class="order-2 sm:order-1 px-8 py-4 bg-gray-700/50 hover:bg-gray-700 text-white font-semibold rounded-2xl transition-all duration-300 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="order-1 sm:order-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-2xl transition-all duration-300 shadow-lg shadow-indigo-500/25 transform hover:-translate-y-1 flex items-center justify-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home Dashboard
                </a>
            </div>

            <div class="mt-10 pt-8 border-t border-gray-700/50">
                <p class="text-gray-500 text-sm">
                    Is this a mistake?
                    <a href="https://fnftrip.com" class="text-indigo-400 hover:text-indigo-300 font-semibold underline underline-offset-4">
                        Contact FnFTrip Admin
                    </a>
                </p>
            </div>
        </div>

        <div class="text-center mt-8 text-gray-500 text-xs tracking-widest uppercase opacity-60">
            <p>&copy; 2026 FnFTrip | All Rights Reserved</p>
        </div>
    </div>

</body>
</html>
