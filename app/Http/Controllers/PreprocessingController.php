<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreprocessingModel;

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

        return redirect()->back()->with('success', 'Sentiment berhasil diperbarui.');
    }
}
