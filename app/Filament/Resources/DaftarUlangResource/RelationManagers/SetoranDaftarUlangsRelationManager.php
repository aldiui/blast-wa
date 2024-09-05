<?php

namespace App\Filament\Resources\DaftarUlangResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DaftarUlang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class SetoranDaftarUlangsRelationManager extends RelationManager
{
    protected static string $relationship = 'setoranDaftarUlangs';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->prefix('Rp')
                    ->required()
                    ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('tanggal')
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nominal')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date("d F Y")
                    ->label('Tanggal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->formatStateUsing(function ($state) {
                        return formatRupiah($state);
                    })
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Setoran')
                    ->after(function ($record, $data): void {
                        $daftarUlang = DaftarUlang::find($record->daftar_ulang_id);
                            $daftarUlang->update([
                                'biaya' => $daftarUlang->biaya - $data['nominal'],
                            ]);
                            
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/daftar-ulang/' . $record->daftarUlang->uuid . '/edit'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record, $data): void {
                        $daftarUlang = DaftarUlang::find($record->daftar_ulang_id);
                            $daftarUlang->update([
                                'biaya' => $daftarUlang->biaya + $record->nominal - $data['nominal'],
                            ]);
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/daftar-ulang/' . $record->daftarUlang->uuid . '/edit'),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record): void {
                        $daftarUlang = DaftarUlang::find($record->daftar_ulang_id);
                            $daftarUlang->update([
                                'biaya' => $daftarUlang->biaya + $record->nominal,
                            ]);
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/daftar-ulang/' . $record->daftarUlang->uuid . '/edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
