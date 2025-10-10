<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(Service::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $service = Service::create($request->only('nama', 'stok'));
        return response()->json($service, 201);
    }

    public function show(Service $service)
    {
        return response()->json($service);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $service->update($request->only('nama', 'stok'));
        return response()->json($service);
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json(['message' => 'Service berhasil dihapus']);
    }
}
