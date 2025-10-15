<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('staff')->get();
        return response()->json($transactions);
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'ref_no' => 'required|string',
        'created_by' => 'required|string',
        'client_name' => 'required|string',
        'age' => 'nullable|integer',
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
    ]);

    $ref = 'TX-' . now()->format('YmdHis') . rand(100,999);
    $user = auth()->user()->username ?? 'system';

    foreach ($validated['items'] as $item) {
        \App\Models\Transaction::create([
            'ref_no' => $ref,
            'created_by' => $user,
            'client_name' => $validated['client_name'],
            'age' => $validated['age'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'sex' => $validated['sex'] ?? null,
            'product_id' => $item['product_id'],
            'product_name' => $item['product_name'],
            'product_type' => $item['product_type'],
            'qty' => $item['qty'],
            'price' => $item['price'],
            'duration' => $item['duration'] ?? null,
            'scheduled_date' => $item['scheduled_date'] ?? null,
            'scheduled_time' => $item['scheduled_time'] ?? null,
            'staff_nik' => $item['staff_nik'],
            'location' => $item['location'],
            'status' => $item['status'] ?? 'NEW',
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
    
}