<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DetectionLog;
use App\Models\EmergencyEvent;
use App\Models\Setting;
use FilesystemIterator;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SettingsController extends Controller
{
    private string $storageRoot;
    private string $pythonSnapshotsRoot;

    public function __construct()
    {
        $this->storageRoot = rtrim(public_path('snapshots'), '/\\');
        // Python service snapshots folder (sibling to Laravel project)
        $this->pythonSnapshotsRoot = base_path('../Facial-recognition-cctv/snapshots');
    }

    public function index(): View
    {
        $logCount = DetectionLog::count();
        $emergencyEventCount = EmergencyEvent::count();

        // Laravel snapshots
        $snapshotFiles = 0;
        $snapshotBytes = 0;
        foreach ($this->snapshotFiles($this->storageRoot) as $file) {
            $snapshotFiles++;
            $snapshotBytes += $file->getSize();
        }

        // Python snapshots
        $pythonSnapshotFiles = 0;
        $pythonSnapshotBytes = 0;
        foreach ($this->snapshotFiles($this->pythonSnapshotsRoot) as $file) {
            $pythonSnapshotFiles++;
            $pythonSnapshotBytes += $file->getSize();
        }

        $faceRecognition = Setting::getGroup('face_recognition');
        $cameraDefaults = Setting::getGroup('camera_defaults');
        $apiIntegration = Setting::getGroup('api_integration');

        return view('dashboard.setting.index', [
            'logCount' => $logCount,
            'emergencyEventCount' => $emergencyEventCount,
            'snapshotFiles' => $snapshotFiles,
            'snapshotBytes' => $snapshotBytes,
            'pythonSnapshotFiles' => $pythonSnapshotFiles,
            'pythonSnapshotBytes' => $pythonSnapshotBytes,
            'snapshotSizeFormatted' => $this->formatBytes($snapshotBytes),
            'pythonSnapshotSizeFormatted' => $this->formatBytes($pythonSnapshotBytes),
            'faceRecognition' => $faceRecognition,
            'cameraDefaults' => $cameraDefaults,
            'apiIntegration' => $apiIntegration,
        ]);
    }

    public function save(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'face_recognition.threshold' => 'nullable|numeric|between:0.3,0.9',
            'face_recognition.min_face_size' => 'nullable|integer|between:20,100',
            'face_recognition.frame_rate' => 'nullable|integer|between:1,30',
            'camera_defaults.reconnect_interval' => 'nullable|integer|between:1,300',
            'camera_defaults.default_status' => 'nullable|string|in:active,inactive,maintenance',
            'api_integration.python_service_url' => 'nullable|string|max:255',
            'api_integration.api_key' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $group => $settings) {
            foreach ($settings as $key => $value) {
                $fullKey = "{$group}.{$key}";
                $existing = Setting::where('key', $fullKey)->first();
                if ($existing) {
                    $existing->update(['value' => $value]);
                }
            }
        }

        // Update .env for API integration if changed
        if (isset($validated['api_integration'])) {
            $this->updateEnvFile($validated['api_integration']);
        }

        return back()->with('success', 'Settings saved successfully.');
    }

    public function cleanup(Request $request)
    {
        $logRetention = max(1, (int) $request->input('retention_days', 30));
        $snapshotRetention = max(1, (int) $request->input('snapshot_retention', 7));
        $cleanPythonSnapshots = $request->boolean('clean_python_snapshots', false);
        $pythonRetention = max(1, (int) $request->input('python_retention_days', 7));

        $logCutoff = now()->subDays($logRetention);
        $snapshotCutoff = now()->subDays($snapshotRetention);
        $pythonCutoff = now()->subDays($pythonRetention);

        $logsDeleted = 0;
        $emergencyEventsDeleted = 0;
        $filesDeleted = 0;
        $pythonFilesDeleted = 0;
        $bytesFreed = 0;
        $pythonBytesFreed = 0;

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

        // 2) Delete emergency events older than the retention period
        $emergencyEventsDeleted = EmergencyEvent::where('occurred_at', '<', $logCutoff)->count();
        EmergencyEvent::where('occurred_at', '<', $logCutoff)->delete();

        // 3) Delete orphan snapshot files in Laravel folder
        $referenced = DetectionLog::pluck('snapshot_path')
            ->filter()
            ->map(fn ($path) => $this->normalizeRelative($path))
            ->flip();

        foreach ($this->snapshotFiles($this->storageRoot) as $file) {
            $relative = $this->normalizeRelative($this->relativePath($file->getPathname(), $this->storageRoot));

            if ($file->getMTime() < $snapshotCutoff->getTimestamp() && !isset($referenced[$relative])) {
                $freed = $this->deleteFile($file->getPathname());
                if ($freed > 0) {
                    $bytesFreed += $freed;
                    $filesDeleted++;
                }
            }
        }

        // 4) Delete old Python snapshots if option selected
        if ($cleanPythonSnapshots && is_dir($this->pythonSnapshotsRoot)) {
            foreach ($this->snapshotFiles($this->pythonSnapshotsRoot) as $file) {
                if ($file->getMTime() < $pythonCutoff->getTimestamp()) {
                    $freed = $this->deleteFile($file->getPathname());
                    if ($freed > 0) {
                        $pythonBytesFreed += $freed;
                        $pythonFilesDeleted++;
                    }
                }
            }
        }

        $messages = [];
        if ($logsDeleted > 0) {
            $messages[] = "{$logsDeleted} detection logs";
        }
        if ($emergencyEventsDeleted > 0) {
            $messages[] = "{$emergencyEventsDeleted} emergency events";
        }
        if ($filesDeleted > 0) {
            $messages[] = "{$filesDeleted} Laravel snapshots ({$this->formatBytes($bytesFreed)})";
        }
        if ($pythonFilesDeleted > 0) {
            $messages[] = "{$pythonFilesDeleted} Python snapshots ({$this->formatBytes($pythonBytesFreed)})";
        }

        if (empty($messages)) {
            return back()->with('success', 'No items to clean up.');
        }

        return back()->with('success', 'Cleanup completed: ' . implode(', ', $messages));
    }

    private function updateEnvFile(array $apiSettings): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        if (isset($apiSettings['python_service_url'])) {
            $content = preg_replace('/^PYTHON_SERVICE_URL=.*/m', 'PYTHON_SERVICE_URL=' . $apiSettings['python_service_url'], $content);
        }
        if (isset($apiSettings['api_key'])) {
            $content = preg_replace('/^FACE_RECOGNITION_API_KEY=.*/m', 'FACE_RECOGNITION_API_KEY=' . $apiSettings['api_key'], $content);
        }

        file_put_contents($envPath, $content);
    }

    private function snapshotFiles(string $root): iterable
    {
        if (!is_dir($root)) {
            return [];
        }

        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
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

    private function relativePath(string $absolute, string $root): string
    {
        return trim(str_replace([$root], '', $absolute), '/\\');
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
