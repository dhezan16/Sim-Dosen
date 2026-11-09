@extends('layouts.app')

@section('title', 'Data Dosen')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Data Dosen</span>
            <h1>Daftar Dosen</h1>
            <p>Kelola data identitas, kepegawaian, dan Tri Dharma dosen.</p>
        </div>
        <div class="actions">
            <a href="{{ route('dosen.export', request()->query()) }}" class="btn btn-outline">⬇ Ekspor CSV</a>
            @can('create', \App\Models\Dosen::class)
                <a href="{{ route('dosen.create') }}" class="btn btn-primary">+ Tambah Dosen</a>
            @endcan
        </div>
    </div>

    <section class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('dosen.index') }}" class="filter-form">
                <div class="form-group">
                    <label for="cari">Cari Dosen</label>
                    <input type="search" id="cari" name="cari" value="{{ request('cari') }}" class="form-control"
                        placeholder="Nama, NIDN, NUPTK, NIP, atau No. Registrasi">
                </div>

                @foreach ([
                    ['fakultas_id', 'Fakultas', $fakultas, 'nama_fakultas'],
                    ['program_studi_id', 'Program Studi', $programStudis, 'nama_program_studi'],
                    ['jabatan_fungsional_id', 'Jabatan', $jabatans, 'nama_jabatan'],
                    ['status_aktivitas_id', 'Status', $statusAktivitas, 'nama_status_aktivitas'],
                ] as [$nama, $label, $items, $kolom])
                    @continue($items->count() <= 1 && $nama !== 'jabatan_fungsional_id' && $nama !== 'status_aktivitas_id')
                    <div class="form-group">
                        <label for="{{ $nama }}">{{ $label }}</label>
                        <select id="{{ $nama }}" name="{{ $nama }}" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" @selected(request($nama) == $item->id)>{{ $item->{$kolom} }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('dosen.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Dosen</th>
                        <th>NIDN / NUPTK</th>
                        <th>Program Studi</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dosens as $dosen)
                        <tr>
                            <td class="num">{{ $dosens->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ route('dosen.show', $dosen) }}" class="cell-person">
                                    <span class="avatar">
                                        @if ($dosen->foto_url)<img src="{{ $dosen->foto_url }}" alt="">@else{{ $dosen->inisial }}@endif
                                    </span>
                                    <span>
                                        <strong>{{ $dosen->nama_lengkap }}</strong>
                                        <small>{{ $dosen->nomor_registrasi }}</small>
                                    </span>
                                </a>
                            </td>
                            <td>
                                {{ $dosen->nidn ?? '-' }}
                                <small class="hint" style="display:block">{{ $dosen->nuptk ?? '-' }}</small>
                            </td>
                            <td>
                                {{ $dosen->programStudi?->nama_program_studi ?? '-' }}
                                <small class="hint" style="display:block">{{ $dosen->programStudi?->fakultas?->nama_fakultas }}</small>
                            </td>
                            <td>{{ $dosen->jabatanFungsional?->nama_jabatan ?? '-' }}</td>
                            <td>
                                @php($status = $dosen->statusAktivitas?->nama_status_aktivitas)
                                <span @class(['badge', 'badge-success' => $status === 'Aktif', 'badge-warning' => $status && $status !== 'Aktif'])>{{ $status ?? '-' }}</span>
                            </td>
                            <td class="aksi">
                                <a href="{{ route('dosen.show', $dosen) }}" class="btn-link">Detail</a>
                                @can('update', $dosen)
                                    <a href="{{ route('dosen.edit', $dosen) }}" class="btn-link">Edit</a>
                                @endcan
                                @can('delete', $dosen)
                                    <form action="{{ route('dosen.destroy', $dosen) }}" method="POST" class="inline"
                                        data-confirm="Hapus data dosen {{ $dosen->nama }} beserta seluruh riwayat dan Tri Dharma-nya?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">
                                {{ request()->hasAny(['cari', 'fakultas_id', 'program_studi_id', 'jabatan_fungsional_id', 'status_aktivitas_id']) ? 'Tidak ada dosen yang sesuai dengan pencarian.' : 'Belum ada data dosen.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $dosens->links('partials.pagination') }}
    </section>
@endsection
