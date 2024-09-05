<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class IuransRelationManager extends RelationManager
{
    protected static string $relationship = 'iurans';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            Select::make('bulan')
                ->label('Bulan')
                ->options([
                    'Januari' => 'Januari',
                    'Februari' => 'Februari',
                    'Maret' => 'Maret',
                    'April' => 'April',
                    'Mei' => 'Mei',
                    'Juni' => 'Juni',
                    'Juli' => 'Juli',
                    'Agustus' => 'Agustus',
                    'September' => 'September',
                    'Oktober' => 'Oktober',
                    'November' => 'November',
                    'Desember' => 'Desember',
                ])
                ->required(),
            Forms\Components\TextInput::make('tahun_ajaran')
                ->label('Tahun Ajaran')
                ->required()
                ->default(now()),
            Forms\Components\TextInput::make('syahriyah')
                ->required()
                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                ->prefix('Rp')
                ->default(getPengaturan()->syahriyah),
            Forms\Components\TextInput::make('uang_makan')
                ->required()
                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                ->prefix('Rp')
                ->default(getPengaturan()->uang_makan),
            Forms\Components\TextInput::make('field_trip')
                ->required()
                ->currencyMask(thousandSeparator: '.', decimalSeparator: ',', precision: 2)
                ->prefix('Rp')
                ->default(getPengaturan()->field_trip),
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    '1' => 'Lunas',
                    '0' => 'Belum Lunas',
                ])
                ->default(0)
                ->required(),
        ])->columns(2);

    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tanggal')


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
