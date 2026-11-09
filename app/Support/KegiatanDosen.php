<?php

namespace App\Support;

use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\Publikasi;
use App\Models\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Definisi data turunan milik dosen (riwayat pendidikan dan Tri Dharma).
 * Formulir, tabel, dan aturan validasi dibangun otomatis dari definisi ini
 * sehingga satu controller dan satu view cukup untuk semua jenis.
 */
class KegiatanDosen
{
    public const PERAN = ['Ketua', 'Anggota'];

    public const JENJANG = ['D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Spesialis'];

    public const JENIS_PUBLIKASI = [
        'Jurnal Internasional Bereputasi',
        'Jurnal Internasional',
        'Jurnal Nasional Terakreditasi',
        'Jurnal Nasional',
        'Prosiding Internasional',
        'Prosiding Nasional',
        'Buku',
        'HKI / Paten',
    ];

    /**
     * Setiap field: label, type (text|number|year|select|url), required,
     * options (untuk select), tabel (tampil sebagai kolom di halaman detail).
     */
    public static function definisi(): array
    {
        return [
            'riwayat-pendidikan' => [
                'model' => RiwayatPendidikan::class,
                'relasi' => 'riwayatPendidikan',
                'label' => 'Riwayat Pendidikan',
                'fields' => [
                    'jenjang' => ['label' => 'Jenjang', 'type' => 'select', 'required' => true, 'options' => self::JENJANG, 'tabel' => true],
                    'nama_institusi' => ['label' => 'Nama Institusi', 'type' => 'text', 'required' => true, 'tabel' => true],
                    'program_studi' => ['label' => 'Program Studi', 'type' => 'text', 'required' => false, 'tabel' => true],
                    'tahun_lulus' => ['label' => 'Tahun Lulus', 'type' => 'year', 'required' => false, 'tabel' => true],
                ],
            ],
            'penelitian' => [
                'model' => Penelitian::class,
                'relasi' => 'penelitian',
                'label' => 'Penelitian',
                'fields' => [
                    'judul' => ['label' => 'Judul Penelitian', 'type' => 'text', 'required' => true, 'tabel' => true],
                    'tahun' => ['label' => 'Tahun', 'type' => 'year', 'required' => true, 'tabel' => true],
                    'skema' => ['label' => 'Skema', 'type' => 'text', 'required' => false, 'tabel' => true],
                    'sumber_dana' => ['label' => 'Sumber Dana', 'type' => 'text', 'required' => false, 'tabel' => false],
                    'jumlah_dana' => ['label' => 'Jumlah Dana (Rp)', 'type' => 'number', 'required' => false, 'tabel' => true],
                    'peran' => ['label' => 'Peran', 'type' => 'select', 'required' => true, 'options' => self::PERAN, 'tabel' => true],
                ],
            ],
            'pengabdian' => [
                'model' => Pengabdian::class,
                'relasi' => 'pengabdian',
                'label' => 'Pengabdian Masyarakat',
                'fields' => [
                    'judul' => ['label' => 'Judul Kegiatan', 'type' => 'text', 'required' => true, 'tabel' => true],
                    'tahun' => ['label' => 'Tahun', 'type' => 'year', 'required' => true, 'tabel' => true],
                    'lokasi' => ['label' => 'Lokasi', 'type' => 'text', 'required' => false, 'tabel' => true],
                    'sumber_dana' => ['label' => 'Sumber Dana', 'type' => 'text', 'required' => false, 'tabel' => false],
                    'jumlah_dana' => ['label' => 'Jumlah Dana (Rp)', 'type' => 'number', 'required' => false, 'tabel' => true],
                    'peran' => ['label' => 'Peran', 'type' => 'select', 'required' => true, 'options' => self::PERAN, 'tabel' => true],
                ],
            ],
            'publikasi' => [
                'model' => Publikasi::class,
                'relasi' => 'publikasi',
                'label' => 'Publikasi',
                'fields' => [
                    'judul' => ['label' => 'Judul Publikasi', 'type' => 'text', 'required' => true, 'tabel' => true],
                    'jenis' => ['label' => 'Jenis', 'type' => 'select', 'required' => true, 'options' => self::JENIS_PUBLIKASI, 'tabel' => true],
                    'nama_media' => ['label' => 'Nama Jurnal / Penerbit', 'type' => 'text', 'required' => false, 'tabel' => true],
                    'tahun' => ['label' => 'Tahun', 'type' => 'year', 'required' => true, 'tabel' => true],
                    'tautan' => ['label' => 'Tautan (URL / DOI)', 'type' => 'url', 'required' => false, 'tabel' => false],
                ],
            ],
        ];
    }

    /**
     * @return array{model: class-string<Model>, relasi: string, label: string, fields: array<string, array>}
     */
    public static function get(string $jenis): array
    {
        return self::definisi()[$jenis] ?? throw new NotFoundHttpException;
    }

    /**
     * Aturan validasi yang dibangun dari definisi field.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function rules(string $jenis): array
    {
        $rules = [];

        foreach (self::get($jenis)['fields'] as $nama => $field) {
            $rule = [$field['required'] ? 'required' : 'nullable'];

            $rule = array_merge($rule, match ($field['type']) {
                'year' => ['integer', 'min:1950', 'max:'.(date('Y') + 5)],
                'number' => ['integer', 'min:0'],
                'url' => ['url', 'max:255'],
                'select' => [Rule::in($field['options'])],
                default => ['string', 'max:500'],
            });

            $rules[$nama] = $rule;
        }

        return $rules;
    }
}
