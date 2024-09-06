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
            Actions\Action::make('kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('warning')
                ->label('Kembali')
                ->url(fn($record) => '/siswa/' . $record->siswa->uuid . '/edit'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
