<?php

use App\Models\Pengaturan;
use Carbon\Carbon;

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
    function cekTahunAjaran()
    {
        $tahunSekarang = now()->year;
        $bulanSekarang = now()->month;

        // Tentukan tahun ajaran berdasarkan bulan sekarang
        if ($bulanSekarang >= 1 && $bulanSekarang <= 6) {
            $tahunAjaranSekarang = ($tahunSekarang - 1) . '/' . $tahunSekarang;
            $mulaiTahun = $tahunSekarang - 1;
        } else {
            $tahunAjaranSekarang = $tahunSekarang . '/' . ($tahunSekarang + 1);
            $mulaiTahun = $tahunSekarang;
        }

        // Hasilkan 15 tahun ajaran terakhir
        $tahunAjaranTerakhir15 = [];
        for ($i = 0; $i < 15; $i++) {
            $tahunAjaran = ($mulaiTahun - $i) . '/' . ($mulaiTahun - $i + 1);
            $tahunAjaranTerakhir15[$tahunAjaran] = $tahunAjaran;
        }
        

        return [
            'tahunAjaranSekarang' => $tahunAjaranSekarang,
            'tahunAjaranTerakhir15' => $tahunAjaranTerakhir15,
        ];
    }
}


if (!function_exists('getNextClass')) {
    function getNextClass($currentClass)
    {
        if (preg_match('/(\d+)\s([A-Z]+)/', $currentClass, $matches)) {
            $currentGrade = (int) $matches[1];
            $currentSection = $matches[2];

            $newGrade = $currentGrade + 1;
            if ($newGrade >= 7) {
                $newGrade = null;
            } else {
                $newGrade = $newGrade . ' ' . $currentSection;
            }
            return $newGrade;
        }
        return null;
    }
}

if (!function_exists('generateBase64Image')) {
    function generateBase64Image($imagePath)
    {
        if (file_exists($imagePath)) {
            $data = file_get_contents($imagePath);
            $type = pathinfo($imagePath, PATHINFO_EXTENSION);
            $base64Image = 'data:image/' . $type . ';base64,' . base64_encode($data);
            \Log::info('Base64 Image Generated: ' . $base64Image); // Tambahkan log ini
            return $base64Image;
        } else {
            \Log::error('File not found: ' . $imagePath); // Tambahkan log ini
            return '';
        }
    }
}

if (!function_exists('getTahunTerakhir')) {
    function getTahunTerakhir($jumlahTahun = 10)
    {
        $tahunSekarang = now()->year;
        $tahunTerakhir = [];

        for ($i = 0; $i < $jumlahTahun; $i++) {
            $tahunTerakhir[] = $tahunSekarang - $i;
        }

        return $tahunTerakhir;
    }
}

