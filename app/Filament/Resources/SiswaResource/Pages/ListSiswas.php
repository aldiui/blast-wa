<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Imports\SiswaImport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->label('Upload Excel')
                ->slideOver()
                ->icon('heroicon-o-arrow-up-tray')
                ->color("warning")
                ->use(SiswaImport::class),
            Actions\CreateAction::make(),
            Actions\CreupdaateAction::make(),
        ];
    }
}
