<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('warning')
                ->label('Kembali')
                ->url(fn($record) => '/kelas/' . $record->kelas->uuid . '/edit'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),

        ];
    }
}
