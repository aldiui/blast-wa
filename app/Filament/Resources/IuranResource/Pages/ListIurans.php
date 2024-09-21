<?php
namespace App\Filament\Resources\IuranResource\Pages;

use App\Filament\Resources\IuranResource;
use App\Models\Iuran;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListIurans extends ListRecords
{
    protected static string $resource = IuranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
            Actions\Action::make('generateIuran')
                ->icon('heroicon-o-document-text')
                ->color('info')
                ->label('Generate')
                ->requiresConfirmation()
                ->modalWidth(MaxWidth::ExtraLarge)
                ->action(function (array $data) {
                    $kelasId = $data['kelas_id'];
                    $bulan = $data['bulan'];
                    $tahunAjaran = $data['tahun_ajaran'];

                    $siswa = Siswa::where('kelas_id', $kelasId)->get();

                    $skippedStudents = [];

                    foreach ($siswa as $s) {
                        $exists = Iuran::where('siswa_id', $s->id)
                            ->where('bulan', $bulan)
                            ->where('tahun_ajaran', $tahunAjaran)
                            ->exists();

                        if ($exists) {
                            $skippedStudents[] = $s->nama;
                        } else {
                            Iuran::create([
                                'siswa_id' => $s->id,
                                'kelas_id' => $kelasId,
                                'bulan' => $bulan,
                                'tahun_ajaran' => $tahunAjaran,
                                'tanggal' => now(),
                                'syahriyah' => getPengaturan()->syahriyah,
                                'uang_makan' => getPengaturan()->uang_makan,
                                'field_trip' => getPengaturan()->field_trip,
                                'status' => '0',
                            ]);
                        }
                    }

                    if (count($skippedStudents) > 0) {
                        Notification::make()
                            ->title('Beberapa Iuran Sudah Ada!')
                            ->warning()
                            ->body('Iuran sudah ada untuk siswa: ' . implode(', ', $skippedStudents))
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Sukses!')
                            ->success()
                            ->body('Iuran berhasil di generate.')
                            ->send();
                    }
                })
                ->form([
                    Select::make('kelas_id')
                        ->label('Kelas')
                        ->options(Kelas::all()->pluck('nama', 'id'))
                        ->required(),
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
                        Select::make('tahun_ajaran')
                        ->label('Tahun Ajaran')
                        ->required()
                        ->options(
                            cekTahunAjaran()['tahunAjaranTerakhir15']
                        )
                        ->default(cekTahunAjaran()['tahunAjaranSekarang']),
                ]),
        ];
    }
}
