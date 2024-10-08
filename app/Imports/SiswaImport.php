<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Ramsey\Uuid\Uuid;

class SiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $rowArray = $row->toArray();

            $kelasNama = $rowArray['kelas'] ?? null;
            $kelas = Kelas::where('nama', $kelasNama)->first();
            $idKelas = $kelas ? $kelas->id : null;

            $nis = $rowArray['nis'] ?? null;
            if (Siswa::where('nis', $nis)->exists()) {
                continue;
            }

            Siswa::create([
                'uuid' => Uuid::uuid4()->toString(),
                'kelas_id' => $idKelas ?? '-',
                'nis' => $nis ?? '-',
                'nama' => $rowArray['nama'] ?? '-',
                'orang_tua' => $rowArray['orang_tua'] ?? '-',
                'no_telepon' => $rowArray['no_telepon'] ?? '081930865458',
                'alamat' => $rowArray['alamat'] ?? '-',
            ]);
        }
    }
}
