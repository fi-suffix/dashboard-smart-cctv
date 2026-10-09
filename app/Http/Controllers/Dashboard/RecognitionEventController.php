<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RecognitionEvent;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecognitionEventController extends Controller
{
    /**
     * Serve a stored event snapshot through an authorized route
     * (never a guessable public URL).
     */
    public function snapshot(RecognitionEvent $recognitionEvent): StreamedResponse
    {
        $path = $recognitionEvent->snapshot_path;

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Storage::disk('public')->response($path);
    }
}
