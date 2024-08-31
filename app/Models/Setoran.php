<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setoran extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tabungan()
    {
        return $this->belongsTo(Tabungan::class, 'id_tabungan');
    }
}
