<?php

namespace Satriotol\Fastcrud\Traits;

use Illuminate\Support\Facades\Storage;

trait RemovesFiles
{
    public function removeFiles($uri)
    {
        $parsedUrl = parse_url($uri);
        parse_str($parsedUrl['query'], $queryParameters);
        $parsed_uri = $queryParameters['url'] ?? null;
        if ($parsed_uri && Storage::disk('minio')->exists($parsed_uri)) {
            Storage::disk('minio')->delete($parsed_uri);
            return true;
        }

        return false;
    }
}
