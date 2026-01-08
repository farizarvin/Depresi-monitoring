@extends('layouts.siswa')

@section('title', 'Diaryku - Sistem Manajemen Siswa')

@php
    $pageTitle = 'Self Care';
    $pageSubtitle = 'Ruang nyaman untuk merawat kesehatan mentalmu';
@endphp

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(120deg, #89f7fe 0%, #66a6ff 100%); color: white;">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h2 class="mb-2 fw-bold"><i class="bi bi-flower1 me-2"></i>Self Care</h2>
                        <p class="mb-0 opacity-90" style="font-size: 1.1rem;">Ambil napas dalam, kamu aman di sini. Mari rawat diri bersama.</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="bi bi-stars" style="font-size: 5rem; opacity: 0.4;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($scores))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h4 class="fw-bold mb-0 text-primary"><i class="bi bi-clipboard-data me-2"></i> Hasil Pemeriksaan Kesehatan Mental Anda</h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 text-center">
                        {{-- Stress --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light">
                                <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Stres</h6>
                                @php
                                    $s = $scores['stress'];
                                    $sLabel = $s <= 14 ? 'Normal' : ($s <= 18 ? 'Ringan' : ($s <= 25 ? 'Sedang' : ($s <= 33 ? 'Parah' : 'Sangat Parah')));
                                    $sColor = $s <= 14 ? 'success' : ($s <= 18 ? 'info' : ($s <= 25 ? 'warning' : 'danger'));
                                @endphp
                                <span class="badge bg-{{ $sColor }} pill fs-5">{{ $sLabel }}</span>
                            </div>
                        </div>
                        {{-- Anxiety --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light">
                                <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Kecemasan</h6>
                                @php
                                    $a = $scores['anxiety'];
                                    $aLabel = $a <= 7 ? 'Normal' : ($a <= 9 ? 'Ringan' : ($a <= 14 ? 'Sedang' : ($a <= 19 ? 'Parah' : 'Sangat Parah')));
                                    $aColor = $a <= 7 ? 'success' : ($a <= 9 ? 'info' : ($a <= 14 ? 'warning' : 'danger'));
                                @endphp
                                <span class="badge bg-{{ $aColor }} pill fs-5">{{ $aLabel }}</span>
                            </div>
                        </div>
                        {{-- Depression --}}
                        <div class="col-md-4">
                            <div class="p-3 rounded bg-light">
                                <h6 class="text-uppercase text-muted fw-bold small mb-2">Tingkat Depresi</h6>
                                @php
                                    $d = $scores['depression'];
                                    $dLabel = $d <= 9 ? 'Normal' : ($d <= 13 ? 'Ringan' : ($d <= 20 ? 'Sedang' : ($d <= 27 ? 'Parah' : 'Sangat Parah')));
                                    $dColor = $d <= 9 ? 'success' : ($d <= 13 ? 'info' : ($d <= 20 ? 'warning' : 'danger'));
                                @endphp
                                <span class="badge bg-{{ $dColor }} pill fs-5">{{ $dLabel }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">
        {{-- Section: Aktivitas Self-Care --}}
        <div class="col-12">
            <h4 class="fw-bold text-dark mb-3"><i class="bi bi-stars text-warning me-2"></i>Aktivitas Self-Care untuk Remaja</h4>
        </div>

        {{-- 1. Mengelola Pikiran --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-lightbulb fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Mengelola Pikiran</h4>
                    </div>
                    <ul class="text-muted ps-3 mb-0" style="line-height: 1.6;">
                        <li class="mb-2"><strong>Percaya Diri:</strong> Belajar percaya pada kemampuan diri sendiri agar tidak terjebak pikiran negatif.</li>
                        <li class="mb-2"><strong>Sadari Pikiran Negatif:</strong> Jika merasa gagal, tanyakan: <em>"Apa ada cara lain melihat situasi ini?"</em></li>
                        <li class="mb-2"><strong>Sudut Pandang Seimbang:</strong> Latih menilai kejadian secara realistis, jangan langsung menyimpulkan hal buruk.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 2. Meningkatkan Kesejahteraan Diri --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-heart fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Meningkatkan Kesejahteraan</h4>
                    </div>
                    <ul class="text-muted ps-3 mb-0" style="line-height: 1.6;">
                        <li class="mb-2"><strong>Terima Keadaan:</strong> Sadari bahwa wajar jika tidak semua hal sesuai ekspektasi.</li>
                        <li class="mb-2"><strong>Aktivitas Sehat:</strong> Olahraga ringan, tidur cukup, makan teratur, dan bersosialisasi dengan orang yang nyaman.</li>
                        <li class="mb-2"><strong>Mindfulness:</strong> Nikmati saat ini dengan sadar. Fokus pada napas saat cemas atau perhatikan hal positif kecil.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 3. Mengembangkan Perilaku Proaktif --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Perilaku Proaktif</h4>
                    </div>
                    <ul class="text-muted ps-3 mb-0" style="line-height: 1.6;">
                        <li class="mb-2"><strong>Berani Terbuka:</strong> Ceritakan perasaanmu pada orang terpercaya (orang tua, guru BK, sahabat).</li>
                        <li class="mb-2"><strong>Cari Bantuan:</strong> Meminta bantuan bukan tanda lemah, melainkan tanda peduli pada diri sendiri.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 4. Aktivitas Relaksasi --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-purple bg-opacity-10 text-purple rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; color: #6f42c1;">
                            <i class="bi bi-wind fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Aktivitas Relaksasi</h4>
                    </div>
                    
                    <div class="accordion" id="accordionRelaksasi">
                        {{-- Box Breathing --}}
                        <div class="accordion-item border-0 mb-2 shadow-sm rounded">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                    <strong>🧘 Relaksasi Nafas Kotak (4-4-4-4)</strong>
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionRelaksasi">
                                <div class="accordion-body text-muted small">
                                    <p class="mb-2">Membantu menenangkan pikiran dan menurunkan stres.</p>
                                    <ol class="mb-0 ps-3">
                                        <li>Duduk/berdiri rileks.</li>
                                        <li>Tarik napas hidung (4 detik).</li>
                                        <li>Tahan napas (4 detik).</li>
                                        <li>Hembuskan lewat mulut (4 detik).</li>
                                        <li>Tahan napas (4 detik).</li>
                                        <li>Ulangi 4-6 kali.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        {{-- Butterfly Hug --}}
                        <div class="accordion-item border-0 shadow-sm rounded">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                    <strong>🦋 Butterfly Hug</strong>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionRelaksasi">
                                <div class="accordion-body text-muted small">
                                    <p class="mb-2">Menenangkan emosi dan memberi rasa aman.</p>
                                    <ol class="mb-0 ps-3">
                                        <li>Silangkan tangan di depan dada (peluk diri sendiri).</li>
                                        <li>Tepuk lembut bahu kanan & kiri bergantian (ritme pelan).</li>
                                        <li>Sambil menepuk, ambil napas pelan & rasakan emosi tanpa menghakimi.</li>
                                        <li>Lakukan 30-60 detik. Affiramsi: <em>"Aku aman, aku tenang"</em>.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
