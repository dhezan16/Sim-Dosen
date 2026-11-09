<?php

namespace Tests\Feature;

use App\Models\Dosen;
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
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Hak akses per peran: admin, operator prodi, dosen.
 */
class PeranTest extends TestCase
{
    use RefreshDatabase;

    private ProgramStudi $prodiA;

    private ProgramStudi $prodiB;

    private Dosen $dosenA;

    private Dosen $dosenB;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ReferensiSeeder::class);

        $this->prodiA = ProgramStudi::first();
        $this->prodiB = $this->prodiA->fakultas->programStudi()->create([
            'kode' => 'SI', 'nama_program_studi' => 'Sistem Informasi', 'jenjang' => 'S1', 'status' => 'Aktif',
        ]);

        $this->dosenA = Dosen::create($this->dataDosen($this->prodiA, ['nomor_registrasi' => 'A-1', 'nama' => 'Dosen Prodi A']));
        $this->dosenB = Dosen::create($this->dataDosen($this->prodiB, ['nomor_registrasi' => 'B-1', 'nama' => 'Dosen Prodi B']));
    }

    private function dataDosen(ProgramStudi $prodi, array $timpa = []): array
    {
        return array_merge([
            'nomor_registrasi' => 'REG-X',
            'nama' => 'Dosen Uji',
            'program_studi_id' => $prodi->id,
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

    // ---------- Operator prodi ----------

    public function test_operator_hanya_melihat_dosen_prodinya(): void
    {
        $this->actingAs(User::factory()->operator($this->prodiA->id)->create());

        $this->get('/')->assertOk()->assertSee('Dosen Prodi A')->assertDontSee('Dosen Prodi B');
        $this->get('/dosen')->assertOk()->assertSee('Dosen Prodi A')->assertDontSee('Dosen Prodi B');
        $this->get('/dosen/create')->assertOk()->assertDontSee('Sistem Informasi');
        $this->get("/dosen/{$this->dosenA->id}")->assertOk();
        $this->get("/dosen/{$this->dosenB->id}")->assertForbidden();
        $this->get("/dosen/{$this->dosenB->id}/edit")->assertForbidden();
        $this->delete("/dosen/{$this->dosenB->id}")->assertForbidden();
        $this->post("/dosen/{$this->dosenB->id}/penelitian", ['judul' => 'X', 'tahun' => 2024, 'peran' => 'Ketua'])->assertForbidden();

        $csv = $this->get('/dosen/export')->streamedContent();
        $this->assertStringContainsString('Dosen Prodi A', $csv);
        $this->assertStringNotContainsString('Dosen Prodi B', $csv);
    }

    public function test_operator_hanya_bisa_menambah_dosen_ke_prodinya(): void
    {
        $this->actingAs(User::factory()->operator($this->prodiA->id)->create());

        $this->post('/dosen', $this->dataDosen($this->prodiB, ['nomor_registrasi' => 'N-1']))
            ->assertSessionHasErrors('program_studi_id');

        $this->post('/dosen', $this->dataDosen($this->prodiA, ['nomor_registrasi' => 'N-2']))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('dosen', ['nomor_registrasi' => 'N-2', 'program_studi_id' => $this->prodiA->id]);
    }

    public function test_operator_tidak_bisa_memindahkan_dosen_ke_prodi_lain(): void
    {
        $this->actingAs(User::factory()->operator($this->prodiA->id)->create());

        $this->put("/dosen/{$this->dosenA->id}", $this->dataDosen($this->prodiB, ['nomor_registrasi' => 'A-1', 'nama' => 'Pindah']))
            ->assertSessionHasErrors('program_studi_id');
        $this->assertSame($this->prodiA->id, $this->dosenA->fresh()->program_studi_id);
    }

    public function test_operator_tidak_bisa_membuka_menu_admin(): void
    {
        $this->actingAs(User::factory()->operator($this->prodiA->id)->create());

        foreach (['/fakultas', '/prodi', '/master/kepangkatan', '/users', '/users/create'] as $url) {
            $this->get($url)->assertForbidden();
        }
    }

    // ---------- Dosen ----------

    public function test_dosen_diarahkan_ke_profil_sendiri_dan_tidak_bisa_melihat_dosen_lain(): void
    {
        $this->actingAs(User::factory()->dosen($this->dosenA->id)->create());

        $this->get('/')->assertRedirect(route('dosen.show', $this->dosenA));
        $this->get("/dosen/{$this->dosenA->id}")->assertOk();
        $this->get("/dosen/{$this->dosenB->id}")->assertForbidden();
        $this->get('/dosen')->assertForbidden();
        $this->get('/dosen/create')->assertForbidden();
        $this->get('/dosen/export')->assertForbidden();
        $this->delete("/dosen/{$this->dosenA->id}")->assertForbidden();
        $this->get('/users')->assertForbidden();
    }

    public function test_dosen_hanya_bisa_mengubah_biodata_bukan_data_akademik(): void
    {
        $this->actingAs(User::factory()->dosen($this->dosenA->id)->create());
        $jabatanLain = JabatanFungsional::where('id', '!=', $this->dosenA->jabatan_fungsional_id)->first();

        $this->get("/dosen/{$this->dosenA->id}/edit")->assertOk()->assertSee('Ubah Biodata');

        $this->put("/dosen/{$this->dosenA->id}", [
            'no_hp' => '081234567890',
            'gelar_belakang' => 'M.Kom.',
            // Field akademik berikut harus diabaikan:
            'nama' => 'Nama Diganti',
            'nomor_registrasi' => 'GANTI',
            'jabatan_fungsional_id' => $jabatanLain->id,
            'program_studi_id' => $this->prodiB->id,
        ])->assertSessionHasNoErrors()->assertRedirect(route('dosen.show', $this->dosenA));

        $dosen = $this->dosenA->fresh();
        $this->assertSame('081234567890', $dosen->no_hp);
        $this->assertSame('M.Kom.', $dosen->gelar_belakang);
        $this->assertSame('Dosen Prodi A', $dosen->nama);
        $this->assertSame('A-1', $dosen->nomor_registrasi);
        $this->assertSame($this->prodiA->id, $dosen->program_studi_id);
        $this->assertNotSame($jabatanLain->id, $dosen->jabatan_fungsional_id);
    }

    public function test_dosen_bisa_mengisi_tri_dharma_sendiri_saja(): void
    {
        $this->actingAs(User::factory()->dosen($this->dosenA->id)->create());

        $this->post("/dosen/{$this->dosenA->id}/publikasi", ['judul' => 'Artikel Saya', 'jenis' => 'Jurnal Nasional', 'tahun' => 2025])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('publikasi', ['dosen_id' => $this->dosenA->id, 'judul' => 'Artikel Saya']);

        $this->post("/dosen/{$this->dosenB->id}/publikasi", ['judul' => 'Bukan Milik', 'jenis' => 'Jurnal Nasional', 'tahun' => 2025])
            ->assertForbidden();
    }

    public function test_akun_dosen_yang_belum_terhubung_melihat_pesan(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::DOSEN, 'dosen_id' => null]));

        $this->get('/')->assertOk()->assertSee('belum terhubung');
    }

    // ---------- Manajemen pengguna (admin) ----------

    public function test_admin_dapat_membuat_akun_operator_dan_dosen(): void
    {
        $this->actingAs(User::factory()->admin()->create());

        $this->post('/users', [
            'name' => 'Operator TI', 'email' => 'op@umb.ac.id', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role' => 'operator', 'program_studi_id' => $this->prodiA->id, 'dosen_id' => $this->dosenA->id,
        ])->assertSessionHasNoErrors();

        $operator = User::firstWhere('email', 'op@umb.ac.id');
        $this->assertSame($this->prodiA->id, $operator->program_studi_id);
        $this->assertNull($operator->dosen_id, 'dosen_id harus dikosongkan untuk operator');

        // Operator tanpa prodi ditolak.
        $this->post('/users', [
            'name' => 'X', 'email' => 'x@umb.ac.id', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123', 'role' => 'operator',
        ])->assertSessionHasErrors('program_studi_id');

        $this->post('/users', [
            'name' => 'Dosen A', 'email' => 'dosen.a@umb.ac.id', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role' => 'dosen', 'dosen_id' => $this->dosenA->id,
        ])->assertSessionHasNoErrors();

        // Satu data dosen hanya boleh punya satu akun.
        $this->post('/users', [
            'name' => 'Dosen A2', 'email' => 'dosen.a2@umb.ac.id', 'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
            'role' => 'dosen', 'dosen_id' => $this->dosenA->id,
        ])->assertSessionHasErrors('dosen_id');
    }

    public function test_admin_tidak_bisa_menghapus_atau_menurunkan_peran_dirinya(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin);

        $this->delete("/users/{$admin->id}")->assertSessionHas('error');
        $this->assertModelExists($admin);

        $this->put("/users/{$admin->id}", [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'dosen', 'dosen_id' => $this->dosenA->id,
        ])->assertSessionHas('error');
        $this->assertTrue($admin->fresh()->isAdmin());
    }

    public function test_semua_peran_dapat_mengganti_kata_sandi_sendiri(): void
    {
        $user = User::factory()->dosen($this->dosenA->id)->create();
        $this->actingAs($user);

        $this->put('/akun', ['name' => 'Nama Baru', 'current_password' => 'salah', 'password' => 'baru12345', 'password_confirmation' => 'baru12345'])
            ->assertSessionHasErrors('current_password');

        $this->put('/akun', ['name' => 'Nama Baru', 'current_password' => 'password', 'password' => 'baru12345', 'password_confirmation' => 'baru12345'])
            ->assertSessionHasNoErrors();

        $this->assertTrue(Hash::check('baru12345', $user->fresh()->password));
        $this->assertSame('Nama Baru', $user->fresh()->name);
    }
}
