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
        if ($q === null || $q === '') {
            return response()->json([]);
        }

        $isNumeric = ctype_digit((string)$q);

        // Services
        $servicesQuery = Service::query();
        if ($isNumeric) {
            $servicesQuery->where('id', (int)$q);
        } else {
            $servicesQuery->where('name', 'like', "%{$q}%");
        }
        $services = $servicesQuery->get(['id','name','price'])
            ->map(fn($s)=>[
                'id'    => $s->id,
                'name'  => $s->name,
                'type'  => 'service',
                'price' => $s->price,
            ])->toArray();

        // Meds
        $medsQuery = Med::query();
        if ($isNumeric) {
            $medsQuery->where('id', (int)$q);
        } else {
            $medsQuery->where('name', 'like', "%{$q}%");
        }
        $meds = $medsQuery->get(['id','name','price'])
            ->map(fn($m)=>[
                'id'    => $m->id,
                'name'  => $m->name,
                'type'  => 'med',
                'price' => $m->price,
            ])->toArray();

        return response()->json(array_merge($services, $meds));
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
