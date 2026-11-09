<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenRequest;
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
use App\Support\KegiatanDosen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DosenController extends Controller
{
    private const FILTER = ['cari', 'fakultas_id', 'program_studi_id', 'jabatan_fungsional_id', 'status_aktivitas_id'];

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Dosen::class);

        $dosens = Dosen::with(['programStudi.fakultas', 'jabatanFungsional', 'statusAktivitas'])
            ->visibleTo($request->user())
            ->filter($request->only(self::FILTER))
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('dosen.index', [
            'dosens' => $dosens,
            'fakultas' => $request->user()->isAdmin() ? Fakultas::orderBy('nama_fakultas')->get() : new Collection,
            'programStudis' => $this->prodiYangBolehDipilih($request->user()),
            'jabatans' => JabatanFungsional::orderBy('id')->get(),
            'statusAktivitas' => StatusAktivitas::orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Dosen::class);

        return view('dosen.form', ['dosen' => new Dosen] + $this->pilihan(new Dosen));
    }

    public function store(DosenRequest $request): RedirectResponse
    {
        $data = $request->safe()->except(['foto', 'hapus_foto']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto-dosen', 'public');
        }

        $dosen = Dosen::create($data);

        return redirect()->route('dosen.show', $dosen)
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen): View
    {
        Gate::authorize('view', $dosen);

        $dosen->load(array_merge(
            Dosen::RELASI_REFERENSI,
            ['programStudi.fakultas.universitas', 'user'],
            array_column(KegiatanDosen::definisi(), 'relasi'),
        ));

        return view('dosen.show', [
            'dosen' => $dosen,
            'kegiatan' => KegiatanDosen::definisi(),
        ]);
    }

    public function edit(Dosen $dosen): View
    {
        Gate::authorize('update', $dosen);

        return view('dosen.form', ['dosen' => $dosen] + $this->pilihan($dosen));
    }

    public function update(DosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $data = $request->safe()->except(['foto', 'hapus_foto']);

        if ($request->hasFile('foto') || $request->boolean('hapus_foto')) {
            $this->hapusFoto($dosen);
            $data['foto'] = $request->hasFile('foto')
                ? $request->file('foto')->store('foto-dosen', 'public')
                : null;
        }

        $dosen->update($data);

        return redirect()->route('dosen.show', $dosen)
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        Gate::authorize('delete', $dosen);

        $this->hapusFoto($dosen);
        $dosen->delete();

        return redirect()->route('dosen.index')
            ->with('success', "Data dosen {$dosen->nama} berhasil dihapus.");
    }

    /**
     * Ekspor daftar dosen (mengikuti filter yang sedang aktif) ke CSV
     * yang dapat dibuka langsung di Microsoft Excel.
     */
    public function export(Request $request): StreamedResponse
    {
        Gate::authorize('viewAny', Dosen::class);

        $query = Dosen::with(Dosen::RELASI_REFERENSI)
            ->visibleTo($request->user())
            ->filter($request->only(self::FILTER))
            ->orderBy('nama');

        $namaFile = 'data-dosen-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            fputcsv($out, [
                'No. Registrasi', 'NIDN', 'NUPTK', 'NIP', 'Nama', 'Jenis Kelamin', 'Email', 'No. HP',
                'Program Studi', 'Fakultas', 'Jabatan Fungsional', 'Kepangkatan', 'Pendidikan',
                'Rumpun Ilmu', 'Status Serdos', 'Status Pegawai', 'Ikatan Kerja', 'Status Aktivitas',
            ], ';');

            $query->chunk(200, function ($dosens) use ($out) {
                foreach ($dosens as $d) {
                    fputcsv($out, [
                        $d->nomor_registrasi, $d->nidn, $d->nuptk, $d->nip, $d->nama_lengkap,
                        ['L' => 'Laki-laki', 'P' => 'Perempuan'][$d->jenis_kelamin] ?? '',
                        $d->email, $d->no_hp,
                        $d->programStudi?->nama_program_studi,
                        $d->programStudi?->fakultas?->nama_fakultas,
                        $d->jabatanFungsional?->nama_jabatan,
                        $d->kepangkatan?->kepangkatan,
                        $d->pendidikan?->nama_pendidikan,
                        $d->rumpunIlmu?->nama_rumpun_ilmu,
                        $d->statusSerdos?->nama_status_serdos,
                        $d->statusPegawai?->nama_status_pegawai,
                        $d->ikatanKerja?->nama_ikatan_kerja,
                        $d->statusAktivitas?->nama_status_aktivitas,
                    ], ';');
                }
            });

            fclose($out);
        }, $namaFile, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Data dropdown untuk formulir tambah/edit dosen.
     *
     * @return array<string, mixed>
     */
    private function pilihan(Dosen $dosen): array
    {
        return [
            // Hanya prodi aktif (kecuali prodi yang sedang dipakai dosen ini);
            // operator hanya dapat memilih prodinya sendiri.
            'programStudis' => ProgramStudi::with('fakultas')
                ->where(fn ($q) => $q->where('status', 'Aktif')->orWhere('id', $dosen->program_studi_id))
                ->when(auth()->user()->isOperator(), fn ($q) => $q->whereKey(auth()->user()->program_studi_id))
                ->orderBy('nama_program_studi')
                ->get(),
            'jabatans' => JabatanFungsional::orderBy('id')->get(),
            'kepangkatans' => Kepangkatan::orderBy('id')->get(),
            'pendidikans' => Pendidikan::orderBy('id')->get(),
            'rumpunIlmus' => RumpunIlmu::orderBy('nama_rumpun_ilmu')->get(),
            'statusSerdos' => StatusSerdos::orderBy('id')->get(),
            'statusPegawais' => StatusPegawai::orderBy('id')->get(),
            'ikatanKerjas' => IkatanKerja::orderBy('id')->get(),
            'statusAktivitas' => StatusAktivitas::orderBy('id')->get(),
        ];
    }

    private function prodiYangBolehDipilih(User $user): Collection
    {
        return ProgramStudi::when(! $user->isAdmin(), fn ($q) => $q->whereKey($user->program_studi_id))
            ->orderBy('nama_program_studi')
            ->get();
    }

    private function hapusFoto(Dosen $dosen): void
    {
        if ($dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }
    }
}
