<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <title>{{ config('app.name') }} | Beautifully Simplified</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
                            <linearGradient id="logoGradient" x1="0" y1="0" x2="140" y2="140" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"></stop>
                                <stop offset="1" stop-color="#7C3AED"></stop>
                            </linearGradient>
                        </defs>
                        <rect x="20" y="20" width="120" height="120" rx="24" fill="url(#logoGradient)"></rect>
                        <rect x="40" y="45" width="14" height="60" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="60" y="55" width="14" height="50" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="80" y="65" width="14" height="40" rx="4" fill="white" opacity="0.95"></rect>
                        <path d="M100 85 L115 100 L135 70" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
                <span class="font-bold text-xl tracking-tight">{{ config('app.name') }}</span>
            </div>
            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-textMuted">
                <a href="#features" class="hover:text-appPurple transition-colors">Features</a>
                <a href="#views" class="hover:text-appPurple transition-colors">Layouts</a>
                <a href="#collaboration" class="hover:text-appPurple transition-colors">Collaboration</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hidden md:block text-sm font-medium hover:text-appPurple">Log in</a>
                <a href="#" class="bg-appPurple hover:bg-opacity-90 text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow-lg shadow-appPurple/30 transition-all transform hover:-translate-y-0.5">
                    Get Started Free
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-40 pb-20 lg:pt-48 lg:pb-32 overflow-hidden flex flex-col items-center text-center px-6">
        <!-- Abstract gradient background glow -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[800px] h-[600px] fluid-bg-soft opacity-30 blur-[100px] rounded-full z-0 pointer-events-none animate-gradient-x"></div>

        <div class="relative z-10 max-w-4xl mx-auto">
            <h1 class="text-5xl lg:text-7xl font-extrabold tracking-tight leading-tight mb-6">
                Project management, <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 via-appPurple to-purple-600">beautifully simplified.</span>
            </h1>
            <p class="text-lg lg:text-xl text-textMuted max-w-2xl mx-auto mb-10 leading-relaxed">
                A workspace designed to balance personal focus with team collaboration. All wrapped in an interface you’ll actually love looking at.
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mb-20">
                <a href="{{ route('register-organization') }}" class="w-full sm:w-auto bg-appPurple text-white text-lg font-semibold px-8 py-4 rounded-full shadow-xl shadow-appPurple/30 hover:shadow-2xl hover:shadow-appPurple/40 transition-all transform hover:-translate-y-1">
                    Try Project Manager Today
                </a>
                {{-- <a href="#features" class="w-full sm:w-auto text-textDark bg-white border border-gray-200 hover:bg-gray-50 text-lg font-semibold px-8 py-4 rounded-full shadow-sm transition-all flex items-center justify-center gap-2">
                    <i data-lucide="play-circle" class="w-5 h-5"></i> See how it works
                </a>
            </div> --}}
        </div>

        <!-- Hero Image Mockup -->
        <div class="relative w-full max-w-6xl mx-auto z-20 animate-float">
            <img
                src="{{ asset('images/screenshot-2026-06-14-2-02-jpeg.jpg') }}"
                alt="App Dashboard showing a kanban board with a fluid gradient background"
                class="w-full rounded-2xl shadow-2xl border border-white/40 ring-1 ring-black/5"
            >
        </div>
    </section>

    <!-- Core Features Container -->
    <section id="features" class="py-24 bg-appLightBg/50 relative">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Feature 1: Unified View -->
            <div class="flex flex-col lg:flex-row items-center gap-16 py-16">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-teal-100 text-teal-600 mb-6">
                        <i data-lucide="layout" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-5 leading-tight">The ultimate cross-project dashboard.</h2>
                    <p class="text-lg text-textMuted leading-relaxed">
                        Stop context-switching. See everything on your plate in one unified view. The 'Your Tasks' dashboard automatically aggregates tasks from every corner of your workspace. Whether it's a team project or a personal checklist, see your entire world at a single glance.
                    </p>
                </div>
                <div class="lg:w-1/2 w-full relative">
                    <img src="{{ asset('images/screenshot-2026-06-14-2-02-jpeg.jpg') }}" alt="Cross-project dashboard" class="w-full rounded-2xl shadow-xl border border-gray-200">
                </div>
            </div>

            <!-- Feature 2: Board vs Grid -->
            <div id="views" class="flex flex-col lg:flex-row-reverse items-center gap-16 py-24">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-appPurple/10 text-appPurple mb-6">
                        <i data-lucide="kanban" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-5 leading-tight">Work in your flow.</h2>
                    <p class="text-lg text-textMuted leading-relaxed">
                        Are you a visual planner or a data tracker? Switch effortlessly between an intuitive Kanban Board and a structured Grid view with a single click. Flexible layouts tailored to how you think keep your priorities straight, your way.
                    </p>
                </div>
                <!-- Staggered Image Component -->
                <div class="lg:w-1/2 w-full relative h-[300px] sm:h-[400px] lg:h-[500px]">
                    <img src="{{ asset('images/screenshot-2026-06-14-2-02-jpeg.jpg') }}" alt="Kanban Board View" class="absolute top-0 left-0 w-4/5 rounded-2xl shadow-2xl border border-gray-200 z-10 hover:z-30 transition-z duration-300">
                    <img src="{{ asset('images/screenshot-2026-06-14-2-01-jpeg.jpg') }}" alt="Grid View" class="absolute bottom-0 right-0 w-4/5 rounded-2xl shadow-2xl border border-gray-200 z-20 hover:z-30 transition-z duration-300 translate-y-8 sm:translate-y-0">
                </div>
            </div>

            <!-- Feature 3: Personal Privacy -->
            <div class="flex flex-col lg:flex-row items-center gap-16 py-24">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-100 text-purple-600 mb-6">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-5 leading-tight">Keep personal work private.</h2>
                    <p class="text-lg text-textMuted leading-relaxed">
                        Not everything needs to be a team discussion. Create distinct spaces for your private tasks alongside your collaborative organization projects. Ensure total privacy for your deep work while staying connected to the team.
                    </p>
                </div>
                <div class="lg:w-1/2 w-full">
                    <img src="{{ asset('images/screenshot-2026-06-14-2-00-jpeg.jpg') }}" alt="Private Projects Interface" class="w-full rounded-2xl shadow-xl border border-gray-200">
                </div>
            </div>

            <!-- Feature 4: Collaboration -->
            <div id="collaboration" class="flex flex-col lg:flex-row-reverse items-center gap-16 py-16">
                <div class="lg:w-1/2">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-100 text-blue-600 mb-6">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-5 leading-tight">Frictionless team collaboration.</h2>
                    <p class="text-lg text-textMuted leading-relaxed">
                        Bring your team along in seconds. No complicated onboarding sequences. Simply drop in an email, send an invite, and start delegating tasks instantly. Managing your organization’s roster is as clean as the rest of your workflow.
                    </p>
                </div>
                <div class="lg:w-1/2 w-full">
                    <img src="{{ asset('images/screenshot-2026-06-14-2-03-jpeg.jpg') }}" alt="Organization and Invites" class="w-full rounded-2xl shadow-xl border border-gray-200">
                </div>
            </div>

        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="relative py-32 overflow-hidden">
        <!-- Vibrant background applied to CTA -->
        <div class="absolute inset-0 fluid-bg-vibrant animate-gradient-x opacity-90"></div>
        <!-- Overlay pattern to give some texture -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-50"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center text-white">
            <h2 class="text-4xl lg:text-6xl font-bold mb-6 tracking-tight">Ready to clear your mind?</h2>
            <p class="text-xl lg:text-2xl mb-12 text-white/90 max-w-2xl mx-auto">
                Join thousands of users organizing their work in a tool they actually enjoy using. Start setting up your space in minutes.
            </p>
            <a href="{{ route('register-organization') }}" class="bg-white text-appPurple text-xl font-bold px-10 py-5 rounded-full shadow-2xl hover:bg-gray-50 hover:scale-105 transition-all transform duration-300">
                Get Started for Free
            </a>
            <p class="mt-6 text-sm text-white/70">No credit card required</p>
            {{-- Free forever plan available. not sure if we want to add this back in --}}
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-2">
                <div class="bg-appPurple text-white rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="20 20 120 120" class="w-8 h-8">
                        <title>{{ config('app.name') }}</title>
                        <defs>
                            <linearGradient id="logoGradient" x1="0" y1="0" x2="140" y2="140" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#2563EB"></stop>
                                <stop offset="1" stop-color="#7C3AED"></stop>
                            </linearGradient>
                        </defs>
                        <rect x="20" y="20" width="120" height="120" rx="24" fill="url(#logoGradient)"></rect>
                        <rect x="40" y="45" width="14" height="60" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="60" y="55" width="14" height="50" rx="4" fill="white" opacity="0.95"></rect>
                        <rect x="80" y="65" width="14" height="40" rx="4" fill="white" opacity="0.95"></rect>
                        <path d="M100 85 L115 100 L135 70" stroke="white" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"></path>
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

    <!-- Initialize Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
