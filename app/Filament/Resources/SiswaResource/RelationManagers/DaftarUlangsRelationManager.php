<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DaftarUlangsRelationManager extends RelationManager
{
    protected static string $relationship = 'daftarUlangs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->required()
                ->default(cekTahunAjaran())
                ->maxLength(255),
            Forms\Components\DatePicker::make('tanggal')
                ->required(),
            Forms\Components\TextInput::make('biaya')
                ->required()
                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                ->prefix('Rp')
                ->default(getPengaturan()->daftar_ulang),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    '1' => 'Lunas',
                    '0' => 'Belum Lunas',
                ])
                ->default(0)
                ->required(),
            Textarea::make('keterangan')
                ->autosize(),
        ])->columns(2);
    }

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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
