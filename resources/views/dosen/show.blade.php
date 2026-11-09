@extends('layouts.app')

@section('title', $dosen->nama)

@section('content')
    @can('viewAny', \App\Models\Dosen::class)
        <div class="breadcrumb"><a href="{{ route('dosen.index') }}">Daftar Dosen</a> / {{ $dosen->nama }}</div>
    @endcan

    <section class="card">
        <div class="card-body">
            <div class="page-heading" style="margin-bottom: 0">
                <div class="profile">
                    <div class="avatar avatar-lg">
                        @if ($dosen->foto_url)<img src="{{ $dosen->foto_url }}" alt="Foto {{ $dosen->nama }}">@else{{ $dosen->inisial }}@endif
                    </div>
                    <div>
                        <h1>{{ $dosen->nama_lengkap }}</h1>
                        <p>
                            {{ $dosen->jabatanFungsional?->nama_jabatan ?? '-' }} ·
                            {{ $dosen->programStudi?->jenjang }} {{ $dosen->programStudi?->nama_program_studi ?? '-' }} ·
                            {{ $dosen->programStudi?->fakultas?->nama_fakultas ?? '-' }}
                        </p>
                        <div class="actions">
                            @php($status = $dosen->statusAktivitas?->nama_status_aktivitas)
                            <span @class(['badge', 'badge-success' => $status === 'Aktif', 'badge-warning' => $status && $status !== 'Aktif'])>{{ $status ?? '-' }}</span>
                            <span class="badge badge-primary">{{ $dosen->ikatanKerja?->nama_ikatan_kerja ?? '-' }}</span>
                            <span class="badge">{{ $dosen->statusSerdos?->nama_status_serdos ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="actions">
                    @can('update', $dosen)
                        <a href="{{ route('dosen.edit', $dosen) }}" class="btn btn-primary">
                            {{ auth()->user()->can('updateAkademik', $dosen) ? 'Edit Data' : 'Ubah Biodata' }}
                        </a>
                    @endcan

                    @if (auth()->user()->isAdmin())
                        @if ($dosen->user)
                            <a href="{{ route('users.edit', $dosen->user) }}" class="btn btn-outline">🔐 Akun Login</a>
                        @else
                            <a href="{{ route('users.create', ['role' => 'dosen', 'dosen_id' => $dosen->id]) }}" class="btn btn-outline">🔐 Buat Akun Login</a>
                        @endif
                    @endif

                    @can('delete', $dosen)
                        <form action="{{ route('dosen.destroy', $dosen) }}" method="POST"
                            data-confirm="Hapus data dosen {{ $dosen->nama }} beserta seluruh riwayat dan Tri Dharma-nya?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline" style="color: var(--danger)">Hapus</button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    <nav class="tabs" data-tabs>
        <a href="#profil" class="active">Profil</a>
        @foreach ($kegiatan as $slug => $def)
            <a href="#{{ $slug }}">{{ $def['label'] }} ({{ $dosen->{$def['relasi']}->count() }})</a>
        @endforeach
    </nav>

    <div class="tab-panel active" id="profil">
        <div class="grid-2">
            <section class="card">
                <div class="card-header"><h2>Identitas & Biodata</h2></div>
                <div class="card-body">
                    <dl class="detail-list">
                        @foreach ([
                            'Nomor Registrasi' => $dosen->nomor_registrasi,
                            'NIDN' => $dosen->nidn,
                            'NUPTK' => $dosen->nuptk,
                            'NIP' => $dosen->nip,
                            'Jenis Kelamin' => ['L' => 'Laki-laki', 'P' => 'Perempuan'][$dosen->jenis_kelamin] ?? null,
                            'Tempat, Tanggal Lahir' => collect([$dosen->tempat_lahir, $dosen->tanggal_lahir?->translatedFormat('d F Y')])->filter()->implode(', '),
                            'Email' => $dosen->email,
                            'No. HP' => $dosen->no_hp,
                            'Alamat' => $dosen->alamat,
                        ] as $label => $nilai)
                            <div><dt>{{ $label }}</dt><dd>{{ filled($nilai) ? $nilai : '-' }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            </section>

            <section class="card">
                <div class="card-header"><h2>Akademik & Kepegawaian</h2></div>
                <div class="card-body">
                    <dl class="detail-list">
                        @foreach ([
                            'Universitas' => $dosen->programStudi?->fakultas?->universitas?->nama_universitas,
                            'Fakultas' => $dosen->programStudi?->fakultas?->nama_fakultas,
                            'Program Studi' => trim($dosen->programStudi?->jenjang.' '.$dosen->programStudi?->nama_program_studi),
                            'Jabatan Fungsional' => $dosen->jabatanFungsional?->nama_jabatan,
                            'Kepangkatan' => $dosen->kepangkatan?->kepangkatan,
                            'Pendidikan Terakhir' => $dosen->pendidikan?->nama_pendidikan,
                            'Rumpun Ilmu' => $dosen->rumpunIlmu?->nama_rumpun_ilmu,
                            'Bidang Keahlian' => $dosen->bidang_keahlian,
                            'Status Serdos' => $dosen->statusSerdos?->nama_status_serdos,
                            'Status Pegawai' => $dosen->statusPegawai?->nama_status_pegawai,
                            'Ikatan Kerja' => $dosen->ikatanKerja?->nama_ikatan_kerja,
                            'TMT Kerja' => $dosen->tmt_kerja?->translatedFormat('d F Y'),
                        ] as $label => $nilai)
                            <div><dt>{{ $label }}</dt><dd>{{ filled($nilai) ? $nilai : '-' }}</dd></div>
                        @endforeach
                    </dl>
                </div>
            </section>
        </div>
    </div>

    @foreach ($kegiatan as $slug => $def)
        @php($kolom = array_filter($def['fields'], fn ($f) => $f['tabel']))
        <div class="tab-panel" id="{{ $slug }}">
            <section class="card">
                <div class="card-header">
                    <h2>{{ $def['label'] }}</h2>
                    <a href="{{ route('kegiatan.create', [$dosen, $slug]) }}" class="btn btn-primary btn-sm">+ Tambah {{ $def['label'] }}</a>
                </div>
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="num">No</th>
                                @foreach ($kolom as $field)
                                    <th>{{ $field['label'] }}</th>
                                @endforeach
                                <th class="aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dosen->{$def['relasi']} as $item)
                                <tr>
                                    <td class="num">{{ $loop->iteration }}</td>
                                    @foreach ($kolom as $nama => $field)
                                        <td>
                                            @if ($nama === 'judul' && ! empty($item->tautan))
                                                <a href="{{ $item->tautan }}" target="_blank" rel="noopener">{{ $item->judul }}</a>
                                            @elseif ($field['type'] === 'number' && $item->{$nama} !== null)
                                                Rp {{ number_format($item->{$nama}, 0, ',', '.') }}
                                            @else
                                                {{ $item->{$nama} ?? '-' }}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="aksi">
                                        <a href="{{ route('kegiatan.edit', [$dosen, $slug, $item->id]) }}" class="btn-link">Edit</a>
                                        <form action="{{ route('kegiatan.destroy', [$dosen, $slug, $item->id]) }}" method="POST" class="inline"
                                            data-confirm="Hapus data {{ strtolower($def['label']) }} ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-link-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="{{ count($kolom) + 2 }}" class="empty">Belum ada data {{ strtolower($def['label']) }}.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        // Tab sederhana berbasis hash URL (#penelitian, #publikasi, ...)
        (function () {
            const links = document.querySelectorAll('[data-tabs] a');

            function tampilkan(id) {
                if (!document.getElementById(id)) id = 'profil';
                links.forEach(a => a.classList.toggle('active', a.hash === '#' + id));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.toggle('active', p.id === id));
            }

            links.forEach(a => a.addEventListener('click', function (e) {
                e.preventDefault();
                history.replaceState(null, '', a.hash);
                tampilkan(a.hash.slice(1));
            }));

            tampilkan(location.hash.slice(1));
        })();
    </script>
@endpush
