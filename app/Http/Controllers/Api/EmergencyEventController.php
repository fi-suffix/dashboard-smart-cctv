<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmergencyEventRequest;
use App\Http\Resources\EmergencyEventResource;
use App\Models\EmergencyEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmergencyEventController extends Controller
{
    /**
     * Store a new emergency event from Python service.
     */
    public function store(StoreEmergencyEventRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // Idempotency: same event_uuid won't be stored twice
        $existing = EmergencyEvent::where('event_uuid', $payload['event_uuid'])->first();
        if ($existing) {
            return (new EmergencyEventResource($existing->load(['camera'])))
                ->additional(['duplicate' => true])
                ->response()
                ->setStatusCode(200);
        }

        // Handle snapshot upload
        $snapshotPath = null;
        if ($request->hasFile('snapshot')) {
            $file = $request->file('snapshot');
            $directory = 'events/' . now()->format('Y/m/d');
            $filename = $payload['event_uuid'] . '-' . time() . '.jpg';
            $snapshotPath = $file->storeAs($directory, $filename, 'public');
        }

        $event = EmergencyEvent::create([
            'event_uuid' => $payload['event_uuid'],
            'camera_id' => $payload['camera_id'],
            'type' => $payload['type'] ?? 'fall',
            'severity' => $payload['severity'] ?? 'critical',
            'confidence' => $payload['confidence'] ?? null,
            'snapshot_path' => $snapshotPath,
            'bbox' => $payload['bbox'] ?? null,
            'fallen_duration' => $payload['fallen_duration'] ?? null,
            'body_angle' => $payload['body_angle'] ?? null,
            'track_id' => $payload['track_id'] ?? null,
            'occurred_at' => $payload['occurred_at'] ?? now(),
        ]);

        return (new EmergencyEventResource($event->load(['camera'])))
            ->additional(['duplicate' => false])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Get latest active emergency events (for real-time dashboard).
     */
    public function latest(Request $request): JsonResponse
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
