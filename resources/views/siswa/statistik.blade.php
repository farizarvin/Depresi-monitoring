@extends('layouts.siswa')

@section('title', 'Statistik - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Laporan Statistik';
    $pageSubtitle = 'Pantau perkembangan dan riwayat absensi Anda';
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1">Status Absensi Hari Ini</h5>
                        <h2 class="mb-0 fw-bold">
                            @if($isTodayPresent)
                                <i class="bi bi-check-circle-fill text-success rounded-circle p-1"></i> Sudah Absen
                            @else
                                <i class="bi bi-x-circle-fill text-danger rounded-circle p-1"></i> Belum Absen
                            @endif
                        </h2>
                        <p class="mt-2 mb-0 opacity-75">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-calendar-check" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Ringkasan Absensi</h5>
                    <div class="row g-3 text-center">
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-success mb-0">{{ $stats['H'] }}</h3>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-info mb-0">{{ $stats['I'] }}</h3>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-3 border-end">
                            <h3 class="fw-bold text-warning mb-0">{{ $stats['S'] }}</h3>
                            <small class="text-muted">Sakit</small>
                        </div>
                        <div class="col-3">
                            <h3 class="fw-bold text-danger mb-0">{{ $stats['A'] }}</h3>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mood Chart --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4">Grafik Mood (14 Hari Terakhir)</h5>
                    <canvas id="moodChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('moodChart').getContext('2d');
        const moodData = @json($moodData);

        const labels = moodData.map(d => d.date);
        const dataPoints = moodData.map(d => d.emoji);
        const pointLabels = moodData.map(d => d.label); // For tooltips

        // Emoji labels for Y-axis (Same as Admin Panel)
        const emojiLabels = {
            1: '😢 Sedih',
            2: '😠 Marah',
            3: '😨 Takut',
            4: '🤢 Jijik',
            5: '😊 Senang',
            6: '😲 Terkejut'
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Mood Level',
                    data: dataPoints,
                    borderColor: '#8B5CF6', // Purple
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4, // Smooth curve
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#8B5CF6',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                // Use the explicit label from backend if available, else map value
                                const idx = context.dataIndex;
                                const explicitLabel = pointLabels[idx];
                                if(explicitLabel) return explicitLabel.charAt(0).toUpperCase() + explicitLabel.slice(1);

                                return emojiLabels[value] || 'Tidak ada data';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 7, // Increased max
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return emojiLabels[value] || '';
                            }
                        },
                        grid: {
                            borderDash: [5, 5]
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection
