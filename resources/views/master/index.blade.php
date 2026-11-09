@extends('layouts.app')

@section('title', $config['label'])

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">{{ $jenis === 'universitas' ? 'Institusi' : 'Data Referensi' }}</span>
            <h1>{{ $config['label'] }}</h1>
            <p>{{ $config['keterangan'] }}</p>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns: minmax(280px, 1fr) 2fr; align-items: start">
        <section class="card">
            <div class="card-header">
                <h2>{{ $sedangDiedit ? 'Edit' : 'Tambah' }} {{ $config['label'] }}</h2>
            </div>
            <div class="card-body">
                <form method="POST"
                    action="{{ $sedangDiedit ? route('master.update', [$jenis, $sedangDiedit->id]) : route('master.store', $jenis) }}">
                    @csrf
                    @if ($sedangDiedit) @method('PUT') @endif

                    <div class="form-group">
                        <label for="nama">Nama {{ $config['label'] }} <span class="required">*</span></label>
                        <input type="text" id="nama" name="nama" required autofocus
                            value="{{ old('nama', $sedangDiedit?->{$config['kolom']}) }}"
                            @class(['form-control', 'is-invalid' => $errors->has('nama')])>
                        @error('nama') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="actions" style="margin-top: 16px">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        @if ($sedangDiedit)
                            <a href="{{ route('master.index', $jenis) }}" class="btn btn-outline">Batal</a>
                        @endif
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
                            <th class="num">Dipakai</th>
                            <th class="aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr @style(['background: var(--primary-soft)' => $sedangDiedit?->id === $item->id])>
                                <td class="num">{{ $loop->iteration }}</td>
                                <td>{{ $item->{$config['kolom']} }}</td>
                                <td class="num">{{ $item->{$config['relasi'].'_count'} }}</td>
                                <td class="aksi">
                                    <a href="{{ route('master.index', [$jenis, 'edit' => $item->id]) }}" class="btn-link">Edit</a>
                                    <form action="{{ route('master.destroy', [$jenis, $item->id]) }}" method="POST" class="inline"
                                        data-confirm="Hapus &quot;{{ $item->{$config['kolom']} }}&quot;?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-link-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="empty">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
