<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsappController;

Route::get('/wa', [WhatsappController::class, 'index']);
