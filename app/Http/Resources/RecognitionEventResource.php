<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecognitionEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_uuid' => $this->event_uuid,
            'camera' => $this->whenLoaded('camera', fn () => [
                'id' => $this->camera->id,
                'name' => $this->camera->name,
                'location' => $this->camera->location,
            ]),
            'employee' => $this->whenLoaded('employee', fn () => $this->employee ? [
                'id' => $this->employee->id,
                'name' => $this->employee->name,
                'department' => $this->employee->department,
            ] : null),
            'type' => $this->type,
            'similarity' => $this->similarity,
            'track_id' => $this->track_id,
            'bbox' => $this->bbox,
            'snapshot_url' => $this->snapshot_path
                ? route('dashboard.recognition_events.snapshot', $this)
                : null,
            'occurred_at' => $this->occurred_at?->toIso8601String(),
        ];
    }
}
