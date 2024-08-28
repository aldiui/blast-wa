<?php

use App\Http\Controllers\Admin\DashboardController;
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

Route::middleware(['auth'])->group(function () {
    Route::resource('/', DashboardController::class)->names('dashboard');
    Route::resource('/siswa', SiswaController::class)->names('siswa');
    Route::resource('/kelas', KelasController::class)->names('kelas');
    Route::resource('/pengumuman', PengumumanController::class)->names('pengumuman');
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::match(['get', 'post'], '/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan');
    Route::match(['get', 'post'], '/whatsapp', [WhatsappController::class, 'index'])->name('whatsapp');

});
