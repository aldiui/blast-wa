<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use App\Models\DaftarUlang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SetoranDaftarUlang extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function daftarUlang(){
        return $this->belongsTo(DaftarUlang::class, 'daftar_ulang_id');
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
