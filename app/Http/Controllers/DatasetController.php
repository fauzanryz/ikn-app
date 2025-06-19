<?php

namespace App\Http\Controllers;

use App\Models\DatasetModel;
use App\Models\PreprocessingModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DatasetController extends Controller
{
    // Tampilkan semua kolom (dataset full)
    public function full()
    {
        $data = DatasetModel::paginate(5);
        return view('dataset.full', compact('data'));
    }

    // Tampilkan hanya kolom full_text
    public function fullText()
    {
        $data = DatasetModel::paginate(5);
        return view('dataset.fulltext', compact('data'));
    }

    // Menghapus seluruh data pada Dataset dan Preprocessing
    public function deleteAll()
    {
        DatasetModel::truncate();
        PreprocessingModel::truncate();
        return redirect()->back()->with('success', 'Semua data berhasil dihapus.');
    }

    // Import data dari file CSV
    public function import(Request $request)
    {
        // Validasi file harus CSV atau TXT
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        try {
            // Baca file CSV
            $spreadsheet = IOFactory::load($file->getPathname());
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Ambil header baris pertama
            $header = $data[1];

            // Header yang diharapkan (sesuai urutan kolom A-O)
            $expectedHeader = [
                'A' => 'conversation_id_str',
                'B' => 'created_at',
                'C' => 'favorite_count',
                'D' => 'full_text',
                'E' => 'id_str',
                'F' => 'image_url',
                'G' => 'in_reply_to_screen_name',
                'H' => 'lang',
                'I' => 'location',
                'J' => 'quote_count',
                'K' => 'reply_count',
                'L' => 'retweet_count',
                'M' => 'tweet_url',
                'N' => 'user_id_str',
                'O' => 'username',
            ];

            // Validasi header: harus sama urutan dan nama kolomnya
            foreach ($expectedHeader as $key => $expectedName) {
                if (!isset($header[$key]) || strtolower(trim($header[$key])) !== strtolower($expectedName)) {
                    return redirect()->back()->with('error', 'Kolom header tidak sesuai format. Pastikan urutan dan nama kolom sudah benar.');
                }
            }

            // Hapus header sebelum simpan data
            unset($data[1]);

            // Simpan data
            foreach ($data as $row) {
                DatasetModel::create([
                    'conversation_id_str' => $row['A'] ?? null,
                    'created_at' => $row['B'] ?? null,
                    'favorite_count' => $row['C'] ?? null,
                    'full_text' => $row['D'] ?? null,
                    'id_str' => $row['E'] ?? null,
                    'image_url' => $row['F'] ?? null,
                    'in_reply_to_screen_name' => $row['G'] ?? null,
                    'lang' => $row['H'] ?? null,
                    'location' => $row['I'] ?? null,
                    'quote_count' => $row['J'] ?? null,
                    'reply_count' => $row['K'] ?? null,
                    'retweet_count' => $row['L'] ?? null,
                    'tweet_url' => $row['M'] ?? null,
                    'user_id_str' => $row['N'] ?? null,
                    'username' => $row['O'] ?? null,
                ]);
            }

            // Jalankan script Python di background
            $command = 'start /b python "C:\\xampp\\htdocs\\ikn-preprocessing\\ikn-prep.py" > C:\\xampp\\htdocs\\ikn-preprocessing\\log.txt 2>&1';
            pclose(popen($command, "r"));

            return redirect()->back()->with('success', 'Data berhasil diimport, preprocessing berlangsung.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }
}
