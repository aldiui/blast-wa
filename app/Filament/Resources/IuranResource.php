<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Iuran;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Resources\IuranResource\Pages;

class IuranResource extends Resource
{
    protected static ?string $model = Iuran::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Iuran';

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?string $slug = 'iuran';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('siswa_id')
                            ->label('Siswa')
                            ->required()
                            ->options(Siswa::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, $set) {
                                $set('kelas_id', $state ? Siswa::find($state)->kelas_id : null);
                            }),
                        Forms\Components\Select::make('kelas_id')
                            ->label('Kelas')
                            ->required()
                            ->options(Kelas::all()->pluck('nama', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('bulan')
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
                        Select::make('tahun_ajaran')
                            ->label('Tahun Ajaran')
                            ->required()
                            ->options(
                                cekTahunAjaran()['tahunAjaranTerakhir15']
                            )
                            ->default(cekTahunAjaran()['tahunAjaranSekarang']),
                        Forms\Components\DatePicker::make('tanggal')
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
                        Forms\Components\Select::make('pembayaran')
                            ->label('Pembayaran')
                            ->options([
                                'Cash' => 'Cash',
                                'Transfer' => 'Transfer',
                            ])
                            ->default('Cash')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                '0' => 'Belum Lunas',
                                '1' => 'Lunas',
                            ])
                            ->default('0')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->searchable(),
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
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'info',
                    })
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        '0' => 'Belum Lunas',
                        '1' => 'Lunas',
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->paginated([50, 100, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIurans::route('/'),
            'create' => Pages\CreateIuran::route('/create'),
            'edit' => Pages\EditIuran::route('/{record}/edit'),
        ];
    }
}
