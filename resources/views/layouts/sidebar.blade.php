@php($pengguna = auth()->user())

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">SD</div>
        <div>
            <strong>SIM-DOSEN</strong>
            <small>Universitas Muhammadiyah Bengkulu</small>
        </div>
    </div>

    <nav class="nav">
        @if ($pengguna->isDosen())
            @if ($pengguna->dosen_id)
                <a href="{{ route('dosen.show', $pengguna->dosen_id) }}" @class(['active' => request()->routeIs('dosen.show', 'kegiatan.*')])>👤 Profil Saya</a>
                <a href="{{ route('dosen.edit', $pengguna->dosen_id) }}" @class(['active' => request()->routeIs('dosen.edit')])>✏️ Ubah Biodata</a>
            @else
                <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>👤 Profil Saya</a>
            @endif
        @else
            <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard')])>📊 Dashboard</a>

            <div class="nav-title">Data Dosen</div>
            <a href="{{ route('dosen.index') }}" @class(['active' => request()->routeIs('dosen.index', 'dosen.show', 'dosen.edit', 'kegiatan.*')])>👩‍🏫 Daftar Dosen</a>
            @can('create', \App\Models\Dosen::class)
                <a href="{{ route('dosen.create') }}" @class(['active' => request()->routeIs('dosen.create')])>➕ Tambah Dosen</a>
            @endcan
        @endif

        @if ($pengguna->isAdmin())
            <div class="nav-title">Institusi</div>
            <a href="{{ route('master.index', 'universitas') }}" @class(['active' => request()->is('master/universitas')])>🏛️ Universitas</a>
            <a href="{{ route('fakultas.index') }}" @class(['active' => request()->routeIs('fakultas.*')])>🏢 Fakultas</a>
            <a href="{{ route('prodi.index') }}" @class(['active' => request()->routeIs('prodi.*')])>📚 Program Studi</a>

            <div class="nav-title">Data Referensi</div>
            @foreach (\App\Support\MasterData::JENIS as $slug => $item)
                @continue($slug === 'universitas')
                <a href="{{ route('master.index', $slug) }}" @class(['active' => request()->is('master/'.$slug)])>• {{ $item['label'] }}</a>
            @endforeach

            <div class="nav-title">Pengaturan</div>
            <a href="{{ route('users.index') }}" @class(['active' => request()->routeIs('users.*')])>🔐 Pengguna & Peran</a>
        @endif

        <div class="nav-title">Akun</div>
        <a href="{{ route('akun.edit') }}" @class(['active' => request()->routeIs('akun.*')])>⚙️ Akun Saya</a>
    </nav>
</aside>
