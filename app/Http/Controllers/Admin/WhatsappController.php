<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendBulkMessageJob;
use App\Services\WhatsappService;

class WhatsappController extends Controller
{
    public function index(WhatsappService $whatsappService)
    {
        $bulkMessages = [
            [
                "number" => "087826753532",
                "message" => "Halo Aldi",
            ],
            [
                "number" => "081930865458",
                "message" => "Halo Ari",
            ],
        ];

        dispatch(new SendBulkMessageJob($bulkMessages))->onQueue('bulk-messages');
        return $whatsapp;
        // return Inertia::render('Admin/Whatsapp/Index');
    }
}
