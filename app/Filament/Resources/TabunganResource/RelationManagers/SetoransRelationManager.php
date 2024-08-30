<?php

namespace App\Filament\Resources\TabunganResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SetoransRelationManager extends RelationManager
{
    protected static string $relationship = 'setorans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->required()
                    ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                    ->maxLength(255),
                    Forms\Components\Select::make('transaksi')
                    ->required()
                    ->options([
                        'Pemasukan' => 'Pemasukan',
                        'Pengeluaran' => 'Pengeluaran',
                    ])
                    ->searchable(),
            ]);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nominal')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaksi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->currency('IDR')
                    ->searchable(),
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
            ])
            ->paginated([25, 50, 100, 'all']);
    }
}
