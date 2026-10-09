<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmergencyEventResource;
use App\Models\EmergencyEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmergencyEventController extends Controller
{
    /**
     * Display emergency events dashboard.
     */
    public function index(Request $request): View
    {
        $query = EmergencyEvent::with(['camera'])
            ->orderBy('occurred_at', 'desc');

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('camera')) {
            $query->where('camera_id', $request->camera);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('occurred_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('occurred_at', '<=', $request->date_to);
        }

        $events = $query->paginate(20)->withQueryString();
        $activeCount = EmergencyEvent::active()->recent(24)->count();
        $todayCount = EmergencyEvent::whereDate('occurred_at', today())->count();

        return view('dashboard.emergency.index', [
            'events' => $events,
            'activeCount' => $activeCount,
            'todayCount' => $todayCount,
            'cameras' => \App\Models\Camera::orderBy('name')->get(),
        ]);
    }

    /**
     * Show single emergency event.
     */
    public function show(EmergencyEvent $emergencyEvent): View
    {
        $emergencyEvent->load(['camera', 'acknowledgedBy']);

        return view('dashboard.emergency.show', [
            'event' => $emergencyEvent,
        ]);
    }

    /**
     * Acknowledge an emergency event.
     */
    public function acknowledge(EmergencyEvent $emergencyEvent): RedirectResponse
    {
        $emergencyEvent->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => auth()->id(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Emergency event acknowledged.');
    }

    /**
     * Resolve an emergency event.
     */
    public function resolve(EmergencyEvent $emergencyEvent): RedirectResponse
    {
        $emergencyEvent->update([
            'status' => 'resolved',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Emergency event resolved.');
    }

    /**
     * Bulk acknowledge active events.
     */
    public function acknowledgeAll(Request $request): RedirectResponse
    {
        $count = EmergencyEvent::active()
            ->recent((int) $request->input('hours', 24))
            ->update([
                'status' => 'acknowledged',
                'acknowledged_at' => now(),
                'acknowledged_by' => auth()->id(),
            ]);

        return redirect()
            ->back()
            ->with('success', "Acknowledged {$count} emergency event(s).");
    }

    /**
     * Get snapshot for an event.
     */
    public function snapshot(EmergencyEvent $emergencyEvent)
    {
        if (!$emergencyEvent->snapshot_url) {
            abort(404, 'No snapshot available');
        }

        return response()->redirectTo($emergencyEvent->snapshot_url);
    }

    /**
     * Get latest events (for AJAX polling).
     */
    public function latest(Request $request): \Illuminate\Http\JsonResponse
    {
        $minutes = $request->input('minutes', 30);
        $limit = $request->input('limit', 10);

        $events = EmergencyEvent::with(['camera'])
            ->recent($minutes)
            ->orderBy('occurred_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'events' => EmergencyEventResource::collection($events),
            'active_count' => EmergencyEvent::active()->recent($minutes)->count(),
        ]);
    }
}
