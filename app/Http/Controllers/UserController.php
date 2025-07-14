<?php

namespace App\Http\Controllers;

use App\Models\UserModel as User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $data = UserModel::latest()->paginate(10);
        return view('users.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        UserModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->tulisLog('Menambahkan', $request->name);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);

        // Proteksi: larang update user ID = 1 atau email ikn@gmail.com
        if ($user->id == 1 || $user->email === 'ikn@gmail.com') {
            return back()->with('error', 'User ini tidak boleh diubah.');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first());
        }

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $this->tulisLog('Mengubah', $user->name);

        return back()->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = UserModel::findOrFail($id);

        // Proteksi: larang hapus user ID = 1 atau email ikn@gmail.com
        if ($user->id == 1 || $user->email === 'ikn@gmail.com') {
            return back()->with('error', 'User ini tidak boleh dihapus.');
        }

        $this->tulisLog('Menghapus', $user->name);

        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    private function tulisLog($aksi, $targetUser)
    {
        $tanggal = now()->format('Y-m-d H:i:s');
        $userEmail = Auth::check() ? Auth::user()->email : 'Guest';
        $baris = "[$tanggal] $aksi user: $targetUser | oleh: $userEmail\n";
        file_put_contents(storage_path('logs/log.txt'), $baris, FILE_APPEND);
    }
}
