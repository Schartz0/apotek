<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Tampilkan daftar semua staff (untuk view dan API)
     */
    public function index(Request $request)
    {
        $staffs = Staff::all();

        if ($request->wantsJson()) {
            return response()->json($staffs);
        }

        return view('pages.staff_index', compact('staffs'));
    }

    /**
     * Simpan data staff baru
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nik' => 'required|string|unique:staffs,nik',
            'name' => 'required|string',
            'sex' => 'nullable|in:M,F',
            'location' => 'nullable|string',
        ]);

        Staff::create($data);

        return redirect()->route('staff.index')->with('success', 'Data staff berhasil ditambahkan!');
    }

    /**
     * Tampilkan halaman edit staff
     */
    public function edit(Staff $staff)
    {
        return view('pages.staff_edit', compact('staff'));
    }

    /**
     * Update data staff
     */
    public function update(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'nik' => 'required|string|unique:staffs,nik,' . $staff->nik . ',nik',
            'name' => 'required|string',
            'sex' => 'nullable|in:M,F',
            'location' => 'nullable|string',
        ]);

        $staff->update($data);

        return redirect()->route('staff.index')->with('success', 'Data staff berhasil diperbarui!');
    }

    /**
     * Hapus data staff
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Data staff berhasil dihapus!');
    }
}
