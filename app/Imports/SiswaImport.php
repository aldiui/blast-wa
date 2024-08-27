<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        return new Siswa([
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'orang_tua' => $row['orang_tua'],
            'no_telepon' => $row['no_telepon'],
            'alamat' => $row['alamat'],
        ]);
    }
}
