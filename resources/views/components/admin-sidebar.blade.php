<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Close Button for Mobile -->
    <button class="sidebar-close" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
    </button>
    <!-- User Container -->
    <div class="user-container">
        <div class="user-profile">
            <div class="user-avatar">ADM</div>
            <div class="user-info">
                <p class="user-name">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="user-role">Administrator</p>
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
                    'href' => route('admin.dashboard'),
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->routeIs('admin.dashboard')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.kelas.index'),
                    'icon' => 'bi-building',
                    'text' => 'Kelas',
                    'active' => request()->routeIs('admin.kelas.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.tahun-akademik.index'),
                    'icon' => 'bi-calendar3',
                    'text' => 'Tahun Akademik',
                    'active' => request()->routeIs('admin.tahun-akademik.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.siswa.index'),
                    'icon' => 'bi-people-fill',
                    'text' => 'Data Siswa',
                    'active' => request()->routeIs('admin.siswa.index')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.guru.index'),
                    'icon' => 'bi-person-badge-fill',
                    'text' => 'Data Guru',
                    'active' => request()->routeIs('admin.guru.*')
                ])
            </li>
            
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.siswa.kehadiran.index'),
                    'icon' => 'bi-calendar-x',
                    'text' => 'Kehadiran Siswa',
                    'active' => request()->routeIs('admin.siswa.kehadiran.index')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.siswa.diary.index'),
                    'icon' => 'bi-calendar-x',
                    'text' => 'Mental Siswa',
                    'active' => request()->routeIs('admin.siswa.diary.index')
                ])
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
