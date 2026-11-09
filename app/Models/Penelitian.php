<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penelitian extends Model
{
    protected $table = 'penelitian';

    protected $fillable = [
        'dosen_id',
        'judul',
        'tahun',
        'skema',
        'sumber_dana',
        'jumlah_dana',
        'peran',
    ];

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }
}
