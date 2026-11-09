@extends('layouts.app')

@php($edit = $user->exists)

@section('title', $edit ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="page-heading">
        <div>
            <div class="breadcrumb"><a href="{{ route('users.index') }}">Pengguna & Peran</a> / {{ $edit ? 'Edit' : 'Tambah' }}</div>
            <h1>{{ $edit ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h1>
            <p>Kolom bertanda <span class="required">*</span> wajib diisi.</p>
        </div>
    </div>

    <section class="card" style="max-width: 860px">
        <div class="card-body">
            <form action="{{ $edit ? route('users.update', $user) : route('users.store') }}" method="POST">
                @csrf
                @if ($edit) @method('PUT') @endif

                <h3 class="section-title">Akun</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Nama <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            @class(['form-control', 'is-invalid' => $errors->has('name')])>
                        @error('name') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email (untuk login) <span class="required">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="off"
                            @class(['form-control', 'is-invalid' => $errors->has('email')])>
                        @error('email') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Kata Sandi @unless ($edit)<span class="required">*</span>@endunless</label>
                        <input type="password" id="password" name="password" @required(! $edit) autocomplete="new-password"
                            @class(['form-control', 'is-invalid' => $errors->has('password')])>
                        <small class="hint">{{ $edit ? 'Kosongkan bila tidak ingin mengganti.' : 'Minimal 8 karakter.' }}</small>
                        @error('password') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Ulangi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="form-control">
                    </div>
                </div>

                <h3 class="section-title">Peran & Hak Akses</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="role">Peran <span class="required">*</span></label>
                        <select id="role" name="role" required @class(['form-control', 'is-invalid' => $errors->has('role')])>
                            @foreach (\App\Models\User::ROLES as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(old('role', $user->role) === $nilai)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('role') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group" data-untuk-peran="operator">
                        <label for="program_studi_id">Program Studi yang Dikelola <span class="required">*</span></label>
                        <select id="program_studi_id" name="program_studi_id"
                            @class(['form-control', 'is-invalid' => $errors->has('program_studi_id')])>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudis as $prodi)
                                <option value="{{ $prodi->id }}" @selected(old('program_studi_id', $user->program_studi_id) == $prodi->id)>
                                    {{ $prodi->jenjang }} {{ $prodi->nama_program_studi }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id') <small class="field-error">{{ $message }}</small> @enderror
                    </div>

                    <div class="form-group" data-untuk-peran="dosen">
                        <label for="dosen_id">Data Dosen <span class="required">*</span></label>
                        <select id="dosen_id" name="dosen_id" @class(['form-control', 'is-invalid' => $errors->has('dosen_id')])>
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" @selected(old('dosen_id', $user->dosen_id) == $dosen->id)>
                                    {{ $dosen->nama_lengkap }} ({{ $dosen->nidn ?? $dosen->nomor_registrasi }})
                                </option>
                            @endforeach
                        </select>
                        <small class="hint">Hanya dosen yang belum memiliki akun yang ditampilkan.</small>
                        @error('dosen_id') <small class="field-error">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <span class="hint"><span class="required">*</span> Wajib diisi.</span>
                    <div class="actions">
                        <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        // Tampilkan pilihan prodi hanya untuk operator, dan pilihan dosen hanya untuk peran dosen.
        (function () {
            const role = document.getElementById('role');

            function sesuaikan() {
                document.querySelectorAll('[data-untuk-peran]').forEach(function (el) {
                    const tampil = el.dataset.untukPeran === role.value;
                    el.hidden = !tampil;
                    el.querySelector('select').required = tampil;
                });
            }

            role.addEventListener('change', sesuaikan);
            sesuaikan();
        })();
    </script>
@endpush
