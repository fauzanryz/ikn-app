<?php

namespace App\Http\Controllers;

use App\Models\DatasetModel;

class DatasetController extends Controller
{
    // Tampilkan semua kolom (dataset full)
    public function full()
    {
        $data = DatasetModel::paginate(20); // Menampilkan 20 data per halaman
        return view('dataset.full', compact('data'));
    }

    // Tampilkan hanya kolom full_text (dataset full text)
    public function fullText()
    {
        // Ambil hanya full_text dari semua record
        $data = DatasetModel::paginate(20);
        return view('dataset.fulltext', compact('data'));
    }

    public function deleteAll()
    {
        DatasetModel::truncate(); // menghapus semua data
        return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
    }
}
