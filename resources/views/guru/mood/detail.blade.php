@extends('layouts.guru')

@section('title', 'Detail Laporan Mood - ' . $siswa->nama_lengkap)

@php
    $pageTitle = 'Detail Laporan Mood';
    $pageSubtitle = $siswa->nama_lengkap;
@endphp

@section('content')
<!-- Back Button & Export -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <a href="{{ route('guru.mood.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left me-2"></i> Kembali
    </a>
    <a href="{{ route('guru.mood.export', $siswa->id) }}" class="btn btn-success rounded-pill px-4">
        <i class="bi bi-download me-2"></i> Download CSV
    </a>
</div>

<!-- Student Info Card -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                <span class="fs-4 fw-bold text-primary">{{ strtoupper(substr($siswa->nama_lengkap, 0, 2)) }}</span>
            </div>
            <div>
                <h4 class="mb-1 fw-bold">{{ $siswa->nama_lengkap }}</h4>
                <p class="text-muted mb-0">NISN: {{ $siswa->nisn }}</p>
            </div>
        </div>
    </div>
</div>

<!-- DASS-21 Results -->
@if($dassScores)
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="mb-1 fw-bold"><i class="bi bi-clipboard2-pulse me-2 text-primary"></i>Hasil DASS-21</h5>
        <p class="text-muted small mb-0">Tanggal pengisian: {{ $dassScores['date'] }}</p>
    </div>
    <div class="card-body p-4">
        <div class="row g-4 text-center">
            <!-- Depression -->
            <div class="col-md-4">
                <div class="p-3 rounded bg-light h-100">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Depresi</h6>
                    <h3 class="fw-bold mb-2">{{ $dassScores['depression'] }}</h3>
                    @php
                        $dColor = match($dassScores['depression_label']) {
                            'Normal' => 'success',
                            'Ringan' => 'info',
                            'Sedang' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <span class="badge bg-{{ $dColor }} rounded-pill px-3 py-2">{{ $dassScores['depression_label'] }}</span>
                </div>
            </div>
            <!-- Anxiety -->
            <div class="col-md-4">
                <div class="p-3 rounded bg-light h-100">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Kecemasan</h6>
                    <h3 class="fw-bold mb-2">{{ $dassScores['anxiety'] }}</h3>
                    @php
                        $aColor = match($dassScores['anxiety_label']) {
                            'Normal' => 'success',
                            'Ringan' => 'info',
                            'Sedang' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <span class="badge bg-{{ $aColor }} rounded-pill px-3 py-2">{{ $dassScores['anxiety_label'] }}</span>
                </div>
            </div>
            <!-- Stress -->
            <div class="col-md-4">
                <div class="p-3 rounded bg-light h-100">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Stres</h6>
                    <h3 class="fw-bold mb-2">{{ $dassScores['stress'] }}</h3>
                    @php
                        $sColor = match($dassScores['stress_label']) {
                            'Normal' => 'success',
                            'Ringan' => 'info',
                            'Sedang' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <span class="badge bg-{{ $sColor }} rounded-pill px-3 py-2">{{ $dassScores['stress_label'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DASS-21 Answers Table -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold"><i class="bi bi-list-check me-2 text-primary"></i>Jawaban DASS-21</h5>
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#dassAnswersCollapse">
            <i class="bi bi-chevron-down"></i> Toggle
        </button>
    </div>
    <div class="collapse show" id="dassAnswersCollapse">
        <div class="card-body p-4 pt-0">
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-2" style="width: 50px;">#</th>
                            <th class="py-2">Pertanyaan</th>
                            <th class="py-2 text-center" style="width: 100px;">Kategori</th>
                            <th class="py-2 text-center" style="width: 120px;">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dassAnswers as $ans)
                        <tr>
                            <td class="text-muted">{{ $ans['no'] }}</td>
                            <td>{{ $ans['question'] }}</td>
                            <td class="text-center">
                                @php
                                    $catBadge = match($ans['category']) {
                                        'Depression' => 'bg-danger bg-opacity-10 text-danger',
                                        'Anxiety' => 'bg-warning bg-opacity-10 text-warning',
                                        'Stress' => 'bg-info bg-opacity-10 text-info',
                                        default => 'bg-secondary bg-opacity-10 text-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $catBadge }} rounded-pill">{{ substr($ans['category'], 0, 1) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary bg-opacity-10 text-dark">{{ $ans['answer'] }} - {{ $ans['answer_text'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="alert alert-info" role="alert">
    <i class="bi bi-info-circle me-2"></i> Siswa ini belum mengisi kuesioner DASS-21.
</div>
@endif

<!-- 14-Day Mood History -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="mb-1 fw-bold"><i class="bi bi-calendar-week me-2 text-primary"></i>Riwayat Mood (14 Hari Terakhir)</h5>
        <p class="text-muted small mb-0">Data presensi dan prediksi emosi dari kamera</p>
    </div>
    <div class="card-body p-4">
        @if(count($moodHistory) > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-2">Tanggal</th>
                        <th class="py-2">Waktu</th>
                        <th class="py-2 text-center">Status</th>
                        <th class="py-2 text-center">Prediksi Kamera</th>
                        <th class="py-2">Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($moodHistory as $mood)
                    <tr>
                        <td class="fw-medium">{{ $mood['tanggal'] }}</td>
                        <td class="text-muted">{{ $mood['waktu'] }}</td>
                        <td class="text-center">
                            @php
                                $statusBadge = match($mood['status']) {
                                    'H' => 'bg-success',
                                    'I' => 'bg-warning',
                                    'S' => 'bg-danger',
                                    default => 'bg-secondary',
                                };
                                $statusText = match($mood['status']) {
                                    'H' => 'Hadir',
                                    'I' => 'Izin',
                                    'S' => 'Sakit',
                                    default => 'Alpha',
                                };
                            @endphp
                            <span class="badge {{ $statusBadge }} rounded-pill">{{ $statusText }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $mood['emotion_label'] }}</span>
                        </td>
                        <td class="text-muted small" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $mood['catatan'] }}">
                            {{ Str::limit($mood['catatan'], 50) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x display-4 d-block mb-3"></i>
            Tidak ada data presensi dalam 14 hari terakhir.
        </div>
        @endif
    </div>
</div>

@endsection
