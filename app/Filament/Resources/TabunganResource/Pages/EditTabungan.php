<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Filament\Resources\TabunganResource;
use App\Models\Tabungan;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditTabungan extends EditRecord
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->getKey()]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($record->jenis_tabungan !== $data['jenis_tabungan']) {
            $cekTabungan = Tabungan::where([
                'id_siswa' => $data['id_siswa'],
                'jenis_tabungan' => $data['jenis_tabungan'],
            ])->first();

            if ($cekTabungan) {
                Notification::make()
                    ->danger()
                    ->title('Tabungan Sudah Ada')
                    ->send();

                return $record;
            }
        }

        $saldoLama = $record->saldo;
        $record->update([
            'id_siswa' => $data['id_siswa'],
            'jenis_tabungan' => $data['jenis_tabungan'],
            'saldo' => $data['saldo'],
        ]);

        if ($data['saldo'] !== $saldoLama) {
            if ($data['saldo'] > $saldoLama) {
                $record->setorans()->create([
                    'transaksi' => 'Pemasukan',
                    'nominal' => $data['saldo'] - $saldoLama,
                ]);
            } elseif ($data['saldo'] < $saldoLama) {
                $record->setorans()->create([
                    'transaksi' => 'Pengeluaran',
                    'nominal' => $saldoLama - $data['saldo'],
                ]);
            }
        }

        return $record;
    }
}
