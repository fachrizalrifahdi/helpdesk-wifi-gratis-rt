@extends('layouts.dashboard')
@section('title', 'Dashboard Ringkasan')
@section('header_subtitle', 'Ringkasan')
@section('header_title', 'Dashboard Overview')
@section('content')
<div class="mb-8" x-data x-init="lucide.createIcons()">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">Dashboard</h1>
        <p class="text-slate-600">Selamat datang, <span class="font-semibold text-blue-600">{{ auth()->user()->nama }}</span> ({{ auth()->user()->role }})</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10 card-entrance">
        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 hover-scale">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Total Tiket</p>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 border-l-4 border-l-red-500 hover-scale">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Open</p>
            <p class="text-3xl font-extrabold text-red-600 mt-1">{{ $stats['open'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 border-l-4 border-l-amber-500 hover-scale">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Proses</p>
            <p class="text-3xl font-extrabold text-amber-600 mt-1">{{ $stats['proses'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 border-l-4 border-l-green-500 hover-scale">
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wider">Selesai</p>
            <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['selesai'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 card-entrance">
        <div class="lg:col-span-2">
            <!-- Chart Section -->
            <div class="bg-white rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 p-6 mb-8"
                x-data="{ 
                    initChart() {
                        const ctx = document.getElementById('statusChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Open', 'Proses', 'Selesai'],
                                datasets: [{
                                    label: 'Jumlah Tiket',
                                    data: [{{ $stats['open'] }}, {{ $stats['proses'] }}, {{ $stats['selesai'] }}],
                                    backgroundColor: [
                                        'rgba(239, 68, 68, 0.2)',
                                        'rgba(245, 158, 11, 0.2)',
                                        'rgba(34, 197, 94, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgb(239, 68, 68)',
                                        'rgb(245, 158, 11)',
                                        'rgb(34, 197, 94)'
                                    ],
                                    borderWidth: 2,
                                    borderRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                                    x: { grid: { display: false } }
                                },
                                plugins: { legend: { display: false } }
                            }
                        });
                    }
                }"
                x-init="initChart()">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center">
                    <i data-lucide="bar-chart-3" class="h-5 w-5 mr-2 text-blue-600"></i>
                    Statistik Status Tiket
                </h3>
                <div class="h-64">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Recent Tickets -->
            <div class="bg-white rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                    <h3 class="font-bold text-slate-900 flex items-center">
                        <i data-lucide="clock" class="h-5 w-5 mr-2 text-blue-600"></i>
                        Laporan Terbaru
                    </h3>
                    <a href="{{ route('dashboard.tiket.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat Semua &rarr;</a>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($recent_tickets as $tiket)
                    <div class="px-8 py-6 flex items-center hover:bg-slate-50/80 transition-all group border-l-4 border-transparent hover:border-blue-500">
                        @if($tiket->foto_keluhan)
                        <div class="mr-5" x-data="{ open: false }">
                            <div @click="open = true" class="relative h-14 w-20 shrink-0 cursor-zoom-in rounded-xl border border-slate-200 overflow-hidden shadow-sm group-hover:shadow-md transition-all bg-slate-50">
                                <img src="{{ Storage::url($tiket->foto_keluhan) }}" 
                                     class="h-full w-full object-cover transition-all duration-300 group-hover:scale-110"
                                     onerror="this.style.display='none'; this.parentElement.classList.add('flex', 'items-center', 'justify-center', 'bg-slate-50', 'text-slate-300'); this.parentElement.innerHTML = '<i data-lucide=\'image-off\' class=\'h-6 w-6\'></i>'; lucide.createIcons();">
                            </div>
                        </div>
                        @else
                        <!-- Premium Functional Placeholder -->
                        <div class="h-14 w-20 mr-5 rounded-xl flex flex-col items-center justify-center border transition-all shadow-sm group-hover:shadow-md
                            @if($tiket->kategori == 'WiFi Terputus') bg-red-50 border-red-100 text-red-400
                            @elseif($tiket->kategori == 'WiFi Lambat') bg-amber-50 border-amber-100 text-amber-400
                            @else bg-blue-50 border-blue-100 text-blue-400 @endif">
                            <i data-lucide="{{ $tiket->kategori == 'WiFi Terputus' ? 'wifi-off' : ($tiket->kategori == 'WiFi Lambat' ? 'zap-off' : 'help-circle') }}" class="h-6 w-6"></i>
                            <span class="text-[8px] font-bold uppercase mt-1 tracking-tighter opacity-60">No Photo</span>
                        </div>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded-lg border border-slate-200">{{ $tiket->ticket_no }}</span>
                                <h4 class="font-extrabold text-slate-900 truncate leading-tight tracking-tight">{{ $tiket->nama_pelapor }}</h4>
                            </div>
                            <div class="flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                                <span class="text-xs font-semibold text-slate-500">{{ $tiket->kategori }}</span>
                                <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                                <span class="text-[11px] font-medium text-slate-400 italic">{{ $tiket->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        
                        <div class="text-right ml-6">
                            <span class="inline-flex items-center px-4 py-2 text-[11px] font-bold rounded-xl shadow-sm tracking-wide transition-all group-hover:scale-105
                                @if($tiket->status == 'Open') bg-rose-50 text-rose-600 ring-1 ring-rose-200
                                @elseif($tiket->status == 'Proses') bg-amber-50 text-amber-600 ring-1 ring-amber-200
                                @else bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200 @endif">
                                {{ $tiket->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="px-8 py-16 text-center">
                        <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                            <i data-lucide="inbox" class="h-10 w-10 text-slate-200"></i>
                        </div>
                        <p class="text-slate-400 font-medium italic">Belum ada laporan terbaru.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar / Actions -->
        <div class="space-y-8">
            <div class="bg-white rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 p-6">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="zap" class="h-5 w-5 mr-2 text-blue-600"></i>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('dashboard.tiket.index', ['status' => 'Open']) }}" class="flex items-center p-4 bg-red-50 hover:bg-red-100 rounded-xl transition-all group">
                        <div class="bg-red-500 p-2 rounded-lg text-white mr-3 group-hover:scale-110 transition-transform">
                            <i data-lucide="alert-circle" class="h-5 w-5"></i>
                        </div>
                        <span class="text-sm font-bold text-red-700">Tinjau Tiket Open</span>
                    </a>
                    
                    <a href="{{ route('dashboard.tiket.index') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-all group">
                        <div class="bg-blue-500 p-2 rounded-lg text-white mr-3 group-hover:scale-110 transition-transform">
                            <i data-lucide="list" class="h-5 w-5"></i>
                        </div>
                        <span class="text-sm font-bold text-blue-700">Semua Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Distribution Section (Requirement 6) -->
    <div class="mt-8 bg-white rounded-2xl shadow-lg shadow-blue-100/50 border border-blue-50 p-6 card-entrance">
        <h3 class="font-bold text-slate-900 mb-6 flex items-center">
            <i data-lucide="map" class="h-5 w-5 mr-2 text-blue-600"></i>
            Sebaran Gangguan WiFi
        </h3>
        <div id="distributionMap" class="h-96 rounded-2xl border border-slate-100 overflow-hidden z-0"></div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locations = @json($all_tickets);
        
        // Default center (Balikpapan)
        const defaultCenter = [-1.2654, 116.8312];

        if (locations.length > 0) {
            const map = L.map('distributionMap').setView([locations[0].latitude, locations[0].longitude], 14);
            
            L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google Maps'
            }).addTo(map);
            
            locations.forEach(loc => {
                const color = loc.status === 'Open' ? '#ef4444' : (loc.status === 'Proses' ? '#f59e0b' : '#22c55e');
                L.circleMarker([loc.latitude, loc.longitude], {
                    radius: 8,
                    fillColor: color,
                    color: "#fff",
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.8
                }).addTo(map)
                .bindPopup(`<b>TKT-${String(loc.id_tiket).padStart(5, '0')}</b><br>${loc.nama_pelapor}<br>Status: ${loc.status}`);
            });
        } else {
            const map = L.map('distributionMap').setView(defaultCenter, 13);
            L.tileLayer('https://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
                maxZoom: 20,
                subdomains: ['mt0', 'mt1', 'mt2', 'mt3'],
                attribution: '© Google Maps'
            }).addTo(map);
            
            document.getElementById('distributionMap').innerHTML = ''; // Clear placeholder if initializing map
        }
    });
</script>
@endsection
