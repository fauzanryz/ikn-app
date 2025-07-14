<?php

namespace App\Http\Controllers;

use App\Models\DatasetModel;
use App\Models\PreprocessingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class DatasetController extends Controller
{
    public function full()
    {
        $data = DatasetModel::paginate(5);
        $latestBackup = $this->getLatestBackupFilename();
        return view('dataset.full', compact('data', 'latestBackup'));
    }

    public function fullText()
    {
        $data = DatasetModel::paginate(5);
        return view('dataset.fulltext', compact('data'));
    }

    public function deleteAll()
    {
        $data = DatasetModel::all();

        // Buat folder backups jika belum ada
        Storage::makeDirectory('backups');

        // Export data ke CSV
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $header = [
            'conversation_id_str',
            'created_at',
            'favorite_count',
            'full_text',
            'id_str',
            'image_url',
            'in_reply_to_screen_name',
            'lang',
            'location',
            'quote_count',
            'reply_count',
            'retweet_count',
            'tweet_url',
            'user_id_str',
            'username'
        ];
        $sheet->fromArray($header, null, 'A1');

        // Data baris
        $rowIndex = 2;
        foreach ($data as $item) {
            $sheet->fromArray([
                $item->conversation_id_str,
                $item->created_at,
                $item->favorite_count,
                $item->full_text,
                $item->id_str,
                $item->image_url,
                $item->in_reply_to_screen_name,
                $item->lang,
                $item->location,
                $item->quote_count,
                $item->reply_count,
                $item->retweet_count,
                $item->tweet_url,
                $item->user_id_str,
                $item->username,
            ], null, 'A' . $rowIndex++);
        }

        $timestamp = now()->format('Ymd_His');
        $filename = "backup_dataset_{$timestamp}.csv";
        $path = storage_path("app/backups/{$filename}");

        // Simpan ke file
        $writer = new Csv($spreadsheet);
        $writer->save($path);

        // Hapus data
        DatasetModel::truncate();
        PreprocessingModel::truncate();

        $this->tulisLog('Backup & Hapus Semua Dataset', $filename);

        return redirect()->back()->with('success', "Semua data berhasil dihapus. Backup disimpan sebagai $filename.");
    }

    public function downloadBackup($filename)
    {
        $path = storage_path("app/backups/{$filename}");

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($path);
    }

    public function getLatestBackupFilename()
    {
        if (!Storage::exists('backups')) return null;

        $files = Storage::files('backups');
        if (empty($files)) return null;

        return collect($files)
            ->sortDesc()
            ->first(); // path: backups/filename.csv
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');

        try {
            $spreadsheet = IOFactory::load($file->getPathname());
            $data = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $header = $data[1];
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

            foreach ($expectedHeader as $key => $expectedName) {
                if (!isset($header[$key]) || strtolower(trim($header[$key])) !== strtolower($expectedName)) {
                    return redirect()->back()->with('error', 'Kolom header tidak sesuai format. Pastikan urutan dan nama kolom sudah benar.');
                }
            }

            unset($data[1]); // Hapus header

            $count = 0;
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
                $count++;
            }

            $this->tulisLog('Mengimport dataset', "sebanyak $count data");

            $command = 'start /b python "C:\\xampp\\htdocs\\ikn-preprocessing\\ikn-prep.py" > C:\\xampp\\htdocs\\ikn-preprocessing\\log.txt 2>&1';
            pclose(popen($command, "r"));

            return redirect()->back()->with('success', 'Data berhasil diimport, preprocessing berlangsung.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage());
        }
    }

    // Fungsi tulis log
    private function tulisLog($aksi, $target)
    {
        $tanggal = now()->format('Y-m-d H:i:s');
        $userEmail = Auth::check() ? Auth::user()->email : 'Guest';
        $baris = "[$tanggal] $aksi $target | oleh: $userEmail\n";
        file_put_contents(storage_path('logs/log.txt'), $baris, FILE_APPEND);
    }
}
