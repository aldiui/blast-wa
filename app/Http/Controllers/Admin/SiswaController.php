<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::search($request->search)->paginate($request->perPage ?? 25)->appends('query',null)->withQueryString();
        $kelas = Kelas::all();
        return Inertia::render('Admin/Siswa/Index',compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return Inertia::render('Admin/Siswa/Create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nisn' => 'required|min:3|max:255|unique',
            'nama' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:kelas,id',
            'orang_tua' => 'required|min:3|max:255',
            'no_telepon' => 'required|min:10|max:13',
            'alamat' => 'required|min:3',
        ]);
        Siswa::create($request->only('nisn', 'nama', 'id_kelas', 'orang_tua', 'no_telepon', 'alamat'));
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa baru ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();
        return Inertia::render('Admin/Siswa/Edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nisn' => 'required|min:3|max:255',
            'nama' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:kelas,id',
            'orang_tua' => 'required|min:3|max:255',
            'no_telepon' => 'required|min:10|max:13',
            'alamat' => 'required|min:3',
        ]);
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->only('nisn', 'nama', 'id_kelas', 'orang_tua', 'no_telepon', 'alamat'));
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa baru ditambahkan.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa baru ditambahkan.');
    }

}
