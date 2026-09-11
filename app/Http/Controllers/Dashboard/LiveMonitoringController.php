<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Camera;
use App\Models\DetectionLog;
use App\Models\Employee;
use Illuminate\View\View;

class LiveMonitoringController extends Controller
{
    public function index(): View
    {
        $cameras = Camera::active()->get();
        $recentDetections = DetectionLog::with(['camera', 'employee'])
            ->latest()
            ->limit(10)
            ->get();
        
        $stats = [
            'total_cameras' => Camera::count(),
            'active_cameras' => Camera::active()->count(),
            'total_employees' => Employee::active()->count(),
            'detections_today' => DetectionLog::today()->count(),
            'recognized_today' => DetectionLog::today()->recognized()->count(),
            'unknown_today' => DetectionLog::today()->unknown()->count(),
        ];

        return view('dashboard.live_monitoring.index', compact('cameras', 'recentDetections', 'stats'));
    }

    public function show(Camera $camera): View
    {
        $recentDetections = DetectionLog::with('employee')
            ->where('camera_id', $camera->id)
            ->latest()
            ->limit(20)
            ->get();

        return view('dashboard.live_monitoring.show', compact('camera', 'recentDetections'));
    }
}