<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title')@yield('title') · @endif{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')

    <script>
        // Terapkan status sidebar (tersembunyi/tampil) sebelum halaman digambar agar tidak berkedip.
        try {
            if (localStorage.getItem('sidebar-collapsed') === '1') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (e) {}
    </script>
</head>

<body>
    @include('layouts.sidebar')
    <div class="backdrop" onclick="toggleSidebar()"></div>

    <div class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="sidebar-toggle" onclick="toggleSidebar()" id="sidebar-toggle"
                    aria-controls="sidebar" aria-label="Tampilkan atau sembunyikan menu" title="Tampilkan / sembunyikan menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" aria-hidden="true">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>
                <strong>@yield('title', 'Dashboard')</strong>
            </div>

            <div class="topbar-right">
                <a href="{{ route('akun.edit') }}" class="user-menu" title="Akun Saya">
                    <div class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="user-info">
                        <strong>{{ auth()->user()->name }}</strong>
                        <small>{{ auth()->user()->role_label }}@if (auth()->user()->isOperator() && auth()->user()->programStudi) · {{ auth()->user()->programStudi->nama_program_studi }}@endif</small>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST" data-confirm="Apakah Anda yakin ingin keluar?">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm">Keluar</button>
                </form>
            </div>
        </header>

        <main class="content">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>

    <script>
        // Layar lebar: sidebar disembunyikan dan konten melebar (status diingat).
        // Layar kecil: sidebar tampil sebagai menu geser di atas konten.
        const layarKecil = window.matchMedia('(max-width: 960px)');

        function toggleSidebar() {
            if (layarKecil.matches) {
                document.body.classList.toggle('sidebar-open');
            } else {
                const tersembunyi = document.documentElement.classList.toggle('sidebar-collapsed');
                try { localStorage.setItem('sidebar-collapsed', tersembunyi ? '1' : '0'); } catch (e) {}
            }
            perbaruiStatusToggle();
        }

        function perbaruiStatusToggle() {
            const terbuka = layarKecil.matches
                ? document.body.classList.contains('sidebar-open')
                : !document.documentElement.classList.contains('sidebar-collapsed');
            document.getElementById('sidebar-toggle').setAttribute('aria-expanded', terbuka);
        }

        layarKecil.addEventListener('change', function () {
            document.body.classList.remove('sidebar-open');
            perbaruiStatusToggle();
        });

        // Pintasan keyboard: Ctrl + B
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b') {
                e.preventDefault();
                toggleSidebar();
            }
        });

        perbaruiStatusToggle();

        // Konfirmasi untuk setiap form yang memiliki atribut data-confirm (hapus, keluar, dll.)
        document.addEventListener('submit', function (e) {
            const pesan = e.target.dataset.confirm;
            if (pesan && !confirm(pesan)) {
                e.preventDefault();
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
