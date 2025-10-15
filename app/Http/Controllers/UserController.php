<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        // Hanya admin yang bisa akses seluruh method
        $this->middleware('isAdmin');
    }

    // Tampilkan semua user yang role = 'user'
    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('pages.user_index', compact('users'));
    }

    // Simpan user baru, role otomatis 'user'
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:1',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan!');
    }

    // Edit user (hanya admin)
    public function edit(User $user)
    {
        return view('pages.user_edit', compact('user'));
    }

    // Update user, role tetap 'user'
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|string|min:4',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['role'] = 'user'; // role tetap user

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui!');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus!');
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:4|confirmed',
    ]);

    if (!Hash::check($request->old_password, auth()->user()->password)) {
        return back()->with('error', 'Password lama tidak cocok!');
    }

    auth()->user()->update([
        'password' => Hash::make($request->new_password),
    ]);

    return back()->with('success', 'Password berhasil diganti!');
}


}


