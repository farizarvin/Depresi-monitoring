@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@php
    $pageTitle = 'Dashboard Guru';
    $pageSubtitle = 'Selamat Datang di Panel Guru';
@endphp

@section('content')
    @if (!$hasClass)
    <div class="row min-vh-50 justify-content-center align-items-center mb-4">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mb-3 bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                            <i class="bi bi-shield-lock-fill fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Hubungkan Kelas</h4>
                        <p class="text-muted">Masukkan token kelas untuk mulai mengelola.</p>
                    </div>

                    <form action="{{ route('guru.class.join') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="token" class="form-label fw-medium text-secondary small text-uppercase ls-1">Token Kelas</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text border-0 bg-light"><i class="bi bi-key"></i></span>
                                <input type="text" 
                                       class="form-control border-0 bg-light fs-4 fw-bold text-center letter-spacing-2" 
                                       id="token" 
                                       name="token" 
                                       placeholder="______" 
                                       maxlength="6"
                                       required
                                       style="letter-spacing: 5px;">
                            </div>
                            <div class="form-text text-center mt-2">Dapatkan token dari Admin Sekolah</div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold shadow-sm hover-up">
                            Hubungkan Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" style="border-radius: 15px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-1">Selamat Datang, Bapak/Ibu Guru!</h4>
                        <p class="mb-0 opacity-75">Anda mengelola kelas: <strong>{{ $kelas->nama }}</strong></p>
                    </div>
                    <div class="d-none d-md-block opactiy-50">
                        <i class="bi bi-person-workspace" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Placeholder -->
    <div class="row g-4">
        <div class="col-md-4">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Total Siswa</h6>
                    <h3 class="fw-bold mb-0">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Kehadiran Hari Ini</h6>
                    <h3 class="fw-bold mb-0">-</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase mb-2">Jadwal Mengajar</h6>
                    <h3 class="fw-bold mb-0">-</h3>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif
@endsection
