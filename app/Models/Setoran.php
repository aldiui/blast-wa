<?php

namespace App\Models;

use App\Models\Tabungan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setoran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class,'id_tabungan');
    }
}
