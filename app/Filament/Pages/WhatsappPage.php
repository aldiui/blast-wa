<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;

class WhatsappPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static string $view = 'filament.pages.whatsapp-page';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Whatsapp';

    protected static ?string $modelLabel = 'Whatsapp';

    protected static ?string $slug = 'whatsapp';

    protected static ?int $navigationSort = 1;

    public $qrCodeUrl;

    public function mount()
    {
        // $response = Http::get('http://localhost:3000/api/qrcode');
        // if ($response->successful()) {
        //     $qrCodeUrl = $response->json()['qrCodeUrl'];
        // } else {
        //     $qrCodeUrl = null;
        // }
    }
}