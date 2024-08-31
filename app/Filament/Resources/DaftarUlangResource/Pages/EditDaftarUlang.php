<?php

namespace App\Filament\Resources\DaftarUlangResource\Pages;

use App\Filament\Resources\DaftarUlangResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDaftarUlang extends EditRecord
{
    protected static string $resource = DaftarUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
