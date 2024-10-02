<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class IuransRelationManager extends RelationManager
{
    protected static string $relationship = 'iurans';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tanggal')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('bulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->searchable()
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
            ])
            ->recordUrl(
                fn($record): string => '/iuran/' . $record->uuid . '/edit',
            )
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }
}
