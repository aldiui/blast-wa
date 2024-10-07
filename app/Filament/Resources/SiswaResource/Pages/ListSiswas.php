<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions;
use App\Imports\SiswaImport;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use App\Filament\Resources\SiswaResource;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('laporan')
            ->label('Laporan')
            ->icon('heroicon-o-document')
            ->action(function (array $data) {
                $jenis = $data['jenis'];
                $tipe = $data['tipe'];
                if($tipe == 'harian') {
                    $params = [
                        'tipe' => $tipe,
                        'jenis' => $jenis,
                        'tanggal' => $data['tanggal'],
                    ];
                } elseif($tipe == 'bulanan') {
                    $params = [
                        'tipe' => $tipe,
                        'jenis' => $jenis,
                        'bulan' => $data['bulan'],
                        'tahun' => $data['tahun'],
                    ];
                } elseif($tipe == 'tahunan') {
                    $params = [
                        'tipe' => $tipe,
                        'jenis' => $jenis,
                        'tahun' => $data['tahun'],
                    ];
                }

                return redirect()->route('laporan.harian', $params);
            })
            ->form([
                Select::make('jenis')
                ->label('Jenis')
                ->options([
                    'laporan_singkat' => 'Laporan Singkat',
                    'laporan' => 'Laporan Lengkap',
                    'tunggakan' => 'Tunggakan',
                ])
                ->required(),
                Select::make('tipe')
                ->label('Tipe')
                ->options([
                    'harian' => 'Harian',
                    'bulanan' => 'Bulanan',
                    'tahunan' => 'Tahunan',
                ])
                ->live(),
            
            DatePicker::make('tanggal')
                ->label('Tanggal')
                ->visible(function ($get) { 
                    return $get('tipe') == 'harian' ? true : false; 
                }),
            Select::make('bulan')
                ->label('Bulan')
                ->visible(function ($get) {
                    return $get('tipe') == 'bulanan' ? true : false;
                })
                ->options([
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember',
                ])
                ->visible(function ($get) {
                    return $get('tipe') == 'bulanan' ? true : false;
                }),
                Select::make('tahun')
                ->label('Tahun')
                ->visible(function ($get) {
                    return $get('tipe') == 'tahunan' || $get('tipe') == 'bulanan' ? true : false;
                })
                ->options(getTahunTerakhir()),
            ])
            ->modalHeading('Export PDF Laporan Harian')
            ->modalSubmitActionLabel('Export')
            ->modalWidth('md'),
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->label('Import')
                ->slideOver()
                ->icon('heroicon-o-arrow-up-tray')
                ->color("warning")
                ->use(SiswaImport::class),
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
            Actions\Action::make('generate')
                ->icon('heroicon-o-arrow-up')
                ->label('Naik Kelas')
                ->color("info")
                ->requiresConfirmation()
                ->action(function () {
                    $records = Siswa::where('kelas_id', '!=', null)->get();
                    $successMessages = [];
                    $errorMessages = [];

                    foreach ($records as $record) {
                        $currentClass = $record->kelas->nama;

                        $newClass = getNextClass($currentClass);

                        if ($newClass) {
                            $kelasBaru = Kelas::where('nama', $newClass)->first();

                            if ($kelasBaru) {
                                $record->update(['kelas_id' => $kelasBaru->id]);
                                $successMessages[] = "Siswa {$record->nama} berhasil dipromosikan ke $newClass.";
                            } else {
                                $errorMessages[] = "Kelas $newClass tidak ditemukan di database untuk siswa {$record->nama}.";
                            }
                        } elseif ($newClass == null) {
                            $record->update(['kelas_id' => null]);
                            $successMessages[] = "Siswa {$record->nama} telah Lulus.";
                        }
                    }

                    // Kirim satu notifikasi untuk semua sukses
                    if (!empty($successMessages)) {
                        Notification::make()
                            ->title('Promosi Berhasil')
                            ->body(implode("\n", $successMessages))
                            ->success()
                            ->send();
                    }

                    // Kirim satu notifikasi untuk semua error
                    if (!empty($errorMessages)) {
                        Notification::make()
                            ->title('Error pada Promosi')
                            ->body(implode("\n", $errorMessages))
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
