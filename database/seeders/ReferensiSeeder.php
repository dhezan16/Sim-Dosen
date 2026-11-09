<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\IkatanKerja;
use App\Models\JabatanFungsional;
use App\Models\Kepangkatan;
use App\Models\Pendidikan;
use App\Models\RumpunIlmu;
use App\Models\StatusAktivitas;
use App\Models\StatusPegawai;
use App\Models\StatusSerdos;
use App\Models\Universitas;
use Illuminate\Database\Seeder;

/**
 * Data referensi standar SIM Dosen (mengacu pada istilah PDDikti/SISTER).
 * Hanya menambahkan yang belum ada; data lama tidak diubah.
 */
class ReferensiSeeder extends Seeder
{
    public function run(): void
    {
        $referensi = [
            [JabatanFungsional::class, 'nama_jabatan', [
                'Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Profesor',
            ]],
            [Kepangkatan::class, 'kepangkatan', [
                'Penata Muda (III/a)', 'Penata Muda Tingkat I (III/b)', 'Penata (III/c)', 'Penata Tingkat I (III/d)',
                'Pembina (IV/a)', 'Pembina Tingkat I (IV/b)', 'Pembina Utama Muda (IV/c)',
                'Pembina Utama Madya (IV/d)', 'Pembina Utama (IV/e)',
            ]],
            [Pendidikan::class, 'nama_pendidikan', ['S1', 'S2', 'S3', 'Profesi', 'Spesialis']],
            [RumpunIlmu::class, 'nama_rumpun_ilmu', [
                'Ilmu Komputer', 'Teknik', 'Ilmu Ekonomi', 'Ilmu Pendidikan', 'Ilmu Hukum',
                'Ilmu Kesehatan', 'Ilmu Sosial', 'Ilmu Agama', 'Matematika dan IPA', 'Pertanian',
            ]],
            [StatusSerdos::class, 'nama_status_serdos', ['Belum Sertifikasi', 'Sudah Sertifikasi']],
            [StatusPegawai::class, 'nama_status_pegawai', ['PNS', 'PNS Dipekerjakan (DPK)', 'Tetap Yayasan', 'Kontrak', 'Honorer']],
            [IkatanKerja::class, 'nama_ikatan_kerja', ['Dosen Tetap', 'Dosen Tidak Tetap', 'Dosen Luar Biasa', 'Dosen Tamu']],
            [StatusAktivitas::class, 'nama_status_aktivitas', [
                'Aktif', 'Tugas Belajar', 'Izin Belajar', 'Cuti', 'Studi Lanjut', 'Pensiun', 'Keluar', 'Almarhum',
            ]],
        ];

        foreach ($referensi as [$model, $kolom, $daftar]) {
            foreach ($daftar as $nama) {
                $model::firstOrCreate([$kolom => $nama]);
            }
        }

        // Struktur institusi minimal, hanya jika masih kosong.
        if (Universitas::doesntExist()) {
            $umb = Universitas::create(['nama_universitas' => 'Universitas Muhammadiyah Bengkulu']);

            Fakultas::create(['nama_fakultas' => 'Fakultas Teknik', 'universitas_id' => $umb->id])
                ->programStudi()->create([
                    'kode' => 'TI',
                    'nama_program_studi' => 'Teknik Informatika',
                    'jenjang' => 'S1',
                    'status' => 'Aktif',
                ]);
        }
    }
}
