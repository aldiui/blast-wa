<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getLogoAttribute()
    {
        return $this->attributes['logo'] ? url('storage/' . $this->attributes['logo']) : null;
    }

}
