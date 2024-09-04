<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumumanResource\Pages;
use App\Models\Pengumuman;
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

    protected static ?string $recordTitleAttribute = 'nama';

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
                Tables\Actions\Action::make('Send Notification')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (Pengumuman $record) {
                        $message = $record->judul . ' ' . $record->deksripsi;
                        $bulk = [
                            [
                                "number" => "087826753532",
                                "message" => $message,
                            ],
                            [
                                "number" => "081930865458",
                                "message" => $message,
                            ],
                        ];

                        $whatsappService = new WhatsappService();

                        try {
                            $whatsappService->sendBulkMessage($bulk);

                            Notification::make()
                                ->title('Notification sent')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {

                            Notification::make()
                                ->title('Failed to send notification')
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
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
