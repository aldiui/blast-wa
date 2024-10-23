<?php

namespace App\Filament\Resources\DaftarUlangResource\RelationManagers;

use App\Models\DaftarUlang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                Forms\Components\Select::make('pembayaran')
                    ->label('Pembayaran')
                    ->options([
                        'Cash' => 'Cash',
                        'Transfer' => 'Transfer',
                    ])
                    ->default('0')
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default(now()),
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
                Tables\Columns\TextColumn::make('pembayaran')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'Cash' => 'heroicon-o-banknotes',
                        'Transfer' => 'heroicon-o-credit-card',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Cash' => 'success',
                        'Transfer' => 'info',
                    })
                    ->formatStateUsing(function ($state) {
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
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
                    ->icon('heroicon-o-pencil')
                    ->before(function ($record, $data): void {
                        $daftarUlang = DaftarUlang::find($record->daftar_ulang_id);
                        $daftarUlang->update([
                            'biaya' => $daftarUlang->biaya + $record->nominal - $data['nominal'],
                        ]);
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/daftar-ulang/' . $record->daftarUlang->uuid . '/edit'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
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
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ]);
    }
}
