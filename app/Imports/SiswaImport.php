<?php
namespace App\Imports;

use App\Models\Kelas;
use App\Models\User;
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

        // Find the kelas by its name
        $kelas = Kelas::where('nama', $namaKelas)->first();

        // Check if the kelas exists
        $id_kelas = $kelas ? $kelas->id : null;

        // Check if a user with the same NIS already exists
        $existingUser = User::where('nis', $row[1])->first();

        // If the user already exists, do not import the row again
        if ($existingUser) {
            return null;
        }

        // Create a new user instance if it does not exist
        return new User([
            'id_kelas' => $id_kelas,
            'nis' => $row[1],
            'nama' => $row[2],
            'orang_tua' => $row[3],
            'no_telepon' => $row[4] ?? "08787654321",
            'alamat' => $row[5],
        ]);
    }
}
