<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    @yield('title')

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind CSS -->
    @vite('resources/js/marketing.js', 'build-marketing')
</head>

<body class="font-sans text-textDark bg-white antialiased overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('marketing') }}" class="bg-appPurple text-white rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="20 20 120 120" class="w-8 h-8">
                        <title>{{ config('app.name') }}</title>
                        <defs>
                            <linearGradient id="logoGradient" x1="0" y1="0" x2="140" y2="140"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"></stop>
                                <stop offset="1" stop-color="#7C3AED"></stop>
                            </linearGradient>
                        </defs>
                        <rect x="20" y="20" width="120" height="120" rx="24" fill="url(#logoGradient)"></rect>
                        <rect x="40" y="45" width="14" height="60" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="60" y="55" width="14" height="50" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="80" y="65" width="14" height="40" rx="4" fill="white" opacity="0.95"></rect>
                        <path d="M100 85 L115 100 L135 70" stroke="white" stroke-width="6" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </a>
                <span class="font-bold text-xl tracking-tight">{{ config('app.name') }}</span>
            </div>
            <div class="flex items-center gap-4">
                @if (Auth::check())
                    <a href="{{ route('dashboard-grid') }}" class="bg-appPurple hover:bg-opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-lg shadow-appPurple/30 transition-all transform hover:-translate-y-0.5">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-appPurple">Log
                        in</a>
                    <a href="{{ route('register-organization') }}"
                        class="bg-appPurple hover:bg-opacity-90 text-white text-sm font-semibold px-2.5 sm:px-5 py-2.5 rounded-full shadow-lg shadow-appPurple/30 transition-all transform hover:-translate-y-0.5">
                        Get Started <span class="hidden sm:inline">for Free</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="bg-appPurple text-white rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="20 20 120 120" class="w-8 h-8">
                        <title>{{ config('app.name') }}</title>
                        <defs>
                            <linearGradient id="logoGradient" x1="0" y1="0" x2="140" y2="140"
                                gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"></stop>
                                <stop offset="1" stop-color="#7C3AED"></stop>
                            </linearGradient>
                        </defs>
                        <rect x="20" y="20" width="120" height="120" rx="24" fill="url(#logoGradient)"></rect>
                        <rect x="40" y="45" width="14" height="60" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="60" y="55" width="14" height="50" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="80" y="65" width="14" height="40" rx="4" fill="white" opacity="0.95"></rect>
                        <path d="M100 85 L115 100 L135 70" stroke="white" stroke-width="6" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </div>
                <span class="font-bold text-lg text-textDark">{{ config('app.name') }}</span>
            </div>

            <div class="flex gap-8 text-sm text-textMuted font-medium">
                <a href="{{ route('privacy') }}" class="hover:text-appPurple transition-colors">Terms & Privacy</a>
            </div>

            <p class="text-sm text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>
