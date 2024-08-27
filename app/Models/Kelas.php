<?php

namespace App\Models;

use App\Models\Iuran;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Kelas extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];

    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    public function iurans()
    {
        return $this->hasMany(Iuran::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'nama' => $this->nama,
        ];
    }
}
