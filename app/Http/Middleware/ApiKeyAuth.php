<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('Authorization');
        $expectedKey = 'Bearer ' . Setting::getValue('api_integration.api_key', config('app.face_recognition_api_key', env('FACE_RECOGNITION_API_KEY')));
        
        if (!$apiKey || $apiKey !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}