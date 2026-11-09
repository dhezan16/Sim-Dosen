<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publikasi extends Model
{
    protected $table = 'publikasi';

    protected $fillable = [
        'dosen_id',
        'judul',
        'jenis',
        'nama_media',
        'tahun',
        'tautan',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
