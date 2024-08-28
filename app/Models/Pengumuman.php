<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Pengumuman extends Model
{
    use HasFactory;
    use Searchable;

    protected $guarded = [];
    protected $table = 'pengumuman';
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'tanggal' => $this->tanggal,
        ];
    }

}
