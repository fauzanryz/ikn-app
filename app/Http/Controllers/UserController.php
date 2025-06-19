<?php

namespace App\Http\Controllers;

use App\Models\UserModel as User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = UserModel::latest()->paginate(10);
        return view('users.index', compact('data'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',            // Nama wajib diisi, string, maksimal 255 karakter
            'email' => 'required|email|unique:users,email', // Email wajib unik di tabel users
            'password' => 'required|min:8|confirmed',       // Password wajib, minimal 8 karakter, harus dikonfirmasi
        ]);

        // Jika validasi gagal, kembali dengan pesan error pertama
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        // Simpan user baru ke database
        UserModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id); // Cari user, jika tidak ada maka error 404

        // Aturan validasi
        $rules = [
            'name' => 'required|string|max:255',                       // Nama wajib
            'email' => 'required|email|unique:users,email,' . $id,     // Email wajib unik kecuali milik user ini
        ];

        // Jika password diisi, validasi juga password
        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed'; // Password minimal 8 karakter
        }

        // Jalankan validasi
        $validator = Validator::make($request->all(), $rules);

        // Jika validasi gagal
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Simpan perubahan
        $user->save();

        return back()->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        UserModel::destroy($id);
        return back()->with('success', 'User berhasil dihapus.');
    }
}
