@extends('layouts.app')

@section('title', 'Program Studi')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Institusi</span>
            <h1>Program Studi</h1>
            <p>Daftar program studi beserta fakultas dan jumlah dosennya.</p>
        </div>
        <a href="{{ route('prodi.create') }}" class="btn btn-primary">+ Tambah Prodi</a>
    </div>

    <section class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('prodi.index') }}" class="filter-form" style="grid-template-columns: 2fr 1fr auto">
                <div class="form-group">
                    <label for="cari">Cari</label>
                    <input type="search" id="cari" name="cari" value="{{ request('cari') }}" class="form-control" placeholder="Nama atau kode prodi">
                </div>
                <div class="form-group">
                    <label for="fakultas_id">Fakultas</label>
                    <select id="fakultas_id" name="fakultas_id" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item->id }}" @selected(request('fakultas_id') == $item->id)>{{ $item->nama_fakultas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="actions">
                    <button type="submit" class="btn btn-primary">Cari</button>
                    <a href="{{ route('prodi.index') }}" class="btn btn-outline">Reset</a>
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
                        <th>Kode</th>
                        <th>Nama Program Studi</th>
                        <th>Jenjang</th>
                        <th>Fakultas</th>
                        <th>Universitas</th>
                        <th class="num">Dosen</th>
                        <th>Status</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prodis as $prodi)
                        <tr>
                            <td class="num">{{ $prodis->firstItem() + $loop->index }}</td>
                            <td><strong>{{ $prodi->kode ?? '-' }}</strong></td>
                            <td>{{ $prodi->nama_program_studi }}</td>
                            <td>{{ $prodi->jenjang ?? '-' }}</td>
                            <td>{{ $prodi->fakultas?->nama_fakultas ?? '-' }}</td>
                            <td>{{ $prodi->fakultas?->universitas?->nama_universitas ?? '-' }}</td>
                            <td class="num"><a href="{{ route('dosen.index', ['program_studi_id' => $prodi->id]) }}">{{ $prodi->dosen_count }}</a></td>
                            <td>
                                <span @class(['badge', 'badge-success' => $prodi->status === 'Aktif', 'badge-danger' => $prodi->status !== 'Aktif'])>{{ $prodi->status ?? '-' }}</span>
                            </td>
                            <td class="aksi">
                                <a href="{{ route('prodi.edit', $prodi) }}" class="btn-link">Edit</a>
                                <form action="{{ route('prodi.destroy', $prodi) }}" method="POST" class="inline"
                                    data-confirm="Hapus program studi {{ $prodi->nama_program_studi }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-link-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="empty">Belum ada data program studi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $prodis->links('partials.pagination') }}
    </section>
@endsection
