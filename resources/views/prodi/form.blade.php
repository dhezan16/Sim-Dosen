@extends('layouts.app')

@php($edit = $prodi->exists)

@section('title', $edit ? 'Edit Program Studi' : 'Tambah Program Studi')

@section('content')
    <div class="page-heading">
        <div>
            <div class="breadcrumb"><a href="{{ route('prodi.index') }}">Program Studi</a> / {{ $edit ? 'Edit' : 'Tambah' }}</div>
            <h1>{{ $edit ? 'Edit Program Studi' : 'Tambah Program Studi' }}</h1>
        </div>
    </div>

    <section class="card" style="max-width: 860px">
        <div class="card-body">
            <form action="{{ $edit ? route('prodi.update', $prodi) : route('prodi.store') }}" method="POST">
                @csrf
                @if ($edit) @method('PUT') @endif

                <div class="form-grid">
                    <div class="form-group">
                        <label for="kode">Kode Prodi <span class="required">*</span></label>
                        <input type="text" id="kode" name="kode" value="{{ old('kode', $prodi->kode) }}" placeholder="Contoh: TI" required
                            @class(['form-control', 'is-invalid' => $errors->has('kode')])>
                        @error('kode') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_program_studi">Nama Program Studi <span class="required">*</span></label>
                        <input type="text" id="nama_program_studi" name="nama_program_studi" required
                            value="{{ old('nama_program_studi', $prodi->nama_program_studi) }}" placeholder="Contoh: Teknik Informatika"
                            @class(['form-control', 'is-invalid' => $errors->has('nama_program_studi')])>
                        @error('nama_program_studi') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    @include('partials.select', ['name' => 'fakultas_id', 'label' => 'Fakultas', 'items' => $fakultas, 'kolom' => 'nama_fakultas', 'value' => $prodi->fakultas_id])

                    <div class="form-group">
                        <label for="jenjang">Jenjang <span class="required">*</span></label>
                        <select id="jenjang" name="jenjang" required @class(['form-control', 'is-invalid' => $errors->has('jenjang')])>
                            @foreach (\App\Models\ProgramStudi::JENJANG as $jenjang)
                                <option value="{{ $jenjang }}" @selected(old('jenjang', $prodi->jenjang) === $jenjang)>{{ $jenjang }}</option>
                            @endforeach
                        </select>
                        @error('jenjang') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="status">Status <span class="required">*</span></label>
                        <select id="status" name="status" required @class(['form-control', 'is-invalid' => $errors->has('status')])>
                            @foreach (\App\Models\ProgramStudi::STATUS as $status)
                                <option value="{{ $status }}" @selected(old('status', $prodi->status) === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                        <small class="hint">Prodi Tidak Aktif tidak muncul di pilihan saat menambah dosen.</small>
                    </div>
                </div>

                <div class="form-footer">
                    <span class="hint"><span class="required">*</span> Wajib diisi.</span>
                    <div class="actions">
                        <a href="{{ route('prodi.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
