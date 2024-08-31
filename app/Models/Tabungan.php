<?php

namespace App\Models;

use App\Models\Siswa;
use Ramsey\Uuid\Uuid;
use App\Models\Setoran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tabungan extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function setorans()
    {
        return $this->hasMany(Setoran::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
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
