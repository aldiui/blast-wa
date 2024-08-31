<?php

namespace App\Models;

use App\Models\Setoran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setorans()
    {
        return $this->hasMany(Setoran::class, 'id_tabungan');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

}
