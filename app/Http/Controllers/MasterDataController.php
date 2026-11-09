<?php

namespace App\Http\Controllers;

use App\Support\MasterData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Satu controller untuk seluruh tabel referensi sederhana
 * (lihat App\Support\MasterData untuk daftarnya).
 */
class MasterDataController extends Controller
{
    public function index(Request $request, string $jenis): View
    {
        $config = MasterData::get($jenis);
        $model = $config['model'];

        $items = $model::withCount($config['relasi'])->orderBy('id')->get();

        return view('master.index', [
            'jenis' => $jenis,
            'config' => $config,
            'items' => $items,
            'sedangDiedit' => $request->filled('edit') ? $items->firstWhere('id', (int) $request->edit) : null,
        ]);
    }

    public function store(Request $request, string $jenis): RedirectResponse
    {
        $config = MasterData::get($jenis);
        $config['model']::create($this->validasi($request, $config));

        return redirect()->route('master.index', $jenis)
            ->with('success', "{$config['label']} berhasil ditambahkan.");
    }

    public function update(Request $request, string $jenis, int $id): RedirectResponse
    {
        $config = MasterData::get($jenis);
        $item = $config['model']::findOrFail($id);
        $item->update($this->validasi($request, $config, $id));

        return redirect()->route('master.index', $jenis)
            ->with('success', "{$config['label']} berhasil diperbarui.");
    }

    public function destroy(string $jenis, int $id): RedirectResponse
    {
        $config = MasterData::get($jenis);
        $item = $config['model']::findOrFail($id);

        if ($item->{$config['relasi']}()->exists()) {
            return back()->with('error', "\"{$item->{$config['kolom']}}\" masih digunakan oleh data lain, sehingga tidak dapat dihapus.");
        }

        $item->delete();

        return redirect()->route('master.index', $jenis)
            ->with('success', "{$config['label']} berhasil dihapus.");
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    private function validasi(Request $request, array $config, ?int $id = null): array
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', Rule::unique($config['tabel'], $config['kolom'])->ignore($id)],
        ], [], ['nama' => strtolower($config['label'])]);

        return [$config['kolom'] => $data['nama']];
    }
}
