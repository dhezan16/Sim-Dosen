@extends('layouts.app')

@php($edit = $fakultas->exists)

@section('title', $edit ? 'Edit Fakultas' : 'Tambah Fakultas')

@section('content')
    <div class="page-heading">
        <div>
            <div class="breadcrumb"><a href="{{ route('fakultas.index') }}">Fakultas</a> / {{ $edit ? 'Edit' : 'Tambah' }}</div>
            <h1>{{ $edit ? 'Edit Fakultas' : 'Tambah Fakultas' }}</h1>
        </div>
    </div>

    <section class="card" style="max-width: 720px">
        <div class="card-body">
            <form action="{{ $edit ? route('fakultas.update', $fakultas) : route('fakultas.store') }}" method="POST">
                @csrf
                @if ($edit) @method('PUT') @endif

                <div class="form-grid">
                    <div class="form-group full">
                        <label for="nama_fakultas">Nama Fakultas <span class="required">*</span></label>
                        <input type="text" id="nama_fakultas" name="nama_fakultas" value="{{ old('nama_fakultas', $fakultas->nama_fakultas) }}"
                            placeholder="Contoh: Fakultas Teknik" required
                            @class(['form-control', 'is-invalid' => $errors->has('nama_fakultas')])>
                        @error('nama_fakultas') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    @include('partials.select', [
                        'name' => 'universitas_id', 'label' => 'Universitas', 'items' => $universitas,
                        'kolom' => 'nama_universitas', 'value' => $fakultas->universitas_id ?? $universitas->first()?->id,
                    ])
                </div>

                <div class="form-footer">
                    <span class="hint"><span class="required">*</span> Wajib diisi.</span>
                    <div class="actions">
                        <a href="{{ route('fakultas.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
