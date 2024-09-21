<?php

namespace App\Filament\Resources\TabunganResource\Pages;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Actions;
use App\Models\Tabungan;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TabunganResource;

class ListTabungans extends ListRecords
{
    protected static string $resource = TabunganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),

            // Custom action to generate tabungan by class
            Actions\Action::make('generateTabunganByClass')
                ->label('Generate')
                ->form([
                    Select::make('kelas_id')
                        ->label('Kelas')
                        ->options(Kelas::all()->pluck('nama', 'id')) // Assuming you have a Kelas model
                        ->searchable()
                        ->required(),
                    Select::make('jenis_tabungan')
                        ->label('Jenis Tabungan')
                        ->options([
                            'Tabungan Wajib' => 'Tabungan Wajib',
                            'Tabungan Reguler' => 'Tabungan Reguler',
                        ])
                        ->required(),
                ])
                ->action(function ($data) {
                    DB::transaction(function () use ($data) {
                        $kelas = Kelas::find($data['kelas_id']); // Moved $kelas definition inside the transaction
                        $students = $kelas->siswas; // Assuming Kelas has a relationship 'siswas' with Siswa

                        foreach ($students as $student) {
                            Tabungan::create([
                                'siswa_id' => $student->id,
                                'jenis_tabungan' => $data['jenis_tabungan'],
                                'saldo' => 0, // Default saldo value
                            ]);
                        }

                        // Notify success after the transaction
                        Notification::make()
                        ->title('Success')
                        ->body('Tabungan berhasil ditambahkan pada kelas ' . Kelas::find($data['kelas_id'])->nama)
                        ->success()
                        ->send();
                    });
                })
                ->icon('heroicon-o-document'),
        ];
    }
}

