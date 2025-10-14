<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    // Menampilkan daftar obat
    public function index()
    {
        $obats = Obat::latest()->get();
        return view('pages.produk_obat', compact('obats'));
    }

    // Menyimpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        Obat::create($request->only('nama', 'stok'));
        return redirect()->route('obat.index')->with('success', 'Obat berhasil ditambahkan');
    }

    // Form edit
    public function edit(Obat $obat)
    {
        return view('pages.produk_obat_edit', compact('obat'));
    }

    // Update data
    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $obat->update($request->only('nama', 'stok'));
        return redirect()->route('obat.index')->with('success', 'Data obat berhasil diubah');
    }

    // Hapus data
    public function destroy(Obat $obat)
    {
        $obat->delete();
        return redirect()->route('obat.index')->with('success', 'Obat berhasil dihapus');
    }
}
