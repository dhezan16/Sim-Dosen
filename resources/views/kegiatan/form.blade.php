@extends('layouts.app')

@php($edit = $item->exists)

@section('title', ($edit ? 'Edit ' : 'Tambah ').$config['label'])

@section('content')
    <div class="page-heading">
        <div>
            <div class="breadcrumb">
                @can('viewAny', \App\Models\Dosen::class)
                    <a href="{{ route('dosen.index') }}">Daftar Dosen</a> /
                @endcan
                <a href="{{ route('dosen.show', $dosen).'#'.$jenis }}">{{ $dosen->nama }}</a> /
                {{ $config['label'] }}
            </div>
            <h1>{{ $edit ? 'Edit' : 'Tambah' }} {{ $config['label'] }}</h1>
            <p>{{ $dosen->nama_lengkap }}</p>
        </div>
    </div>

    <section class="card" style="max-width: 900px">
        <div class="card-body">
            <form method="POST"
                action="{{ $edit ? route('kegiatan.update', [$dosen, $jenis, $item->id]) : route('kegiatan.store', [$dosen, $jenis]) }}">
                @csrf
                @if ($edit) @method('PUT') @endif

                <div class="form-grid">
                    @foreach ($config['fields'] as $nama => $field)
                        <div @class(['form-group', 'full' => $nama === 'judul'])>
                            <label for="{{ $nama }}">{{ $field['label'] }} @if ($field['required'])<span class="required">*</span>@endif</label>

                            @if ($field['type'] === 'select')
                                <select id="{{ $nama }}" name="{{ $nama }}" @required($field['required'])
                                    @class(['form-control', 'is-invalid' => $errors->has($nama)])>
                                    <option value="">-- Pilih --</option>
                                    @foreach ($field['options'] as $opsi)
                                        <option value="{{ $opsi }}" @selected(old($nama, $item->{$nama}) === $opsi)>{{ $opsi }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input id="{{ $nama }}" name="{{ $nama }}" value="{{ old($nama, $item->{$nama}) }}" @required($field['required'])
                                    type="{{ ['year' => 'number', 'number' => 'number', 'url' => 'url'][$field['type']] ?? 'text' }}"
                                    @if ($field['type'] === 'year') min="1950" max="{{ date('Y') + 5 }}" placeholder="{{ date('Y') }}" @endif
                                    @if ($field['type'] === 'number') min="0" step="1" @endif
                                    @class(['form-control', 'is-invalid' => $errors->has($nama)])>
                            @endif

                            @error($nama) <small class="field-error">{{ $message }}</small> @enderror
                        </div>
                    @endforeach
                </div>

                <div class="form-footer">
                    <span class="hint"><span class="required">*</span> Wajib diisi.</span>
                    <div class="actions">
                        <a href="{{ route('dosen.show', $dosen).'#'.$jenis }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
