<?php

namespace App\Http\Controllers;

use App\Models\Med;
use Illuminate\Http\Request;

class MedController extends Controller
{
    /**
     * Tampilkan semua data obat (untuk view & API)
     */
    public function index(Request $request)
    {
        $meds = Med::all();

        // Jika akses via API
        if ($request->wantsJson()) {
            return response()->json($meds);
        }

        // Jika akses via web (Blade)
        return view('pages.produk_med', compact('meds'));
    }
    public function show(Med $med)
    {
        return response()->json([
            'id'    => $med->id,
            'name'  => $med->name,
            'price' => $med->price,
        ]);
    }
    /**
     * Simpan data obat baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Med::create($data);

        return redirect()->route('med.index')->with('success', 'Obat berhasil ditambahkan!');
    }

    /**
     * Tampilkan halaman edit
     */
    public function edit(Med $med)
    {
        return view('pages.produk_med_edit', compact('med'));
    }

    /**
     * Update data obat
     */
    public function update(Request $request, Med $med)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $med->update($data);

        return redirect()->route('med.index')->with('success', 'Obat berhasil diperbarui!');
    }

    /**
     * Hapus data obat
     */
    public function destroy(Med $med)
    {
        $med->delete();

        return redirect()->route('med.index')->with('success', 'Obat berhasil dihapus!');
    }
}
