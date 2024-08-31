<?php

namespace App\Filament\Resources\TabunganResource\RelationManagers;

use App\Models\Tabungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SetoransRelationManager extends RelationManager
{
    protected static string $relationship = 'setorans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nominal')
                    ->prefix('Rp')
                    ->required()
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
                    ->date("d F Y")
                    ->label('Tanggal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaksi')
                    ->badge()
                    ->icon(fn(string $state): string => match ($state) {
                        'Pemasukan' => 'heroicon-o-arrow-down-circle',
                        'Pengeluaran' => 'heroicon-o-arrow-up-circle',
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'Pemasukan' => 'success',
                        'Pengeluaran' => 'danger',
                    })
                    ->formatStateUsing(function ($state) {
                        return $state;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('nominal')
                    ->formatStateUsing(function ($state) {
                        return formatRupiah($state);
                    })
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Setoran')
                    ->after(function ($record, $data): void {
                        $tabungan = Tabungan::find($record->id_tabungan);
                        if ($record->transaksi == 'Pemasukan') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo + $data['nominal'],
                            ]);
                        } elseif ($record->transaksi == 'Pengeluaran') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo - $data['nominal'],
                            ]);
                        }
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/tabungan/' . $record->id_tabungan . '/edit'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->before(function ($record, $data): void {
                        $tabungan = Tabungan::find($record->id_tabungan);
                        if ($record->transaksi == 'Pemasukan' && $data['transaksi'] == 'Pemasukan') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo - $record->nominal + $data['nominal'],
                            ]);
                        } elseif ($record->transaksi == 'Pengeluaran' && $data['transaksi'] == 'Pengeluaran') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo + $record->nominal - $data['nominal'],
                            ]);
                        } elseif ($record->transaksi == 'Pemasukan' && $data['transaksi'] == 'Pengeluaran') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo - $record->nominal - $data['nominal'],
                            ]);
                        } elseif ($record->transaksi == 'Pengeluaran' && $data['transaksi'] == 'Pemasukan') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo + $record->nominal + $data['nominal'],
                            ]);
                        }
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/tabungan/' . $record->id_tabungan . '/edit'),
                Tables\Actions\DeleteAction::make()
                    ->before(function ($record): void {
                        $tabungan = Tabungan::find($record->id_tabungan);
                        if ($record->transaksi == 'Pemasukan') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo - $record->nominal,
                            ]);
                        } elseif ($record->transaksi == 'Pengeluaran') {
                            $tabungan->update([
                                'saldo' => $tabungan->saldo + $record->nominal,
                            ]);
                        }
                    })
                    ->successRedirectUrl(fn(Model $record): string => '/tabungan/' . $record->id_tabungan . '/edit'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([25, 50, 100, 'all']);
    }
}