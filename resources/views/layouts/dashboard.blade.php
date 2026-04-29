<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">
    <title>@yield('title', 'Admin') - {{ config('app.name', 'WiFi RT') }}</title>

    <!-- PWA & Favicon -->
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <link rel="manifest" href="/manifest.json">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WiFi RT Dashboard">
    <link rel="apple-touch-icon" href="/icon.svg">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
        
        .sidebar { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .page-transition { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

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
<body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased" x-data="{ sidebarCollapsed: localStorage.getItem('sidebar-collapsed') === 'true', settingsOpen: false }" x-click-away="settingsOpen = false">
    <div id="page-loader"></div>
    <script>
        window.showLoader = () => {
            document.getElementById('page-loader').classList.add('loading');
        };

        window.addEventListener('beforeunload', () => {
            showLoader();
        });

        // Smart SweetAlert Confirm Helper
        window.confirmAction = (event, title, text, icon = 'warning') => {
            event.preventDefault();
            const form = event.target;
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-3xl border-none shadow-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 font-bold',
                    cancelButton: 'rounded-xl px-6 py-3 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoader(); // Only show loader if they actually click "Yes"
                    form.submit();
                }
            });
        };
    </script>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside 
            class="sidebar fixed top-0 left-0 z-40 h-screen transition-all duration-300 ease-in-out"
            :class="sidebarCollapsed ? 'w-20' : 'w-72'"
            x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebar-collapsed', val))">
            <div class="flex h-full flex-col bg-gradient-to-b from-slate-900 via-slate-900 to-slate-800 border-r border-slate-700/50 shadow-2xl">
                
                <!-- Logo Section -->
                <div class="flex h-20 items-center shrink-0 border-b border-slate-700/50"
                     :class="sidebarCollapsed ? 'justify-center px-0' : 'justify-between px-5'">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/30">
                            <i data-lucide="wifi" class="h-6 w-6"></i>
                        </div>
                        <div x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0" class="flex flex-col whitespace-nowrap">
                            <span class="text-xl font-bold tracking-tight text-white leading-none">WiFi <span class="text-blue-400">RT</span></span>
                            <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-widest mt-1">Management Hub</span>
                        </div>
                    </div>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="p-2 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all" x-show="!sidebarCollapsed">
                        <i data-lucide="panel-left-close" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Toggle Button (When collapsed) -->
                <div class="px-3 py-4" x-show="sidebarCollapsed" x-transition>
                    <button @click="sidebarCollapsed = !sidebarCollapsed" class="w-full p-3 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all flex justify-center">
                        <i data-lucide="panel-left-open" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 space-y-1 px-3 py-4 overflow-y-auto overflow-x-hidden">
                    <p x-show="!sidebarCollapsed" class="px-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Menu</p>
                    
                    <a href="{{ route('dashboard') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                        <i data-lucide="layout-dashboard" class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap tracking-tight">Dashboard</span>
                    </a>

                    <a href="{{ route('dashboard.tiket.index') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('dashboard.tiket.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                        <i data-lucide="ticket" class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard.tiket.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap tracking-tight">Manajemen Tiket</span>
                    </a>

                    <a href="{{ route('dashboard.settings') }}" 
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('dashboard.settings') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                       :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                        <i data-lucide="settings" class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard.settings') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                        <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap tracking-tight">Pengaturan</span>
                    </a>

                    @if(auth()->user()->role == 'Admin')
                    <div class="pt-4 mt-4 border-t border-slate-700/50">
                        <p x-show="!sidebarCollapsed" class="px-4 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Admin Area</p>
                        <a href="{{ route('dashboard.petugas.index') }}" 
                           class="group flex items-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-200 {{ request()->routeIs('dashboard.petugas.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/25' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                           :class="sidebarCollapsed ? 'justify-center px-3' : ''">
                            <i data-lucide="users" class="h-5 w-5 shrink-0 {{ request()->routeIs('dashboard.petugas.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"></i>
                            <span x-show="!sidebarCollapsed" x-transition class="whitespace-nowrap tracking-tight">Manajemen Petugas</span>
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- User Section -->
                <div class="p-3 border-t border-slate-700/50">
                    <div class="flex items-center gap-3 rounded-2xl bg-slate-800/50 p-3 transition-all border border-slate-700/30" :class="sidebarCollapsed ? 'justify-center p-2' : ''">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </div>
                        <div x-show="!sidebarCollapsed" x-transition class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->nama }}</p>
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide">{{ auth()->user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" x-show="!sidebarCollapsed" x-transition onsubmit="confirmAction(event, 'Keluar dari Sistem?', 'Anda harus login kembali untuk mengakses panel dashboard.', 'question')">
                            @csrf
                            <button type="submit" class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-xl transition-all" title="Keluar">
                                <i data-lucide="log-out" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area (Scrollable) -->
        <div class="flex flex-col flex-1 min-w-0 min-h-screen bg-slate-50 relative transition-all duration-300"
             :class="sidebarCollapsed ? 'ml-20' : 'ml-72'">
            <!-- Header (Fixed at top of content area) -->
            <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 h-24 flex items-center px-10 shrink-0 sticky top-0 z-20">
                <div class="flex-1">
                    <ol class="flex items-center space-x-3 text-[11px] font-bold uppercase tracking-widest text-slate-400">
                        <li>
                            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors flex items-center">
                                <i data-lucide="home" class="h-3 w-3 mr-1.5"></i>
                                Panel
                            </a>
                        </li>
                        <li><i data-lucide="chevron-right" class="h-3 w-3 text-slate-300"></i></li>
                        <li class="text-blue-600">
                            @yield('header_subtitle', 'Beranda')
                        </li>
                    </ol>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight mt-1">
                        @yield('header_title', 'Ringkasan Sistem')
                    </h2>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notification Dropdown -->
                    <div class="relative" x-data="{ notificationOpen: false }" @click.outside="notificationOpen = false">
                        <button @click="notificationOpen = !notificationOpen" class="h-11 w-11 rounded-2xl border border-slate-200 px-3 flex items-center justify-center bg-white text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all cursor-pointer shadow-sm active:scale-95 group relative">
                            <i data-lucide="bell" class="h-4 w-4 group-hover:animate-bounce"></i>
                            @if($unassigned_count > 0)
                                <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-lg shadow-red-500/30 animate-pulse">
                                    {{ $unassigned_count > 9 ? '9+' : $unassigned_count }}
                                </span>
                            @endif
                        </button>
                        
                        <div x-show="notificationOpen" 
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 scale-95" 
                             x-transition:enter-end="opacity-100 scale-100" 
                             x-transition:leave="transition ease-in duration-150" 
                             x-transition:leave-start="opacity-100 scale-100" 
                             x-transition:leave-end="opacity-0 scale-95" 
                             class="absolute right-0 mt-3 w-80 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-200/50 z-50 overflow-hidden" 
                             style="display: none;">
                            
                            <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                                <h3 class="text-sm font-bold text-slate-900">Notifikasi</h3>
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full uppercase tracking-wider">{{ $unassigned_count }} Baru</span>
                            </div>
                            
                            <div class="max-h-96 overflow-y-auto">
                                @forelse($unassigned_tickets as $tiket)
                                <div class="flex items-start gap-3 px-5 py-4 hover:bg-blue-50 transition-colors border-b border-slate-50 last:border-0 group relative">
                                    <div class="h-10 w-10 shrink-0 rounded-xl bg-red-100 text-red-600 flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all">
                                        <i data-lucide="alert-circle" class="h-5 w-5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0 pr-6">
                                        <a href="{{ route('dashboard.tiket.index', ['status' => 'Open']) }}" class="block">
                                            <div class="flex items-center gap-2">
                                                <span class="bg-blue-100 text-blue-700 text-[8px] font-bold px-1 rounded">{{ $tiket->ticket_no }}</span>
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ $tiket->nama_pelapor }}</p>
                                            </div>
                                            <p class="text-xs text-slate-500 line-clamp-1 mt-0.5">{{ $tiket->kategori }}: {{ $tiket->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                            <p class="text-[10px] text-blue-600 font-semibold mt-1 flex items-center">
                                                <i data-lucide="clock" class="h-3 w-3 mr-1"></i>
                                                {{ $tiket->tgl_lapor->diffForHumans() }}
                                            </p>
                                        </a>
                                    </div>
                                    <!-- Mark as Read Button -->
                                    <form action="{{ route('dashboard.tiket.mark-read', $tiket->id_tiket) }}" method="POST" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @csrf
                                        <button type="submit" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all" title="Tandai sudah dibaca">
                                            <i data-lucide="check" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                                @empty
                                <div class="px-5 py-10 text-center">
                                    <div class="h-12 w-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <i data-lucide="bell-off" class="h-6 w-6"></i>
                                    </div>
                                    <p class="text-sm text-slate-400 italic">Tidak ada notifikasi baru</p>
                                </div>
                                @endforelse
                            </div>
                            
                            @if($unassigned_count > 0)
                            <a href="{{ route('dashboard.tiket.index', ['status' => 'Open']) }}" class="block py-3 text-center text-xs font-bold text-blue-600 bg-slate-50 hover:bg-blue-50 hover:text-blue-700 transition-colors border-t border-slate-100">
                                Lihat Semua Tiket Baru
                            </a>
                            @endif
                        </div>
                    </div>
                    <!-- Settings Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false" @click.stop>
                        <button @click="open = !open" class="h-11 w-11 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-lg shadow-slate-900/20 active:scale-95 cursor-pointer hover:bg-slate-800 transition-all">
                            <i data-lucide="settings" class="h-5 w-5" :class="{ 'rotate-90': open }"></i>
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-56 rounded-2xl bg-white border border-slate-200 shadow-xl shadow-slate-200/50 py-2 z-50" style="display: none;">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->nama }}</p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->role }}</p>
                            </div>
                            <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <i data-lucide="user" class="h-4 w-4"></i>
                                Profil
                            </a>
                            <a href="{{ route('dashboard.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                <i data-lucide="settings" class="h-4 w-4"></i>
                                Pengaturan
                            </a>
                            <div class="border-t border-slate-100 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}" onsubmit="confirmAction(event, 'Keluar?', 'Apakah Anda yakin ingin keluar dari akun?', 'question')">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 w-full text-sm text-red-600 hover:bg-red-50 transition-colors text-left uppercase font-bold tracking-tight">
                                        <i data-lucide="log-out" class="h-4 w-4"></i>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content (Scrollable Container) -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden p-10 page-transition">
                <div class="max-w-screen-2xl mx-auto pb-20">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('modals')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
