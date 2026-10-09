<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmergencyEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by middleware (internal.token)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'event_uuid' => 'required|string|max:36',
            'camera_id' => 'required|integer|exists:cameras,id',
            'type' => 'sometimes|string|in:fall,immobility,other',
            'severity' => 'sometimes|string|in:warning,critical',
            'confidence' => 'sometimes|numeric|between:0,1',
            'bbox' => 'sometimes|array',
            'bbox.*' => 'numeric',
            'fallen_duration' => 'sometimes|numeric|min:0',
            'body_angle' => 'sometimes|numeric|min:0|max:180',
            'track_id' => 'sometimes|string|max:100',
            'occurred_at' => 'sometimes|date',
            'snapshot' => 'sometimes|file|image|max:5120', // max 5MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'event_uuid.required' => 'Event UUID is required for idempotency.',
            'camera_id.required' => 'Camera ID is required.',
            'camera_id.exists' => 'The specified camera does not exist.',
        ];
    }
}
