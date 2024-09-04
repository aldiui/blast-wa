<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class WhatsappPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static string $view = 'filament.pages.whatsapp-page';

    public $qrCodeUrl;

    public function mount()
    {
        // Fetch the QR code URL from your API
        $response = Http::get('http://localhost:3000/api/qrcode');
        if ($response->successful()) {
            $qrCodeUrl = $response->json()['qrCodeUrl'];
        } else {
            $qrCodeUrl = null;
        }
    }
}
