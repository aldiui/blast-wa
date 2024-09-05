<?php

namespace App\Models;

use App\Models\Kelas;
use App\Models\Siswa;
use Ramsey\Uuid\Uuid;
use App\Models\SetoranDaftarUlang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DaftarUlang extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [''];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function setoranDaftarUlangs(){
        return $this->hasMany(SetoranDaftarUlang::class);
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
