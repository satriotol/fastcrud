<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:menu-index|menu-create|menu-edit|menu-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:menu-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menu-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:menu-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $menus = Menu::latest()->paginate();
        return view('backend.menu.index',compact('menus'));
    }
    public function create()
    {
        return view('backend.menu.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
'content' => 'required',
        ]);
        

        Menu::create($data);
        session()->flash('success', 'Menu Berhasil Dibuat');
        return redirect(route('menu.index'));
    }
    public function edit($uuid)
    {
        $menu = Menu::where('uuid', $uuid)->firstOrFail();
        return view('backend.menu.create', compact('menu'));
    }
    public function update(Request $request, $uuid)
    {
        $menu = Menu::where('uuid', $uuid)->firstOrFail();
        $data = $request->validate([
            'title' => 'required',
'content' => 'required',
        ]);
        

        $menu->update($data);
        session()->flash('success', 'Menu Berhasil Diubah');
        return redirect(route('menu.index'));
    }
    public function destroy($uuid)
    {
        $menu = Menu::where('uuid', $uuid)->firstOrFail();
        $menu->delete();
        session()->flash('success', 'Menu Berhasil Dihapus');
        return back();
    }
}