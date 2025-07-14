<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreprocessingModel;
use Illuminate\Support\Facades\Auth;

class PreprocessingController extends Controller
{
    public function index()
    {
        $data = PreprocessingModel::paginate(5);
        return view('preprocessing.index', compact('data'));
    }

    public function dataclean()
    {
        $data = PreprocessingModel::select('id', 'data_clean', 'sentiment')->paginate(5);
        return view('preprocessing.dataclean', compact('data'));
    }

    public function updateSentimen(Request $request, $id)
    {
        // Validasi input: sentiment wajib diisi dan harus salah satu dari positif atau negatif
        $request->validate([
            'sentiment' => 'required|in:positif,negatif',
        ]);

        // Cari data berdasarkan id, jika tidak ditemukan akan error 404
        $data = PreprocessingModel::findOrFail($id);

        // Update kolom sentiment dengan nilai dari form
        $data->sentiment = $request->sentiment;

        // Simpan perubahan ke database
        $data->save();

        $this->tulisLog('Mengubah sentiment', "ID {$data->id} menjadi {$request->sentiment}");

        return redirect()->back()->with('success', 'Sentiment berhasil diperbarui.');
    }

    private function tulisLog($aksi, $target)
    {
        $tanggal = now()->format('Y-m-d H:i:s');
        $userEmail = Auth::check() ? Auth::user()->email : 'Guest';
        $baris = "[$tanggal] $aksi: $target | oleh: $userEmail\n";
        file_put_contents(storage_path('logs/log.txt'), $baris, FILE_APPEND);
    }
}
