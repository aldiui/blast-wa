<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Whatsapp/Index');
    }
}
