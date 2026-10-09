<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecognitionEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already guarded by the internal.token middleware
    }

    public function rules(): array
    {
        return [
            'event_uuid' => ['required', 'string', 'max:64'],
            'camera_id' => ['required', 'exists:cameras,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'type' => ['required', 'in:known,unknown'],
            'similarity' => ['nullable', 'numeric', 'min:0', 'max:1'],
            'track_id' => ['required', 'string', 'max:64'],
            'bbox' => ['nullable', 'array'],
            'bbox.*' => ['numeric'],
            'occurred_at' => ['required', 'date'],
            'snapshot' => ['nullable', 'file', 'image', 'max:3072'], // 3 MB
        ];
    }
}
