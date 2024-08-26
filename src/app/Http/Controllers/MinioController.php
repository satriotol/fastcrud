<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class MinioController extends Controller
{
    public function getFile(Request $request)
    {
        $file = Storage::disk('minio')->get($request->url);
        $mimetype = Storage::disk('minio')->mimeType($request->url);
        $response =  Response::make($file, 200, [
            'Content-Type' => $mimetype
        ]);
        return $response;
    }}
