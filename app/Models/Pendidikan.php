<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendidikan extends Model
{
    protected $table = 'pendidikan';

    protected $fillable = [
        'nama_pendidikan',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
