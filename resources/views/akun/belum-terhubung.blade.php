@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
    <section class="card" style="max-width: 640px">
        <div class="card-body">
            <span class="eyebrow">Profil Saya</span>
            <h1 style="font-size: 1.3rem; margin-bottom: 8px">Akun Anda belum terhubung ke data dosen</h1>
            <p class="hint" style="font-size: .9rem">
                Silakan hubungi administrator SIM-DOSEN agar akun <strong>{{ auth()->user()->email }}</strong>
                dihubungkan dengan data dosen Anda. Setelah terhubung, Anda dapat melihat profil,
                mengubah biodata, serta mengisi riwayat pendidikan dan Tri Dharma.
            </p>
        </div>
    </section>
@endsection
