<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmergencyEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_uuid' => $this->event_uuid,
            'camera_id' => $this->camera_id,
            'camera' => $this->whenLoaded('camera', function () {
                return [
                    'id' => $this->camera->id,
                    'name' => $this->camera->name,
                    'location' => $this->camera->location,
                ];
            }),
            'type' => $this->type,
            'type_label' => $this->type_label,
            'severity' => $this->severity,
            'confidence' => $this->confidence,
            'snapshot_url' => $this->snapshot_url,
            'bbox' => $this->bbox,
            'fallen_duration' => $this->fallen_duration,
            'body_angle' => $this->body_angle,
            'track_id' => $this->track_id,
            'status' => $this->status,
            'acknowledged_at' => $this->acknowledged_at?->toIso8601String(),
            'occurred_at' => $this->occurred_at?->toIso8601String(),
            'is_new' => $this->is_new,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
