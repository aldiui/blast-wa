<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Kelas;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'nis' => $this->nis,
            'nama' => $this->nama,
            'orang_tua' => $this->orang_tua,
            'no_telepon' => $this->no_telepon,
            'id_kelas' => $this->id_kelas,
            'alamat' => $this->alamat
        ];
    }
}
