<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CountingOverview extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        $siswa = Siswa::count();
        $kelas = Kelas::count();

        return [
            Stat::make('Kelas', $kelas)
                ->icon('heroicon-m-building-office')
                ->chart([1, 6, 10, 5, 14])
                ->color('primary'),
            Stat::make('Siswa', $siswa)
                ->icon('heroicon-m-user')
                ->chart([1, 6, 10, 5, 14])
                ->color('success'),
        ];
    }
}
