<?php

namespace Satriotol\Fastcrud\Middleware;

use App\Http\Controllers\Api\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use App\Models\ApiKey;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || !ApiKey::where('key', $apiKey)->where('is_active', true)->exists()) {
            return ResponseFormatter::error("Token Expired", 'Unauthorized', 401);
        }

        // Update last_used_at timestamp for the API key
        $apiKeyRecord = ApiKey::where('key', $apiKey)->first();
        $apiKeyRecord->last_used_at = now();
        $apiKeyRecord->save();

        return $next($request);
    }
}
