<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RumpunIlmu extends Model
{
    protected $table = 'rumpun_ilmu';

    protected $fillable = [
        'nama_rumpun_ilmu',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
