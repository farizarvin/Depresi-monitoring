@extends('layouts.guru')

@section('title', 'Laporan Nilai Siswa')

@php
    $pageTitle = 'Laporan Nilai';
    $pageSubtitle = 'Rekapitulasi nilai akademik siswa';
@endphp

@section('content')
    <div class="card text-center p-5 shadow-sm border-0" style="border-radius: 15px;">
        <div class="card-body">
            <div class="mb-4">
                <i class="bi bi-cone-striped text-warning" style="font-size: 4rem;"></i>
            </div>
            <h3 class="mt-3 fw-bold">Fitur Belum Tersedia</h3>
            <p class="text-muted mb-4">
                Sistem penilaian akademik belum terintegrasi dengan database.<br>
                Fitur ini akan segera hadir dalam pembaruan berikutnya.
            </p>
            <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-primary px-4 rounded-pill">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>
@endsection
