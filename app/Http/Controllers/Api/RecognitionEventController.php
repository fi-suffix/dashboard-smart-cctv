<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecognitionEventRequest;
use App\Http\Resources\RecognitionEventResource;
use App\Models\RecognitionEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class RecognitionEventController extends Controller
{
    public function store(StoreRecognitionEventRequest $request): JsonResponse
    {
        $payload = $request->validated();

        // Idempotency: the same event_uuid (with the same camera) is never stored twice.
        $existing = RecognitionEvent::where('event_uuid', $payload['event_uuid'])->first();
        if ($existing) {
            return (new RecognitionEventResource($existing->load(['camera', 'employee'])))
                ->additional(['duplicate' => true])
                ->response()
                ->setStatusCode(200);
        }

        $customFilename = $payload['event_uuid'].'-'.Str::random(6);
        $snapshotPath = null;
        if ($request->hasFile('snapshot')) {
            $file = $request->file('snapshot');
            $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            if (! in_array($extension, ['jpg', 'jpeg', 'png'], true)) {
                $extension = 'jpg';
            }
            $directory = 'events/'.now()->format('Y/m/d');
            $snapshotPath = $file->storeAs($directory, $customFilename.'.'.$extension, 'public');
        }

        $event = RecognitionEvent::create([
            'event_uuid' => $payload['event_uuid'],
            'camera_id' => $payload['camera_id'],
            'employee_id' => $payload['employee_id'] ?? null,
            'type' => $payload['type'],
            'similarity' => $payload['similarity'] ?? null,
            'track_id' => $payload['track_id'],
            'snapshot_path' => $snapshotPath,
            'bbox' => isset($payload['bbox']) ? array_map('floatval', $payload['bbox']) : null,
            'occurred_at' => $payload['occurred_at'],
        ]);

        return (new RecognitionEventResource($event->load(['camera', 'employee'])))
            ->additional(['duplicate' => false])
            ->response()
            ->setStatusCode(201);
    }
}
