<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('staff')->get();
        return response()->json($transactions);
    }

   public function store(Request $request)
{
    // Validasi dasar (tanpa ref_no & created_by dari FE)
    $validated = $request->validate([
        'client_name' => 'required|string',
        'age' => 'nullable|integer|min:0|max:120',
        'occupation' => 'nullable|string',
        'sex' => 'nullable|in:M,F',

        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|integer',
        'items.*.product_name' => 'required|string',
        'items.*.product_type' => 'required|in:service,med',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.duration' => 'nullable|string',

        'items.*.scheduled_date' => 'nullable|date',
        'items.*.scheduled_time' => 'nullable',
        'items.*.staff_nik' => 'required|string',
        'items.*.location' => 'required|string',
        'items.*.status' => 'nullable|in:NEW,COMPLETED',
    ]);

    // Validasi kondisional: service wajib jadwal, med dilarang jadwal; staff wajib
    $errors = [];
    foreach ($validated['items'] as $idx => $item) {
        $row = $idx + 1;

        if ($item['product_type'] === 'service') {
            if (empty($item['scheduled_date']) || empty($item['scheduled_time'])) {
                $errors["items.$idx.scheduled"] = ["Baris #$row (service) wajib isi tanggal & jam."];
            }
        } else { // med
            if (!empty($item['scheduled_date']) || !empty($item['scheduled_time'])) {
                $errors["items.$idx.scheduled"] = ["Baris #$row (med) tidak boleh punya tanggal/jam."];
            }
        }

        if (empty($item['staff_nik'])) {
            $errors["items.$idx.staff_nik"] = ["Baris #$row wajib pilih staff."];
        }
    }
    if (!empty($errors)) {
        return response()->json(['message' => 'Validasi gagal', 'errors' => $errors], 422);
    }

    // Generate ref & created_by di server
    $ref  = 'TX-' . now()->format('YmdHis') . rand(100, 999);
    $user = auth()->user()->username ?? 'system';

    foreach ($validated['items'] as $item) {
        Transaction::create([
            'ref_no'            => $ref,
            'channel'           => 'Point Of Sale',
            'created_at_manual' => now(), // ubah/nullable sesuai kebutuhanmu
            'created_by'        => $user,

            'client_name'       => $validated['client_name'],
            'age'               => $validated['age'] ?? null,
            'occupation'        => $validated['occupation'] ?? null,
            'sex'               => $validated['sex'] ?? null,

            'product_id'        => $item['product_id'],
            'product_name'      => $item['product_name'],
            'product_type'      => $item['product_type'],
            'qty'               => $item['qty'],
            'price'             => $item['price'],
            'duration'          => $item['duration'] ?? null,

            'scheduled_date'    => $item['product_type'] === 'service' ? ($item['scheduled_date'] ?? null) : null,
            'scheduled_time'    => $item['product_type'] === 'service' ? ($item['scheduled_time'] ?? null) : null,

            'staff_nik'         => $item['staff_nik'],
            'location'          => $item['location'],

            'status'            => $item['status'] ?? 'NEW',
        ]);
    }

    return response()->json(['success' => true, 'ref_no' => $ref]);
}


    public function show(Transaction $transaction)
    {
        return response()->json($transaction->load('staff'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $data = $request->validate([
            'client_name' => 'nullable|string',
            'age' => 'nullable|integer|min:0|max:120',
            'occupation' => 'nullable|string',
            'sex' => 'nullable|in:M,F',
            'status' => 'in:NEW,COMPLETED'
        ]);

        $transaction->update($data);
        return response()->json($transaction);
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return response()->noContent();
    }

    public function listPage()
{
    // Ringkas transaksi per ref_no
    $rows = \App\Models\Transaction::select(
        'ref_no',
        \DB::raw('MIN(created_at) as created_at'),
        \DB::raw('MIN(created_by) as created_by'),
        \DB::raw('MIN(client_name) as client_name'),
        \DB::raw('SUM(qty * price) as total'),
        \DB::raw('COUNT(*) as items_count')
    )
    ->groupBy('ref_no')
    ->orderBy(\DB::raw('MIN(created_at)'), 'desc')
    ->get();

    return view('pages.list', compact('rows'));
}
public function destroyByRef(string $ref_no)
{
    // Opsi: tambahkan otorisasi di sini kalau perlu (mis. Gate/Policy)

    $count = \App\Models\Transaction::where('ref_no', $ref_no)->count();
    if ($count === 0) {
        return redirect()->route('list.page')->with('error', "Transaksi {$ref_no} tidak ditemukan.");
    }

    \App\Models\Transaction::where('ref_no', $ref_no)->delete();

    return redirect()->route('list.page')->with('success', "Transaksi {$ref_no} ({$count} item) berhasil dihapus.");
}

//detailtransaksi
    public function detailByRef(string $ref_no)
{
    $items = \App\Models\Transaction::with('staff')
        ->where('ref_no', $ref_no)
        ->orderBy('id')
        ->get();

    if ($items->isEmpty()) {
        return redirect()->route('list.page')->with('error', "Transaksi $ref_no tidak ditemukan.");
    }

    $first = $items->first();
    $header = (object)[
        'ref_no'      => $first->ref_no,
        'created_at'  => $first->created_at,
        'created_by'  => $first->created_by,
        'client_name' => $first->client_name,
    ];

    $services = $items->where('product_type', 'service')->values();
    $meds     = $items->where('product_type', 'med')->values();

    $total = $items->reduce(fn($c,$it)=> $c + ($it->qty * $it->price), 0);

    return view('pages.detail', compact('header','services','meds','total'));
}


public function recommend(Request $request)
{
    $age        = $request->filled('age') ? (int)$request->age : null;
    $sex        = $request->sex ?: null;
    $occupation = $request->occupation ? trim($request->occupation) : null;
    $limit      = 10; // top-N yang kamu mau

    // helper build query dengan opsi pelonggaran
    $build = function(bool $useAge, int $ageRange, bool $useSex, bool $useOcc) use ($age,$sex,$occupation) {
        $q = Transaction::query();

        if ($useSex && $sex) {
            $q->where('sex', $sex);
        }

        if ($useAge && $age !== null) {
            $min = max(0, $age - $ageRange);
            $max = min(120, $age + $ageRange);
            // Sertakan age NULL supaya data lama tidak “hilang total” saat filter usia aktif
            $q->where(function($w) use ($min,$max) {
                $w->whereBetween('age', [$min,$max])
                  ->orWhereNull('age');
            });
        }

        if ($useOcc && $occupation) {
            // case-insensitive dan trimming
            $q->whereRaw('LOWER(TRIM(occupation)) LIKE ?', ['%'.strtolower($occupation).'%']);
        }

        return $q;
    };

    // ===== Progressive backoff =====
    // 1) ketat: age±5 + sex + occupation
    $candidates = $build(true, 5, true, true)->get(['product_id','product_type']);
    // 2) longgarkan usia (±10), tetap sex, drop occ jika perlu
    if ($candidates->isEmpty()) $candidates = $build(true,10, true, true)->get(['product_id','product_type']);
    if ($candidates->isEmpty()) $candidates = $build(true,10, true, false)->get(['product_id','product_type']);
    // 3) hanya sex
    if ($candidates->isEmpty()) $candidates = $build(false,0, true, false)->get(['product_id','product_type']);
    // 4) hanya occupation
    if ($candidates->isEmpty()) $candidates = $build(false,0, false, true)->get(['product_id','product_type']);
    // 5) trending global (fallback)
    $fallbackUsed = false;
    if ($candidates->isEmpty()) {
        $fallbackUsed = true;
        $candidates = Transaction::query()
            ->latest('created_at')
            ->limit(1000) // batasi scan
            ->get(['product_id','product_type']);
    }

    // Hitung frekuensi per (product_id, product_type)
    $counts = [];
    foreach ($candidates as $tx) {
        if (!$tx->product_id || !$tx->product_type) continue;
        $key = $tx->product_id . '|' . $tx->product_type;
        $counts[$key] = ($counts[$key] ?? 0) + 1;
    }
    arsort($counts);

    // Ambil Top-N dan map ke bentuk FE
    $topKeys = array_slice(array_keys($counts), 0, $limit);
    $recommendations = [];
    foreach ($topKeys as $key) {
        [$pid, $ptype] = explode('|', $key);
        $recommendations[] = [
            'product_id'   => (int)$pid,
            'product_type' => $ptype,
        ];
    }

    return response()->json([
        'recommendations' => $recommendations,
        'total_matches'   => array_sum(array_intersect_key($counts, array_flip($topKeys))) ?: $candidates->count(),
        'fallback_used'   => $fallbackUsed,
    ]);
}



}


