@extends('layouts.siswa')

@section('title', 'Dashboard - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Dashboard';
    $pageSubtitle = 'Sistem Manajemen Siswa';
@endphp

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/siswa/dashboard.css') }}">
@endsection

@section('content')
    <!-- Section Title -->
    <h2 class="section-title">Ringkasan Aktivitas</h2>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Kehadiran Bulan Ini</p>
                    <h3 class="stat-value">{{ $attendancePercentage }}%</h3>
                </div>
                <div class="stat-icon attendance">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Rata-rata Nilai</p>
                    <h3 class="stat-value">{{ $averageGrade }}</h3>
                </div>
                <div class="stat-icon grades">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Mood Overall</p>
                    <h3 class="stat-value">{{ $moodLabel }}</h3>
                </div>
                <div class="stat-icon mood">
                    <i class="bi bi-emoji-smile-fill"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-info">
                    <p class="stat-label">Peringkat</p>
                    <h3 class="stat-value">{{ $rank }}</h3>
                </div>
                <div class="stat-icon rank">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Announcements Row -->
    <div class="row">
        <!-- Mood Chart -->
        <div class="col-lg-12 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Mood Overall 14 Hari</h3>
                    <p class="chart-subtitle">Rata-rata mood 14 hari terakhir: {{ $averageMood > 0 ? $averageMood.'/5' : '-' }}</p>
                </div>
                <div class="chart-content">
                    <canvas id="moodChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Announcements -->
        {{-- <div class="col-lg-5 mb-4">
            <div class="announcements-card">
                <div class="announcements-header">
                    <h3 class="announcements-title">Pengumuman Terbaru</h3>
                </div>
                <div class="announcements-content">
                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Ujian Matematika</h4>
                            <span class="announcement-badge">2 jam lagi</span>
                        </div>
                        <p class="announcement-description">Persiapkan diri untuk ujian matematika</p>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Libur Nasional</h4>
                            <span class="announcement-badge">3 hari lagi</span>
                        </div>
                        <p class="announcement-description">Sekolah libur pada tanggal 17 Agustus</p>
                    </div>

                    <div class="announcement-item">
                        <div class="announcement-header">
                            <h4 class="announcement-title-text">Ujian Bahasa Indonesia</h4>
                            <span class="announcement-badge besok">Besok</span>
                        </div>
                        <p class="announcement-description">Persiapkan diri untuk ujian bahasa indonesia</p>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    maintainAspectRatio: false,
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
                            max: 7, // Increased max for better spacing
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
        });
    </script>
@endsection
