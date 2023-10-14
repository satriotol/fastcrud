<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:media-index|media-create|media-edit|media-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:media-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:media-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:media-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $medias = Media::latest()->paginate();
        return view('backend.media.index', compact('medias'));
    }
    public function create()
    {
        return view('backend.media.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|image|max:2048',
        ]);

        $file = $request->file('file');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'file/' . date('mdYHis') . '-' . Str::random(8) . '.' . $fileExtension;
        $file->storeAs('public', $fileName);

        $data = [
            'title' => $request->input('title'),
            'file' => $fileName, // Menyimpan nama file yang dihasilkan oleh Laravel
            'original_name' => $file->getClientOriginalName(), // Nama asli file
            'mime_type' => $file->getMimeType(), // Tipe MIME
            'file_size' => $file->getSize(), // Ukuran file
            'user_id' => Auth::id(), // Menggunakan metode otentikasi Auth
        ];

        Media::create($data);

        session()->flash('success', 'Media Berhasil Dibuat');
        return redirect()->route('media.index');
    }
    public function edit($uuid)
    {
        $media = Media::where('uuid', $uuid)->firstOrFail();
        return view('backend.media.create', compact('media'));
    }
    public function update(Request $request, $uuid)
    {
        $media = Media::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'title' => 'required',
            'file' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->input('title'),
        ];

        if ($request->file('file')) {
            $file = $request->file('file');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName = 'file/' . date('mdYHis') . '-' . Str::random(8) . '.' . $fileExtension;
            $file->storeAs('public', $fileName);

            // Jika file baru diunggah, perbarui data file
            $data['file'] = $fileName;
            $data['original_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getMimeType();
            $data['file_size'] = number_format($file->getSize() / 1048576, 2); // Ukuran dalam MB
        }

        $data['user_id'] = Auth::id(); // Menggunakan metode otentikasi Auth

        $media->update($data);

        session()->flash('success', 'Media Berhasil Diubah');
        return redirect()->route('media.index');
    }
    public function destroy($uuid)
    {
        $media = Media::where('uuid', $uuid)->firstOrFail();
        $media->delete();
        session()->flash('success', 'Media Berhasil Dihapus');
        return back();
    }
}
