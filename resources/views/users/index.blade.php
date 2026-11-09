@extends('layouts.app')

@section('title', 'Pengguna & Peran')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Pengaturan</span>
            <h1>Pengguna & Peran</h1>
            <p>Kelola akun login dan hak akses: Administrator, Operator Prodi, dan Dosen.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah Pengguna</a>
    </div>

    <div class="stats-grid">
        @foreach ([
            'admin' => ['Administrator', 'Akses penuh ke seluruh data, institusi, referensi, dan pengguna.'],
            'operator' => ['Operator Prodi', 'Mengelola data dosen beserta Tri Dharma pada satu program studi.'],
            'dosen' => ['Dosen', 'Melihat profil sendiri, mengubah biodata, dan mengisi Tri Dharma.'],
        ] as $role => [$label, $keterangan])
            <a href="{{ route('users.index', ['role' => $role]) }}" class="stat-card" style="text-decoration: none; color: inherit; align-items: flex-start">
                <div>
                    <span>{{ $label }}</span>
                    <p class="hint" style="margin: 4px 0 0">{{ $keterangan }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <section class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="filter-form" style="grid-template-columns: 2fr 1fr auto">
                <div class="form-group">
                    <label for="cari">Cari</label>
                    <input type="search" id="cari" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nama atau email">
                </div>
                <div class="form-group">
                    <label for="role">Peran</label>
                    <select id="role" name="role" class="form-control">
                        <option value="">Semua</option>
                        @foreach (\App\Models\User::ROLES as $nilai => $label)
                            <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
                </div>
            </form>
        </div>
    </section>

    <section class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="num">No</th>
                        <th>Nama</th>
                        <th>Peran</th>
                        <th>Cakupan Akses</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="num">{{ $users->firstItem() + $loop->index }}</td>
                            <td>
                                <div class="cell-person">
                                    <span class="avatar">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                    <span>
                                        <strong>{{ $user->name }}</strong>
                                        @if ($user->is(auth()->user())) <span class="badge">Anda</span> @endif
                                        <small>{{ $user->email }}</small>
                                    </span>
                                </div>
                            </td>
                            <td>
                                <span @class([
                                    'badge',
                                    'badge-danger' => $user->isAdmin(),
                                    'badge-primary' => $user->isOperator(),
                                    'badge-success' => $user->isDosen(),
                                ])>{{ $user->role_label }}</span>
                            </td>
                            <td>
                                @if ($user->isAdmin())
                                    Semua data
                                @elseif ($user->isOperator())
                                    {{ $user->programStudi ? 'Prodi '.$user->programStudi->nama_program_studi : '⚠ Prodi belum ditentukan' }}
                                @elseif ($user->dosen)
                                    <a href="{{ route('dosen.show', $user->dosen) }}">{{ $user->dosen->nama_lengkap }}</a>
                                @else
                                    ⚠ Belum terhubung ke data dosen
                                @endif
                            </td>
                            <td class="aksi">
                                <a href="{{ route('users.edit', $user) }}" class="btn-link">Edit</a>
                                @unless ($user->is(auth()->user()))
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                        data-confirm="Hapus akun {{ $user->name }}?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link-danger">Hapus</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">Tidak ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links('partials.pagination') }}
    </section>
@endsection
