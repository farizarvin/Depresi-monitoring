@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@php
    $pageTitle = 'Dashboard Guru';
    $pageSubtitle = 'Selamat Datang di Panel Guru';
@endphp

@section('content')
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" style="border-radius: 15px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Selamat Datang, Bapak/Ibu Guru!</h4>
                        <p class="mb-0 opacity-75">Pantau perkembangan siswa secara menyeluruh di sini.</p>
                    </div>
                    <div class="d-none d-md-block opactiy-50">
                        <i class="bi bi-person-workspace" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4">
        <div class="col-md-6">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Total Siswa</h6>
                    <h3 class="fw-bold mb-0 text-primary">{{ $totalSiswa }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Kehadiran Hari Ini</h6>
                    <h3 class="fw-bold mb-0 text-success">{{ $todayPresence }}</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
