<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Kelas;
use App\Models\Tabungan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function tabungans()
    {
        return $this->hasMany(Tabungan::class, 'id_siswa');
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class, 'id_siswa');
    }
}
