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
            Actions\Action::make('kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('warning')
                ->label('Kembali')
                ->url(fn($record) => '/siswa/' . $record->siswa->uuid . '/edit'),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record->uuid]);
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($record->jenis_tabungan !== $data['jenis_tabungan']) {
            $cekTabungan = Tabungan::where([
                'siswa_id' => $data['siswa_id'],
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
            'siswa_id' => $data['siswa_id'],
            'jenis_tabungan' => $data['jenis_tabungan'],
            'saldo' => $data['saldo'],
        ]);

        if ($data['saldo'] !== $saldoLama) {
            if ($data['saldo'] > $saldoLama) {
                $record->setorans()->create([
                    'tanggal' => date('Y-m-d'),
                    'transaksi' => 'Pemasukan',
                    'nominal' => $data['saldo'] - $saldoLama,
                    'pembayaran' => 'Cash',
                ]);
            } elseif ($data['saldo'] < $saldoLama) {
                $record->setorans()->create([
                    'tanggal' => date('Y-m-d'),
                    'transaksi' => 'Pengeluaran',
                    'nominal' => $saldoLama - $data['saldo'],
                    'pembayaran' => 'Cash',
                ]);
            }
        }

        return $record;
    }
}
