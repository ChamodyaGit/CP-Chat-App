<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'ProChat') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen flex flex-col items-center">

    <!-- Navigation -->
    <header
        class="w-full max-w-7xl px-6 py-6 flex justify-between items-center glass sticky top-0 z-50 border-b border-gray-100">
        <div class="flex items-center gap-2">
            <div
                class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                <i class="fa-solid fa-comments text-white"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-gray-900">ProChat</span>
        </div>

        @if (Route::has('login'))
            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-full font-medium hover:bg-indigo-700 transition shadow-md">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-5 py-2 text-gray-600 font-medium hover:text-indigo-600 transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-6 py-2 border-2 border-indigo-600 text-indigo-600 rounded-full font-semibold hover:bg-indigo-50 transition">Get
                            Started</a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>

    <!-- Hero Section -->
    <main class="w-full max-w-7xl px-6 py-16 lg:py-24 flex flex-col lg:flex-row items-center gap-12">
        <div class="flex-1 text-center lg:text-left space-y-8">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold border border-indigo-100 animate-bounce">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-600"></span>
                </span>
                Next-Gen Messaging
            </div>

            <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight">
                Professional Chat <br>
                <span class="text-indigo-600 italic">Redefined.</span>
            </h1>

            <p class="text-lg text-gray-600 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Elevate your peer-to-peer communication with real-time delivery, seen receipts, and a clean interface
                built for productivity.
            </p>

            <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4 pt-4">
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold text-lg hover:bg-black transition-all shadow-xl hover:-translate-y-1">
                    Start Chatting Now
                </a>
                <div class="flex -space-x-3 items-center justify-center">
                    <img class="w-10 h-10 rounded-full border-4 border-white shadow-sm"
                        src="https://i.pravatar.cc/150?u=1" alt="">
                    <img class="w-10 h-10 rounded-full border-4 border-white shadow-sm"
                        src="https://i.pravatar.cc/150?u=2" alt="">
                    <img class="w-10 h-10 rounded-full border-4 border-white shadow-sm"
                        src="https://i.pravatar.cc/150?u=3" alt="">
                    <span class="pl-5 text-sm text-gray-500 font-medium">Joined by 2k+ professionals</span>
                </div>
            </div>
        </div>

        <!-- Visual Element (Feature Cards) -->
        <div class="flex-1 relative w-full max-w-lg">
            <div
                class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse">
            </div>
            <div
                class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse delay-700">
            </div>

            <div class="relative grid grid-cols-2 gap-4">
                <div
                    class="p-6 bg-white rounded-3xl shadow-xl border border-gray-100 hover:scale-105 transition-transform duration-500">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-bolt text-blue-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold mb-1">Ultra Fast</h3>
                    <p class="text-xs text-gray-500">Real-time message delivery under 100ms.</p>
                </div>

                <div
                    class="mt-8 p-6 bg-white rounded-3xl shadow-xl border border-gray-100 hover:scale-105 transition-transform duration-500">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-lock text-green-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold mb-1">Secure</h3>
                    <p class="text-xs text-gray-500">End-to-end focus on data privacy.</p>
                </div>

                <div
                    class="p-6 bg-white rounded-3xl shadow-xl border border-gray-100 hover:scale-105 transition-transform duration-500">
                    <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-check-double text-orange-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold mb-1">Seen Receipts</h3>
                    <p class="text-xs text-gray-500">Know exactly when your messages are read.</p>
                </div>

                <div
                    class="mt-8 p-6 bg-white rounded-3xl shadow-xl border border-gray-100 hover:scale-105 transition-transform duration-500">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center mb-4">
                        <i class="fa-solid fa-palette text-purple-500 text-xl"></i>
                    </div>
                    <h3 class="font-bold mb-1">Modern UI</h3>
                    <p class="text-xs text-gray-500">Clean, professional & dark-mode ready.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-auto w-full py-8 border-t border-gray-100 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} ProChat System. Built with Laravel & Tailwind.
    </footer>

</body>

</html>
