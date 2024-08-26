<?php

namespace App\Models;

use App\Models\Setoran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tabungan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function setorans()
    {
        return $this->hasMany(Setoran::class);
    }
}
