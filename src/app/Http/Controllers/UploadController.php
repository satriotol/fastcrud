<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = date('mdYHis') . '-' . Str::random(8) . '.' . $fileExtension;
            $file = $file->storeAs('file', $fileName, 'public');

            TemporaryFile::create([
                'filename' => $fileName
            ]);
            return $file;
        };
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = date('mdYHis') . '-' . Str::random(8) . '.' . $fileExtension;
            $file = $file->storeAs('image', $fileName, 'public');

            TemporaryFile::create([
                'filename' => $file
            ]);
            return $file;
        };
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $images) {
                $fileExtension = $images->getClientOriginalExtension();
                $fileName = date('mdYHis') . '-' . Str::random(8) . '.' . $fileExtension;
                $images = $images->storeAs('images', $fileName, 'public');

                TemporaryFile::create([
                    'filename' => $images
                ]);
                return $images;
            }
        };
    }
    public function revert(Request $request)
    {
        $temporaryFile = TemporaryFile::where('filename', $request->getContent())->first();
        $temporaryFile->delete();
    }
}
