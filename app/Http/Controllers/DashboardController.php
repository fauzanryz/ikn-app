<?php

namespace App\Http\Controllers;

use App\Models\PreprocessingModel;

class DashboardController extends Controller
{
    public function index()
    {
        $positiveCount = PreprocessingModel::where('sentimen', 'positif')->count();
        $negativeCount = PreprocessingModel::where('sentimen', 'negatif')->count();

        $positifWords = PreprocessingModel::where('sentimen', 'positif')->pluck('stemming');
        $negatifWords = PreprocessingModel::where('sentimen', 'negatif')->pluck('stemming');

        $frekuensiPositif = $this->hitungFrekuensi($positifWords);
        $frekuensiNegatif = $this->hitungFrekuensi($negatifWords);

        return view('dashboard.index', compact(
            'positiveCount', 
            'negativeCount', 
            'frekuensiPositif', 
            'frekuensiNegatif'
        ));
    }

    private function hitungFrekuensi($wordsCollection)
    {
        $allWords = [];

        foreach ($wordsCollection as $jsonArrayString) {
            // Jika data string JSON array, decode dulu jadi array PHP
            $kataArray = json_decode($jsonArrayString, true);

            // Jika gagal decode (misal bukan JSON), fallback split biasa (optional)
            if (!is_array($kataArray)) {
                // fallback, misal split spasi
                $kataArray = explode(' ', $jsonArrayString);
            }

            foreach ($kataArray as $kata) {
                $kata = trim($kata);
                if ($kata != '') {
                    if (isset($allWords[$kata])) {
                        $allWords[$kata]++;
                    } else {
                        $allWords[$kata] = 1;
                    }
                }
            }
        }

        return $allWords;
    }
}
