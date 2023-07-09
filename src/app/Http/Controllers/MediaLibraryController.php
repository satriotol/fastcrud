<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MediaLibrary;

class MediaLibraryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:media_library-index|media_library-create|media_library-edit|media_library-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:media_library-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:media_library-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:media_library-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $media_libraries = MediaLibrary::paginate();
        return view('backend.media_library.index', compact('media_libraries'));
    }
    public function create()
    {
        return view('backend.media_library.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'file' => 'required',
        ]);
        //$image = TemporaryFile::where('filename', $request->image)->first();
        //if ($image) {
        //    $data['image'] = $image->filename;
        //$image->delete();
        //};
        MediaLibrary::create($data);
        session()->flash('success');
        return redirect(route('media_library.index'));
    }
    public function edit($uuid)
    {
        $media_library = MediaLibrary::where('uuid', $uuid)->firstOrFail();
        return view('backend.media_library.create', compact('media_library'));
    }
    public function update(Request $request, $uuid)
    {
        $media_library = MediaLibrary::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'name' => 'required',
            'file' => 'required',
        ]);
        $media_library->update($data);
        session()->flash('success');
        return redirect(route('media_library.index'));
    }
    public function destroy($uuid)
    {
        $media_library = MediaLibrary::where('uuid', $uuid)->firstOrFail();
        $media_library->delete();
        session()->flash('success');
        return back();
    }
}
