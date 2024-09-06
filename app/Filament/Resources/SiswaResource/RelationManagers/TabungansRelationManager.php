<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TabungansRelationManager extends RelationManager
{
    protected static string $relationship = 'tabungans';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('saldo')
            ->columns([
                Tables\Columns\TextColumn::make('jenis_tabungan'),
                Tables\Columns\TextColumn::make('saldo')
                    ->formatStateUsing(function ($state) {
                        return formatRupiah($state);
                    })
                    ->sortable(),
            ])
            ->recordUrl(
                fn ($record): string => '/tabungan/' . $record->uuid . '/edit',
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }
}
