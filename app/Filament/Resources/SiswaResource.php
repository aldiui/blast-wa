<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers\DaftarUlangsRelationManager;
use App\Filament\Resources\SiswaResource\RelationManagers\IuransRelationManager;
use App\Filament\Resources\SiswaResource\RelationManagers\TabungansRelationManager;
use App\Models\DaftarUlang;
use App\Models\Iuran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Tabungan;
use App\Services\WhatsappService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $navigationGroup = 'Data Master';

    protected static ?string $slug = 'siswa';

    protected static ?int $navigationSort = 3;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama', 'nis'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\Select::make('kelas_id')
                        ->label('Kelas')
                        ->options(Kelas::all()->pluck('nama', 'id'))
                        ->searchable(),
                    Forms\Components\TextInput::make('nis')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nama')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('orang_tua')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('no_telepon')
                        ->label('Nomor Telepon')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextArea::make('alamat')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->sortable(),
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('orang_tua')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telepon')
                    ->label('Nomor Telepon')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->options(Kelas::all()->pluck('nama', 'id'))
                    ->searchable(),
                Tables\Filters\TrashedFilter::make(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\Action::make('Blast')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->action(function ($record) {
                        $tabungan = Tabungan::where('siswa_id', $record->id)->sum('saldo');
                        $iuranSyahriyah = Iuran::where('siswa_id', $record->id)->whereStatus('0')->sum('syahriyah');
                        $iuranFieldTrip = Iuran::where('siswa_id', $record->id)->whereStatus('0')->sum('field_trip');
                        $iuranUangMakan = Iuran::where('siswa_id', $record->id)->whereStatus('0')->sum('uang_makan');
                        $daftarUlang = DaftarUlang::where('siswa_id', $record->id)->whereStatus('0')->sum('biaya');
                        $tabunganRupiah = formatRupiah($tabungan);
                        $iuranSyahriyahRupiah = formatRupiah($iuranSyahriyah);
                        $iuranFieldTripRupiah = formatRupiah($iuranFieldTrip);
                        $iuranUangMakanRupiah = formatRupiah($iuranUangMakan);

                        $totalIuran = $iuranSyahriyah + $iuranFieldTrip + $iuranUangMakan;
                        $totalIuranRupiah = formatRupiah($totalIuran);

                        $tahunAjaran = cekTahunAjaran();

                        $kelas = $record->kelas ? $record->kelas->nama : 'Unknown Class'; // Adjust if necessary
                        $nis = $record->nis;
                        $nama = $record->nama;
                        $orangTua = $record->orang_tua;
                        $noTelepon = $record->no_telepon;
                        $alamat = $record->alamat;

                        $sekolah = getPengaturan()->nama;

                        $text = <<<EOT
                SISTEM KEUANGAN SEKOLAH
                INFORMASI TAGIHAN
                ==========================

                Assalamu’alaikum Warahmatullahi Wabarakatuh,

                Yth Bapak/Ibu Wali Murid,

                Berdasarkan data pada sistem kami hingga 10 Juli 2024 11:45 WIB kami sampaikan data tagihan ananda hingga Bulan Juli 2024:

                Siswa:
                NIS      : $nis
                Nama  : $nama
                Kelas   : $kelas


                Tabungan Tahun 2024/2025  $tabunganRupiah

                Rincian Tagihan:
                • Syahriyah Tahun 2024/2025 (Juli) $iuranSyahriyahRupiah
                • Fieldtrip Tahun 2024/2025 (Juli) $iuranFieldTripRupiah
                • Uang Makan Tahun 2024/2025 (Juli) $iuranUangMakanRupiah
                ----------------------------------
                Total Tagihan: $totalIuranRupiah



                Untuk buku paket bisa diambil di sekolah mulai pada hari Selasa,16 Juli 2024 di guru kelas masing-masing

                Atas perhatian dan kerjasamanya kami sampaikan terima kasih.
                Wassalamu'alaikum Warahmatullahi Wabarakatuh,

                $sekolah
                EOT;

                        $whatsappService = new WhatsappService();
                        $whatsappService->sendMessage([
                            'number' => "081930865458",
                            "message" => $text,
                        ]);

                        Notification::make()
                            ->title('Whatsapp')
                            ->body('Whatsapp terkirim')
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
            DaftarUlangsRelationManager::class,
            TabungansRelationManager::class,
            IuransRelationManager::class,
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
}
