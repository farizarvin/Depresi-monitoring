@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Jadwal Pelajaran';
    $pageSubtitle = 'Lihat dan kelola jadwal mingguan Anda - Kelas XI IPA 1';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/jadwal.css') }}">
@endsection

@section('content')
    <!-- Summary Cards -->
    <div class="summary-grid">
        <div class="summary-card card-purple">
            <div class="summary-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="summary-content">
                <p class="summary-label">Total Jam Pelajaran</p>
                <h3 class="summary-value">34</h3>
                <p class="summary-desc">jam/minggu</p>
            </div>
        </div>

        <div class="summary-card card-blue">
            <div class="summary-icon">
                <i class="bi bi-book"></i>
            </div>
            <div class="summary-content">
                <p class="summary-label">Total Mata Pelajaran</p>
                <h3 class="summary-value">21</h3>
                <p class="summary-desc">pelajaran</p>
            </div>
        </div>

        <div class="summary-card card-green">
            <div class="summary-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="summary-content">
                <p class="summary-label">Hari Aktif</p>
                <h3 class="summary-value">5</h3>
                <p class="summary-desc">hari</p>
            </div>
        </div>

        <div class="summary-card card-orange">
            <div class="summary-icon">
                <i class="bi bi-alarm"></i>
            </div>
            <div class="summary-content">
                <p class="summary-label">Jam Masuk</p>
                <h3 class="summary-value">07:00</h3>
                <p class="summary-desc">WIB</p>
            </div>
        </div>
    </div>

    <!-- SENIN -->
    <div class="jadwal-section">
        <div class="section-header">
            <div class="section-info">
                <span class="day-badge">Senin</span>
                <h2 class="section-title">5 Pelajaran</h2>
            </div>
        </div>

        <div class="jadwal-grid">
            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-blue',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Matematika',
                'kodeMapel' => 'MAT-101',
                'waktu' => '07:00 - 08:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Budi Santoso, S.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-green',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Bahasa Indonesia',
                'kodeMapel' => 'BIN-101',
                'waktu' => '08:30 - 10:00',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Siti Aminah, S.S'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-purple',
                'badge' => 'Lab',
                'badgeClass' => 'badge-lab',
                'mataPelajaran' => 'Fisika (Lab)',
                'kodeMapel' => 'FIS-101L',
                'waktu' => '10:15 - 12:30',
                'durasi' => '3x45 menit',
                'lokasi' => 'Lab Fisika Lt.3',
                'guru' => 'Ahmad Hidayat, S.Si'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-cyan',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Bahasa Inggris',
                'kodeMapel' => 'ENG-101',
                'waktu' => '13:00 - 14:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Linda Wijaya, M.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-teal',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Pendidikan Agama',
                'kodeMapel' => 'PAI-101',
                'waktu' => '14:30 - 15:15',
                'durasi' => '1x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Usman Ibrahim, S.Ag'
            ])
        </div>
    </div>

    <!-- SELASA -->
    <div class="jadwal-section">
        <div class="section-header">
            <div class="section-info">
                <span class="day-badge">Selasa</span>
                <h2 class="section-title">4 Pelajaran</h2>
            </div>
        </div>

        <div class="jadwal-grid">
            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-pink',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Kimia',
                'kodeMapel' => 'KIM-101',
                'waktu' => '07:00 - 08:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Dewi Kartika, S.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-blue',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Matematika',
                'kodeMapel' => 'MAT-101',
                'waktu' => '08:30 - 10:00',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Budi Santoso, S.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-cyan',
                'badge' => 'Praktikum',
                'badgeClass' => 'badge-praktikum',
                'mataPelajaran' => 'Biologi (Praktikum)',
                'kodeMapel' => 'BIO-101P',
                'waktu' => '10:15 - 12:30',
                'durasi' => '3x45 menit',
                'lokasi' => 'Lab Biologi Lt.2',
                'guru' => 'Maya Sari, M.S'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-yellow',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Sejarah Indonesia',
                'kodeMapel' => 'SEJ-101',
                'waktu' => '13:00 - 14:30',
                'durasi' => '1x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Hadi Purnomo, S.Pd'
            ])
        </div>
    </div>

    <!-- RABU -->
    <div class="jadwal-section">
        <div class="section-header">
            <div class="section-info">
                <span class="day-badge">Rabu</span>
                <h2 class="section-title">5 Pelajaran</h2>
            </div>
        </div>

        <div class="jadwal-grid">
            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-purple',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Fisika',
                'kodeMapel' => 'FIS-101',
                'waktu' => '07:00 - 08:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Ahmad Hidayat, S.Si'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-pink',
                'badge' => 'Lab',
                'badgeClass' => 'badge-lab',
                'mataPelajaran' => 'Kimia (Lab)',
                'kodeMapel' => 'KIM-101L',
                'waktu' => '08:30 - 11:00',
                'durasi' => '3x45 menit',
                'lokasi' => 'Lab Kimia Lt.3',
                'guru' => 'Dewi Kartika, S.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-blue',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Ekonomi',
                'kodeMapel' => 'EKO-101',
                'waktu' => '11:15 - 12:45',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Rina Susanti, S.E'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-pink-light',
                'badge' => 'Praktikum',
                'badgeClass' => 'badge-praktikum',
                'mataPelajaran' => 'Seni Budaya',
                'kodeMapel' => 'SBD-101',
                'waktu' => '13:00 - 14:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Ruang Seni Lt.1',
                'guru' => 'Adi Nugroho, S.Sn'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-lime',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Geografi',
                'kodeMapel' => 'GEO-101',
                'waktu' => '14:30 - 15:15',
                'durasi' => '1x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Rudi Hartono, S.Pd'
            ])
        </div>
    </div>

    <!-- KAMIS -->
    <div class="jadwal-section">
        <div class="section-header">
            <div class="section-info">
                <span class="day-badge">Kamis</span>
                <h2 class="section-title">4 Pelajaran</h2>
            </div>
        </div>

        <div class="jadwal-grid">
            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-blue',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Matematika (Lanjutan)',
                'kodeMapel' => 'MAT-102',
                'waktu' => '07:00 - 09:15',
                'durasi' => '3x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Budi Santoso, S.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-green',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Bahasa Inggris',
                'kodeMapel' => 'ENG-101',
                'waktu' => '09:30 - 11:00',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Linda Wijaya, M.Pd'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-cyan',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Biologi',
                'kodeMapel' => 'BIO-101',
                'waktu' => '11:15 - 12:45',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Maya Sari, M.S'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-orange',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'PJOK (Olahraga)',
                'kodeMapel' => 'PJK-101',
                'waktu' => '13:00 - 15:15',
                'durasi' => '2x45 menit',
                'lokasi' => 'Lapangan Utama',
                'guru' => 'Joko Widodo, S.Pd'
            ])
        </div>
    </div>

    <!-- JUMAT -->
    <div class="jadwal-section">
        <div class="section-header">
            <div class="section-info">
                <span class="day-badge">Jumat</span>
                <h2 class="section-title">3 Pelajaran</h2>
            </div>
        </div>

        <div class="jadwal-grid">
            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-teal',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Pendidikan Agama',
                'kodeMapel' => 'PAI-101',
                'waktu' => '07:00 - 08:30',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Usman Ibrahim, S.Ag'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-green',
                'badge' => 'Teori',
                'badgeClass' => 'badge-teori',
                'mataPelajaran' => 'Bahasa Indonesia',
                'kodeMapel' => 'BIN-101',
                'waktu' => '08:30 - 10:00',
                'durasi' => '2x45 menit',
                'lokasi' => 'Kelas XI IPA 1',
                'guru' => 'Siti Aminah, S.S'
            ])

            @include('components.jadwal.card-jadwal', [
                'bgClass' => 'bg-purple',
                'badge' => 'Lab',
                'badgeClass' => 'badge-lab',
                'mataPelajaran' => 'TIK (Lab Komputer)',
                'kodeMapel' => 'TIK-101',
                'waktu' => '10:15 - 11:45',
                'durasi' => '2x45 menit',
                'lokasi' => 'Lab Komputer 1',
                'guru' => 'Nina Frisiana, S.Kom'
            ])
        </div>
    </div>

    <!-- Legend Information -->
    <div class="legend-section">
        <div class="legend-box">
            <h3 class="legend-title">Keterangan Tipe Kelas</h3>
            <div class="legend-items">
                <div class="legend-item">
                    <span class="legend-badge badge-teori">Teori</span>
                    <span class="legend-text">Pelajaran di kelas</span>
                </div>
                <div class="legend-item">
                    <span class="legend-badge badge-praktikum">Praktikum</span>
                    <span class="legend-text">Praktik lapangan</span>
                </div>
                <div class="legend-item">
                    <span class="legend-badge badge-lab">Lab</span>
                    <span class="legend-text">Laboratorium</span>
                </div>
            </div>
        </div>

        <div class="legend-box">
            <h3 class="legend-title">Keterangan Warna Mata Pelajaran</h3>
            <div class="legend-grid">
                <div class="legend-color-item">
                    <div class="color-box bg-blue-box"></div>
                    <span class="legend-text">Matematika</span>
                </div>
                <div class="legend-color-item">
                    <div class="color-box bg-green-box"></div>
                    <span class="legend-text">Bahasa</span>
                </div>
                <div class="legend-color-item">
                    <div class="color-box bg-purple-box"></div>
                    <span class="legend-text">Fisika</span>
                </div>
                <div class="legend-color-item">
                    <div class="color-box bg-pink-box"></div>
                    <span class="legend-text">Kimia</span>
                </div>
                <div class="legend-color-item">
                    <div class="color-box bg-cyan-box"></div>
                    <span class="legend-text">Biologi</span>
                </div>
                <div class="legend-color-item">
                    <div class="color-box bg-orange-box"></div>
                    <span class="legend-text">Olahraga</span>
                </div>
            </div>
        </div>
    </div>
@endsection
