<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Backups;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function index()
    {
        $backups = Backups::paginate(10);
        $all_backups = Backups::all();

        return view('dashboard.backups.index', compact('backups', 'all_backups'));
    }

    public function store(Request $request)
    {
        $fileName = 'backup_' . date('Y_m_d_H_i_s') . '_' . Str::random(5) . '.sql';

        $storagePath = storage_path('app/public/backups');

        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0777, true);
        }

        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbDatabase = env('DB_DATABASE');
        $dbUsername = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s --ignore-table=%s.backups %s > %s',
            escapeshellarg($dbUsername),
            escapeshellarg($dbPassword),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbDatabase),
            escapeshellarg($dbDatabase),
            escapeshellarg($storagePath . '/' . $fileName)
        );

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            $relativePath = 'backups/' . $fileName;

            $fullUrl = asset('storage/' . $relativePath);

            Backups::create([
                'file_path' => $fullUrl,
            ]);

            return redirect()->back()->with('toast_success', 'Backup database berhasil.');
        } else {
            return redirect()->back()->with('toast_error', 'Gagal backup database.');
        }
    }


    public function download($id)
    {
        $backup = Backups::findOrFail($id);

        $fileUrl = $backup->file_path;
        $parsedUrl = parse_url($fileUrl, PHP_URL_PATH);

        $relativePath = str_replace('/storage/', '', $parsedUrl);

        // dd($relativePath);

        if (!Storage::disk('public')->exists($relativePath)) {
            return redirect()->back()->with('toast_error', 'File backup tidak ditemukan.');
        }

        $filePath = Storage::disk('public')->path($relativePath);
        return response()->download($filePath);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_id' => 'required|exists:backups,id',
        ]);

        $backup = Backups::findOrFail($request->backup_id);

        $fileUrl = $backup->file_path;
        $parsedUrl = parse_url($fileUrl, PHP_URL_PATH);
        $relativePath = str_replace('/storage/', '', $parsedUrl);

        $filePath = storage_path('app/public/' . $relativePath);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('toast_error', 'File backup tidak ditemukan.');
        }

        $dbHost = env('DB_HOST');
        $dbPort = env('DB_PORT');
        $dbDatabase = env('DB_DATABASE');
        $dbUsername = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');

        $command = sprintf(
            'mysql --user=%s --password=%s --host=%s --port=%s %s < %s',
            escapeshellarg($dbUsername),
            escapeshellarg($dbPassword),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbDatabase),
            escapeshellarg($filePath)
        );

        $result = null;
        $output = null;
        exec($command, $output, $result);

        if ($result === 0) {
            return redirect()->back()->with('toast_success', 'Database berhasil direstore.');
        } else {
            return redirect()->back()->with('toast_error', 'Gagal melakukan restore database.');
        }
    }
}
