@extends('layouts.app')
@section('title', 'Buat Tiket')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 p-4" x-data="{ showNotice: true, step: 1, lat: null, lng: null, locationCaptured: false }" x-init="lucide.createIcons()">
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Knowledge Base (Requirement 7) -->
        <div class="lg:col-span-1 space-y-6" x-data="{ openFaq: null }">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl p-6 shadow-xl shadow-blue-100/50 border border-white/50 h-fit sticky top-4">
                <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                    <i data-lucide="help-circle" class="h-6 w-6 mr-2 text-blue-600"></i>
                    Bantuan Cepat
                </h2>
                
                <div class="space-y-4">
                    <!-- FAQ 1 -->
                    <div class="overflow-hidden border border-slate-100 rounded-2xl transition-all" :class="openFaq === 1 ? 'bg-blue-50 ring-1 ring-blue-200' : 'bg-white'">
                        <button @click="openFaq = (openFaq === 1 ? null : 1)" class="w-full p-4 text-left flex items-center justify-between group">
                            <h3 class="font-bold text-sm" :class="openFaq === 1 ? 'text-blue-900' : 'text-slate-700'">WiFi Lambat?</h3>
                            <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-300" :class="openFaq === 1 ? 'rotate-180 text-blue-600' : 'text-slate-400 group-hover:text-blue-500'"></i>
                        </button>
                        <div x-show="openFaq === 1" x-transition class="px-4 pb-4">
                            <p class="text-xs text-blue-700 leading-relaxed">
                                Coba matikan router selama 10 detik lalu nyalakan kembali. Ini akan membersihkan cache router dan mencari kanal frekuensi yang lebih bersih.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="overflow-hidden border border-slate-100 rounded-2xl transition-all" :class="openFaq === 2 ? 'bg-amber-50 ring-1 ring-amber-200' : 'bg-white'">
                        <button @click="openFaq = (openFaq === 2 ? null : 2)" class="w-full p-4 text-left flex items-center justify-between group">
                            <h3 class="font-bold text-sm" :class="openFaq === 2 ? 'text-amber-900' : 'text-slate-700'">Lampu Router Merah?</h3>
                            <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-300" :class="openFaq === 2 ? 'rotate-180 text-amber-600' : 'text-slate-400 group-hover:text-amber-500'"></i>
                        </button>
                        <div x-show="openFaq === 2" x-transition class="px-4 pb-4">
                            <p class="text-xs text-amber-700 leading-relaxed">
                                Indikasi lampu LOS merah berarti kabel fiber optik bermasalah. Pastikan kabel kuning kecil tidak tertekuk tajam atau terjepit pintu.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="overflow-hidden border border-slate-100 rounded-2xl transition-all" :class="openFaq === 3 ? 'bg-indigo-50 ring-1 ring-indigo-200' : 'bg-white'">
                        <button @click="openFaq = (openFaq === 3 ? null : 3)" class="w-full p-4 text-left flex items-center justify-between group">
                            <h3 class="font-bold text-sm" :class="openFaq === 3 ? 'text-indigo-900' : 'text-slate-700'">Ganti Password?</h3>
                            <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-300" :class="openFaq === 3 ? 'rotate-180 text-indigo-600' : 'text-slate-400 group-hover:text-indigo-500'"></i>
                        </button>
                        <div x-show="openFaq === 3" x-transition class="px-4 pb-4">
                            <p class="text-xs text-indigo-700 leading-relaxed">
                                Untuk alasan keamanan, pengaturan password WiFi hanya bisa dilakukan oleh teknisi atau melalui kabel LAN. Hubungi kami untuk bantuan reset.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 border-2 border-dashed border-slate-200 rounded-2xl">
                    <p class="text-xs text-slate-500 text-center leading-relaxed">
                        Jika solusi di atas tidak membantu, silakan isi formulir di samping untuk bantuan teknisi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-blue-100/50 border border-white/50 overflow-hidden relative">
                <!-- Header -->
                <div class="relative bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600 px-8 py-10 overflow-hidden">
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSA2MCAwIEwgMCAwIDAgNjAiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzE1NmZmZiIgc3Ryb2tlLXdpZHRoPSIwLjUiIG9wYWNpdHk9IjAuMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-30"></div>
                    <div class="relative z-10 text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                            <i data-lucide="wifi" class="h-6 w-6 text-white"></i>
                            <span class="text-blue-100 font-medium">Layanan Helpdesk</span>
                        </div>
                        <h1 class="text-3xl font-bold text-white">Buat Tiket Baru</h1>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Global Error Alert -->
                    @if($errors->any())
                    <div class="mb-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-start gap-4">
                        <i data-lucide="alert-circle" class="h-6 w-6 text-red-500 mt-0.5"></i>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800">Tiket gagal dikirim! Periksa isian berikut:</p>
                            <ul class="mt-2 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-xs text-red-700 flex items-center">
                                        <i data-lucide="x" class="h-3 w-3 mr-2"></i> {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- High-Impact Success Modal -->
                    @if(session('success'))
                    <div x-show="showNotice" class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
                        <div x-show="showNotice" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i data-lucide="check-check" class="h-10 w-10 text-green-600 animate-bounce"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-slate-900 mb-2">Tiket Terkirim!</h2>
                            <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                                {{ session('success') }}
                            </p>
                            <button @click="showNotice = false" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                Mengerti
                            </button>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('tiket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" 
                        onsubmit="const alpine = document.querySelector('[x-data]').__x.$data; if(!alpine.locationCaptured) { alert('Maaf, Anda wajib mengambil lokasi GPS sebelum mengirim Tiket.'); document.getElementById('map').scrollIntoView({behavior: 'smooth'}); return false; }">
                        @csrf
                        
                        <!-- Section 1: Data Pelapor -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-full border-b border-slate-100 pb-2">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest flex items-center">
                                    <i data-lucide="user" class="h-4 w-4 mr-2"></i> Identitas
                                </h3>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_pelapor" value="{{ old('nama_pelapor') }}" required class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="Masukkan nama Anda">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 bg-slate-100 border border-r-0 border-slate-200 rounded-l-2xl text-slate-500 font-bold">+62</span>
                                    <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-r-2xl text-slate-900 focus:ring-2 focus:ring-blue-500 transition-all outline-none" placeholder="812345678">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Lokasi & Map (Requirement 6) -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-100 pb-2">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest flex items-center">
                                    <i data-lucide="map-pin" class="h-4 w-4 mr-2"></i> Lokasi & Alamat
                                </h3>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="col-span-1">
                                    <label class="block text-xs font-bold text-slate-500 mb-1">RT <span class="text-red-500">*</span></label>
                                    <input type="text" name="rt" value="{{ old('rt') }}" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="01">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-xs font-bold text-slate-500 mb-1">RW <span class="text-slate-300">(Opsional)</span></label>
                                    <input type="text" name="rw" value="{{ old('rw') }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none" placeholder="05">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">Kelurahan <span class="text-red-500">*</span></label>
                                    <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" required class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            
                            <!-- Leaflet Map Integration (Requirement: Required Pin) -->
                            <div class="space-y-3">
                                <label class="block text-sm font-bold text-slate-700">Pin Lokasi <span class="text-red-500">*</span></label>
                                <div id="map" class="h-48 rounded-2xl border-2 overflow-hidden z-0" :class="locationCaptured ? 'border-green-400' : 'border-slate-200 @error('latitude') border-red-400 @enderror'"></div>
                                <input type="hidden" name="latitude" id="lat_input" x-model="lat">
                                <input type="hidden" name="longitude" id="lng_input" x-model="lng">
                                
                                @error('latitude')
                                    <p class="text-xs text-red-500 font-bold flex items-center">
                                        <i data-lucide="map-pin-off" class="h-3 w-3 mr-1"></i> Wajib mengambil lokasi GPS sebelum mengirim
                                    </p>
                                @enderror

                                <button type="button" 
                                    @click="getCurrentLocation()" 
                                    :class="locationCaptured ? 'bg-green-600 text-white' : 'bg-blue-600 text-white hover:bg-blue-700'"
                                    class="w-full py-4 rounded-2xl text-sm font-bold flex items-center justify-center transition-all shadow-md active:scale-95 group">
                                    <template x-if="!locationCaptured">
                                        <span class="flex items-center">
                                            <i data-lucide="navigation" class="h-4 w-4 mr-2 group-hover:animate-pulse"></i> 
                                            Klik untuk Ambil Lokasi GPS Terkini
                                        </span>
                                    </template>
                                    <template x-if="locationCaptured">
                                        <span class="flex items-center">
                                            <i data-lucide="shield-check" class="h-4 w-4 mr-2"></i> 
                                            Lokasi Terverifikasi & Terkunci
                                        </span>
                                    </template>
                                </button>
                                <p class="text-[10px] text-slate-400 text-center italic">Tiket tidak dapat dikirim tanpa koordinat GPS yang akurat.</p>
                            </div>
                        </div>

                        <!-- Section 3: Masalah & Foto (Requirement 2) -->
                        <div class="space-y-6">
                            <div class="border-b border-slate-100 pb-2">
                                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest flex items-center">
                                    <i data-lucide="camera" class="h-4 w-4 mr-2"></i> Detail Masalah
                                </h3>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Masalah</label>
                                <select name="kategori" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all outline-none appearance-none">
                                    <option value="WiFi Lambat">🐢 WiFi Lambat</option>
                                    <option value="WiFi Terputus">📡 WiFi Terputus</option>
                                    <option value="Tidak Bisa Konek">❌ Tidak Bisa Konek</option>
                                    <option value="Kabel Putus">🔌 Kabel Putus</option>
                                    <option value="Lainnya">📝 Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Keluhan</label>
                                <textarea name="deskripsi" rows="3" class="block w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500 transition-all outline-none resize-none" placeholder="Ceritakan detail masalah yang dialami...">{{ old('deskripsi') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Unggah Foto (Opsional)</label>
                                <div class="relative group" x-data="{ imageUrl: null }">
                                    <input type="file" name="foto_keluhan" accept="image/*" 
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20"
                                        @change="const file = $event.target.files[0]; if (file) { imageUrl = URL.createObjectURL(file) }">
                                    
                                    <!-- Dynamic Preview UI -->
                                    <template x-if="!imageUrl">
                                        <div class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center group-hover:border-blue-400 group-hover:bg-blue-50 transition-all" :class="'@error('foto_keluhan') border-red-300 bg-red-50 @enderror'">
                                            <i data-lucide="image-plus" class="h-10 w-10 text-slate-300 mx-auto mb-2 group-hover:text-blue-500" :class="'@error('foto_keluhan') text-red-400 @enderror'"></i>
                                            <p class="text-xs text-slate-500 group-hover:text-blue-700" :class="'@error('foto_keluhan') text-red-700 @enderror'">Lampirkan foto kondisi router atau kabel (Maks 5MB)</p>
                                        </div>
                                    </template>

                                    <!-- Final Preview -->
                                    <template x-if="imageUrl">
                                        <div class="relative bg-slate-50 rounded-2xl p-2 border-2 border-blue-400 overflow-hidden shadow-inner">
                                            <img :src="imageUrl" class="h-40 w-full object-cover rounded-xl shadow-lg">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                                                <p class="text-white text-[10px] font-bold uppercase tracking-widest flex items-center">
                                                    <i data-lucide="check" class="h-3 w-3 mr-1"></i> Foto Siap Diunggah
                                                </p>
                                            </div>
                                            <button type="button" @click="imageUrl = null" class="absolute top-3 right-3 bg-red-600 text-white w-8 h-8 rounded-full shadow-xl flex items-center justify-center hover:bg-red-700 z-30 transition-all active:scale-90 border-2 border-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                @error('foto_keluhan')
                                    <p class="mt-2 text-xs text-red-600 font-bold flex items-center">
                                        <i data-lucide="alert-triangle" class="h-3 w-3 mr-1"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-5 px-6 rounded-3xl transition-all shadow-xl shadow-blue-200 active:scale-95 flex items-center justify-center gap-3">
                            <i data-lucide="send" class="h-6 w-6"></i>
                            Kirim Tiket Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map, marker;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Init Map (Requirement 6) with Google Maps style
        map = L.map('map').setView([-1.2654, 116.8312], 13); // Default Balikpapan
        L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
            attribution: '© Google Maps'
        }).addTo(map);

        // Disabled map click to prevent manual pinning (Requirement: No Fake GPS)
    });

    function updateMarker(lat, lng) {
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        
        // Direct input update for safety
        document.getElementById('lat_input').value = lat;
        document.getElementById('lng_input').value = lng;

        // Update Alpine state
        const alpineEl = document.querySelector('[x-data]');
        if (alpineEl && alpineEl.__x) {
            alpineEl.__x.$data.lat = lat;
            alpineEl.__x.$data.lng = lng;
            alpineEl.__x.$data.locationCaptured = true;
        } else {
            // Fallback for newer Alpine versions
            const data = Alpine.$data(alpineEl);
            data.lat = lat;
            data.lng = lng;
            data.locationCaptured = true;
        }
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 17);
                updateMarker(lat, lng);
            }, function() {
                alert('Tidak dapat mengambil lokasi. Pastikan GPS aktif.');
            });
        }
    }
</script>
@endsection
