<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Close Button for Mobile -->
    <button class="sidebar-close" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
    </button>
    <!-- User Container -->
    @php
        $currentSiswa = Illuminate\Support\Facades\Auth::user()->siswa ?? null;
        $namaLengkap = $currentSiswa ? $currentSiswa->nama_lengkap : 'Siswa';
        // Generate initials from name (e.g., "John Doe" -> "JD")
        $nameParts = explode(' ', $namaLengkap);
        $initials = '';
        foreach (array_slice($nameParts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }
    @endphp
    <div class="user-container">
        <div class="user-profile">
            <div class="user-avatar">{{ $initials }}</div>
            <div class="user-info">
                <p class="user-name">{{ $namaLengkap }}</p>
                <p class="user-role">Siswa</p>
            </div>
        </div>
        <div class="welcome-divider">
            <p class="welcome-text">Selamat Datang! 👋</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <ul class="list-unstyled">
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/dashboard',
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->is('siswa/dashboard')
                ])
                                    </li>
                                    <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/presensi',
                    'icon' => 'bi-calendar-check',
                    'text' => 'Absensi',
                    'active' => request()->is('siswa/presensi')
                ])
            </li>
            {{-- <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/jadwal',
                    'icon' => 'bi-calendar-event',
                    'text' => 'Jadwal',
                    'active' => request()->is('siswa/jadwal')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/laporan-nilai',
                    'icon' => 'bi-file-earmark-text',
                    'text' => 'Laporan Nilai',
                    'active' => request()->is('siswa/laporan-nilai')
                ])
            </li> --}}
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/statistik',
                    'icon' => 'bi-bar-chart-fill',
                    'text' => 'Statistik',
                    'active' => request()->is('siswa/statistik')
                ])
            @php
                $siswa = Illuminate\Support\Facades\Auth::user()->siswa;
                $showSelfCare=$siswa->is_depressed || $siswa->need_selfcare;
                // $hasFilledDass = $siswa && $siswa->kuesionerResults()->exists();
                // $showSelfCare = false;

                // if ($hasFilledDass) {
                //     $latestResult = $siswa->kuesionerResults()->latest()->first();
                //     if ($latestResult) {
                //         $scores = $latestResult->calculateScores();
                //         // Thresholds for Normal: Depression <= 9, Anxiety <= 7, Stress <= 14
                //         if ($scores['depression'] <= 9 && $scores['anxiety'] <= 7 && $scores['stress'] <= 14) {
                //             $showSelfCare = true;
                //         }
                //     }
                // }
            @endphp

            <li class="nav-item">
                @if($showSelfCare)
                    @include('components.buttons.button-sidebar', [
                        'href' => '/siswa/diaryku',
                        'icon' => 'bi-journal-medical',
                        'text' => 'Self Care',
                        'active' => request()->is('siswa/diaryku')
                    ])
                @endif
            </li>
        </ul>
    </nav>

    <!-- Logout Section -->
    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout" style="background: #dc3545; color: white; border: none; width: 100%; text-align: left; display: flex; align-items: center; cursor: pointer; padding: 10px 15px; border-radius: 10px; transition: background 0.3s;">
                <i class="bi bi-box-arrow-right"></i>
                <span style="margin-left: 10px; font-weight: 500;">Keluar</span>
            </button>
        </form>
    </div>
</aside>
