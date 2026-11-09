<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengabdian extends Model
{
    protected $table = 'pengabdian';

    protected $fillable = [
        'dosen_id',
        'judul',
        'tahun',
        'lokasi',
        'sumber_dana',
        'jumlah_dana',
        'peran',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
