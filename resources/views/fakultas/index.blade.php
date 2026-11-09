@extends('layouts.app')

@section('title', 'Fakultas')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Institusi</span>
            <h1>Fakultas</h1>
            <p>Daftar fakultas beserta jumlah program studi dan dosennya.</p>
        </div>
        <a href="{{ route('fakultas.create') }}" class="btn btn-primary">+ Tambah Fakultas</a>
    </div>

    <section class="card">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th class="num">No</th>
                        <th>Nama Fakultas</th>
                        <th>Universitas</th>
                        <th class="num">Prodi</th>
                        <th class="num">Dosen</th>
                        <th class="aksi">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($fakultas as $item)
                        <tr>
                            <td class="num">{{ $loop->iteration }}</td>
                            <td><strong>{{ $item->nama_fakultas }}</strong></td>
                            <td>{{ $item->universitas?->nama_universitas ?? '-' }}</td>
                            <td class="num"><a href="{{ route('prodi.index', ['fakultas_id' => $item->id]) }}">{{ $item->program_studi_count }}</a></td>
                            <td class="num"><a href="{{ route('dosen.index', ['fakultas_id' => $item->id]) }}">{{ $item->dosen_count }}</a></td>
                            <td class="aksi">
                                <a href="{{ route('fakultas.edit', $item) }}" class="btn-link">Edit</a>
                                <form action="{{ route('fakultas.destroy', $item) }}" method="POST" class="inline"
                                    data-confirm="Hapus fakultas {{ $item->nama_fakultas }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-link-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Belum ada data fakultas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
