<?php

namespace App\Filament\Resources\DaftarUlangResource\Pages;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions;
use App\Models\DaftarUlang;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DaftarUlangResource;

class ListDaftarUlangs extends ListRecords
{
    protected static string $resource = DaftarUlangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('daftarUlangByKelas')
                ->label('Tambah Daftar Ulang Berdasar Kelas')
                ->form([
                    Select::make('kelas_id')
                        ->label('Pilih Kelas')
                        ->options(Kelas::all()->pluck('nama', 'id'))
                        ->required()
                        ->searchable(),
                ])
                ->action(function (array $data) {
                    $kelasId = $data['kelas_id'];

                    $siswaList = Siswa::where('kelas_id', $kelasId)->get();

                    foreach ($siswaList as $siswa) {
                        DaftarUlang::create([
                            'siswa_id' => $siswa->id,
                            'kelas_id' => $kelasId,
                            'tahun_ajaran' => cekTahunAjaran(),
                            'tanggal' => now(),
                            'biaya' => getPengaturan()->daftar_ulang,
                            'status' => '0', 
                            'keterangan' => 'Belum Lunas',
                        ]);
                    }

                    Notification::make()
                        ->title('Daftar Ulang Berhasil Ditambahkan')
                        ->body('Daftar Ulang berhasil ditambahkan untuk kelas yang dipilih.')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->color('primary'),

        ];
    }
}
