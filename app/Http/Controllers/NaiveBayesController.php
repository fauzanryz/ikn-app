<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreprocessingModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class NaiveBayesController extends Controller
{
 public function index(Request $request)
 {
  // Ambil data preprocessing yang sudah ada sentimen dan stemming-nya
  $data = PreprocessingModel::whereNotNull('sentiment')
   ->whereNotNull('stemming')
   ->get(['stemming', 'sentiment']);

  // Siapkan array dokumen
  $documents = [];
  foreach ($data as $row) {
   $terms = json_decode($row->stemming, true); // decode stemming ke array
   if (!is_array($terms)) continue;

   $documents[] = [
    'terms' => $terms, // kata-kata hasil stemming
    'label' => strtolower(trim($row->sentiment)), // label sentimen
   ];
  }

  // Hitung total dokumen
  $totalDocuments = count($documents);
  if ($totalDocuments === 0) {
   // Jika tidak ada data, kembalikan view dengan data kosong
   $emptyPaginator = new LengthAwarePaginator([], 0, 10);
   return view('naivebayes.index', [
    'data' => $emptyPaginator,
    'priorProb' => null,
    'likelihoodTable' => new LengthAwarePaginator([], 0, 10),
    'posteriorTable' => new LengthAwarePaginator([], 0, 10),
    'confMatrix' => null
   ]);
  }

  // Ambil parameter split dari input, default testSize = 0.25
  $testSize = floatval($request->input('testSize', 0.25));
  $randomState = intval($request->input('randomState', 42));

  // ------------------------
  // Stratified split manual
  // ------------------------

  // 1. Kelompokkan index berdasarkan label
  // Tujuan: supaya kita tahu index mana saja yang termasuk masing-masing label (positif/negatif)
  $labelToIndices = [];
  foreach ($documents as $i => $doc) {
   $label = $doc['label'];
   // Masukkan index dokumen ke kelompok label yang sesuai
   $labelToIndices[$label][] = $i;
  }

  // 2. Acak urutan index dalam setiap kelompok (stratified shuffle)
  // Tujuan: supaya data train dan test tetap seimbang untuk tiap label dan acaknya konsisten
  foreach ($labelToIndices as $label => &$indices) {
   // Acak index dokumen dalam kelompok label menggunakan seed tertentu agar hasilnya konsisten
   $this->seededShuffle($indices, $randomState);
  }
  unset($indices); // Hapus reference biar aman

  // 3. Bagi data train dan test dari setiap kelompok label sesuai proporsi testSize
  // Tujuan: menjaga proporsi data test dan train untuk setiap label
  $trainIndices = [];
  $testIndices = [];
  foreach ($labelToIndices as $label => $indices) {
   // Hitung titik pemisah antara train dan test (jumlah train = (1 - testSize) * total)
   $splitAt = (int) floor((1 - $testSize) * count($indices));
   // Ambil index untuk train
   $trainIndices = array_merge($trainIndices, array_slice($indices, 0, $splitAt));
   // Ambil index untuk test
   $testIndices = array_merge($testIndices, array_slice($indices, $splitAt));
  }

  // 4. Acak ulang hasil train dan test agar index dari kedua label bercampur (opsional tapi dianjurkan)
  // Tujuan: supaya data train dan test tidak terurut berdasarkan label
  $this->seededShuffle($trainIndices, $randomState);
  $this->seededShuffle($testIndices, $randomState + 1);

  // ======================
  // Hitung PRIOR PROBABILITY
  // ======================

  // Hitung jumlah dokumen train untuk setiap label (positif/negatif)
  // Tujuan: kita ingin tahu berapa proporsi dokumen tiap kelas di data train
  $labelCountsTrain = [];
  foreach ($trainIndices as $i) {
   $label = $documents[$i]['label'];
   // Tambah 1 untuk label ini
   $labelCountsTrain[$label] = ($labelCountsTrain[$label] ?? 0) + 1;
  }

  // Hitung prior probability = proporsi tiap kelas pada data train
  $priorProb = [];
  $totalTrainDocs = count($trainIndices); // total dokumen train
  foreach ($labelCountsTrain as $label => $count) {
   $priorProb[$label] = $count / $totalTrainDocs; // prior = jumlah label / total train
  }

  // ======================
  // Proses TF-IDF
  // ======================

  // Hitung Term Frequency (TF) untuk setiap dokumen
  // Hitung Document Frequency (DF) untuk setiap term
  $termFreqPerDoc = []; // term frequency per dokumen
  $termDocFreq = [];    // document frequency per term

  foreach ($documents as $i => $doc) {
   // Hitung jumlah kemunculan tiap kata di dokumen (TF)
   $counts = array_count_values($doc['terms']);
   $termFreqPerDoc[$i] = $counts;

   // Tambahkan DF: hitung berapa dokumen mengandung term ini
   foreach (array_keys($counts) as $term) {
    $termDocFreq[$term] = ($termDocFreq[$term] ?? 0) + 1;
   }
  }

  // Hitung Inverse Document Frequency (IDF) untuk setiap term
  // Rumus: log((1 + N) / (1 + df)) + 1
  // Tujuan: mengurangi bobot term yang sering muncul di banyak dokumen
  $idf = [];
  foreach ($termDocFreq as $term => $df) {
   $idf[$term] = log((1 + $totalDocuments) / (1 + $df)) + 1;
  }

  // Hitung TF-IDF dan normalisasi vektor
  $tfidfPerDoc = [];
  foreach ($termFreqPerDoc as $docIndex => $termCounts) {
   $totalTerms = array_sum($termCounts); // total term dalam dokumen
   $vector = [];
   foreach ($termCounts as $term => $count) {
    $tf = $count / $totalTerms; // term frequency = count / totalTerms
    $vector[$term] = $tf * ($idf[$term] ?? 0); // TF-IDF = TF * IDF
   }

   // Normalisasi vektor (panjang vektor jadi 1) supaya adil
   $norm = sqrt(array_sum(array_map(fn($x) => $x * $x, $vector)));
   if ($norm > 0) {
    foreach ($vector as $term => $val) {
     $vector[$term] = $val / $norm;
    }
   }

   // Simpan TF-IDF normal untuk dokumen ini
   $tfidfPerDoc[$docIndex] = $vector;
  }

  // ======================
  // Hitung Likelihood per Kelas
  // ======================

  // Dapatkan semua term unik (vocab) dari dataset
  $vocab = array_keys($termDocFreq);
  $vocabSize = count($vocab);

  // Simpan total tf-idf untuk tiap kelas dan tiap term
  $tfidfSumPerClass = [];   // jumlah total tf-idf semua term di kelas
  $tfidfTermPerClass = [];  // jumlah tf-idf tiap term di kelas

  // Hitung tf-idf per kelas untuk data train
  foreach ($trainIndices as $i) {
   $label = $documents[$i]['label'];
   $vec = $tfidfPerDoc[$i]; // tf-idf vektor dokumen

   foreach ($vec as $term => $val) {
    // Jumlahkan tf-idf term ini ke kelasnya
    $tfidfTermPerClass[$label][$term] = ($tfidfTermPerClass[$label][$term] ?? 0) + $val;

    // Jumlahkan total tf-idf kelas
    $tfidfSumPerClass[$label] = ($tfidfSumPerClass[$label] ?? 0) + $val;
   }
  }

  // Hitung likelihood (probabilitas term per kelas dengan smoothing)
  // Rumus: (tfidf term + 1) / (total tfidf semua term + |vocab|)
  $likelihood = [];
  foreach ($tfidfTermPerClass as $label => $terms) {
   $denominator = $tfidfSumPerClass[$label] + $vocabSize; // +vocabSize = Laplace smoothing
   foreach ($vocab as $term) {
    $numerator = ($terms[$term] ?? 0) + 1; // +1 smoothing
    $likelihood[$label][$term] = $numerator / $denominator;
   }
  }

  // ======================
  // Prediksi data uji
  // ======================

  $predictions = []; // simpan hasil prediksi
  $actuals = [];     // simpan label asli

  // Loop setiap dokumen uji
  foreach ($testIndices as $i) {
   $docIndex = $i;
   $actualLabel = $documents[$docIndex]['label']; // label asli
   $vec = $tfidfPerDoc[$docIndex];                // tf-idf dokumen

   $scores = []; // skor log-probabilitas per kelas
   foreach ($priorProb as $label => $prior) {
    $score = log($prior); // mulai dengan log prior probability

    // Tambahkan log likelihood untuk setiap term di dokumen
    foreach ($vec as $term => $value) {
     // Jika term tidak ada di likelihood, gunakan smoothing
     // Rumus: log(p(term|class)) dikali nilai tf-idf term
     $score += $value * log($likelihood[$label][$term] ?? (1 / ($tfidfSumPerClass[$label] + $vocabSize)));
    }
    $scores[$label] = $score;
   }

   // Ambil label dengan skor tertinggi
   arsort($scores);
   $predicted = array_key_first($scores);

   // Simpan hasil
   $actuals[] = $actualLabel;
   $predictions[] = $predicted;
  }

  // ======================
  // Confusion Matrix + Akurasi
  // ======================

  $confMatrix = [];  // Menyimpan jumlah prediksi untuk kombinasi (aktual, prediksi)
  $correct = 0;      // Hitung prediksi yang benar

  foreach ($predictions as $i => $pred) {
   $actual = $actuals[$i];

   // Inisialisasi baris untuk label aktual jika belum ada
   if (!isset($confMatrix[$actual])) $confMatrix[$actual] = [];

   // Tambahkan hitungan pada kombinasi aktual-prediksi
   $confMatrix[$actual][$pred] = ($confMatrix[$actual][$pred] ?? 0) + 1;

   // Tambahkan jumlah correct jika prediksi = aktual
   if ($actual === $pred) $correct++;
  }

  // Pastikan semua kombinasi label ada di matriks (lengkapi dengan 0 jika tidak ada)
  $allLabels = array_unique(array_merge(array_keys($confMatrix), array_keys($priorProb)));
  sort($allLabels); // urutkan label biar rapi

  foreach ($allLabels as $a) {
   foreach ($allLabels as $b) {
    // Jika kombinasi belum ada, isi dengan 0
    $confMatrix[$a][$b] = $confMatrix[$a][$b] ?? 0;
   }
  }
  // Hitung akurasi: jumlah prediksi benar dibagi total prediksi
  $accuracy = count($predictions) > 0 ? $correct / count($predictions) : 0;

  // ======================
  // TF-IDF Data Table (Pagination)
  // ======================

  // Bentuk array untuk setiap term dalam dokumen, isinya: no dokumen, term, nilai tf-idf
  $tfidfTable = [];
  foreach ($tfidfPerDoc as $i => $terms) {
   foreach ($terms as $term => $val) {
    $tfidfTable[] = [
     'doc_number' => $i + 1,
     'term' => $term,
     'tfidf' => $val,
    ];
   }
  }

  // Paginate hasil TF-IDF
  $perPage = 5;
  $page = $request->get('page', 1);
  $offset = ($page - 1) * $perPage;
  $paginated = new LengthAwarePaginator(
   array_slice($tfidfTable, $offset, $perPage), // ambil data sesuai halaman
   count($tfidfTable),
   $perPage,
   $page,
   ['path' => $request->url(), 'query' => $request->query()]
  );

  // ======================
  // Likelihood Table (Pagination)
  // ======================

  // Bentuk tabel likelihood: untuk setiap term pada dokumen uji, tampilkan p(term|negatif) & p(term|positif)
  $likelihoodTable = [];
  foreach ($testIndices as $docIndex) {
   $docTerms = $documents[$docIndex]['terms'];
   $seen = []; // untuk hindari term yang berulang di dokumen
   foreach ($docTerms as $term) {
    if (isset($seen[$term])) continue; // hindari duplikat term
    $seen[$term] = true;

    $likelihoodTable[] = [
     'document' => ($docIndex + 1),
     'term' => $term,
     'p_negatif' => $likelihood['negatif'][$term] ?? 0,
     'p_positif' => $likelihood['positif'][$term] ?? 0,
    ];
   }
  }

  // Paginate likelihood table
  $pageLikelihood = $request->get('page_likelihood', 1);
  $offsetLikelihood = ($pageLikelihood - 1) * $perPage;
  $paginatedLikelihood = new LengthAwarePaginator(
   array_slice($likelihoodTable, $offsetLikelihood, $perPage),
   count($likelihoodTable),
   $perPage,
   $pageLikelihood,
   ['path' => $request->url(), 'pageName' => 'page_likelihood']
  );

  // ======================
  // Posterior Table (Pagination)
  // ======================

  // Bentuk tabel posterior: hitung P(positif|dok) dan P(negatif|dok) untuk setiap dokumen uji
  $posteriorTable = [];
  foreach ($testIndices as $i) {
   $vec = $tfidfPerDoc[$i];
   $actualLabel = $documents[$i]['label'];
   $dataClean = implode(' ', $documents[$i]['terms']);

   // Hitung log-posterior untuk setiap kelas
   $logPosteriors = [];
   foreach ($priorProb as $label => $prior) {
    $score = log($prior);
    foreach ($vec as $term => $value) {
     $score += $value * log($likelihood[$label][$term] ?? (1 / ($tfidfSumPerClass[$label] + $vocabSize)));
    }
    $logPosteriors[$label] = $score;
   }

   // Ubah ke probabilitas posterior
   $expScores = array_map('exp', $logPosteriors);
   $sumExp = array_sum($expScores);
   $posterior = [];
   foreach ($expScores as $label => $val) {
    $posterior[$label] = $val / $sumExp;
   }

   // Ambil kelas dengan probabilitas terbesar
   arsort($posterior);
   $predicted = array_key_first($posterior);

   $posteriorTable[] = [
    'p_negatif' => $posterior['negatif'] ?? 0,
    'p_positif' => $posterior['positif'] ?? 0,
    'prediksi' => $predicted,
    'aktual' => $actualLabel,
    'data_clean' => $dataClean,
   ];
  }

  $pagePosterior = $request->get('page_posterior', 1);
  $offsetPosterior = ($pagePosterior - 1) * $perPage;
  $paginatedPosterior = new LengthAwarePaginator(
   array_slice($posteriorTable, $offsetPosterior, $perPage),
   count($posteriorTable),
   $perPage,
   $pagePosterior,
   ['path' => $request->url(), 'pageName' => 'page_posterior']
  );

  // ======================
  // Precision, Recall, F1 (Macro)
  // ======================

  // Inisialisasi array untuk precision, recall, dan f1-score per label
  $precision = [];
  $recall = [];
  $f1Score = [];

  foreach ($allLabels as $label) {
   // True Positive: jumlah prediksi benar untuk label ini
   $tp = $confMatrix[$label][$label];
   $fp = 0; // False Positive
   $fn = 0; // False Negative

   // Hitung False Positives: prediksi ke label ini tapi harusnya label lain
   foreach ($allLabels as $otherLabel) {
    if ($otherLabel !== $label) {
     $fp += $confMatrix[$otherLabel][$label];
    }
   }

   // Hitung False Negatives: prediksi ke label lain padahal harusnya label ini
   foreach ($allLabels as $otherLabel) {
    if ($otherLabel !== $label) {
     $fn += $confMatrix[$label][$otherLabel];
    }
   }

   // Precision = TP / (TP + FP)
   $precision[$label] = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;

   // Recall = TP / (TP + FN)
   $recall[$label] = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;

   // F1 Score = 2 * (Precision * Recall) / (Precision + Recall)
   $prec = $precision[$label];
   $rec = $recall[$label];
   $f1Score[$label] = ($prec + $rec) > 0 ? 2 * $prec * $rec / ($prec + $rec) : 0;
  }

  // Hitung rata-rata macro (rata-rata untuk semua label)
  $macroPrecision = array_sum($precision) / count($allLabels);
  $macroRecall = array_sum($recall) / count($allLabels);
  $macroF1 = array_sum($f1Score) / count($allLabels);

  // ======================
  // Simpan model ke file JSON
  // ======================

  // Siapkan data model yang ingin disimpan
  $modelData = [
   'priorProb' => $priorProb,          // prior probability untuk setiap label
   'likelihood' => $likelihood,        // likelihood term per label
   'vocab' => $vocab,                  // daftar kata dalam vocab
   'tfidfSumPerClass' => $tfidfSumPerClass, // jumlah tfidf per kelas (untuk smoothing)
  ];

  // Lokasi file model (di folder app)
  $modelFilePath = base_path('app/model.json'); // Laravel helper base_path() akan mengembalikan root project

  // Ubah array model ke format JSON agar bisa disimpan ke file
  $modelJson = json_encode($modelData, JSON_PRETTY_PRINT);

  // Simpan file JSON ke lokasi yang sudah ditentukan
  file_put_contents($modelFilePath, $modelJson);

  return view('naivebayes.index', [
   'data' => $paginated,
   'priorProb' => $priorProb,
   'likelihood' => $likelihood,
   'confMatrix' => [
    'labels' => $allLabels,
    'matrix' => $confMatrix,
    'accuracy' => $accuracy,
    'precision' => $precision,
    'recall' => $recall,
    'f1Score' => $f1Score,
    'macro' => [
     'precision' => $macroPrecision,
     'recall' => $macroRecall,
     'f1' => $macroF1,
    ],
   ],
   'testSize' => $testSize,
   'randomState' => $randomState,
   'likelihoodTable' => $paginatedLikelihood,
   'posteriorTable' => $paginatedPosterior,
  ]);
 }

 // Fungsi shuffle dengan seed agar hasil shuffle konsisten
 // Tujuan: agar pembagian train-test selalu sama jika seed-nya sama
 private function seededShuffle(array &$array, int $seed)
 {
  // Atur seed random agar hasil mt_rand konsisten
  mt_srand($seed);
  $count = count($array);

  // Lakukan shuffle dari belakang ke depan (Fisher-Yates Shuffle)
  for ($i = $count - 1; $i > 0; $i--) {
   // Ambil index acak antara 0 dan i
   $j = mt_rand(0, $i);

   // Tukar posisi array[i] dengan array[j]
   $temp = $array[$i];
   $array[$i] = $array[$j];
   $array[$j] = $temp;
  }
 }
}
