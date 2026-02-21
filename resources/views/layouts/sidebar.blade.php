<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="brand-link d-flex align-items-center justify-content-between">
        <span>
            <i class="fas fa-school brand-icon"></i>
            <span class="brand-text font-weight-light">SMA Muh. Kasihan</span>
        </span>
        <span class="sidebar-close-mobile d-inline-flex d-md-none" data-widget="pushmenu" role="button" title="Tutup Menu">
            <i class="fas fa-times"></i>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle img-circle elevation-2"></i>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                <small class="text-light">{{ ucfirst(Auth::user()->role) }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="menu-section-label">Navigasi</li>
                
                @if(Auth::user()->isAdmin())
                <!-- Menu Admin -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.data-siswa.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.data-siswa.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Siswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.user-management.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.user-management.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Manajemen User</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.jenis-pembayaran.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.jenis-pembayaran.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Jenis Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.laporan.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Pengaturan Sistem</p>
                    </a>
                </li>

                @elseif(Auth::user()->isBendahara())
                <!-- Menu Bendahara - SUDAH DIPERBAIKI -->
                <li class="nav-item">
                    <a href="{{ route('bendahara.dashboard') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bendahara.approval-pembayaran.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.approval-pembayaran.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-check-circle"></i>
                        <p>Approval Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bendahara.data-siswa.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.data-siswa.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Siswa</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bendahara.jenis-pembayaran.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.jenis-pembayaran.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-money-bill-wave"></i>
                        <p>Jenis Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bendahara.penagihan.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.penagihan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>Manajemen Penagihan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('bendahara.laporan-keuangan.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('bendahara.laporan-keuangan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Laporan Keuangan</p>
                    </a>
                </li>

                @elseif(Auth::user()->isSiswa())
                <!-- Menu Siswa -->
                <li class="nav-item">
                    <a href="{{ route('siswa.dashboard') }}" class="nav-link menu-link-modern {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.tagihan.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('siswa.tagihan.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>Pembayaran</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('siswa.profile.index') }}" class="nav-link menu-link-modern {{ request()->routeIs('siswa.profile.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
                @endif

                <!-- Logout -->
                <li class="nav-item mt-3">
                    <a href="{{ route('logout') }}" class="nav-link menu-link-modern text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>