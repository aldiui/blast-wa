<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->label('Upload Excel')
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
                ->action(function () {
                    $records = Siswa::all();
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
                            } elseif($newClass == 'Lulus') {
                                $record->update(['kelas_id' => null ]);
                                $successMessages[] = "Siswa {$record->nama} berhasil dipromosikan ke $newClass.";
                            } else {
                                $errorMessages[] = "Kelas $newClass tidak ditemukan di database untuk siswa {$record->nama}.";
                            }
                        } else {
                            $errorMessages[] = "Format kelas tidak valid untuk siswa {$record->nama}.";
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
