<?php

namespace App\Http\Controllers;

use App\Models\TemporaryFile;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        if ($request->file('filepond')) {
            $file = $request->file('filepond');
            $name = $file->getClientOriginalName();
            $file_name = date('mdYHis') . '-' . $name;
            $file = $file->storeAs('file', $file_name, 'public_uploads');

            TemporaryFile::create([
                'filename' => $file
            ]);
            return $file;
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $images) {
                $name = $images->getClientOriginalName();
                $image_name = date('mdYHis') . '-' . $name;
                $images = $images->storeAs('images', $image_name, 'public_uploads');

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
