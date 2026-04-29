<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use App\Models\Petugas;

class DashboardTiketController extends Controller
{
    public function index(Request $request)
    {
        $query = Tiket::with('petugas');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelapor', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('no_whatsapp', 'like', "%{$search}%");
            });
        }

        if (auth()->user()->role == 'Teknisi') {
            $query->where(function($q) {
                $q->where('id_petugas', auth()->id())
                  ->orWhere('status', 'Open');
            });
        }

        $tikets = $query->latest()->paginate(10)->withQueryString();
        
        $teknisis = Petugas::where('role', 'Teknisi')->get();

        return view('dashboard.tiket.index', compact('tikets', 'teknisis'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'id_petugas' => 'required|exists:petugas,id_petugas',
        ]);

        $tiket = Tiket::findOrFail($id);
        $tiket->update([
            'id_petugas' => $request->id_petugas,
            'status' => 'Proses', // Auto update to Proses when assigned
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil ditugaskan ke Teknisi.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,Proses,Selesai',
        ]);

        $tiket = Tiket::findOrFail($id);
        $tiket->update([
            'status' => $request->status,
            'catatan_teknisi' => $request->catatan_teknisi ?? $tiket->catatan_teknisi
        ]);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function claim($id)
    {
        $tiket = Tiket::findOrFail($id);
        
        if ($tiket->status != 'Open') {
            return redirect()->back()->with('error', 'Tiket sudah diambil oleh orang lain.');
        }

        $tiket->update([
            'id_petugas' => auth()->id(),
            'status' => 'Proses',
        ]);

        return redirect()->back()->with('success', 'Tiket berhasil Anda ambil.');
    }

    public function markRead($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang dapat menghapus tiket.');
        }

        $tiket = Tiket::findOrFail($id);
        $tiket->delete();

        return redirect()->back()->with('success', 'Tiket berhasil dihapus.');
    }
}
