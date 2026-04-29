@extends('layouts.dashboard')
@section('title', 'Pengaturan Akun')
@section('header_subtitle', 'Konfigurasi')
@section('header_title', 'Pengaturan Profil')
@section('content')
    <div class="mb-8" x-data x-init="lucide.createIcons()">
        <h1 class="text-3xl font-bold text-slate-900 flex items-center">
            <i data-lucide="settings" class="h-8 w-8 mr-3 text-blue-600"></i>
            Pengaturan Akun
        </h1>
        <p class="text-slate-600 mt-1">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center animate-in fade-in slide-in-from-top-4 duration-300 shadow-sm">
            <i data-lucide="check-circle-2" class="h-5 w-5 mr-3"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Profile Info -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900 flex items-center">
                    <i data-lucide="user" class="h-5 w-5 mr-2 text-blue-600"></i>
                    Informasi Profil
                </h3>
                <p class="text-sm text-slate-500">Update nama dan username Anda.</p>
            </div>
            
            <form action="{{ route('dashboard.settings.profile') }}" method="POST" class="p-8 space-y-6" onsubmit="showLoader()">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" 
                        class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50" required>
                    @error('nama') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                        class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50" required>
                    @error('username') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-200 active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Security -->
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900 flex items-center">
                    <i data-lucide="lock" class="h-5 w-5 mr-2 text-amber-500"></i>
                    Keamanan Akun
                </h3>
                <p class="text-sm text-slate-500">Ganti password Anda secara berkala.</p>
            </div>
            
            <form action="{{ route('dashboard.settings.password') }}" method="POST" class="p-8 space-y-6" onsubmit="showLoader()">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" 
                        class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50" required>
                    @error('current_password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" 
                            class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50" required>
                        @error('password') <p class="text-xs text-red-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" 
                            class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50" required>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-6 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-2xl transition-all shadow-lg shadow-slate-200 active:scale-95">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
