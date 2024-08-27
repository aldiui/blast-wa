<?php

use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\WhatsappController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});

Route::prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::match(['get', 'post'], '/whatsapp', [WhatsappController::class, 'index'])->name('admin.whatsapp');

    Route::resource('/kelas', KelasController::class)->names('admin.kelas');
    Route::resource('/siswa', SiswaController::class)->names('admin.siswa');
    Route::resource('/pengumuman', PengumumanController::class)->names('admin.siswa');

});