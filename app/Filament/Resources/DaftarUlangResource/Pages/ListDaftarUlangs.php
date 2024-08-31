<?php

namespace App\Filament\Resources\DaftarUlangResource\Pages;

use App\Filament\Resources\DaftarUlangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDaftarUlangs extends ListRecords
{
    protected static string $resource = DaftarUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
