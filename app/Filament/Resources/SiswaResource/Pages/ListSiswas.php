<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use Filament\Actions;
use App\Imports\SiswaImport;
use App\Filament\Resources\SiswaResource;
use Filament\Resources\Pages\ListRecords;
use EightyNine\ExcelImport\ExcelImportAction;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->slideOver()
                ->color("primary")
                ->use(SiswaImport::class),
            Actions\CreateAction::make(),
        ];
    }
}
