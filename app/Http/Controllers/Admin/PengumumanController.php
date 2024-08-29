<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $pengumuman = Pengumuman::search($request->search)->paginate($request->perPage ?? 25)->appends('query', null)->withQueryString();
        return Inertia::render('Admin/Pengumuman/Index', compact('pengumuman'));
    }

    public function create()
    {
        return Inertia::render('Admin/Pengumuman/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|min:10|max:255',
            'deksripsi' => 'required|min:10',
            'tanggal' => 'required',
        ]);

        Pengumuman::create($request->only('judul', 'deksripsi', 'tanggal'));
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman baru ditambahkan.');
    }

    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return Inertia::render('Admin/Pengumuman/Edit', compact('pengumuman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|min:10|max:255',
            'deksripsi' => 'required|min:10',
            'tanggal' => 'required',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->update($request->only('judul', 'deksripsi', 'tanggal'));
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman baru ditambahkan.');
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman baru ditambahkan.');
    }

}