@extends('layouts.dashboard')
@section('title', 'Manajemen Petugas')
@section('header_subtitle', 'SDM')
@section('header_title', 'Kelola Petugas')
@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4" x-data x-init="lucide.createIcons()">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                <i data-lucide="users" class="h-8 w-8 mr-3 text-blue-600"></i>
                Manajemen Petugas
            </h1>
            <p class="text-slate-600 mt-1">Kelola akun Admin dan Teknisi sistem.</p>
        </div>
        <div>
            <button @click="$dispatch('buka-modal-petugas')" class="group px-6 py-3.5 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-200 flex items-center active:scale-95 space-x-2">
                <div class="bg-white/20 p-1 rounded-lg group-hover:rotate-90 transition-transform duration-300">
                    <i data-lucide="plus" class="h-5 w-5"></i>
                </div>
                <span>Tambah Petugas</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center animate-in fade-in slide-in-from-top-4 duration-300">
            <i data-lucide="check-circle-2" class="h-5 w-5 mr-3"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl flex items-center animate-in fade-in slide-in-from-top-4 duration-300">
            <i data-lucide="alert-circle" class="h-5 w-5 mr-3"></i>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Petugas</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Username</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Role</th>
                        <th class="px-8 py-5 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic-none">
                    @foreach($petugas as $p)
                    <tr class="hover:bg-blue-50/50 transition-colors group">
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $p->role == 'Admin' ? 'from-blue-500 to-blue-600' : 'from-slate-700 to-slate-800' }} text-white flex items-center justify-center shadow-md mr-4">
                                    <i data-lucide="{{ $p->role == 'Admin' ? 'shield-check' : 'wrench' }}" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $p->nama }}</div>
                                    @if($p->id_petugas === auth()->id())
                                        <span class="text-[10px] bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-bold uppercase tracking-tight">Anda</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <div class="text-sm font-medium text-slate-600 font-mono">{{ $p->username }}</div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $p->role == 'Admin' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }}">
                                {{ $p->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap text-right">
                            @if($p->id_petugas !== auth()->id())
                            <div class="flex items-center justify-end gap-2">
                                <form action="{{ route('dashboard.petugas.reset', $p->id_petugas) }}" method="POST" onsubmit="confirmAction(event, 'Reset Password?', 'Password {{ $p->nama }} akan direset menjadi: password123', 'question')">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all" title="Reset Password">
                                        <i data-lucide="key-round" class="h-5 w-5"></i>
                                    </button>
                                </form>
                                <form action="{{ route('dashboard.petugas.destroy', $p->id_petugas) }}" method="POST" onsubmit="confirmAction(event, 'Hapus Petugas?', 'Semua data akses {{ $p->nama }} akan dihapus permanen!', 'warning')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all" title="Hapus Petugas">
                                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-xs text-slate-400 italic">No Action</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('modals')
    <!-- Multi-Layered Modal Fix -->
    <div x-data="{ open: false }" 
        @buka-modal-petugas.window="open = true" 
        @keydown.escape.window="open = false"
        x-show="open" 
        class="fixed inset-0 z-[10000]" 
        style="display: none;">
        
        <!-- Layer 1: Darkened Backdrop (with its own blur) -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" 
             @click="open = false"></div>

        <!-- Layer 2: Modal Content (No blur applied here) -->
        <div class="fixed inset-0 z-[10001] overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="open" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-100"
                     @click.away="open = false">
                    
                    <div class="bg-blue-600 px-8 py-6">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <i data-lucide="user-plus" class="h-6 w-6 mr-3"></i>
                            Tambah Petugas Baru
                        </h3>
                    </div>

                    <form action="{{ route('dashboard.petugas.store') }}" method="POST" class="p-8 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" required class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                            <input type="text" name="username" required class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                            <input type="password" name="password" required class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Role</label>
                            <select name="role" required class="block w-full px-4 py-3 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-slate-50/50">
                                <option value="Teknisi">Teknisi</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>

                        <div class="pt-4 flex gap-3">
                            <button type="button" @click="open = false" class="flex-1 py-3 px-6 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-2xl transition-all">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl transition-all shadow-lg shadow-blue-200 active:scale-95">
                                Simpan Petugas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endpush
@endsection
