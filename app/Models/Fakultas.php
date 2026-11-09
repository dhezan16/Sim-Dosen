<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Fakultas extends Model
{
    protected $table = 'fakultas';

    protected $fillable = [
        'nama_fakultas',
        'universitas_id',
    ];

    public function universitas(): BelongsTo
    {
        return $this->belongsTo(Universitas::class);
    }

    public function programStudi(): HasMany
    {
        return $this->hasMany(ProgramStudi::class);
    }

    public function dosen(): HasManyThrough
    {
        return $this->hasManyThrough(Dosen::class, ProgramStudi::class);
    }
}
