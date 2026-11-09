<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JabatanFungsional extends Model
{
    protected $table = 'jabatan_fungsional';

    protected $fillable = [
        'nama_jabatan',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
