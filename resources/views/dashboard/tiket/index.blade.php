@extends('layouts.dashboard')
@section('title', 'Manajemen Tiket')
@section('header_subtitle', 'Laporan')
@section('header_title', 'Daftar Tiket Warga')
@section('content')
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4" x-data x-init="lucide.createIcons()">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 flex items-center">
                <i data-lucide="ticket" class="h-8 w-8 mr-3 text-blue-600"></i>
                Manajemen Tiket
            </h1>
            <p class="text-slate-600 mt-1">Daftar semua laporan keluhan WiFi dari warga.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('dashboard.tiket.index') }}" 
               class="px-4 py-2 border rounded-xl text-sm font-bold transition-all flex items-center {{ !request('status') ? 'bg-slate-900 border-slate-900 text-white shadow-lg shadow-slate-200' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                <i data-lucide="layers" class="h-4 w-4 mr-2"></i> Semua
            </a>
            <a href="{{ route('dashboard.tiket.index', ['status' => 'Open']) }}" 
               class="px-4 py-2 border rounded-xl text-sm font-bold transition-all flex items-center {{ request('status') == 'Open' ? 'bg-red-600 border-red-600 text-white shadow-lg shadow-red-100' : 'bg-white border-slate-200 text-slate-600 hover:bg-red-50 hover:text-red-600' }}">
                <i data-lucide="alert-circle" class="h-4 w-4 mr-2"></i> Open
            </a>
            <a href="{{ route('dashboard.tiket.index', ['status' => 'Proses']) }}" 
               class="px-4 py-2 border rounded-xl text-sm font-bold transition-all flex items-center {{ request('status') == 'Proses' ? 'bg-amber-500 border-amber-500 text-white shadow-lg shadow-amber-100' : 'bg-white border-slate-200 text-slate-600 hover:bg-amber-50 hover:text-amber-600' }}">
                <i data-lucide="activity" class="h-4 w-4 mr-2"></i> Proses
            </a>
            <a href="{{ route('dashboard.tiket.index', ['status' => 'Selesai']) }}" 
               class="px-4 py-2 border rounded-xl text-sm font-bold transition-all flex items-center {{ request('status') == 'Selesai' ? 'bg-green-600 border-green-600 text-white shadow-lg shadow-green-100' : 'bg-white border-slate-200 text-slate-600 hover:bg-green-50 hover:text-green-600' }}">
                <i data-lucide="check-circle" class="h-4 w-4 mr-2"></i> Selesai
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-center animate-in fade-in slide-in-from-top-4 duration-300">
            <i data-lucide="check-circle-2" class="h-5 w-5 mr-3"></i>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 mb-6">
        <form action="{{ route('dashboard.tiket.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-center">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-slate-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all" 
                    placeholder="Cari nama warga, nomor WA, kategori, atau deskripsi...">
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('dashboard.tiket.index', ['status' => request('status')]) }}" class="w-full md:w-auto px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all active:scale-95 text-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-xl shadow-blue-100/50 border border-blue-50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Warga / Lokasi</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Keluhan</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Petugas</th>
                        <th class="px-6 py-5 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic-none">
                    @forelse($tikets as $tiket)
                    <tr class="hover:bg-blue-50/50 transition-colors group">
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg text-blue-600 mr-3 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <i data-lucide="user" class="h-5 w-5"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 bg-slate-100 text-slate-500 rounded uppercase tracking-tight">{{ $tiket->ticket_no }}</span>
                                            <div class="text-[10px] text-slate-400 font-medium">
                                                <i data-lucide="calendar" class="h-3 w-3 inline mr-0.5"></i>
                                                {{ $tiket->created_at->translatedFormat('d M Y') }}
                                            </div>
                                        </div>
                                        <div class="text-sm font-bold text-slate-900 truncate">{{ $tiket->nama_pelapor }}</div>
                                    </div>
                                    <div class="text-xs text-slate-500 flex items-center mt-1">
                                        <i data-lucide="map-pin" class="h-3 w-3 mr-1"></i> RT {{ $tiket->rt }} / RW {{ $tiket->rw }}
                                        @if($tiket->latitude)
                                            <a href="https://www.google.com/maps?q={{ $tiket->latitude }},{{ $tiket->longitude }}" target="_blank" class="ml-2 text-blue-500 hover:underline flex items-center">
                                                <i data-lucide="external-link" class="h-3 w-3 mr-0.5"></i> Map
                                            </a>
                                        @endif
                                    </div>
                                     @if($tiket->foto_keluhan)
                                    <div class="mt-3" x-data="{ open: false }">
                                        <div @click="open = true" class="relative group/img h-14 w-24 shrink-0 cursor-zoom-in rounded-xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-all bg-slate-50">
                                            <img src="{{ Storage::url($tiket->foto_keluhan) }}" 
                                                 class="h-full w-full object-cover transition-all duration-300 group-hover:scale-105"
                                                 onerror="this.style.display='none'; this.parentElement.classList.add('flex', 'items-center', 'justify-center', 'bg-slate-50', 'text-slate-300'); this.parentElement.innerHTML = '<i data-lucide=\'image-off\' class=\'h-6 w-6\'></i>'; lucide.createIcons();">
                                            
                                            <div class="absolute inset-0 bg-black/10 opacity-0 group-hover/img:opacity-100 flex items-center justify-center transition-opacity">
                                                <i data-lucide="maximize-2" class="h-4 w-4 text-white"></i>
                                            </div>
                                        </div>

                                        <!-- Fullscreen Lightbox -->
                                        <div x-show="open" @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md" style="display: none;">
                                            <div class="relative bg-white p-2 rounded-3xl max-w-4xl w-full shadow-2xl">
                                                <button @click="open = false" class="absolute -top-4 -right-4 h-11 w-11 bg-white rounded-full shadow-2xl flex items-center justify-center text-slate-500 hover:text-red-500 transition-colors z-[110] border border-slate-100">
                                                    <i data-lucide="x" class="h-6 w-6"></i>
                                                </button>
                                                <img src="{{ Storage::url($tiket->foto_keluhan) }}" class="w-full max-h-[85vh] object-contain rounded-2xl">
                                                <div class="p-4 bg-slate-50 mt-2 rounded-xl flex justify-between items-center">
                                                    <p class="text-sm font-bold text-slate-900">Bukti Foto: {{ $tiket->ticket_no }}</p>
                                                    <a href="{{ Storage::url($tiket->foto_keluhan) }}" download class="text-xs font-bold text-blue-600 hover:underline flex items-center">
                                                        <i data-lucide="download" class="h-3 w-3 mr-1"></i> Download
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="text-xs font-bold text-blue-600 flex items-center mt-1">
                                        <i data-lucide="phone" class="h-3 w-3 mr-1"></i> {{ $tiket->no_whatsapp }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <div class="text-sm font-bold text-slate-900">{{ $tiket->kategori }}</div>
                            <div class="text-xs text-slate-500 truncate max-w-xs mt-1">{{ $tiket->deskripsi ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            <div class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full 
                                @if($tiket->status == 'Open') bg-red-100 text-red-700
                                @elseif($tiket->status == 'Proses') bg-amber-100 text-amber-700
                                @else bg-green-100 text-green-700 @endif">
                                <span class="h-1.5 w-1.5 rounded-full mr-2 
                                    @if($tiket->status == 'Open') bg-red-500
                                    @elseif($tiket->status == 'Proses') bg-amber-500
                                    @else bg-green-500 @endif"></span>
                                {{ $tiket->status }}
                            </div>
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap">
                            @if($tiket->petugas)
                                <div class="flex items-center">
                                    <div class="bg-slate-100 p-1.5 rounded-full text-slate-600 mr-2">
                                        <i data-lucide="shield-check" class="h-4 w-4"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">{{ $tiket->petugas->nama }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase font-bold tracking-tight">{{ $tiket->petugas->role }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">Belum ditugaskan</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end items-center space-x-3">
                                <!-- Hubungi WA -->
                                <a href="https://wa.me/{{ $tiket->formatted_whatsapp }}?text=Halo%20{{ urlencode($tiket->nama_pelapor) }}%2C%20saya%20teknisi%20WiFi%20RT%20ingin%20mengonfirmasi%20laporan%20Anda." target="_blank" class="p-2.5 text-green-600 hover:bg-green-50 rounded-xl transition-all border border-transparent hover:border-green-100" title="Hubungi via WhatsApp">
                                    <i data-lucide="message-square" class="h-5 w-5"></i>
                                </a>

                                <!-- Admin logic -->
                                @if(auth()->user()->role == 'Admin' && $tiket->status == 'Open')
                                <form action="{{ route('dashboard.tiket.assign', $tiket->id_tiket) }}" method="POST" class="flex items-center">
                                    @csrf
                                    <select name="id_petugas" required class="text-xs border-slate-200 rounded-l-xl focus:ring-blue-500 focus:border-blue-500 bg-slate-50 py-2 pl-3 pr-8">
                                        <option value="">Pilih Teknisi</option>
                                        @foreach($teknisis as $teknisi)
                                            <option value="{{ $teknisi->id_petugas }}">{{ $teknisi->nama }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-blue-600 text-white text-xs font-bold px-4 py-2.5 rounded-r-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                        Assign
                                    </button>
                                </form>
                                @endif

                                 <!-- Consolidated Technician Action Button -->
                                 @if($tiket->status != 'Selesai')
                                     @php
                                         $isTeknisi = auth()->user()->role == 'Teknisi';
                                         $canClaim = $isTeknisi && !$tiket->id_petugas;
                                         $isAssignedToMe = $tiket->id_petugas == auth()->id();
                                     @endphp

                                     <div class="flex items-center gap-2">
                                         @if($canClaim)
                                             <!-- One-click Claim and Start -->
                                             <form action="{{ route('dashboard.tiket.claim', $tiket->id_tiket) }}" method="POST">
                                                 @csrf
                                                 <button type="submit" class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-blue-100 active:scale-95 whitespace-nowrap">
                                                     <i data-lucide="hand" class="h-4 w-4 mr-2"></i>
                                                     Ambil & Kerjakan
                                                 </button>
                                             </form>
                                         @elseif($isAssignedToMe)
                                             <!-- Status Update Logic for assigned technician -->
                                             <form action="{{ route('dashboard.tiket.update-status', $tiket->id_tiket) }}" method="POST" x-data="{ showNotes: false }" class="flex items-center gap-2">
                                                 @csrf
                                                 <input type="hidden" name="status" value="{{ $tiket->status == 'Open' ? 'Proses' : 'Selesai' }}">
                                                 
                                                 @if($tiket->status == 'Proses')
                                                     <div x-show="showNotes" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-[2px]">
                                                         <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl border border-slate-200">
                                                             <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                                                                 <i data-lucide="clipboard-edit" class="h-4 w-4 mr-2 text-blue-600"></i>
                                                                 Catatan Perbaikan
                                                             </h4>
                                                             <textarea name="catatan_teknisi" class="text-sm w-full p-3 border border-slate-200 rounded-xl bg-slate-50 focus:ring-2 focus:ring-blue-500 outline-none mb-4" rows="4" placeholder="Apa yang diperbaiki? (Contoh: Ganti kabel, Restart ONU)"></textarea>
                                                             <div class="flex gap-2">
                                                                 <button type="button" @click="showNotes = false" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">Batal</button>
                                                                 <button type="submit" class="flex-1 px-4 py-2.5 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700 transition-all shadow-lg shadow-green-100">Simpan & Selesai</button>
                                                             </div>
                                                         </div>
                                                     </div>
                                                 @endif

                                                 <button type="{{ $tiket->status == 'Proses' ? 'button' : 'submit' }}" 
                                                         @if($tiket->status == 'Proses') @click="showNotes = true" @endif
                                                         class="inline-flex items-center px-4 py-2.5 {{ $tiket->status == 'Open' ? 'bg-amber-500 hover:bg-amber-600 shadow-amber-100' : 'bg-green-600 hover:bg-green-700 shadow-green-100' }} text-white text-xs font-bold rounded-xl transition-all shadow-lg active:scale-95 whitespace-nowrap">
                                                     <i data-lucide="{{ $tiket->status == 'Open' ? 'play' : 'check-circle' }}" class="h-4 w-4 mr-2"></i>
                                                     {{ $tiket->status == 'Open' ? 'Mulai Kerja' : 'Selesaikan' }}
                                                 </button>
                                             </form>
                                         @elseif(auth()->user()->role == 'Admin' && $tiket->id_petugas)
                                             <!-- Admin only sees status label if already assigned -->
                                             <span class="text-[10px] font-bold text-slate-400 uppercase italic">Sedang dikerjakan</span>
                                         @endif
                                     </div>
                                 @else
                                     <!-- Detail CATATAN for Selesai -->
                                     <div class="text-right">
                                         <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Hasil Perbaikan:</p>
                                         <p class="text-[11px] text-slate-600 max-w-[150px] truncate italic" title="{{ $tiket->catatan_teknisi }}">{{ $tiket->catatan_teknisi ?? 'Selesai tanpa catatan' }}</p>
                                     </div>
                                 @endif

                                 <!-- Auto WhatsApp Follow-up (Requirement 4) -->
                                 @php
                                     $waMessage = "Halo " . $tiket->nama_pelapor . ", ini teknisi WiFi RT. ";
                                     if($tiket->status == 'Proses') $waMessage .= "Tiket Anda " . $tiket->ticket_no . " sedang kami kerjakan.";
                                     if($tiket->status == 'Selesai') $waMessage .= "Tiket Anda " . $tiket->ticket_no . " telah selesai diperbaiki. Terima kasih.";
                                 @endphp
                                 <div class="flex items-center gap-1">
                                     <!-- Standard Chat (Requirement: Manual Chat) -->
                                     <a href="https://wa.me/{{ $tiket->formatted_whatsapp }}?text=Halo%20{{ urlencode($tiket->nama_pelapor) }}%2C%20ini%20teknisi%20WiFi%20RT." target="_blank" class="p-2.5 text-green-600 hover:bg-green-50 rounded-xl transition-all border border-green-100" title="Chat WA (Manual)">
                                         <i data-lucide="message-circle" class="h-5 w-5"></i>
                                     </a>
                                     <!-- Update Notification (Requirement: Automated Status) -->
                                     <a href="https://wa.me/{{ $tiket->formatted_whatsapp }}?text={{ urlencode($waMessage) }}" target="_blank" class="p-2.5 text-blue-600 hover:bg-blue-50 rounded-xl transition-all border border-blue-100" title="Kirim Update Status (Otomatis)">
                                         <i data-lucide="bell" class="h-5 w-5"></i>
                                     </a>
                                 </div>

                                <!-- Hapus Tiket (Admin Only) -->
                                @if(auth()->user()->role == 'Admin')
                                <form action="{{ route('dashboard.tiket.destroy', $tiket->id_tiket) }}" method="POST" onsubmit="confirmAction(event, 'Hapus Tiket?', 'Laporan {{ $tiket->ticket_no }} akan dihapus secara permanen dari sistem.', 'warning')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all border border-transparent hover:border-red-100" title="Hapus Tiket">
                                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-slate-50 p-4 rounded-full mb-4">
                                    <i data-lucide="folder-open" class="h-10 w-10 text-slate-300"></i>
                                </div>
                                <p class="text-slate-400 italic">Belum ada tiket yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tikets->hasPages())
        <div class="px-6 py-5 bg-slate-50/50 border-t border-slate-100">
            <div class="pagination-custom">
                {{ $tikets->links() }}
            </div>
        </div>
        @endif
    </div>

    <style>
        /* Modernized Pagination Styling */
        .pagination-custom nav div:first-child { display: none !important; } /* Hide the 'Showing X to Y' text on small screens if needed, or just let Tailwind handle it */
        .pagination-custom nav .relative.inline-flex { border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .pagination-custom nav span[aria-current="page"] span { background-color: #0f172a !important; border-color: #0f172a !important; font-weight: 700; color: white !important; }
        .pagination-custom nav a { transition: all 0.2s; font-weight: 600; text-decoration: none !important; }
        .pagination-custom nav a:hover { background-color: #f8fafc !important; color: #2563eb !important; }
    </style>
@endsection
