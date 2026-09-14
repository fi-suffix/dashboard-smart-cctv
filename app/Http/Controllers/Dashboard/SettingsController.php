<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DetectionLog;
use FilesystemIterator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SettingsController extends Controller
{
    private string $storageRoot;

    public function __construct()
    {
        $this->storageRoot = rtrim(public_path('snapshots'), '/\\');
    }

    public function index(): View
    {
        $logCount = DetectionLog::count();

        $snapshotFiles = 0;
        $snapshotBytes = 0;
        foreach ($this->snapshotFiles() as $file) {
            $snapshotFiles++;
            $snapshotBytes += $file->getSize();
        }

        return view('dashboard.setting.index', [
            'logCount' => $logCount,
            'snapshotFiles' => $snapshotFiles,
            'snapshotSizeFormatted' => $this->formatBytes($snapshotBytes),
        ]);
    }

    public function cleanup(Request $request)
    {
        $logRetention = max(1, (int) $request->input('retention_days', 30));
        $snapshotRetention = max(1, (int) $request->input('snapshot_retention', 7));

        $logCutoff = now()->subDays($logRetention);
        $snapshotCutoff = now()->subDays($snapshotRetention);

        $logsDeleted = 0;
        $filesDeleted = 0;
        $bytesFreed = 0;

        // 1) Delete detection logs older than the retention period, plus their snapshot files
        DetectionLog::where('detected_at', '<', $logCutoff)
            ->chunkById(200, function ($chunk) use (&$logsDeleted, &$filesDeleted, &$bytesFreed) {
                foreach ($chunk as $log) {
                    $logsDeleted++;

                    if ($log->snapshot_path) {
                        $file = $this->resolveSnapshotPath($log->snapshot_path);
                        $freed = $this->deleteFile($file);
                        if ($freed > 0) {
                            $bytesFreed += $freed;
                            $filesDeleted++;
                        }
                    }
                }

                DetectionLog::whereKey($chunk->modelKeys())->delete();
            });

        // 2) Delete orphan snapshot files (no longer referenced by any log)
        //    older than the snapshot retention period to free disk space.
        $referenced = DetectionLog::pluck('snapshot_path')
            ->filter()
            ->map(fn ($path) => $this->normalizeRelative($path))
            ->flip();

        foreach ($this->snapshotFiles() as $file) {
            $relative = $this->normalizeRelative($this->relativePath($file->getPathname()));

            if ($file->getMTime() < $snapshotCutoff->getTimestamp() && !isset($referenced[$relative])) {
                $freed = $this->deleteFile($file->getPathname());
                if ($freed > 0) {
                    $bytesFreed += $freed;
                    $filesDeleted++;
                }
            }
        }

        return back()->with('success', sprintf(
            'Cleanup selesai: %d log deteksi dan %d snapshot dihapus (%s ruang dibebaskan).',
            $logsDeleted,
            $filesDeleted,
            $this->formatBytes($bytesFreed)
        ));
    }

    private function snapshotFiles(): iterable
    {
        if (!is_dir($this->storageRoot)) {
            return [];
        }

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->storageRoot, FilesystemIterator::SKIP_DOTS)
        );
    }

    private function resolveSnapshotPath(string $storedPath): string
    {
        return $this->storageRoot . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->normalizeRelative($storedPath));
    }

    private function normalizeRelative(string $path): string
    {
        $clean = ltrim(preg_replace('#^snapshots/#i', '', $path), '/\\');

        return trim(str_replace(['/', '\\'], '/', $clean), '/');
    }

    private function relativePath(string $absolute): string
    {
        return trim(str_replace([$this->storageRoot], '', $absolute), '/\\');
    }

    private function deleteFile(string $path): int
    {
        if (!is_file($path)) {
            return 0;
        }

        $bytes = filesize($path);

        return @unlink($path) ? $bytes : 0;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = (int) floor(log($bytes, 1024));

        return round($bytes / (1024 ** $i), 1) . ' ' . $units[$i];
    }
}