<?php

namespace App\Models;

use App\Models\Siswa;
use App\Models\Setoran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tabungan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function setorans()
    {
        return $this->hasMany(Setoran::class,'id_tabungan');
    }
    public function siswa(){
        return $this->belongsTo(Siswa::class,'id_siswa');
    }

}
