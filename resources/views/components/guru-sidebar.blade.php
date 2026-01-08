<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Close Button for Mobile -->
    <button class="sidebar-close" id="sidebarClose">
        <i class="bi bi-x-lg"></i>
    </button>
    <!-- User Container -->
    <div class="user-container">
        <div class="user-profile">
            <div class="user-avatar">GURU</div>
            <div class="user-info">
                <p class="user-name">{{ Auth::user()->name ?? 'Guru' }}</p>
                <p class="user-role">Guru</p>
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
                    'href' => route('guru.dashboard'),
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->routeIs('guru.dashboard')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('guru.mood.index'),
                    'icon' => 'bi-emoji-smile',
                    'text' => 'Laporan Mood',
                    'active' => request()->routeIs('guru.mood.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('guru.nilai.index'),
                    'icon' => 'bi-file-earmark-text',
                    'text' => 'Laporan Nilai',
                    'active' => request()->routeIs('guru.nilai.*')
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
