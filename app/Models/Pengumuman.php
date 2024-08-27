<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
            'tanggal' => $this->tanggal
        ];
    }
    
}
