<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::search($request->search)->paginate($request->perPage ?? 25)->appends('query',null)->withQueryString();
        return Inertia::render('Admin/Kelas/Index', compact('kelas'));
    }

    public function create()
    {
        return Inertia::render('Admin/Kelas/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|min:3|max:255',
        ]);
        Kelas::create($request->only('nama'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru ditambahkan.');
    }

    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        return Inertia::render('Admin/Kelas/Edit', compact('kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|min:3|max:255',
        ]);
        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->only('nama'));
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru ditambahkan.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru ditambahkan.');
    }
}
