<?php

namespace App\Http\Controllers;

use App\Models\Fakultas;
use App\Models\Universitas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FakultasController extends Controller
{
    public function index(): View
    {
        return view('fakultas.index', [
            'fakultas' => Fakultas::with('universitas')
                ->withCount(['programStudi', 'dosen'])
                ->orderBy('nama_fakultas')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('fakultas.form', [
            'fakultas' => new Fakultas,
            'universitas' => Universitas::orderBy('nama_universitas')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Fakultas::create($this->validasi($request));

        return redirect()->route('fakultas.index')
            ->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakultas): View
    {
        return view('fakultas.form', [
            'fakultas' => $fakultas,
            'universitas' => Universitas::orderBy('nama_universitas')->get(),
        ]);
    }

    public function update(Request $request, Fakultas $fakultas): RedirectResponse
    {
        $fakultas->update($this->validasi($request, $fakultas));

        return redirect()->route('fakultas.index')
            ->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakultas): RedirectResponse
    {
        if ($fakultas->programStudi()->exists()) {
            return back()->with('error', "Fakultas {$fakultas->nama_fakultas} masih memiliki program studi, sehingga tidak dapat dihapus.");
        }

        $fakultas->delete();

        return redirect()->route('fakultas.index')
            ->with('success', 'Fakultas berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request, ?Fakultas $fakultas = null): array
    {
        return $request->validate([
            'nama_fakultas' => ['required', 'string', 'max:255', Rule::unique('fakultas')->ignore($fakultas)],
            'universitas_id' => ['required', 'exists:universitas,id'],
        ], [], [
            'nama_fakultas' => 'nama fakultas',
            'universitas_id' => 'universitas',
        ]);
    }
}
