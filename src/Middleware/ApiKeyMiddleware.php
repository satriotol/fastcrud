<?php

namespace Satriotol\Fastcrud\Middleware;

use App\Http\Controllers\Api\ResponseFormatter;
use Closure;
use Illuminate\Http\Request;
use Satriotol\Fastcrud\Models\ApiKey;
use Satriotol\Fastcrud\Models\ApiKeyLog;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey || !ApiKey::where('key', $apiKey)->where('is_active', true)->exists()) {
            return ResponseFormatter::error("Token Expired", 'Unauthorized', 401);
        }

        $apiKeyRecord = ApiKey::where('key', $apiKey)->first();
        $clientType = $this->detectClientType($request);

        // Enforce platform access
        if (!$this->isClientAllowed($apiKeyRecord, $clientType, $request)) {
            $this->log($apiKeyRecord, $request, $clientType, 403);
            return ResponseFormatter::error("API Key tidak diizinkan untuk platform: {$clientType}", 'Forbidden', 403);
        }

        // Enforce route whitelist
        if (!empty($apiKeyRecord->allowed_routes)) {
            $path = $request->path();
            $allowed = false;
            foreach ($apiKeyRecord->allowed_routes as $pattern) {
                if ($path === $pattern || fnmatch($pattern, $path)) {
                    $allowed = true;
                    break;
                }
            }
            if (!$allowed) {
                $this->log($apiKeyRecord, $request, $clientType, 403);
                return ResponseFormatter::error("API Key tidak diizinkan mengakses endpoint ini", 'Forbidden', 403);
            }
        }

        // Update last_used_at
        $apiKeyRecord->last_used_at = now();
        $apiKeyRecord->save();

        $response = $next($request);

        $this->log($apiKeyRecord, $request, $clientType, $response->getStatusCode());

        // Auto-prune: cek tiap ~100 request, hapus terlama jika >= 1jt
        if (rand(1, 100) === 1) {
            $count = ApiKeyLog::count();
            if ($count >= 1_000_000) {
                $cutoffId = ApiKeyLog::orderBy('id')->skip(100_000)->value('id');
                if ($cutoffId) {
                    ApiKeyLog::where('id', '<=', $cutoffId)->delete();
                }
            }
        }

        return $response;
    }

    /**
     * Deteksi tipe client dari User-Agent dan Origin/Referer.
     */
    protected function detectClientType(Request $request): string
    {
        $ua = strtolower($request->userAgent() ?? '');
        $origin = $request->header('Origin');
        $referer = $request->header('Referer');

        // Flutter: Dart UA
        if (str_contains($ua, 'dart') || str_contains($ua, 'flutter')) {
            return 'flutter';
        }

        // Desktop apps
        if (str_contains($ua, 'electron') || str_contains($ua, 'java/') || str_contains($ua, 'python') || str_contains($ua, 'curl/')) {
            return 'desktop';
        }

        // Backend / server-to-server / API clients
        if (empty($ua)
            || str_contains($ua, 'guzzle')
            || str_contains($ua, 'axios')
            || str_contains($ua, 'node-fetch')
            || str_contains($ua, 'node/')
            || str_starts_with($ua, 'node')
            || str_contains($ua, 'go-http-client')
            || str_contains($ua, 'postman')
            || str_contains($ua, 'insomnia')
            || str_contains($ua, 'httpie')
            || str_contains($ua, 'wget')
            || str_contains($ua, 'okhttp')) {
            return 'backend';
        }

        // Mobile native (non-Flutter)
        if (str_contains($ua, 'okhttp') || (str_contains($ua, 'mobile') && !$origin && !$referer)) {
            return 'mobile';
        }

        // Web browser: punya Origin atau Referer, atau UA mengandung Mozilla/Chrome/Safari
        if ($origin || $referer || str_contains($ua, 'mozilla') || str_contains($ua, 'chrome') || str_contains($ua, 'safari')) {
            return 'web';
        }

        return 'unknown';
    }

    /**
     * Cek apakah client type diizinkan berdasarkan checklist API key.
     */
    protected function isClientAllowed(ApiKey $apiKeyRecord, string $clientType, Request $request): bool
    {
        // Cek localhost
        $ip = $request->ip();
        $isLocalhost = in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.');
        if ($isLocalhost && !$apiKeyRecord->allow_localhost) {
            return false;
        }

        // Jika semua platform false, berarti belum diatur — izinkan semua (backward compat)
        $hasAnyPlatformSet = $apiKeyRecord->allow_web || $apiKeyRecord->allow_mobile || $apiKeyRecord->allow_flutter || $apiKeyRecord->allow_desktop || $apiKeyRecord->allow_backend;
        if (!$hasAnyPlatformSet) {
            return true;
        }

        return match ($clientType) {
            'web'     => (bool) $apiKeyRecord->allow_web,
            'mobile'  => (bool) $apiKeyRecord->allow_mobile,
            'flutter' => (bool) $apiKeyRecord->allow_flutter,
            'desktop' => (bool) $apiKeyRecord->allow_desktop,
            'backend' => (bool) $apiKeyRecord->allow_backend,
            default   => true, // unknown = izinkan
        };
    }

    protected function log(ApiKey $apiKeyRecord, Request $request, string $clientType, int $statusCode): void
    {
        ApiKeyLog::create([
            'api_key_id'  => $apiKeyRecord->id,
            'method'      => $request->method(),
            'endpoint'    => $request->path(),
            'ip_address'  => $request->ip(),
            'user_agent'  => $request->userAgent(),
            'client_type' => $clientType,
            'status_code' => $statusCode,
        ]);
    }
}
