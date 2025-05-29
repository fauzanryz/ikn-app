<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PreprocessingModel;
use Illuminate\Pagination\LengthAwarePaginator;

class NaiveBayesController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data
        $data = PreprocessingModel::whereNotNull('sentimen')
            ->whereNotNull('stemming')
            ->get(['stemming', 'sentimen']);

        $documents = [];
        foreach ($data as $row) {
            $terms = json_decode($row->stemming, true);
            if (!is_array($terms)) continue;

            $documents[] = [
                'terms' => $terms,
                'label' => strtolower(trim($row->sentimen)),
            ];
        }

        $totalDocuments = count($documents);
        if ($totalDocuments === 0) {
            return response()->json(['message' => 'Tidak ada dokumen ditemukan.']);
        }

        // Parameter split
        $testSize = floatval($request->input('testSize', 0.25));
        $randomState = intval($request->input('randomState', 42));

        // ------------------------
        // Stratified split manual
        // ------------------------

        // 1. Kelompokkan index berdasarkan label
        $labelToIndices = [];
        foreach ($documents as $i => $doc) {
            $label = $doc['label'];
            $labelToIndices[$label][] = $i;
        }

        // 2. Shuffle tiap kelompok dengan seed yang sama
        foreach ($labelToIndices as $label => &$indices) {
            $this->seededShuffle($indices, $randomState);
        }
        unset($indices);

        // 3. Ambil data train dan test dengan proporsi sesuai testSize per label
        $trainIndices = [];
        $testIndices = [];
        foreach ($labelToIndices as $label => $indices) {
            $splitAt = (int) floor((1 - $testSize) * count($indices));
            $trainIndices = array_merge($trainIndices, array_slice($indices, 0, $splitAt));
            $testIndices = array_merge($testIndices, array_slice($indices, $splitAt));
        }

        // 4. Shuffle ulang train & test indices supaya acak campur (opsional)
        $this->seededShuffle($trainIndices, $randomState);
        $this->seededShuffle($testIndices, $randomState + 1);

        // ======================
        // Hitung PRIOR PROBABILITY
        // ======================
        $labelCountsTrain = [];
        foreach ($trainIndices as $i) {
            $label = $documents[$i]['label'];
            $labelCountsTrain[$label] = ($labelCountsTrain[$label] ?? 0) + 1;
        }

        $priorProb = [];
        $totalTrainDocs = count($trainIndices);
        foreach ($labelCountsTrain as $label => $count) {
            $priorProb[$label] = $count / $totalTrainDocs;
        }

        // ======================
        // Proses TF-IDF
        // ======================
        $termFreqPerDoc = [];
        $termDocFreq = [];

        foreach ($documents as $i => $doc) {
            $counts = array_count_values($doc['terms']);
            $termFreqPerDoc[$i] = $counts;

            foreach (array_keys($counts) as $term) {
                $termDocFreq[$term] = ($termDocFreq[$term] ?? 0) + 1;
            }
        }

        $idf = [];
        foreach ($termDocFreq as $term => $df) {
            $idf[$term] = log((1 + $totalDocuments) / (1 + $df)) + 1;
        }

        // Hitung TF-IDF
        $tfidfPerDoc = [];
        foreach ($termFreqPerDoc as $docIndex => $termCounts) {
            $totalTerms = array_sum($termCounts);
            $vector = [];
            foreach ($termCounts as $term => $count) {
                $tf = $count / $totalTerms;
                $vector[$term] = $tf * ($idf[$term] ?? 0);
            }

            // normalisasi
            $norm = sqrt(array_sum(array_map(fn($x) => $x * $x, $vector)));
            if ($norm > 0) {
                foreach ($vector as $term => $val) {
                    $vector[$term] = $val / $norm;
                }
            }

            $tfidfPerDoc[$docIndex] = $vector;
        }

        // ======================
        // Hitung Likelihood (TF-IDF Naive Bayes)
        // ======================
        $vocab = array_keys($termDocFreq);
        $vocabSize = count($vocab);

        $tfidfSumPerClass = [];
        $tfidfTermPerClass = [];

        foreach ($trainIndices as $i) {
            $label = $documents[$i]['label'];
            $vec = $tfidfPerDoc[$i];

            foreach ($vec as $term => $val) {
                $tfidfTermPerClass[$label][$term] = ($tfidfTermPerClass[$label][$term] ?? 0) + $val;
                $tfidfSumPerClass[$label] = ($tfidfSumPerClass[$label] ?? 0) + $val;
            }
        }

        $likelihood = [];
        foreach ($tfidfTermPerClass as $label => $terms) {
            $denominator = $tfidfSumPerClass[$label] + $vocabSize;
            foreach ($vocab as $term) {
                $numerator = ($terms[$term] ?? 0) + 1;
                $likelihood[$label][$term] = $numerator / $denominator;
            }
        }

        // ======================
        // Prediksi data uji
        // ======================
        $predictions = [];
        $actuals = [];

        foreach ($testIndices as $i) {
            $docIndex = $i;
            $actualLabel = $documents[$docIndex]['label'];
            $vec = $tfidfPerDoc[$docIndex];

            $scores = [];
            foreach ($priorProb as $label => $prior) {
                $score = log($prior);
                foreach ($vec as $term => $value) {
                    $score += $value * log($likelihood[$label][$term] ?? (1 / ($tfidfSumPerClass[$label] + $vocabSize)));
                }
                $scores[$label] = $score;
            }

            arsort($scores);
            $predicted = array_key_first($scores);

            $actuals[] = $actualLabel;
            $predictions[] = $predicted;
        }

        // ======================
        // Confusion Matrix + Akurasi
        // ======================
        $confMatrix = [];
        $correct = 0;
        foreach ($predictions as $i => $pred) {
            $actual = $actuals[$i];
            if (!isset($confMatrix[$actual])) $confMatrix[$actual] = [];
            $confMatrix[$actual][$pred] = ($confMatrix[$actual][$pred] ?? 0) + 1;

            if ($actual === $pred) $correct++;
        }

        $allLabels = array_unique(array_merge(array_keys($confMatrix), array_keys($priorProb)));
        sort($allLabels);
        foreach ($allLabels as $a) {
            foreach ($allLabels as $b) {
                $confMatrix[$a][$b] = $confMatrix[$a][$b] ?? 0;
            }
        }

        $accuracy = count($predictions) > 0 ? $correct / count($predictions) : 0;

        // ======================
        // TF-IDF Data Table (Pagination)
        // ======================
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

        $perPage = 50;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $paginated = new LengthAwarePaginator(
            array_slice($tfidfTable, $offset, $perPage),
            count($tfidfTable),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('naivebayes.index', [
            'data' => $paginated,
            'priorProb' => $priorProb,
            'likelihood' => $likelihood,
            'confMatrix' => [
                'labels' => $allLabels,
                'matrix' => $confMatrix,
                'accuracy' => $accuracy,
            ],
            'testSize' => $testSize,
            'randomState' => $randomState,
        ]);
    }

    // Fungsi shuffle dengan seed agar hasil shuffle konsisten
    private function seededShuffle(array &$array, int $seed)
    {
        mt_srand($seed);
        $count = count($array);
        for ($i = $count - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            // Tukar posisi
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
    }
}
