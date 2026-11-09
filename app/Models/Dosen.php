<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dosen extends Model
{
    protected $table = 'dosen';

    /**
     * Relasi referensi yang hampir selalu ditampilkan bersama data dosen.
     */
    public const RELASI_REFERENSI = [
        'programStudi.fakultas',
        'jabatanFungsional',
        'kepangkatan',
        'pendidikan',
        'rumpunIlmu',
        'statusSerdos',
        'statusPegawai',
        'ikatanKerja',
        'statusAktivitas',
    ];

    protected $fillable = [
        'nomor_registrasi',
        'nuptk',
        'nidn',
        'nip',
        'nama',
        'gelar_depan',
        'gelar_belakang',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'email',
        'no_hp',
        'alamat',
        'bidang_keahlian',
        'tmt_kerja',
        'foto',
        'program_studi_id',
        'jabatan_fungsional_id',
        'kepangkatan_id',
        'pendidikan_id',
        'rumpun_ilmu_id',
        'status_serdos_id',
        'status_pegawai_id',
        'ikatan_kerja_id',
        'status_aktivitas_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tmt_kerja' => 'date',
        ];
    }

    /**
     * Nama beserta gelar, misalnya "Dr. Budi Santoso, M.Kom.".
     */
    public function getNamaLengkapAttribute(): string
    {
        $nama = trim(($this->gelar_depan ? $this->gelar_depan.' ' : '').$this->nama);

        return $this->gelar_belakang ? $nama.', '.$this->gelar_belakang : $nama;
    }

    public function getFotoUrlAttribute(): ?string
    {
        // asset() mengikuti host yang sedang diakses (localhost:8000 / simdosen.test).
        return $this->foto ? asset('storage/'.$this->foto) : null;
    }

    public function getInisialAttribute(): string
    {
        return mb_strtoupper(mb_substr($this->nama, 0, 1));
    }

    /**
     * Pencarian dan filter yang dipakai di daftar dosen dan ekspor CSV.
     *
     * @param  array<string, mixed>  $filter
     */
    public function scopeFilter(Builder $query, array $filter): Builder
    {
        return $query
            ->when($filter['cari'] ?? null, function (Builder $q, string $cari) {
                $q->where(function (Builder $q) use ($cari) {
                    $q->where('nama', 'like', "%{$cari}%")
                        ->orWhere('nuptk', 'like', "%{$cari}%")
                        ->orWhere('nidn', 'like', "%{$cari}%")
                        ->orWhere('nomor_registrasi', 'like', "%{$cari}%")
                        ->orWhere('nip', 'like', "%{$cari}%");
                });
            })
            ->when($filter['fakultas_id'] ?? null, fn (Builder $q, $id) => $q->whereHas(
                'programStudi',
                fn (Builder $p) => $p->where('fakultas_id', $id)
            ))
            ->when($filter['program_studi_id'] ?? null, fn (Builder $q, $id) => $q->where('program_studi_id', $id))
            ->when($filter['jabatan_fungsional_id'] ?? null, fn (Builder $q, $id) => $q->where('jabatan_fungsional_id', $id))
            ->when($filter['status_aktivitas_id'] ?? null, fn (Builder $q, $id) => $q->where('status_aktivitas_id', $id));
    }

    /**
     * Batasi data dosen sesuai peran pengguna:
     * admin melihat semua, operator hanya prodinya, dosen hanya dirinya.
     */
    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        return match ($user->role) {
            User::ADMIN => $query,
            User::OPERATOR => $query->where('program_studi_id', $user->program_studi_id ?? 0),
            default => $query->whereKey($user->dosen_id ?? 0),
        };
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function jabatanFungsional(): BelongsTo
    {
        return $this->belongsTo(JabatanFungsional::class);
    }

    public function kepangkatan(): BelongsTo
    {
        return $this->belongsTo(Kepangkatan::class);
    }

    public function pendidikan(): BelongsTo
    {
        return $this->belongsTo(Pendidikan::class);
    }

    public function rumpunIlmu(): BelongsTo
    {
        return $this->belongsTo(RumpunIlmu::class);
    }

    public function statusSerdos(): BelongsTo
    {
        return $this->belongsTo(StatusSerdos::class);
    }

    public function statusPegawai(): BelongsTo
    {
        return $this->belongsTo(StatusPegawai::class);
    }

    public function ikatanKerja(): BelongsTo
    {
        return $this->belongsTo(IkatanKerja::class);
    }

    public function statusAktivitas(): BelongsTo
    {
        return $this->belongsTo(StatusAktivitas::class);
    }

    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class)->orderByDesc('tahun_lulus');
    }

    public function penelitian(): HasMany
    {
        return $this->hasMany(Penelitian::class)->orderByDesc('tahun');
    }

    public function pengabdian(): HasMany
    {
        return $this->hasMany(Pengabdian::class)->orderByDesc('tahun');
    }

    public function publikasi(): HasMany
    {
        return $this->hasMany(Publikasi::class)->orderByDesc('tahun');
    }
}
