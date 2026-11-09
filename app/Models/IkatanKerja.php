<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IkatanKerja extends Model
{
    protected $table = 'ikatan_kerja';

    protected $fillable = [
        'nama_ikatan_kerja',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
