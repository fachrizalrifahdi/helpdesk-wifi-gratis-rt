<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>@yield('title', 'Helpdesk') - {{ config('app.name', 'WiFi RT') }}</title>
    
    <!-- PWA & Favicon -->
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WiFi RT">
    <link rel="apple-touch-icon" href="/icon.svg">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Global Loading Bar */
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: #2563eb;
            z-index: 9999;
            transform: translateX(-100%);
            transition: transform 0.4s ease-in-out;
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.5);
        }
        #page-loader.loading {
            transform: translateX(0);
            animation: progress-pulse 2s infinite linear;
        }
        @keyframes progress-pulse {
            0% { opacity: 1; }
            50% { opacity: 0.6; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-900 antialiased h-full">
    <div id="page-loader"></div>
    <script>
        window.addEventListener('beforeunload', () => {
            document.getElementById('page-loader').classList.add('loading');
        });
        document.addEventListener('submit', () => {
            document.getElementById('page-loader').classList.add('loading');
        });
    </script>
    <nav class="bg-white shadow-sm border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <a href="{{ route('landing') }}" class="flex items-center group">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="bg-blue-600 rounded-lg p-2 mr-3 transition-transform group-hover:scale-110">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-slate-900 tracking-tight group-hover:text-blue-600 transition-colors">Helpdesk WiFi <span class="text-blue-600">RT</span></span>
                    </div>
                </a>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-slate-500 hover:text-slate-700">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-10 page-transition">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-blue-100 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} Helpdesk WiFi RT. Semua Hak Dilindungi.</p>
        </div>
    </footer>
    <script>
        // Icons are now handled by Alpine x-init in view components
    </script>
</body>
</html>
