<?php

namespace App\Support;

use App\Models\IkatanKerja;
use App\Models\JabatanFungsional;
use App\Models\Kepangkatan;
use App\Models\Pendidikan;
use App\Models\RumpunIlmu;
use App\Models\StatusAktivitas;
use App\Models\StatusPegawai;
use App\Models\StatusSerdos;
use App\Models\Universitas;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Daftar tabel referensi sederhana (satu kolom nama) yang dikelola
 * melalui satu controller generik: MasterDataController.
 */
class MasterData
{
    /**
     * @var array<string, array{model: class-string<Model>, kolom: string, label: string, relasi: string, keterangan: string}>
     */
    public const JENIS = [
        'universitas' => [
            'model' => Universitas::class,
            'kolom' => 'nama_universitas',
            'label' => 'Universitas',
            'relasi' => 'fakultas',
            'keterangan' => 'Perguruan tinggi induk dari fakultas.',
        ],
        'jabatan-fungsional' => [
            'model' => JabatanFungsional::class,
            'kolom' => 'nama_jabatan',
            'label' => 'Jabatan Fungsional',
            'relasi' => 'dosen',
            'keterangan' => 'Jenjang jabatan akademik dosen (Asisten Ahli s.d. Guru Besar).',
        ],
        'kepangkatan' => [
            'model' => Kepangkatan::class,
            'kolom' => 'kepangkatan',
            'label' => 'Kepangkatan',
            'relasi' => 'dosen',
            'keterangan' => 'Pangkat dan golongan dosen.',
        ],
        'pendidikan' => [
            'model' => Pendidikan::class,
            'kolom' => 'nama_pendidikan',
            'label' => 'Pendidikan',
            'relasi' => 'dosen',
            'keterangan' => 'Jenjang pendidikan terakhir.',
        ],
        'rumpun-ilmu' => [
            'model' => RumpunIlmu::class,
            'kolom' => 'nama_rumpun_ilmu',
            'label' => 'Rumpun Ilmu',
            'relasi' => 'dosen',
            'keterangan' => 'Bidang atau rumpun keilmuan dosen.',
        ],
        'status-serdos' => [
            'model' => StatusSerdos::class,
            'kolom' => 'nama_status_serdos',
            'label' => 'Status Serdos',
            'relasi' => 'dosen',
            'keterangan' => 'Status sertifikasi dosen.',
        ],
        'status-pegawai' => [
            'model' => StatusPegawai::class,
            'kolom' => 'nama_status_pegawai',
            'label' => 'Status Pegawai',
            'relasi' => 'dosen',
            'keterangan' => 'Status kepegawaian (PNS, Tetap Yayasan, dll.).',
        ],
        'ikatan-kerja' => [
            'model' => IkatanKerja::class,
            'kolom' => 'nama_ikatan_kerja',
            'label' => 'Ikatan Kerja',
            'relasi' => 'dosen',
            'keterangan' => 'Jenis ikatan kerja dosen dengan institusi.',
        ],
        'status-aktivitas' => [
            'model' => StatusAktivitas::class,
            'kolom' => 'nama_status_aktivitas',
            'label' => 'Status Aktivitas',
            'relasi' => 'dosen',
            'keterangan' => 'Status keaktifan dosen (Aktif, Tugas Belajar, Cuti, dll.).',
        ],
    ];

    /**
     * @return array{model: class-string<Model>, kolom: string, label: string, relasi: string, keterangan: string, tabel: string}
     */
    public static function get(string $jenis): array
    {
        $config = self::JENIS[$jenis] ?? throw new NotFoundHttpException;

        return $config + ['tabel' => (new $config['model'])->getTable()];
    }
}
