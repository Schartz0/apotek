<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Med;

class ProductController extends Controller
{
    public function search(Request $request)
    {
        try {
            $q = $request->query('q');
            if (!$q) {
                return response()->json([]);
            }

            // Ambil data dari tabel services
            $services = Service::where('name', 'like', "%{$q}%")
                ->get(['id', 'name', 'price'])
                ->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'type' => 'service',
                        'price' => $s->price,
                    ];
                })
                ->toArray(); // ⬅️ ubah jadi array di sini

            // Ambil data dari tabel meds
            $meds = Med::where('name', 'like', "%{$q}%")
                ->get(['id', 'name', 'price'])
                ->map(function ($m) {
                    return [
                        'id' => $m->id,
                        'name' => $m->name,
                        'type' => 'med',
                        'price' => $m->price,
                    ];
                })
                ->toArray(); // ⬅️ ubah juga jadi array di sini

            // Gabungkan dua array biasa, bukan Collection
            $result = array_merge($services, $meds);

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
