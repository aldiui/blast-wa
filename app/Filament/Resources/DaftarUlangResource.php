<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DaftarUlangResource\Pages;
use App\Filament\Resources\DaftarUlangResource\RelationManagers\SetoranDaftarUlangsRelationManager;
use App\Models\DaftarUlang;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DaftarUlangResource extends Resource
{
    protected static ?string $model = DaftarUlang::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Daftar Ulang';

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?string $slug = 'daftar-ulang';

    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
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
                    Select::make('tahun_ajaran')
                        ->label('Tahun Ajaran')
                        ->required()
                        ->options(
                            cekTahunAjaran()['tahunAjaranTerakhir15']
                        )
                        ->default(cekTahunAjaran()['tahunAjaranSekarang']),
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
                        ->default('0')
                        ->required(),
                    Textarea::make('keterangan')
                        ->default('-')
                        ->autosize(),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_ajaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('biaya')
                    ->numeric()
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
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash'),
                Tables\Actions\RestoreAction::make()
                    ->icon('heroicon-o-refresh'),
                Tables\Actions\ForceDeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->icon('heroicon-o-refresh'),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ]),
            ])
            ->paginated([50, 100, 'all']);

    }

    public static function getRelations(): array
    {
        return [
            SetoranDaftarUlangsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDaftarUlangs::route('/'),
            'create' => Pages\CreateDaftarUlang::route('/create'),
            'edit' => Pages\EditDaftarUlang::route('/{record}/edit'),
        ];
    }
}
