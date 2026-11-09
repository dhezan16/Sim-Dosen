@extends('layouts.app')

@php
    $edit = $dosen->exists;
    // false = dosen mengubah datanya sendiri: hanya biodata yang dapat diubah.
    $akademik = auth()->user()->can('updateAkademik', $dosen);
@endphp

@section('title', $edit ? ($akademik ? 'Edit Dosen' : 'Ubah Biodata') : 'Tambah Dosen')

@section('content')
    <div class="page-heading">
        <div>
            <div class="breadcrumb">
                @can('viewAny', \App\Models\Dosen::class)
                    <a href="{{ route('dosen.index') }}">Daftar Dosen</a> /
                @endcan
                @if ($edit) <a href="{{ route('dosen.show', $dosen) }}">{{ $dosen->nama }}</a> / @endif
                {{ $edit ? 'Edit' : 'Tambah' }}
            </div>
            <h1>{{ $edit ? ($akademik ? 'Edit Data Dosen' : 'Ubah Biodata') : 'Tambah Dosen' }}</h1>
            <p>Kolom bertanda <span class="required">*</span> wajib diisi.</p>
        </div>
    </div>

    @unless ($akademik)
        <div class="alert alert-info">
            Data identitas resmi dan kepegawaian (abu-abu) dikelola oleh operator prodi / admin.
            Hubungi mereka bila ada data yang perlu diperbaiki.
        </div>
    @endunless

    <form action="{{ $edit ? route('dosen.update', $dosen) : route('dosen.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($edit) @method('PUT') @endif

        <section class="card">
            <div class="card-body">
                <h3 class="section-title">Identitas</h3>
                <div class="form-grid">
                    @foreach ([
                        ['nomor_registrasi', 'Nomor Registrasi', true, 'Contoh: REG-002'],
                        ['nidn', 'NIDN', false, '10 digit Nomor Induk Dosen Nasional'],
                        ['nuptk', 'NUPTK', false, '16 digit NUPTK'],
                        ['nip', 'NIP', false, '18 digit NIP (jika PNS / pegawai tetap)'],
                        ['gelar_depan', 'Gelar Depan', false, 'Contoh: Dr.'],
                        ['nama', 'Nama Lengkap (tanpa gelar)', true, 'Contoh: Budi Santoso'],
                        ['gelar_belakang', 'Gelar Belakang', false, 'Contoh: S.Kom., M.Kom.'],
                    ] as [$nama, $label, $wajib, $placeholder])
                        <div class="form-group">
                            <label for="{{ $nama }}">{{ $label }} @if ($wajib)<span class="required">*</span>@endif</label>
                            <input type="text" id="{{ $nama }}" name="{{ $nama }}" value="{{ old($nama, $dosen->{$nama}) }}"
                                placeholder="{{ $placeholder }}" @class(['form-control', 'is-invalid' => $errors->has($nama)]) @required($wajib)
                                @disabled(! $akademik && ! str_starts_with($nama, 'gelar_'))>
                            @error($nama) <small class="field-error">{{ $message }}</small> @enderror
                        </div>
                    @endforeach
                </div>

                <h3 class="section-title">Biodata & Kontak</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" @class(['form-control', 'is-invalid' => $errors->has('jenis_kelamin')])>
                            <option value="">-- Pilih --</option>
                            <option value="L" @selected(old('jenis_kelamin', $dosen->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $dosen->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $dosen->tempat_lahir) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $dosen->tanggal_lahir?->format('Y-m-d')) }}"
                            @class(['form-control', 'is-invalid' => $errors->has('tanggal_lahir')])>
                        @error('tanggal_lahir') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $dosen->email) }}"
                            @class(['form-control', 'is-invalid' => $errors->has('email')])>
                        @error('email') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. HP / WhatsApp</label>
                        <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $dosen->no_hp) }}" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                            @class(['form-control', 'is-invalid' => $errors->has('foto')])>
                        <small class="hint">JPG, PNG, atau WEBP, maksimal 2 MB.</small>
                        @error('foto') <small class="field-error">{{ $message }}</small> @enderror
                        @if ($dosen->foto_url)
                            <label class="checkbox"><input type="checkbox" name="hapus_foto" value="1"> Hapus foto saat ini</label>
                        @endif
                    </div>

                    <div class="form-group full">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control">{{ old('alamat', $dosen->alamat) }}</textarea>
                    </div>
                </div>

                <h3 class="section-title">Akademik & Kepegawaian</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="program_studi_id">Program Studi <span class="required">*</span></label>
                        <select id="program_studi_id" name="program_studi_id" required @disabled(! $akademik)
                            @class(['form-control', 'is-invalid' => $errors->has('program_studi_id')])>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudis->groupBy(fn ($p) => $p->fakultas?->nama_fakultas ?? 'Tanpa Fakultas') as $namaFakultas => $prodis)
                                <optgroup label="{{ $namaFakultas }}">
                                    @foreach ($prodis as $prodi)
                                        <option value="{{ $prodi->id }}" @selected(old('program_studi_id', $dosen->program_studi_id) == $prodi->id)>
                                            {{ $prodi->jenjang }} {{ $prodi->nama_program_studi }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        @error('program_studi_id') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'jabatan_fungsional_id', 'label' => 'Jabatan Fungsional', 'items' => $jabatans, 'kolom' => 'nama_jabatan', 'value' => $dosen->jabatan_fungsional_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'kepangkatan_id', 'label' => 'Kepangkatan', 'items' => $kepangkatans, 'kolom' => 'kepangkatan', 'value' => $dosen->kepangkatan_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'pendidikan_id', 'label' => 'Pendidikan Terakhir', 'items' => $pendidikans, 'kolom' => 'nama_pendidikan', 'value' => $dosen->pendidikan_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'rumpun_ilmu_id', 'label' => 'Rumpun Ilmu', 'items' => $rumpunIlmus, 'kolom' => 'nama_rumpun_ilmu', 'value' => $dosen->rumpun_ilmu_id])

                    <div class="form-group">
                        <label for="bidang_keahlian">Bidang Keahlian</label>
                        <input type="text" id="bidang_keahlian" name="bidang_keahlian" value="{{ old('bidang_keahlian', $dosen->bidang_keahlian) }}"
                            class="form-control" placeholder="Contoh: Kecerdasan Buatan">
                    </div>

                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'status_serdos_id', 'label' => 'Status Serdos', 'items' => $statusSerdos, 'kolom' => 'nama_status_serdos', 'value' => $dosen->status_serdos_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'status_pegawai_id', 'label' => 'Status Pegawai', 'items' => $statusPegawais, 'kolom' => 'nama_status_pegawai', 'value' => $dosen->status_pegawai_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'ikatan_kerja_id', 'label' => 'Ikatan Kerja', 'items' => $ikatanKerjas, 'kolom' => 'nama_ikatan_kerja', 'value' => $dosen->ikatan_kerja_id])
                    @include('partials.select', ['disabled' => ! $akademik,'name' => 'status_aktivitas_id', 'label' => 'Status Aktivitas', 'items' => $statusAktivitas, 'kolom' => 'nama_status_aktivitas', 'value' => $dosen->status_aktivitas_id])

                    <div class="form-group">
                        <label for="tmt_kerja">TMT Kerja</label>
                        <input type="date" id="tmt_kerja" name="tmt_kerja" value="{{ old('tmt_kerja', $dosen->tmt_kerja?->format('Y-m-d')) }}" class="form-control" @disabled(! $akademik)>
                        <small class="hint">Terhitung mulai tanggal bekerja.</small>
                    </div>
                </div>

                <div class="form-footer">
                    <span class="hint"><span class="required">*</span> Wajib diisi.</span>
                    <div class="actions">
                        <a href="{{ $edit ? route('dosen.show', $dosen) : route('dosen.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">{{ $edit ? 'Simpan Perubahan' : 'Simpan Dosen' }}</button>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
