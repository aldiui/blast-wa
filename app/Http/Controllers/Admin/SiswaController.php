<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::search($request->search)
            ->when($request->id_kelas, function ($query, $id_kelas) {
                $query->where('id_kelas', $id_kelas);
            })
            ->query(function ($query) {
                return $query->with('kelas');
            })
            ->paginate($request->perPage ?? 25)
            ->appends('query', null)
            ->withQueryString();
        $kelas = Kelas::all();
        return Inertia::render('Admin/Siswa/Index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return Inertia::render('Admin/Siswa/Create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|min:3|max:255|unique:siswas,nis',
            'nama' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:kelas,id',
            'orang_tua' => 'required|min:3|max:255',
            'no_telepon' => 'required|min:10|max:13',
            'alamat' => 'required|min:3',
        ]);

        Siswa::create($request->only('nis', 'nama', 'id_kelas', 'orang_tua', 'no_telepon', 'alamat'));
        return redirect()->route('siswa.index')->with('success', 'Siswa baru ditambahkan.');
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
            'nis' => 'required|min:3|max:255|unique:siswas,nis,' . $id,
            'nama' => 'required|min:3|max:255',
            'id_kelas' => 'required|exists:kelas,id',
            'orang_tua' => 'required|min:3|max:255',
            'no_telepon' => 'required|min:10|max:13',
            'alamat' => 'required|min:3',
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->only('nis', 'nama', 'id_kelas', 'orang_tua', 'no_telepon', 'alamat'));
        return redirect()->route('siswa.index')->with('success', 'Siswa baru ditambahkan.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa baru ditambahkan.');
    }

}
