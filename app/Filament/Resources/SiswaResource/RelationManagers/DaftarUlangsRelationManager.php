<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Resources\RelationManagers\RelationManager;

class DaftarUlangsRelationManager extends RelationManager
{
    protected static string $relationship = 'daftarUlangs';

    protected static ?string $title = 'Daftar Ulang';

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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        '0' => 'Belum Lunas',
                        '1' => 'Lunas',
                    ])
                    ->searchable(),
            ], layout: FiltersLayout::AboveContent)
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
