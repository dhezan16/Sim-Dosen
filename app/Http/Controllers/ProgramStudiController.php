<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramStudiController extends Controller
{
    public function index(Request $request): View
    {
        $prodis = ProgramStudi::with('fakultas.universitas')
            ->withCount('dosen')
            ->when($request->filled('fakultas_id'), fn ($q) => $q->where('fakultas_id', $request->fakultas_id))
            ->when($request->filled('cari'), fn ($q) => $q->where(fn ($q) => $q
                ->where('nama_program_studi', 'like', "%{$request->cari}%")
                ->orWhere('kode', 'like', "%{$request->cari}%")))
            ->orderBy('nama_program_studi')
            ->paginate(15)
            ->withQueryString();

        return view('prodi.index', [
            'prodis' => $prodis,
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
        ]);
    }

    public function create(): View
    {
        return view('prodi.form', [
            'prodi' => new ProgramStudi(['jenjang' => 'S1', 'status' => 'Aktif']),
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        ProgramStudi::create($this->validasi($request));

        return redirect()->route('prodi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit(ProgramStudi $prodi): View
    {
        return view('prodi.form', [
            'prodi' => $prodi,
            'fakultas' => Fakultas::orderBy('nama_fakultas')->get(),
        ]);
    }

    public function update(Request $request, ProgramStudi $prodi): RedirectResponse
    {
        $prodi->update($this->validasi($request, $prodi));

        return redirect()->route('prodi.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy(ProgramStudi $prodi): RedirectResponse
    {
        if ($prodi->dosen()->exists()) {
            return back()->with('error', "Program studi {$prodi->nama_program_studi} masih memiliki dosen. Pindahkan dosen terlebih dahulu atau ubah status prodi menjadi Tidak Aktif.");
        }

        $prodi->delete();

        return redirect()->route('prodi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request, ?ProgramStudi $prodi = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('program_studi')->ignore($prodi)],
            'nama_program_studi' => ['required', 'string', 'max:255'],
            'fakultas_id' => ['required', 'exists:fakultas,id'],
            'jenjang' => ['required', Rule::in(ProgramStudi::JENJANG)],
            'status' => ['required', Rule::in(ProgramStudi::STATUS)],
        ], [], [
            'kode' => 'kode prodi',
            'nama_program_studi' => 'nama program studi',
            'fakultas_id' => 'fakultas',
        ]);
    }
}
