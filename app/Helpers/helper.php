<?php

use Carbon\Carbon;
use App\Models\Pengaturan;

if (!function_exists('formatTanggal')) {
    function formatTanggal($tanggal = null, $format = 'l, j F Y')
    {
        $parsedDate = Carbon::parse($tanggal)->locale('id')->settings(['formatFunction' => 'translatedFormat']);
        return $parsedDate->format($format);
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($amount, $mode = null)
    {
        $isNegative = $amount < 0;

        $amount = abs($amount);

        $formatted = $mode == 1 ? number_format($amount, 0, ',', '.') : "Rp. " . number_format($amount, 0, ',', '.');

        if ($isNegative) {
            $formatted = '- ' . $formatted;
        }

        return $formatted;
    }
}

if (!function_exists('getPengaturan')) {

    function getPengaturan()
    {
        return Pengaturan::first();
    }
}

if (!function_exists('cekTahunAjaran')) {
    function cekTahunAjaran($tanggal = null)
    {
        $date = Carbon::parse($tanggal ?? Carbon::now());
        $year = $date->year;
        $academicYearStart = Carbon::create($year, 7, 1);

        if ($date->greaterThanOrEqualTo($academicYearStart)) {
            $startYear = $year;
            $endYear = $year + 1;
        } else {
            $startYear = $year - 1;
            $endYear = $year;
        }

        return "$startYear/$endYear";
    }
}