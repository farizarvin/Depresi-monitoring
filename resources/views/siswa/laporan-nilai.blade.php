@extends('layouts.siswa')

@section('title', 'Laporan Nilai - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Laporan Nilai';
    $pageSubtitle = 'Lihat hasil belajar dan nilai Anda';
@endphp

@section('content')
    <div class="card text-center p-5 shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body">
            <i class="bi bi-cone-striped text-warning" style="font-size: 4rem;"></i>
            <h3 class="mt-3 fw-bold">Dalam Tahap Pengembangan</h3>
            <p class="text-muted">Halaman ini sedang dikerjakan. Silakan kembali lagi nanti.</p>
            <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary mt-3 px-4 rounded-pill">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
