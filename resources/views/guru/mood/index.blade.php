@extends('layouts.guru')

@section('title', 'Laporan Mood Siswa')

@php
    $pageTitle = 'Laporan Mood';
    $pageSubtitle = 'Monitor kondisi emosional siswa';
@endphp

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body p-4">
        <!-- Header with info -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h5 class="mb-1 fw-bold">Daftar Siswa</h5>
                <p class="text-muted small mb-0">Klik nama siswa untuk melihat laporan lengkap</p>
            </div>
            </span>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('guru.mood.index') }}" method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Nama atau NISN..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <select name="kelas" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelases as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="gender" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Gender</option>
                        <option value="L" {{ request('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jenjang" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jenjang</option>
                        @foreach($jenjangs as $jenjang)
                            <option value="{{ $jenjang }}" {{ request('jenjang') == $jenjang ? 'selected' : '' }}>{{ $jenjang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select name="jurusan" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusans as $jurusan)
                            <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-4" style="border-top-left-radius: 10px;">Nama Siswa</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Update Terakhir</th>
                        <th class="py-3 text-center">Mood Terakhir</th>
                        <th class="py-3 pe-4 text-center" style="border-top-right-radius: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaData as $siswa)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $siswa['nama'] }}</td>
                            <td>
                                <span class="badge bg-{{ $siswa['status_color'] }} bg-opacity-10 text-{{ $siswa['status_color'] }} border border-{{ $siswa['status_color'] }} rounded-pill px-3">
                                    {{ $siswa['status_label'] }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $siswa['last_update'] }}</td>
                            <td class="text-center h4 mb-0" title="{{ $siswa['mood_label'] }}">
                                {{ $siswa['mood_emoji'] }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('guru.mood.detail', $siswa['id']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                Belum ada data siswa yang mengambil tes DASS-21.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
