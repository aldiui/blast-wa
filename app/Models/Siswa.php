<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Kelas;
use App\Models\Tabungan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function getRouteKeyName()
    // {
    //     return 'nis';
    // }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class,'id_kelas');
    }
    public function tabungans()
    {
        return $this->hasMany(Tabungan::class,'id_siswa');
    }
    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
}

