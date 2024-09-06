<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DaftarUlangsRelationManager extends RelationManager
{
    protected static string $relationship = 'daftarUlangs';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('biaya')
            ->columns([

                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->formatStateUsing(function ($state) {
                        return formatRupiah($state);
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-x-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        '1' => 'Lunas',
                        '0' => 'Belum Lunas',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->searchable(),
            ])
            ->recordUrl(
                fn($record): string => '/daftar-ulang/' . $record->uuid . '/edit',
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }
}
