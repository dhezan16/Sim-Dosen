<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Support\KegiatanDosen;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * CRUD data turunan dosen: riwayat pendidikan, penelitian,
 * pengabdian masyarakat, dan publikasi.
 */
class KegiatanDosenController extends Controller
{
    public function create(Dosen $dosen, string $jenis): View
    {
        Gate::authorize('update', $dosen);
        $config = KegiatanDosen::get($jenis);

        return view('kegiatan.form', [
            'dosen' => $dosen,
            'jenis' => $jenis,
            'config' => $config,
            'item' => new $config['model'],
        ]);
    }

    public function store(Request $request, Dosen $dosen, string $jenis): RedirectResponse
    {
        Gate::authorize('update', $dosen);
        $config = KegiatanDosen::get($jenis);
        $dosen->{$config['relasi']}()->create($this->validasi($request, $jenis, $config));

        return $this->kembali($dosen, $jenis, "{$config['label']} berhasil ditambahkan.");
    }

    public function edit(Dosen $dosen, string $jenis, int $id): View
    {
        Gate::authorize('update', $dosen);
        $config = KegiatanDosen::get($jenis);

        return view('kegiatan.form', [
            'dosen' => $dosen,
            'jenis' => $jenis,
            'config' => $config,
            'item' => $this->cari($dosen, $config, $id),
        ]);
    }

    public function update(Request $request, Dosen $dosen, string $jenis, int $id): RedirectResponse
    {
        Gate::authorize('update', $dosen);
        $config = KegiatanDosen::get($jenis);
        $this->cari($dosen, $config, $id)->update($this->validasi($request, $jenis, $config));

        return $this->kembali($dosen, $jenis, "{$config['label']} berhasil diperbarui.");
    }

    public function destroy(Dosen $dosen, string $jenis, int $id): RedirectResponse
    {
        Gate::authorize('update', $dosen);
        $config = KegiatanDosen::get($jenis);
        $this->cari($dosen, $config, $id)->delete();

        return $this->kembali($dosen, $jenis, "{$config['label']} berhasil dihapus.");
    }

    /**
     * Data hanya dicari di dalam milik dosen tersebut, sehingga ID milik
     * dosen lain tidak bisa diubah lewat URL.
     *
     * @param  array<string, mixed>  $config
     */
    private function cari(Dosen $dosen, array $config, int $id): Model
    {
        return $dosen->{$config['relasi']}()->findOrFail($id);
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private function validasi(Request $request, string $jenis, array $config): array
    {
        return $request->validate(
            KegiatanDosen::rules($jenis),
            [],
            array_map(fn (array $f) => strtolower($f['label']), $config['fields']),
        );
    }

    private function kembali(Dosen $dosen, string $jenis, string $pesan): RedirectResponse
    {
        return redirect()->to(route('dosen.show', $dosen).'#'.$jenis)->with('success', $pesan);
    }
}
