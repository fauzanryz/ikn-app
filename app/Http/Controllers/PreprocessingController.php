<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreprocessingModel;

class PreprocessingController extends Controller
{
    public function index()
    {
        $data = PreprocessingModel::paginate(20);
        return view('preprocessing.index', compact('data'));
    }

    public function dataclean()
    {
        $data = PreprocessingModel::select('id', 'data_clean', 'sentimen')->paginate(20);
        return view('preprocessing.dataclean', compact('data'));
    }

    public function updateSentimen(Request $request, $id)
    {
        $request->validate([
            'sentimen' => 'required|in:positif,negatif',
        ]);

        $data = PreprocessingModel::findOrFail($id);
        $data->sentimen = $request->sentimen;
        $data->save();

        return redirect()->back()->with('success', 'Sentimen berhasil diperbarui.');
    }
}
