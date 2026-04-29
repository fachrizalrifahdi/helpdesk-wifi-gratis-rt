<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isTeknisi = $user->role == 'Teknisi';

        $stats = [
            'total' => Tiket::when($isTeknisi, fn($q) => $q->where('id_petugas', $user->id_petugas))->count(),
            'open' => Tiket::where('status', 'Open')->count(), // Keep global 'Open' count so they see work available
            'proses' => Tiket::where('status', 'Proses')->when($isTeknisi, fn($q) => $q->where('id_petugas', $user->id_petugas))->count(),
            'selesai' => Tiket::where('status', 'Selesai')->when($isTeknisi, fn($q) => $q->where('id_petugas', $user->id_petugas))->count(),
        ];

        $recent_tickets = Tiket::with('petugas')
            ->when($isTeknisi, fn($q) => $q->where('id_petugas', $user->id_petugas))
            ->latest()
            ->take(5)
            ->get();

        $all_tickets = Tiket::whereNotNull('latitude')->whereNotNull('longitude')->get(['latitude', 'longitude', 'id_tiket', 'status', 'nama_pelapor']);

        return view('dashboard.index', compact('stats', 'recent_tickets', 'all_tickets'));
    }
}
