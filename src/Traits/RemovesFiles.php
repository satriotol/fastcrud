<?php

namespace Satriotol\Fastcrud\Traits;

use Illuminate\Support\Facades\Storage;

trait RemovesFiles
{
    public function removeFiles($uri)
    {
        if (Storage::disk('minio')->exists($uri)) {
            Storage::disk('minio')->delete($uri);
            return true;
        }

        return false;
    }
}
