<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Manajemen akun pengguna (khusus admin).
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with(['programStudi', 'dosen'])
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('cari'), fn ($q) => $q->where(fn ($q) => $q
                ->where('name', 'like', "%{$request->cari}%")
                ->orWhere('email', 'like', "%{$request->cari}%")))
            ->orderByRaw("case role when 'admin' then 1 when 'operator' then 2 else 3 end")
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('users.index', ['users' => $users]);
    }

    public function create(Request $request): View
    {
        // Bisa diisi otomatis dari halaman detail dosen: ?role=dosen&dosen_id=1
        $dosen = Dosen::find($request->integer('dosen_id'));

        return view('users.form', [
            'user' => new User([
                'role' => $request->input('role', User::OPERATOR),
                'dosen_id' => $dosen?->id,
                'name' => $dosen?->nama_lengkap,
                'email' => $dosen?->email,
            ]),
        ] + $this->pilihan());
    }

    public function store(Request $request): RedirectResponse
    {
        User::create($this->validasi($request));

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user): View
    {
        return view('users.form', ['user' => $user] + $this->pilihan($user));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validasi($request, $user);

        if ($user->is($request->user()) && $data['role'] !== User::ADMIN) {
            return back()->withInput()->with('error', 'Anda tidak dapat menurunkan peran akun Anda sendiri.');
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Akun {$user->name} berhasil dihapus.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request, ?User $user = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user)],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
            'program_studi_id' => ['nullable', 'required_if:role,'.User::OPERATOR, 'exists:program_studi,id'],
            'dosen_id' => [
                'nullable', 'required_if:role,'.User::DOSEN, 'exists:dosen,id',
                Rule::unique('users', 'dosen_id')->ignore($user),
            ],
        ], [
            'program_studi_id.required_if' => 'Operator wajib memiliki program studi yang dikelola.',
            'dosen_id.required_if' => 'Akun dosen wajib dihubungkan dengan data dosen.',
            'dosen_id.unique' => 'Data dosen ini sudah memiliki akun.',
        ], [
            'name' => 'nama',
            'password' => 'kata sandi',
            'role' => 'peran',
            'program_studi_id' => 'program studi',
            'dosen_id' => 'data dosen',
        ]);

        // Hanya simpan relasi yang sesuai dengan perannya.
        $data['program_studi_id'] = $data['role'] === User::OPERATOR ? $data['program_studi_id'] : null;
        $data['dosen_id'] = $data['role'] === User::DOSEN ? $data['dosen_id'] : null;

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function pilihan(?User $user = null): array
    {
        return [
            'programStudis' => ProgramStudi::orderBy('nama_program_studi')->get(),
            // Dosen yang belum punya akun, ditambah dosen milik akun yang sedang diedit.
            'dosens' => Dosen::where(fn ($q) => $q->doesntHave('user')->orWhereKey($user?->dosen_id))
                ->orderBy('nama')
                ->get(),
        ];
    }
}
