<?php

namespace App\Filament\Resources\IuranResource\Pages;

use App\Filament\Resources\IuranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIuran extends EditRecord
{
    protected static string $resource = IuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('kembali')
            ->color('warning')
            ->label('Kembali')
            ->url(fn ($record) => '/siswa/' . $record->siswa->uuid . '/edit'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
