<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Kelas;
use Ramsey\Uuid\Uuid;
use App\Models\Tabungan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tabungans()
    {
        return $this->hasMany(Tabungan::class);
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
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
