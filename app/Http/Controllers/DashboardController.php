<?php

namespace App\Http\Controllers;

use App\Models\PreprocessingModel;

class DashboardController extends Controller
{
    public function index()
    {
        // Hitung jumlah data dengan sentimen positif
        $positiveCount = PreprocessingModel::where('sentiment', 'positif')->count();

        // Hitung jumlah data dengan sentimen negatif
        $negativeCount = PreprocessingModel::where('sentiment', 'negatif')->count();

        // Ambil kolom 'stemming' untuk data positif (berisi kata-kata yang sudah di-stemming)
        $positifWords = PreprocessingModel::where('sentiment', 'positif')->pluck('stemming');

        // Ambil kolom 'stemming' untuk data negatif
        $negatifWords = PreprocessingModel::where('sentiment', 'negatif')->pluck('stemming');

        // Hitung frekuensi kata pada data positif
        $frekuensiPositif = $this->hitungFrekuensi($positifWords);

        // Hitung frekuensi kata pada data negatif
        $frekuensiNegatif = $this->hitungFrekuensi($negatifWords);

        // Kirim data ke view dashboard.index
        return view('dashboard.index', compact(
            'positiveCount',
            'negativeCount',
            'frekuensiPositif',
            'frekuensiNegatif'
        ));
    }

    // Fungsi untuk menghitung frekuensi kata dari kumpulan data kata
    private function hitungFrekuensi($wordsCollection)
    {
        $allWords = []; // array untuk menyimpan kata dan jumlah kemunculannya

        foreach ($wordsCollection as $jsonArrayString) {
            // Decode string JSON menjadi array PHP
            $kataArray = json_decode($jsonArrayString, true);

            // Jika gagal decode (bukan JSON), sebagai alternatif split dengan spasi
            if (!is_array($kataArray)) {
                $kataArray = explode(' ', $jsonArrayString);
            }

            // Hitung frekuensi setiap kata
            foreach ($kataArray as $kata) {
                $kata = trim($kata); // hilangkan spasi di awal/akhir kata
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
