@extends('layouts.app')

@section('title', 'Layanan Helpdesk WiFi RT')

@section('content')
<div class="min-h-[80vh] flex flex-col items-center justify-center relative overflow-hidden px-4" x-data x-init="lucide.createIcons()">
    <!-- Background Decor -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[1000px] bg-blue-50/50 rounded-full blur-3xl -z-10"></div>
    <div class="absolute -bottom-48 -left-24 w-96 h-96 bg-indigo-50/50 rounded-full blur-3xl -z-10"></div>

    <div class="max-w-4xl w-full text-center space-y-12 relative">
        <!-- Hero Image/Icon Section -->
        <div class="relative inline-block" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
            <div class="absolute inset-0 bg-blue-500 rounded-full blur-2xl opacity-20 animate-pulse"></div>
            <div class="relative bg-white p-8 rounded-[2.5rem] shadow-2xl shadow-blue-100 border border-white/50 backdrop-blur-xl">
                <div class="w-24 h-24 bg-gradient-to-tr from-blue-600 to-blue-400 rounded-3xl flex items-center justify-center transform transition-transform duration-500" :class="hover ? 'rotate-12 scale-110' : ''">
                    <i data-lucide="wifi" class="w-12 h-12 text-white"></i>
                </div>
            </div>
            
            <!-- Floating Elements -->
            <div class="absolute -top-6 -right-6 bg-amber-400 p-4 rounded-2xl shadow-lg animate-bounce duration-[3000ms]">
                <i data-lucide="zap" class="w-6 h-6 text-white"></i>
            </div>
            <div class="absolute -bottom-4 -left-8 bg-green-500 p-3 rounded-2xl shadow-lg animate-bounce duration-[4000ms]">
                <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
            </div>
        </div>

        <!-- Text Content -->
        <div class="space-y-6">
            <h1 class="text-5xl md:text-7xl font-bold text-slate-900 tracking-tight leading-tight">
                WiFi Ngadat? <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Laporin Aja!</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-600 max-w-2xl mx-auto leading-relaxed">
                Layanan helpdesk mandiri untuk warga RT. Kami siap membantu menangani gangguan WiFi Anda dengan cepat, transparan, dan profesional.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('tiket.create') }}" class="group relative inline-flex items-center justify-center px-8 py-5 font-bold text-white transition-all duration-200 bg-blue-600 font-pj rounded-2xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 hover:bg-blue-700 shadow-xl shadow-blue-200 active:scale-95 w-full sm:w-auto">
                <i data-lucide="plus-circle" class="mr-2 w-6 h-6 group-hover:rotate-90 transition-transform duration-300"></i>
                Buat Tiket Sekarang
            </a>
            
            <button onclick="document.getElementById('features').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center justify-center px-8 py-5 font-bold text-slate-700 transition-all duration-200 bg-white border border-slate-200 rounded-2xl hover:bg-slate-50 hover:border-slate-300 active:scale-95 w-full sm:w-auto">
                Pelajari Lebih Lanjut
            </button>
        </div>

        <!-- Hidden Petugas Login Trigger -->
        <div class="pt-20 opacity-0 hover:opacity-100 transition-opacity duration-500">
            <a href="{{ route('login') }}" class="text-[10px] text-slate-300 hover:text-blue-400 transition-colors">
                Panel Petugas Login
            </a>
        </div>
    </div>
</div>

<!-- Simple Features Section -->
<div id="features" class="bg-white py-24">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="space-y-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Respon Cepat</h3>
                <p class="text-slate-600">Tim teknisi kami akan segera merespon tiket Anda dalam waktu kurang dari 24 jam.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                    <i data-lucide="map-pin" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Pelacakan Lokasi</h3>
                <p class="text-slate-600">Pinpoint lokasi gangguan memudahkan teknisi menemukan titik masalah secara akurat.</p>
            </div>
            <div class="space-y-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                    <i data-lucide="message-square" class="w-6 h-6"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Notifikasi WA</h3>
                <p class="text-slate-600">Dapatkan update status tiket Anda secara real-time langsung melalui WhatsApp.</p>
            </div>
        </div>
    </div>
</div>

@endsection
