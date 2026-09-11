<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DetectionLog;
use App\Models\Camera;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DetectionLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = DetectionLog::with(['camera', 'employee'])->latest();

        // Filters
        if ($request->filled('camera_id')) {
            $query->where('camera_id', $request->camera_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('detected_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('detected_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();
        $cameras = Camera::all();
        $employees = Employee::active()->get();

        return view('dashboard.detection_history.index', compact('logs', 'cameras', 'employees'));
    }

    public function show(DetectionLog $detectionLog): View
    {
        $detectionLog->load(['camera', 'employee']);
        return view('dashboard.detection_history.show', compact('detectionLog'));
    }

    public function stats(Request $request)
    {
        $query = DetectionLog::query();

        if ($request->filled('date_from')) {
            $query->whereDate('detected_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('detected_at', '<=', $request->date_to);
        }

        $total = $query->count();
        $recognized = (clone $query)->where('status', 'recognized')->count();
        $unknown = (clone $query)->where('status', 'unknown')->count();
        $avgConfidence = (clone $query)->where('status', 'recognized')->avg('confidence') ?? 0;

        // Daily stats for chart
        $dailyStats = DetectionLog::selectRaw('DATE(detected_at) as date, status, COUNT(*) as count')
            ->when($request->filled('date_from'), fn($q) => $q->whereDate('detected_at', '>=', $request->date_from))
            ->when($request->filled('date_to'), fn($q) => $q->whereDate('detected_at', '<=', $request->date_to))
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

        return response()->json([
            'total' => $total,
            'recognized' => $recognized,
            'unknown' => $unknown,
            'avg_confidence' => round($avgConfidence * 100, 1),
            'daily_stats' => $dailyStats,
        ]);
    }
}