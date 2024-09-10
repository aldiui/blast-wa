<?php

namespace App\Filament\Pages;

use App\Services\WhatsappService;
use Filament\Pages\Page;

class WhatsappPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static string $view = 'filament.pages.whatsapp-page';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Whatsapp';

    protected static ?string $modelLabel = 'Whatsapp';

    protected static ?string $slug = 'whatsapp';

    protected static ?int $navigationSort = 1;

    public $whatsapp;

    public function mount()
    {
        $whatsappService = new WhatsappService;
        $this->whatsapp = $whatsappService->connectWhatsapp();
    }
}
