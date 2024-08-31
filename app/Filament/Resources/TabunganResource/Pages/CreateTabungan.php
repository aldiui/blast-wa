<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Filament\Resources\TabunganResource;
use App\Models\Tabungan;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTabungan extends CreateRecord
{
    protected static string $resource = TabunganResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $cekTabungan = Tabungan::where([
            'siswa_id' => $data['siswa_id'],
            'jenis_tabungan' => $data['jenis_tabungan'],
        ])->first();

        if ($cekTabungan) {
            Notification::make()
                ->danger()
                ->title('Tabungan Sudah Ada')
                ->send();

            return $cekTabungan;
        }

        $tabungan = Tabungan::create([
            'siswa_id' => $data['siswa_id'],
            'jenis_tabungan' => $data['jenis_tabungan'],
            'saldo' => $data['saldo'],
        ]);

        if ($tabungan && $data['saldo'] > 0) {
            $tabungan->setorans()->create([
                'transaksi' => 'Pemasukan',
                'nominal' => $data['saldo'],
            ]);
        }

        return $tabungan;
    }
}
