<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
}
