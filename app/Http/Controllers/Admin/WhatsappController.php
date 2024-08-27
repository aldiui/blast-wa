<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\WhatsappService;

class WhatsappController extends Controller
{
    public function index(WhatsappService $whatsappService)
    {
        $bulk = [
            [
                "number" => "087826753532",
                "message" => "Halo Aldi",
            ],
            [
                "number" => "081930865458",
                "message" => "Halo Ari",
            ],
        ];
        $whatsapp = $whatsappService->sendBulkMessage(compact('bulk'));
        return $whatsapp;
        // return Inertia::render('Admin/Whatsapp/Index');
    }
}