<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StatusSerdos extends Model
{
    protected $table = 'status_serdos';

    protected $fillable = [
        'nama_status_serdos',
    ];

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
