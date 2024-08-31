<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class, 'id_kelas');
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
