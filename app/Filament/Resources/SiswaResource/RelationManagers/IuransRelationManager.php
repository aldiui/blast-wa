<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Tables;
use App\Models\Iuran;
use Filament\Tables\Table;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Resources\RelationManagers\RelationManager;

class IuransRelationManager extends RelationManager
{
    protected static string $relationship = 'iurans';

    protected static ?string $title = 'Iuran';

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
                Tables\Columns\TextColumn::make('uuid')
                    ->formatStateUsing(function ($state) {
                        $record = Iuran::where('uuid', $state)->first();
                        $syahriyah = $record->syahriyah ?? 0;
                        $uangMakan = $record->uang_makan ?? 0;
                        $fieldTrip = $record->field_trip ?? 0;

                        $total = $syahriyah + $uangMakan + $fieldTrip;
                        return formatRupiah($total);
                    })
                    ->sortable()
                    ->searchable()
                    ->label('Total'), 
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