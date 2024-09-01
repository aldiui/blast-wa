<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Pengaturan extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getLogoAttribute()
    {
        return $this->attributes['logo'] ? url('storage/' . $this->attributes['logo']) : null;
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
