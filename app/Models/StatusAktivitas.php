<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusAktivitas extends Model
{
    protected $table = 'status_aktivitas';

    protected $fillable = [
        'nama_status_aktivitas',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
