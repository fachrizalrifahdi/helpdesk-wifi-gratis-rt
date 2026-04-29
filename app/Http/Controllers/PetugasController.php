<?php

namespace App\Http\Controllers;

use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PetugasController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        $petugas = Petugas::latest()->get();
        return view('dashboard.petugas.index', compact('petugas'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:petugas,username',
            'password' => ['required', Password::defaults()],
            'role' => 'required|in:Admin,Teknisi',
        ]);

        Petugas::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Petugas baru berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang dapat mengakses halaman ini.');
        }

        $petugas = Petugas::findOrFail($id);
        
        // Don't allow deleting self
        if ($petugas->id_petugas === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $petugas->delete();
        return redirect()->back()->with('success', 'Petugas berhasil dihapus.');
    }

    public function resetPassword(Request $request, $id)
    {
        if (auth()->user()->role !== 'Admin') {
            abort(403);
        }

        $petugas = Petugas::findOrFail($id);
        $petugas->update([
            'password' => \Illuminate\Support\Facades\Hash::make('password123')
        ]);

        return redirect()->back()->with('success', "Password {$petugas->nama} direset menjadi: password123");
    }
}
