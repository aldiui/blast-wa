<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Kelas extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
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
