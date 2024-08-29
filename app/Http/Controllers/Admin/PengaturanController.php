<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        $pengaturan = Pengaturan::findOrFail(1);
        if ($request->method() == 'PUT') {
            $request->validate([
                'nama' => 'required|min:3|max:255',
                // 'logo' => 'nullable|mimes:png,jpg,jpeg|max:2048',
                'email' => 'required|email|max:255',
                'no_telepon' => 'required|min:10|max:13',
                'alamat' => 'required|min:3|max:255',
                'syahriyah' => 'required|numeric',
                'uang_makan' => 'required|numeric',
                'field_trip' => 'required|numeric',
                'daftar_ulang' => 'required|numeric',
            ]);

            $pengaturan->update($request->only('nama', 'email', 'no_telepon', 'alamat', 'syahriyah', 'uang_makan', 'field_trip', 'daftar_ulang'));
            return redirect()->route('pengaturan')->with('success', 'Pengaturan baru ditambahkan.');
        }
        return Inertia::render('Admin/Pengaturan/Index', compact('pengaturan'));
    }

}