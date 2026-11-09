@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">Dashboard</span>
            <h1>Selamat datang, {{ auth()->user()->name }}</h1>
            <p>
                @if (auth()->user()->isOperator())
                    Ringkasan data dosen Program Studi {{ auth()->user()->programStudi?->nama_program_studi ?? '(belum ditentukan)' }}.
                @else
                    Ringkasan data dosen Universitas Muhammadiyah Bengkulu.
                @endif
            </p>
        </div>
        <div class="actions">
            <a href="{{ route('dosen.export') }}" class="btn btn-outline">⬇ Ekspor Data Dosen</a>
            @can('create', \App\Models\Dosen::class)
                <a href="{{ route('dosen.create') }}" class="btn btn-primary">+ Tambah Dosen</a>
            @endcan
        </div>
    </div>

    @php
        $kartu = [
            ['Total Dosen', $ringkasan['dosen'], '🎓', '#e0f2fe'],
            ['Fakultas', $ringkasan['fakultas'], '🏛️', '#f0fdf4'],
            ['Program Studi', $ringkasan['prodi'], '📚', '#fef3c7'],
            ['Penelitian', $ringkasan['penelitian'], '🔬', '#f3e8ff'],
            ['Pengabdian', $ringkasan['pengabdian'], '🤝', '#ffe4e6'],
            ['Publikasi', $ringkasan['publikasi'], '📄', '#e0e7ff'],
        ];
    @endphp

    <div class="stats-grid">
        @foreach ($kartu as [$label, $nilai, $ikon, $warna])
            <div class="stat-card">
                <div class="stat-icon" style="background: {{ $warna }}">{{ $ikon }}</div>
                <div>
                    <span>{{ $label }}</span>
                    <h3>{{ number_format($nilai, 0, ',', '.') }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid-2">
        @foreach ($sebaran as $judul => $data)
            @php($maks = max($data->max() ?? 0, 1))
            <section class="card">
                <div class="card-header">
                    <h2>Sebaran {{ $judul }}</h2>
                </div>
                <div class="card-body">
                    <div class="bar-list">
                        @forelse ($data as $nama => $jumlah)
                            <div class="bar-row">
                                <span>{{ $nama }}</span>
                                <div class="bar-track">
                                    <div class="bar-fill" style="width: {{ round($jumlah / $maks * 100) }}%"></div>
                                </div>
                                <strong>{{ $jumlah }}</strong>
                            </div>
                        @empty
                            <p class="hint">Belum ada data referensi.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        @endforeach

        <section class="card">
            <div class="card-header">
                <h2>Jumlah Dosen per Program Studi</h2>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('prodi.index') }}" class="btn-link">Lihat semua</a>
                @endif
            </div>
            <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Program Studi</th>
                            <th>Fakultas</th>
                            <th class="num">Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($perProdi as $prodi)
                            <tr>
                                <td>{{ $prodi->jenjang }} {{ $prodi->nama_program_studi }}</td>
                                <td>{{ $prodi->fakultas?->nama_fakultas ?? '-' }}</td>
                                <td class="num"><strong>{{ $prodi->dosen_count }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="empty">Belum ada program studi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card">
            <div class="card-header">
                <h2>Dosen Terbaru</h2>
                <a href="{{ route('dosen.index') }}" class="btn-link">Lihat semua</a>
            </div>
            <div class="table-wrap">
                <table class="table">
                    <tbody>
                        @forelse ($dosenTerbaru as $dosen)
                            <tr>
                                <td>
                                    <a href="{{ route('dosen.show', $dosen) }}" class="cell-person">
                                        <span class="avatar">
                                            @if ($dosen->foto_url)<img src="{{ $dosen->foto_url }}" alt="">@else{{ $dosen->inisial }}@endif
                                        </span>
                                        <span>
                                            <strong>{{ $dosen->nama_lengkap }}</strong>
                                            <small>{{ $dosen->programStudi?->nama_program_studi ?? '-' }}</small>
                                        </span>
                                    </a>
                                </td>
                                <td class="aksi hint">{{ $dosen->created_at?->translatedFormat('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td class="empty">Belum ada data dosen.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
