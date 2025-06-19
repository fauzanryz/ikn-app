<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

class PageController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function cekSentimen(Request $request)
    {
        // Ambil input komentar dari form
        $komentar = $request->input('sentimen');

        // Preprocessing
        // Ubah teks jadi huruf kecil semua
        $text = strtolower($komentar);

        // Hilangkan karakter non-huruf (punctuation, angka, simbol) kecuali spasi
        $text = preg_replace("/[^\p{L}\s]/u", '', $text);

        // Tokenisasi: pecah menjadi kata-kata
        $tokens = explode(' ', $text);

        // Hilangkan stopword (kata umum yang tidak penting untuk analisis, misal "dan", "di")
        $stopWordFactory = new StopWordRemoverFactory();
        $stopword = $stopWordFactory->createStopWordRemover();
        $text = $stopword->remove(implode(' ', $tokens));

        // Stemming: ubah kata ke bentuk dasar (misal "berlari" jadi "lari")
        $stemmerFactory = new StemmerFactory();
        $stemmer = $stemmerFactory->createStemmer();
        $text = $stemmer->stem($text);

        // Tokenisasi lagi setelah stemming
        $tokens = explode(' ', $text);

        // Load model
        // Path ke file model.json (model Naive Bayes disimpan dalam file JSON)
        $modelPath = base_path('app/model.json');

        // Jika model belum ada, tampilkan error
        if (!file_exists($modelPath)) {
            return redirect()->back()->with('error', 'Model belum tersedia.');
        }

        // Baca file JSON dan decode jadi array PHP
        $modelJson = file_get_contents($modelPath);
        $model = json_decode($modelJson, true);

        // Ambil prior probability, likelihood, dan vocab dari model
        $priorProb = $model['priorProb']; // probabilitas awal setiap kelas
        $likelihood = $model['likelihood']; // probabilitas kata muncul di tiap kelas
        $vocab = $model['vocab']; // daftar kata yang dikenal

        // Prediksi
        $hasil = $this->predictNaiveBayes($tokens, $priorProb, $likelihood, $vocab);

        return redirect()->back()->withInput()->with('hasil', ucfirst($hasil));
    }

    private function predictNaiveBayes($tokens, $priorProb, $likelihood, $vocab)
    {
        $classes = array_keys($priorProb); // daftar kelas
        $scores = []; // simpan skor masing-masing kelas

        foreach ($classes as $class) {
            // Mulai dengan log prior probability (logaritma untuk menghindari underflow)
            $score = log($priorProb[$class]);

            foreach ($tokens as $token) {
                if (in_array($token, $vocab)) {
                    // Jika kata ada di vocab dan ada likelihood-nya untuk kelas ini
                    if (isset($likelihood[$class][$token])) {
                        $score += log($likelihood[$class][$token]);
                    } else {
                        // Smoothing: jika kata tidak ada likelihood-nya, beri nilai kecil supaya skor tidak nol
                        $score += log(1e-6);
                    }
                }
                // Jika token tidak ada di vocab, token diabaikan (tidak memengaruhi skor)
            }
            $scores[$class] = $score;
        }

        // Urutkan skor dari besar ke kecil dan ambil kelas dengan skor terbesar
        arsort($scores);
        return key($scores);
    }
}
