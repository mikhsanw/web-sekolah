<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Jobs\BackupDatabaseToGoogle;
use App\Jobs\BackupFileToGoogle;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [];
        $backups = \App\Models\File::whereNotNull('data->backup')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($f) {
                return [
                    'id' => $f->id,
                    'nama' => $f->alias,
                    'path' => $f->target,
                    'status' => $f->data['backup']['status'] ?? 'unknown',
                    'time' => $f->data['backup']['time'] ?? $f->updated_at,
                ];
            });

        //tambahkan file backup database terakhir, langsung check di storage google
        // cek jika tidak ada folder database maka skip
        $latestDbBackup = null;
        if (Storage::disk('google')->exists('/database/')) {
            $googleFiles = Storage::disk('google')->files('/database/');
            foreach ($googleFiles as $filePath) {
                if (str_starts_with(basename($filePath), 'backup_db_')) {
                    if ($latestDbBackup === null || $filePath > $latestDbBackup['path']) {
                        $latestDbBackup = [
                            'id' => null,
                            'nama' => basename($filePath),
                            'path' => $filePath,
                            'status' => 'done',
                            'time' => Storage::disk('google')->lastModified($filePath),
                        ];
                    }
                }
            }
            if ($latestDbBackup) {
                $backups->prepend($latestDbBackup);
            }
        }

        $jobs = [
            'total_files' => File::whereNull('data->backup->status')->count(),
            'total_jobs' => \DB::table('jobs')->where('queue', 'default')->count(),
        ];

        return view('backend.settings.index', compact('backups', 'settings', 'jobs'));
    }

    public function backupFiles(Request $request)
    {
        if (\DB::table('jobs')->where('queue', 'default')->count() > 0) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Masih ada proses backup yang berjalan. Silakan tunggu hingga selesai.',
            ]);
        }
        //backup database
        BackupDatabaseToGoogle::dispatch();

        // backup file
        $files = File::whereNull('data->backup->status')->get();

        if ($files->isEmpty()) {
            return response()->json(['message' => 'Semua file sudah dibackup']);
        }

        $jobs = $files->map(fn($f) => new BackupFileToGoogle($f))->toArray();
        Bus::batch($jobs)->dispatch();

        return response()->json([
            'status' => 'success',
            'message' => 'Backup dalam proses...',
            'total' => count($jobs),
        ]);
    }
}
