<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumumanResource\Pages;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Services\WhatsappService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?string $recordTitleAttribute = 'judul';

    protected static ?string $navigationLabel = 'Pengumuman';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'pengumuman';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('judul')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DateTimePicker::make('tanggal')
                        ->required(),
                    Forms\Components\Textarea::make('deksripsi')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('Blast')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->form(function (Pengumuman $record) {
                        return [
                            Forms\Components\Select::make('target')
                                ->required()
                                ->options(Kelas::all()->pluck('nama', 'id'))
                                ->label('Pilih Kelas')
                                ->searchable()
                                ->multiple()
                                ->columnSpan(2),
                        ];
                    })
                    ->action(function (Pengumuman $record, array $data) {
                        $kelas = $data['target'];
                        $bulk = [];
                        foreach ($kelas as $k) {
                            $checkKelas = Kelas::find($k);
                            if ($checkKelas) {
                                $siswas = Siswa::where('kelas_id', $checkKelas->id)->get();
                                foreach ($siswas as $siswa) {
                                    $bulk[] = [
                                        'number' => $siswa->no_telepon,
                                        'message' => $record->deksripsi,
                                    ];
                                }
                            }
                        }
                        $whatsappService = new WhatsappService();
                        dd($whatsappService->sendBulkMessage(compact('bulk')));

                        Notification::make()
                            ->title('Pengumuman')
                            ->body('Pengumuman terkirim')
                            ->success()
                            ->send();
                    }),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumumen::route('/'),
            'create' => Pages\CreatePengumuman::route('/create'),
            'edit' => Pages\EditPengumuman::route('/{record}/edit'),
        ];
    }
}