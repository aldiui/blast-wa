<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use Ramsey\Uuid\Uuid;
use App\Models\Pengaturan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '11221122',
        ]);

        $kelasData = [
            ["nama" => "1 A"],
            ["nama" => "1 B"],
            ["nama" => "1 C"],
            ["nama" => "2 A"],
            ["nama" => "2 B"],
            ["nama" => "2 C"],
            ["nama" => "3 A"],
            ["nama" => "3 B"],
            ["nama" => "3 C"],
            ["nama" => "4 A"],
            ["nama" => "4 B"],
            ["nama" => "4 C"],
            ["nama" => "5 A"],
            ["nama" => "5 B"],
            ["nama" => "5 C"],
            ["nama" => "6 A"],
            ["nama" => "6 B"],
            ["nama" => "6 C"],
        ];

        foreach ($kelasData as $data) {
            Kelas::create([
                'uuid' => Uuid::uuid4()->toString(),
                'nama' => $data['nama']
            ]);
        }

        Pengaturan::create([
            'uuid' => Uuid::uuid4()->toString(),
            'nama' => 'MI Condong',
            'logo' => 'logo.png',
            'email' => 'micondong@gmail.com',
            'no_telepon' => '085710000000',
            'alamat' => 'CirebonKomplek Pesantren Condong 46196 Tasikmalaya Jawa Barat',
        ]);
    }
}