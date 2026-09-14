<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\DetectionLog;
use App\Models\Employee;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::active()->count();

        $totalCameras = Camera::count();
        $activeCameras = Camera::active()->get();

        $detectionsToday = DetectionLog::today()->count();
        $recognizedToday = DetectionLog::today()->recognized()->count();
        $unknownToday = DetectionLog::today()->unknown()->count();

        $avgConfidenceToday = DetectionLog::today()->recognized()->avg('confidence') ?? 0;

        // Recent detections
        $recentDetections = DetectionLog::with(['camera', 'employee'])
            ->latest()
            ->limit(10)
            ->get();

        // 7-day stats for chart
        $sevenDaysAgo = now()->subDays(6)->startOfDay();
        $dailyStats = DetectionLog::selectRaw('DATE(detected_at) as date, status, COUNT(*) as count')
            ->where('detected_at', '>=', $sevenDaysAgo)
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                $result = ['date' => $items[0]->date, 'recognized' => 0, 'unknown' => 0];
                foreach ($items as $item) {
                    $result[$item->status] = $item->count;
                }

                return $result;
            })
            ->values();

        // Fill missing days
        $filledStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $existing = $dailyStats->firstWhere('date', $date);
            $filledStats[] = $existing ?? ['date' => $date, 'recognized' => 0, 'unknown' => 0];
        }

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'totalCameras',
            'activeCameras',
            'detectionsToday',
            'recognizedToday',
            'unknownToday',
            'avgConfidenceToday',
            'recentDetections',
            'dailyStats',
            'filledStats'
        ));
    }
}
