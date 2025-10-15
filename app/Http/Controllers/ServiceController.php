<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Tampilkan semua data service (untuk view & API)
     */
    public function index(Request $request)
    {
        $services = Service::all();

        // Jika akses via API
        if ($request->wantsJson()) {
            return response()->json($services);
        }

        // Jika akses via web (Blade)
        return view('pages.produk_service', compact('services'));
    }

    /**
     * Simpan data service baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'duration'  => 'nullable|string|max:100',
            'price'     => 'required|numeric|min:0',
            'available' => 'required|boolean',
        ]);

        Service::create($data);

        return redirect()->route('service.index')->with('success', 'Service berhasil ditambahkan!');
    }

    /**
     * Tampilkan halaman edit
     */
    public function edit(Service $service)
    {
        return view('pages.produk_service_edit', compact('service'));
    }

    /**
     * Update data service
     */
    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'duration'  => 'nullable|string|max:100',
            'price'     => 'required|numeric|min:0',
            'available' => 'required|boolean',
        ]);

        $service->update($data);

        return redirect()->route('service.index')->with('success', 'Service berhasil diperbarui!');
    }

    /**
     * Hapus data service
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('service.index')->with('success', 'Service berhasil dihapus!');
    }
}
