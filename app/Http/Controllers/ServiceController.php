<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return view('pages.produk_service', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        Service::create($request->only('nama', 'stok'));
        return redirect()->route('service.index')->with('success', 'Service berhasil ditambahkan');
    }

    public function edit(Service $service)
    {
        return view('pages.produk_service_edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|in:Ada,Kosong',
        ]);

        $service->update($request->only('nama', 'stok'));
        return redirect()->route('service.index')->with('success', 'Service berhasil diupdate');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('service.index')->with('success', 'Service berhasil dihapus');
    }
}
