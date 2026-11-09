<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Universitas extends Model
{
    protected $table = 'universitas';

    protected $fillable = [
        'nama_universitas',
    ];

    public function fakultas(): HasMany
    {
        return $this->hasMany(Fakultas::class);
    }
}
