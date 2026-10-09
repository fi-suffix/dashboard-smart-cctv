<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalTokenAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = trim((string) $request->header('X-Internal-Token', ''));
        $expected = (string) Setting::getValue(
            'api_integration.internal_token',
            env('AI_INTERNAL_TOKEN', '')
        );

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
