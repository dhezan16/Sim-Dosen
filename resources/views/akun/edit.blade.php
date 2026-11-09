@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Akun</span>
            <h1>Akun Saya</h1>
            <p>Ubah nama tampilan dan kata sandi Anda.</p>
        </div>
    </div>

    <div class="grid-2" style="grid-template-columns: 2fr minmax(260px, 1fr); align-items: start">
        <section class="card">
            <div class="card-body">
                <form action="{{ route('akun.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="name">Nama <span class="required">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                @class(['form-control', 'is-invalid' => $errors->has('name')])>
                        </div>
                    </div>

                    <h3 class="section-title" style="margin-top: 24px">Ganti Kata Sandi</h3>
                    <div class="form-grid">
                        <div class="form-group full">
                            <label for="current_password">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                                @class(['form-control', 'is-invalid' => $errors->has('current_password')])>
                            <small class="hint">Kosongkan ketiga kolom kata sandi bila tidak ingin mengganti.</small>
                        </div>
                        <div class="form-group">
                            <label for="password">Kata Sandi Baru</label>
                            <input type="password" id="password" name="password" autocomplete="new-password"
                                @class(['form-control', 'is-invalid' => $errors->has('password')])>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Ulangi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" class="form-control">
                        </div>
                    </div>

                    <div class="form-footer">
                        <span></span>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </section>

        <section class="card">
            <div class="card-header"><h2>Informasi Akun</h2></div>
            <div class="card-body">
                <dl class="detail-list" style="grid-template-columns: 1fr">
                    <div><dt>Email</dt><dd>{{ $user->email }}</dd></div>
                    <div><dt>Peran</dt><dd>{{ $user->role_label }}</dd></div>
                    @if ($user->isOperator())
                        <div><dt>Program Studi</dt><dd>{{ $user->programStudi?->nama_program_studi ?? '-' }}</dd></div>
                    @endif
                    @if ($user->isDosen())
                        <div><dt>Data Dosen</dt><dd>{{ $user->dosen?->nama_lengkap ?? 'Belum terhubung' }}</dd></div>
                    @endif
                </dl>
                <p class="hint" style="margin-bottom: 0">Email dan peran hanya dapat diubah oleh administrator.</p>
            </div>
        </section>
    </div>
@endsection
