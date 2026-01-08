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
            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                <i class="bi bi-people me-1"></i> {{ count($siswaData) }} Siswa
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-4" style="border-top-left-radius: 10px;">Nama Siswa</th>
                        <th class="py-3">Update Terakhir</th>
                        <th class="py-3 text-center">Mood Terakhir</th>
                        <th class="py-3 pe-4 text-center" style="border-top-right-radius: 10px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaData as $siswa)
                        <tr>
                            <td class="ps-4 fw-medium">{{ $siswa['nama'] }}</td>
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
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-4 d-block mb-3"></i>
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
