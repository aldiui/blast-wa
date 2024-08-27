<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PengumumanController;


Route::middleware(['guest'])->group(function () {
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});

Route::prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan.index');

    Route::resource('/kelas', App\Http\Controllers\Admin\KelasController::class)->names('admin.kelas');
    Route::resource('/siswa', App\Http\Controllers\Admin\SiswaController::class)->names('admin.siswa');
    Route::resource('/pengumuman', App\Http\Controllers\Admin\PengumumanController::class)->names('admin.siswa');
   
});
