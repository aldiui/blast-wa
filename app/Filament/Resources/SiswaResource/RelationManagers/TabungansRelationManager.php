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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('jenis_tabungan')
                        ->label('Jenis Tabungan')
                        ->required()
                        ->options([
                            'Tabungan Wajib' => 'Tabungan Wajib',
                            'Tabungan Reguler' => 'Tabungan Reguler',
                        ])
                        ->searchable(),
                    Forms\Components\TextInput::make('saldo')
                        ->prefix('Rp')
                        ->required()
                        ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                        ->default(0)
                        ->visible('create'),
                ])->columns(2),
            ]);
    }

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
