@extends('layouts.app')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto w-full max-w-md text-center">
        <div class="inline-flex items-center justify-center p-3 bg-blue-600 rounded-2xl shadow-lg mb-6">
            <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-slate-900">Login Petugas</h2>
        <p class="mt-2 text-sm text-slate-600">Akses dashboard untuk manajemen tiket.</p>
    </div>

    <div class="mt-8 sm:mx-auto w-full max-w-md">
        <div class="bg-white py-10 px-6 shadow-xl shadow-blue-100/50 sm:rounded-2xl border border-blue-50">
            <form class="space-y-6" action="{{ route('authenticate') }}" method="POST">
                @csrf
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700">Username</label>
                    <div class="mt-1">
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required class="appearance-none block w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-slate-50 transition-all">
                    </div>
                    @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required class="appearance-none block w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-slate-50 transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-slate-700">Ingat saya</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all">
                        Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-6 text-center">
            <a href="/" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                &larr; Kembali ke Form Laporan
            </a>
        </div>
    </div>
</div>
@endsection
