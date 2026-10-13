<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Kampus')</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root { --primary-color: #0d6efd; --sidebar-width: 260px; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f6f9; overflow-x: hidden; }
        .sidebar-desktop { width: var(--sidebar-width); height: 100vh; position: fixed; top: 0; left: 0; background-color: #ffffff; border-right: 1px solid #e9ecef; z-index: 1000; padding: 1.5rem 1rem; display: flex; flex-direction: column; justify-content: space-between; }
        .main-content { margin-left: var(--sidebar-width); padding: 2rem; min-height: 100vh; min-width: 0; }
        .nav-link { border-radius: 10px; padding: 0.75rem 1rem; font-weight: 600; color: #6c757d; margin-bottom: 0.3rem; transition: all 0.2s; text-decoration: none; display: flex; align-items: center; }
        .nav-link:hover { background-color: #f8f9fa; color: var(--primary-color); }
        .nav-link.active { background-color: #e7f1ff; color: var(--primary-color) !important; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); background: #ffffff; }
        .btn-settings-icon { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; color: #6c757d; transition: all 0.2s; border: none; flex-shrink: 0; }
        .btn-settings-icon:hover { background-color: #e9ecef; color: #212529; transform: rotate(90deg); }
        
        @media (max-width: 767.98px) {
            .sidebar-desktop { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 1rem !important; }
        }
    </style>
</head>
<body>

    <!-- Topbar Mobile -->
    <div class="d-md-none bg-white border-bottom px-3 py-2.5 sticky-top d-flex align-items-center justify-content-between shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn btn-light border p-1 px-2 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            <span class="fw-bold text-dark">Inventaris<span class="text-primary">Kampus</span></span>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary">{{ auth()->user()->fakultas ?? 'UNIMED' }}</span>
    </div>

    <!-- Offcanvas Sidebar Mobile -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" style="width: 280px;">
        <div class="offcanvas-header border-bottom">
            <h6 class="fw-bold text-dark mb-0">Inventaris<span class="text-primary">Kampus</span></h6>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}"><i class="bi bi-box-seam-fill me-2"></i> Data Aset</a>
                <a class="nav-link {{ request()->routeIs('transfer.*') || request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('transfer.index') }}"><i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat</a>
                
                @if(auth()->user()->role === 'super_admin')
                <a class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }}" href="{{ route('master-data.index') }}"><i class="bi bi-database-fill-gear me-2"></i> Master Data</a>
                @endif

                <a class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}"><i class="bi bi-tools me-2"></i> Pemeliharaan</a>
                
                @if(auth()->user()->role === 'super_admin')
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i> Manajemen Pengguna</a>
                @endif
            </div>
            <div class="border-top pt-3">
                <div class="d-flex align-items-center justify-content-between">
                    <small class="text-muted">{{ auth()->user()->name }} ({{ auth()->user()->fakultas }})</small>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2"><i class="bi bi-box-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Desktop -->
    <div class="sidebar-desktop d-none d-md-flex">
        <div>
            <div class="d-flex align-items-center mb-4 px-2">
                <div class="bg-primary text-white p-2 rounded-3 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;"><i class="bi bi-box-seam fs-5"></i></div>
                <h5 class="fw-bold text-dark mb-0">Inventaris<span class="text-primary">Kampus</span></h5>
            </div>

            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill me-2"></i> Dashboard</a>
                <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}"><i class="bi bi-box-seam-fill me-2"></i> Data Aset</a>
                <a class="nav-link {{ request()->routeIs('transfer.*') || request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('transfer.index') }}"><i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat</a>
                
                @if(auth()->user()->role === 'super_admin')
                <a class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }}" href="{{ route('master-data.index') }}"><i class="bi bi-database-fill-gear me-2"></i> Master Data</a>
                @endif

                <a class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}"><i class="bi bi-tools me-2"></i> Pemeliharaan</a>
                
                @if(auth()->user()->role === 'super_admin')
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-people-fill me-2"></i> Manajemen Pengguna</a>
                @endif
            </div>
        </div>

        <div class="border-top pt-3 px-2 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center overflow-hidden">
                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="min-width: 36px; height: 36px;"><i class="bi bi-person-fill text-secondary fs-5"></i></div>
                <div class="text-truncate">
                    <h6 class="mb-0 fw-bold fs-7 text-truncate">{{ auth()->user()->name }}</h6>
                    <small class="text-muted fs-8 text-truncate d-block">{{ auth()->user()->fakultas }} | {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                </div>
            </div>
            <a href="{{ route('settings.index') }}" class="btn-settings-icon ms-2" title="Pengaturan"><i class="bi bi-gear-fill"></i></a>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>