<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        return response()->json(Obat::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $obat = Obat::create($request->only('nama', 'stok'));
        return response()->json($obat, 201);
    }

    public function show(Obat $obat)
    {
        return response()->json($obat);
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $obat->update($request->only('nama', 'stok'));
        return response()->json($obat);
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return response()->json(['message' => 'Obat berhasil dihapus']);
    }
}
