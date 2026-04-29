<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;

class PublicTiketController extends Controller
{
    public function landing()
    {
        return view('public.landing');
    }

    public function index()
    {
        return view('public.tiket_create');
    }

    public function store(Request $request)
    {
        // Handle case where file is too large for PHP config (before Laravel validation)
        if ($request->has('foto_keluhan') && $request->file('foto_keluhan') === null && $_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()->withErrors(['foto_keluhan' => 'Ukuran file terlalu besar. Maksimal yang diizinkan server kemungkinan 2MB-8MB. Mohon gunakan foto dengan ukuran lebih kecil.'])->withInput();
        }

        $request->validate([
            'nama_pelapor' => 'required|string|max:100',
            'no_whatsapp' => 'required|string|max:20',
            'rt' => 'required|string|max:10',
            'rw' => 'nullable|string|max:10',
            'kelurahan' => 'required|string|max:50',
            'kecamatan' => 'required|string|max:50',
            'kategori' => 'nullable|string|max:50',
            'deskripsi' => 'nullable|string',
            'foto_keluhan' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_keluhan')) {
            $fotoPath = $request->file('foto_keluhan')->store('keluhan', 'public');
        }

        $tiket = Tiket::create([
            'nama_pelapor' => $request->nama_pelapor,
            'no_whatsapp' => $request->no_whatsapp,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'foto_keluhan' => $fotoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Open',
            'id_petugas' => null,
            'tgl_lapor' => now(),
        ]);

        return redirect()->back()->with('success', 'Tiket ' . $tiket->ticket_no . ' telah terkirim! Teknisi akan segera menghubungi Anda melalui WhatsApp.');
    }
}
