<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class, 'id_kelas');
    }

}
