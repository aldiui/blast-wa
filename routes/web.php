<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;


Route::get('/laporan/harian', [PdfController::class, 'index'])->name('laporan.harian');