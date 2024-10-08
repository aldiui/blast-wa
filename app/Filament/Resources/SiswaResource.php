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
use Illuminate\Database\Eloquent\Model;

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

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Nama' => $record->nama,
            'Nis' => $record->nis,
        ];
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
            ->headerActions([
                Tables\Actions\Action::make('bulk_blast')
                    ->label('Blast')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->modalWidth('md')
                    ->form(function () {
                        return [
                            Forms\Components\Select::make('kelas')
                                ->required()
                                ->options(fn() => Kelas::all()->pluck('nama', 'id'))
                                ->label('Pilih Kelas')
                                ->multiple()
                                ->columnSpan(2),
                            Forms\Components\Select::make('bulan')
                                ->required()
                                ->options(
                                    [
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
                                    ]
                                ),
                            Forms\Components\Select::make('tahun_ajaran')
                                ->required()
                                ->options(
                                    cekTahunAjaran()['tahunAjaranTerakhir15']
                                )
                                ->label('Pilih Tahun Ajaran')
                                ->columnSpan(2),
                        ];
                    })

                    ->action(function ($record, array $data) {
                        $kelasIds = $data['kelas'];
                        $bulan = $data['bulan'];
                        $tahunAjaran = $data['tahun_ajaran'];
                        $waktu = now();
                        $daful = 0 ;
                        $bulk = [];

                        foreach ($kelasIds as $kelasId) {
                            $kelas = Kelas::find($kelasId);
                            if ($kelas) {
                                $siswas = Siswa::where('kelas_id', $kelas->id)->get();

                                if ($siswas->isNotEmpty()) {
                                    foreach ($siswas as $siswa) {

                                        if (!empty($siswa->no_telepon || $siswa->no_telpon != '081930865458')) {$tabungan = Tabungan::where('siswa_id', $siswa->id)->sum('saldo');

                                            $daftarUlang = DaftarUlang::where('siswa_id', $siswa->id)->whereStatus('0')->where('tahun_ajaran', $tahunAjaran)->sum('biaya');

                                            $daful = intval($daful) + intval($daftarUlang);
                                            $daful = formatRupiah($daful);

                                            $iuranSyahriyah = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('bulan', $bulan)
                                                ->where('tahun_ajaran', $tahunAjaran)
                                                ->sum('syahriyah');

                                            $iuranFieldTrip = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('bulan', $bulan)
                                                ->where('tahun_ajaran', $tahunAjaran)
                                                ->sum('field_trip');

                                            $iuranUangMakan = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('bulan', $bulan)
                                                ->where('tahun_ajaran', $tahunAjaran)
                                                ->sum('uang_makan');

                                            $tunggakanSyahriyah = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('tahun_ajaran', '<', $tahunAjaran)->sum('syahriyah');

                                            $tunggakanFieldTrip = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('tahun_ajaran', '<', $tahunAjaran)->sum('field_trip');

                                            $tunggakanUangMakan = Iuran::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('tahun_ajaran', '<', $tahunAjaran)->sum('uang_makan');

                                            $tunggakanIuran = $tunggakanSyahriyah + $tunggakanFieldTrip + $tunggakanUangMakan;

                                            $tunggakanDaftarUlang = DaftarUlang::where('siswa_id', $siswa->id)
                                                ->where('status', '0')
                                                ->where('tahun_ajaran', '<', $tahunAjaran)
                                                ->sum('biaya');

                                            $totalTunggakan = formatRupiah($tunggakanDaftarUlang + $tunggakanIuran);

                                            $tabunganRupiah = formatRupiah($tabungan);
                                            $iuranSyahriyahRupiah = formatRupiah($iuranSyahriyah);
                                            $iuranFieldTripRupiah = formatRupiah($iuranFieldTrip);
                                            $iuranUangMakanRupiah = formatRupiah($iuranUangMakan);

                                            $totalIuran = $iuranSyahriyah + $iuranFieldTrip + $iuranUangMakan;
                                            $totalIuranRupiah = formatRupiah($totalIuran);

                                            $tahunAjaran = cekTahunAjaran()['tahunAjaranSekarang'];
                                            $nis = $siswa->nis;
                                            $nama = $siswa->nama;
                                            $kelasNama = $kelas->nama;
                                            $sekolah = getPengaturan()->nama;

                                            $text = <<<EOT
                                SISTEM KEUANGAN SEKOLAH
                                INFORMASI TAGIHAN
                                ==========================

                                Assalamu’alaikum Warahmatullahi Wabarakatuh,

                                Yth Bapak/Ibu Wali Murid,

                                Berdasarkan data pada sistem kami hingga $waktu, kami sampaikan data tagihan ananda hingga Bulan $bulan Tahun $tahunAjaran:

                                Siswa:
                                NIS      : $nis
                                Nama  : $nama
                                Kelas   : $kelasNama

                                Tabungan Tahun $tahunAjaran $tabunganRupiah

                                Rincian Tagihan:
                                • Syahriyah Tahun $tahunAjaran ($bulan) $iuranSyahriyahRupiah
                                • Fieldtrip Tahun $tahunAjaran ($bulan) $iuranFieldTripRupiah
                                • Uang Makan Tahun $tahunAjaran ($bulan) $iuranUangMakanRupiah
                                ----------------------------------
                                Total Tagihan: $totalIuranRupiah

                                Biaya Daftar Ulang : $daful

                                Tunggakan: $totalTunggakan

                                Pembayaran Melalui Transfer Bank:
                                - Bank Syariah Indonesia (BSI)
                                9199998878 a.n. MI CONDONG

                                - Bank Rakyat Indonesia (BRI)
                                444401009372539 a.n. MI CONDONG

                                Harap konfirmasi ke nomor ini setelah melakukan pembayaran.

                                Atas perhatian dan kerjasamanya kami sampaikan terima kasih.
                                Wassalamu'alaikum Warahmatullahi Wabarakatuh,

                                $sekolah
                            EOT;

                                            $bulk[] = [
                                                'number' => $siswa->no_telepon,
                                                'message' => $text,
                                            ];}
                                    }
                                }
                            }
                        }

                        $whatsappService = new WhatsappService();
                        $whatsappService->sendBulkMessage(compact('bulk'));

                        Notification::make()
                            ->title('Whatsapp')
                            ->body('Pesan WhatsApp berhasil dikirim ke siswa di kelas yang dipilih.')
                            ->success()
                            ->send();
                    }),

            ])
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nis')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orang_tua')
                    ->searchable()
                    ->sortable(),
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
                    ->form(function () {
                        return [
                            Forms\Components\Select::make('bulan')
                                ->required()
                                ->options(
                                    [
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
                                    ]
                                )
                                ->columnSpan(2),
                            Forms\Components\Select::make('tahun_ajaran')
                                ->required()
                                ->options(
                                    cekTahunAjaran()['tahunAjaranTerakhir15']
                                )
                                ->label('Pilih Tahun Ajaran')
                                ->columnSpan(2),
                        ];
                    })
                    ->modalWidth('md')
                    ->action(function ($record, array $data) {

                        $bulan = $data['bulan'];
                        $tahunAjaran = $data['tahun_ajaran'];
                        $waktu = now();
                        $daful = 0;

                        $daftarUlang = DaftarUlang::where('siswa_id', $record->id)->whereStatus('0')->where('tahun_ajaran', $tahunAjaran)->sum('biaya');

                        $daful += $daftarUlang;
                        $daful = formatRupiah($daful);

                        $tabungan = Tabungan::where('siswa_id', $record->id)->sum('saldo');
                        $iuranSyahriyah = Iuran::where('siswa_id', $record->id)->where('bulan', $bulan)->whereStatus('0')->sum('syahriyah');
                        $iuranFieldTrip = Iuran::where('siswa_id', $record->id)->where('bulan', $bulan)->whereStatus('0')->sum('field_trip');
                        $iuranUangMakan = Iuran::where('siswa_id', $record->id)->where('bulan', $bulan)->whereStatus('0')->sum('uang_makan');
                        $daftarUlang = DaftarUlang::where('siswa_id', $record->id)->whereStatus('0')->sum('biaya');
                        $tabunganRupiah = formatRupiah($tabungan);
                        $iuranSyahriyahRupiah = formatRupiah($iuranSyahriyah);
                        $iuranFieldTripRupiah = formatRupiah($iuranFieldTrip);
                        $iuranUangMakanRupiah = formatRupiah($iuranUangMakan);

                        $totalIuran = $iuranSyahriyah + $iuranFieldTrip + $iuranUangMakan;
                        $totalIuranRupiah = formatRupiah($totalIuran);

                        $tunggakanIuranSyahriyah = Iuran::where('siswa_id', $record->id)->where('tahun_ajaran', '<', $tahunAjaran)->where('bulan', $bulan)->whereStatus('0')->sum('syahriyah');
                        $tunggakanIuranFieldTrip = Iuran::where('siswa_id', $record->id)->where('tahun_ajaran', '<', $tahunAjaran)->where('bulan', $bulan)->whereStatus('0')->sum('field_trip');
                        $tunggakanIuranUangMakan = Iuran::where('siswa_id', $record->id)->where('tahun_ajaran', '<', $tahunAjaran)->where('bulan', $bulan)->whereStatus('0')->sum('uang_makan');
                        $tunggakanDaftarUlang = DaftarUlang::where('siswa_id', $record->id)
                            ->where('tahun_ajaran', '<', $tahunAjaran)
                            ->where('status', '0')
                            ->sum('biaya');

                        $tunggakanRupiah = formatRupiah($tunggakanDaftarUlang + $tunggakanIuranSyahriyah + $tunggakanIuranFieldTrip + $tunggakanIuranUangMakan);

                        $kelas = $record->kelas ? $record->kelas->nama : 'Unknown Class';
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

                Berdasarkan data pada sistem kami hingga $waktu kami sampaikan data tagihan ananda hingga Bulan $bulan Tahun $tahunAjaran:

                Siswa:
                NIS      : $nis
                Nama  : $nama
                Kelas   : $kelas


                Tabungan Tahun $tahunAjaran $tabunganRupiah

                Rincian Tagihan:
                • Syahriyah Tahun $tahunAjaran ($bulan) $iuranSyahriyahRupiah
                • Fieldtrip Tahun $tahunAjaran ($bulan) $iuranFieldTripRupiah
                • Uang Makan Tahun $tahunAjaran ($bulan) $iuranUangMakanRupiah
                ----------------------------------
                Total Tagihan : $totalIuranRupiah

                Biaya Daftar Ulang : $daful

                Tunggakan : $tunggakanRupiah

                Pembayaran Melalui Transfer Bank:
                - Bank Syariah Indonesia (BSI)
                9199998878 a.n. MI CONDONG

                - Bank Rakyat Indonesia (BRI)
                444401009372539 a.n. MI CONDONG

                Harap konfirmasi ke nomor ini setelah melakukan pembayaran.

                Atas perhatian dan kerjasamanya kami sampaikan terima kasih.
                Wassalamu'alaikum Warahmatullahi Wabarakatuh,

                $sekolah
                EOT;
                        $whatsappService = new WhatsappService();
                        $whatsappService->sendMessage([
                            'number' => $noTelepon,
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
                    ->icon('heroicon-o-arrow-path'),
                Tables\Actions\ForceDeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                    Tables\Actions\RestoreBulkAction::make()
                        ->icon('heroicon-o-arrow-path'),
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