<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TabunganResource\Pages;
use App\Filament\Resources\TabunganResource\RelationManagers;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tabungan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class TabunganResource extends Resource
{
    protected static ?string $model = Tabungan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Tabungan';

    protected static ?string $navigationGroup = 'Data Transaksi';

    protected static ?string $slug = 'tabungan';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('id_siswa')
                        ->label('Siswa')
                        ->options(Siswa::all()->pluck('nama', 'id'))
                        ->searchable(),
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
                        ->default(0)
                        ->visible('create'),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama')
                    ->label('Kelas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_tabungan'),
                Tables\Columns\TextColumn::make('saldo')
                    ->formatStateUsing(function ($state) {
                        return formatRupiah($state);
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_siswa')
                    ->label('Siswa')
                    ->options(Siswa::all()->pluck('nama', 'id'))
                    ->searchable(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])->paginated([25, 50, 100, 'all']);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SetoransRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTabungans::route('/'),
            'create' => Pages\CreateTabungan::route('/create'),
            'edit' => Pages\EditTabungan::route('/{record}/edit'),
        ];
    }
}