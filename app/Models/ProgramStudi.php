<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramStudi extends Model
{
    public const JENJANG = ['D3', 'D4', 'S1', 'S2', 'S3', 'Profesi'];

    public const STATUS = ['Aktif', 'Tidak Aktif'];

    protected $table = 'program_studi';

    protected $fillable = [
        'kode',
        'nama_program_studi',
        'fakultas_id',
        'jenjang',
        'status',
    ];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function dosen(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }
}
