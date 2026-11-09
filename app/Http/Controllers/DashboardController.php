<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JabatanFungsional;
use App\Models\Pendidikan;
use App\Models\Penelitian;
use App\Models\Pengabdian;
use App\Models\ProgramStudi;
use App\Models\Publikasi;
use App\Models\StatusAktivitas;
use App\Models\StatusSerdos;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Dosen langsung diarahkan ke profilnya sendiri.
        if ($user->isDosen()) {
            return $user->dosen_id
                ? redirect()->route('dosen.show', $user->dosen_id)
                : view('akun.belum-terhubung');
        }

        $milikDosenTerlihat = fn ($q) => $q->whereHas('dosen', fn ($d) => $d->visibleTo($user));

        return view('dashboard', [
            'ringkasan' => [
                'dosen' => Dosen::visibleTo($user)->count(),
                'fakultas' => Fakultas::count(),
                'prodi' => ProgramStudi::count(),
                'penelitian' => Penelitian::where($milikDosenTerlihat)->count(),
                'pengabdian' => Pengabdian::where($milikDosenTerlihat)->count(),
                'publikasi' => Publikasi::where($milikDosenTerlihat)->count(),
            ],
            'sebaran' => [
                'Jabatan Fungsional' => $this->sebaran(JabatanFungsional::class, 'nama_jabatan', $user),
                'Pendidikan Terakhir' => $this->sebaran(Pendidikan::class, 'nama_pendidikan', $user),
                'Status Sertifikasi' => $this->sebaran(StatusSerdos::class, 'nama_status_serdos', $user),
                'Status Aktivitas' => $this->sebaran(StatusAktivitas::class, 'nama_status_aktivitas', $user),
            ],
            'perProdi' => ProgramStudi::with('fakultas')
                ->when($user->isOperator(), fn ($q) => $q->whereKey($user->program_studi_id))
                ->withCount('dosen')
                ->orderByDesc('dosen_count')
                ->limit(8)
                ->get(),
            'dosenTerbaru' => Dosen::with('programStudi')->visibleTo($user)->latest()->limit(5)->get(),
        ]);
    }

    /**
     * Jumlah dosen (yang boleh dilihat pengguna) per item referensi.
     *
     * @param  class-string<Model>  $model
     * @return Collection<string, int>
     */
    private function sebaran(string $model, string $kolom, User $user): Collection
    {
        return $model::withCount(['dosen' => fn ($q) => $q->visibleTo($user)])
            ->orderBy('id')
            ->get()
            ->mapWithKeys(fn (Model $item) => [$item->{$kolom} => $item->dosen_count]);
    }
}
