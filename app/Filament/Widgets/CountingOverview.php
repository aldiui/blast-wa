<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class CountingOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $siswa = Siswa::count();
        $kelas = Kelas::count();

        return [
            Stat::make('Kelas', $kelas)
            ->icon('heroicon-m-building-office')
            ->chart([1, 6, 10,5,14])
            ->color('primary'),
            Stat::make('Siswa', $siswa)
            ->icon('heroicon-m-user')
            ->chart([1, 6, 10,5,14])
            ->color('success'),
        ];
    }
}
