<?php
namespace App\Imports;

use App\Models\Kelas;
use App\Models\User; // Assuming you have a Kelas model that represents the kelas table
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
/**
 * @param array $row
 *
 * @return \Illuminate\Database\Eloquent\Model|null
 */
    public function model(array $row)
    {
        $namaKelas = $row[0];
        $kelas = Kelas::where('name', $namaKelas)->first();

        if ($kelas) {
            $id_kelas = $kelas->id;
        } else {
            $id_kelas = null;
        }

        return new User([
            'id_kelas' => $id_kelas,
            'nis' => $row['nis'],
            'nama' => $row['nama'],
            'orang_tua' => $row['orang_tua'],
            'no_telepon' => $row['no_telepon'],
            'alamat' => $row['alamat'],
        ]);
    }
}
