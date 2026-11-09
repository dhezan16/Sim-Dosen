<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\IkatanKerja;
use App\Models\JabatanFungsional;
use App\Models\Kepangkatan;
use App\Models\Pendidikan;
use App\Models\ProgramStudi;
use App\Models\RumpunIlmu;
use App\Models\StatusAktivitas;
use App\Models\StatusPegawai;
use App\Models\StatusSerdos;
use App\Models\User;
use Database\Seeders\ReferensiSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SimDosenTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ReferensiSeeder::class);
        $this->admin = User::factory()->admin()->create();
    }

    private function dataDosen(array $timpa = []): array
    {
        return array_merge([
            'nomor_registrasi' => 'REG-100',
            'nidn' => '0101018501',
            'nama' => 'Siti Aminah',
            'gelar_belakang' => 'M.Kom.',
            'jenis_kelamin' => 'P',
            'email' => 'siti@umb.ac.id',
            'program_studi_id' => ProgramStudi::first()->id,
            'jabatan_fungsional_id' => JabatanFungsional::first()->id,
            'kepangkatan_id' => Kepangkatan::first()->id,
            'pendidikan_id' => Pendidikan::first()->id,
            'rumpun_ilmu_id' => RumpunIlmu::first()->id,
            'status_serdos_id' => StatusSerdos::first()->id,
            'status_pegawai_id' => StatusPegawai::first()->id,
            'ikatan_kerja_id' => IkatanKerja::first()->id,
            'status_aktivitas_id' => StatusAktivitas::first()->id,
        ], $timpa);
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get('/dosen')->assertRedirect(route('login'));
    }

    public function test_admin_dapat_login_dan_logout(): void
    {
        $this->post('/login', ['email' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($this->admin);

        $this->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_login_gagal_dengan_kata_sandi_salah(): void
    {
        $this->post('/login', ['email' => $this->admin->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_semua_halaman_utama_dapat_dibuka(): void
    {
        $dosen = Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        foreach ([
            '/', '/dosen', '/dosen/create', "/dosen/{$dosen->id}", "/dosen/{$dosen->id}/edit",
            '/fakultas', '/fakultas/create', '/prodi', '/prodi/create',
            '/master/universitas', '/master/jabatan-fungsional', '/master/status-aktivitas',
            "/dosen/{$dosen->id}/penelitian/create", "/dosen/{$dosen->id}/publikasi/create",
            '/users', '/users/create', "/users/create?role=dosen&dosen_id={$dosen->id}", '/akun',
        ] as $url) {
            $this->get($url)->assertOk();
        }

        $this->get('/master/tidak-ada')->assertNotFound();
    }

    public function test_crud_dosen_dengan_foto(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $this->post('/dosen', $this->dataDosen(['foto' => UploadedFile::fake()->image('foto.jpg')]))
            ->assertSessionHasNoErrors();

        $dosen = Dosen::firstWhere('nomor_registrasi', 'REG-100');
        $this->assertSame('Siti Aminah, M.Kom.', $dosen->nama_lengkap);
        Storage::disk('public')->assertExists($dosen->foto);

        $this->put("/dosen/{$dosen->id}", $this->dataDosen(['nama' => 'Siti Aminah Putri', 'hapus_foto' => 1]))
            ->assertRedirect(route('dosen.show', $dosen));
        $this->assertNull($dosen->fresh()->foto);
        $this->assertSame('Siti Aminah Putri', $dosen->fresh()->nama);

        $this->delete("/dosen/{$dosen->id}")->assertRedirect(route('dosen.index'));
        $this->assertModelMissing($dosen);
    }

    public function test_validasi_dosen_menolak_data_duplikat(): void
    {
        Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        $this->post('/dosen', $this->dataDosen(['nama' => '']))
            ->assertSessionHasErrors(['nomor_registrasi', 'nidn', 'email', 'nama']);
    }

    public function test_pencarian_dan_filter_dosen(): void
    {
        Dosen::create($this->dataDosen());
        Dosen::create($this->dataDosen([
            'nomor_registrasi' => 'REG-200', 'nidn' => null, 'email' => null, 'nama' => 'Budi Hartono',
        ]));
        $this->actingAs($this->admin);

        $this->get('/dosen?cari=Budi')->assertSee('Budi Hartono')->assertDontSee('Siti Aminah');
        $this->get('/dosen?cari=0101018501')->assertSee('Siti Aminah')->assertDontSee('Budi Hartono');
    }

    public function test_ekspor_csv_dosen(): void
    {
        Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        $response = $this->get('/dosen/export');
        $response->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Siti Aminah', $response->streamedContent());
    }

    public function test_crud_tri_dharma_dosen(): void
    {
        $dosen = Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        $this->post("/dosen/{$dosen->id}/penelitian", [
            'judul' => 'Deteksi Penyakit Tanaman dengan CNN', 'tahun' => 2025, 'peran' => 'Ketua', 'jumlah_dana' => 15000000,
        ])->assertSessionHasNoErrors();
        $this->post("/dosen/{$dosen->id}/publikasi", [
            'judul' => 'Sistem Pakar', 'jenis' => 'Jurnal Nasional Terakreditasi', 'tahun' => 2024,
        ])->assertSessionHasNoErrors();

        $penelitian = $dosen->penelitian()->first();
        $this->assertSame(15000000, (int) $penelitian->jumlah_dana);

        $this->put("/dosen/{$dosen->id}/penelitian/{$penelitian->id}", [
            'judul' => 'Judul Baru', 'tahun' => 2025, 'peran' => 'Anggota',
        ])->assertSessionHasNoErrors();
        $this->assertSame('Anggota', $penelitian->fresh()->peran);

        $this->get("/dosen/{$dosen->id}")->assertSee('Judul Baru')->assertSee('Sistem Pakar');

        // Validasi: jenis publikasi harus dari daftar.
        $this->post("/dosen/{$dosen->id}/publikasi", ['judul' => 'X', 'jenis' => 'Asal', 'tahun' => 2024])
            ->assertSessionHasErrors('jenis');

        $this->delete("/dosen/{$dosen->id}/penelitian/{$penelitian->id}")->assertRedirect();
        $this->assertModelMissing($penelitian);
    }

    public function test_tri_dharma_dosen_lain_tidak_bisa_diubah_lewat_url(): void
    {
        $dosenA = Dosen::create($this->dataDosen());
        $dosenB = Dosen::create($this->dataDosen(['nomor_registrasi' => 'REG-2', 'nidn' => null, 'email' => null]));
        $penelitian = $dosenA->penelitian()->create(['judul' => 'Milik A', 'tahun' => 2024, 'peran' => 'Ketua']);
        $this->actingAs($this->admin);

        $this->delete("/dosen/{$dosenB->id}/penelitian/{$penelitian->id}")->assertNotFound();
        $this->assertModelExists($penelitian);
    }

    public function test_crud_fakultas_dan_prodi(): void
    {
        $this->actingAs($this->admin);
        $universitasId = Fakultas::first()->universitas_id;

        $this->post('/fakultas', ['nama_fakultas' => 'Fakultas Hukum', 'universitas_id' => $universitasId])
            ->assertSessionHasNoErrors();
        $fakultas = Fakultas::firstWhere('nama_fakultas', 'Fakultas Hukum');

        $this->post('/prodi', [
            'kode' => 'IH', 'nama_program_studi' => 'Ilmu Hukum', 'fakultas_id' => $fakultas->id,
            'jenjang' => 'S1', 'status' => 'Aktif',
        ])->assertSessionHasNoErrors();
        $prodi = ProgramStudi::firstWhere('kode', 'IH');

        // Fakultas yang masih punya prodi tidak boleh dihapus.
        $this->delete("/fakultas/{$fakultas->id}")->assertSessionHas('error');
        $this->assertModelExists($fakultas);

        $this->put("/prodi/{$prodi->id}", [
            'kode' => 'IH', 'nama_program_studi' => 'Ilmu Hukum', 'fakultas_id' => $fakultas->id,
            'jenjang' => 'S2', 'status' => 'Tidak Aktif',
        ])->assertSessionHasNoErrors();
        $this->assertSame('S2', $prodi->fresh()->jenjang);

        $this->delete("/prodi/{$prodi->id}")->assertSessionHas('success');
        $this->delete("/fakultas/{$fakultas->id}")->assertSessionHas('success');
        $this->assertModelMissing($fakultas);
    }

    public function test_prodi_yang_memiliki_dosen_tidak_bisa_dihapus(): void
    {
        $dosen = Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        $this->delete("/prodi/{$dosen->program_studi_id}")->assertSessionHas('error');
        $this->assertModelExists($dosen->programStudi);
    }

    public function test_crud_master_data(): void
    {
        $this->actingAs($this->admin);

        $this->post('/master/jabatan-fungsional', ['nama' => 'Jabatan Uji'])->assertSessionHasNoErrors();
        $jabatan = JabatanFungsional::firstWhere('nama_jabatan', 'Jabatan Uji');

        $this->post('/master/jabatan-fungsional', ['nama' => 'Jabatan Uji'])->assertSessionHasErrors('nama');

        $this->put("/master/jabatan-fungsional/{$jabatan->id}", ['nama' => 'Jabatan Uji 2'])->assertSessionHasNoErrors();
        $this->assertSame('Jabatan Uji 2', $jabatan->fresh()->nama_jabatan);

        $this->delete("/master/jabatan-fungsional/{$jabatan->id}")->assertSessionHas('success');
        $this->assertModelMissing($jabatan);
    }

    public function test_master_data_yang_dipakai_dosen_tidak_bisa_dihapus(): void
    {
        $dosen = Dosen::create($this->dataDosen());
        $this->actingAs($this->admin);

        $this->delete("/master/kepangkatan/{$dosen->kepangkatan_id}")->assertSessionHas('error');
        $this->assertModelExists($dosen->kepangkatan);
    }
}
