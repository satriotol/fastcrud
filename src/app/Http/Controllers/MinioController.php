<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MinioController extends Controller
{
    public function getFile(Request $request)
    {
        $path = $request->input('url');

        if (!$path) {
            return Response::make('File path is required.', 400);
        }

        if (!Storage::disk('minio')->exists($path)) {
            return Response::make('File not found.', 404);
        }

        $file = Storage::disk('minio')->get($path);
        $mimetype = Storage::disk('minio')->mimeType($path);

        return Response::make($file, 200, [
            'Content-Type' => $mimetype,
        ]);
    }
}
