<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms\Form;
use Actions\CreateAction;
use Filament\Tables\Table;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Excel;
use App\Imports\MyClientImport;
use Filament\Resources\Resource;
use YOS\FilamentExcel\Actions\Import;
use Filament\Tables\Enums\FiltersLayout;
use EightyNine\ExcelImport\ExcelImportAction;
use App\Filament\Resources\SiswaResource\Pages;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'siswa';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make('Data Siswa')
                    ->schema([
                        Forms\Components\Select::make('id_kelas')
                            ->label('Kelas')
                            ->options(Kelas::all()->pluck('nama', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('nis')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('orang_tua')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('no_telepon')
                            ->tel()
                            ->required()
                            ->maxLength(15),
                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->label('Kelas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orang_tua')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kelas')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('nama', 'id'))
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
            ]);
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
 
    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->slideOver()
                ->color("primary")
                ->use(SiswaImport::class),
            Actions\CreateAction::make(),
        ];
    }

}
