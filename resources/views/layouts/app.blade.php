<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Kampus - Professional Asset Management')</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js CDN (Wajib di <head> agar directive x-data ter-parse sempurna) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-color: #0d6efd;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f6f9;
            overflow-x: hidden;
        }

        /* Sidebar Desktop */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
            padding: 1.5rem 1rem;
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-width: 0;
        }
        .nav-link {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.3rem;
            transition: all 0.2s;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
        .nav-link.active {
            background-color: #e7f1ff;
            color: var(--primary-color) !important;
        }

        /* Penyesuaian Khusus Layar HP / Mobile (< 768px) */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
            }
        }

        /* Badge Styles */
        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: #ffffff;
        }
        .badge-baik { background-color: #d1e7dd; color: #0f5132; font-weight: 600; }
        .badge-rusak { background-color: #f8d7da; color: #842029; font-weight: 600; }
        .badge-ringan { background-color: #fff3cd; color: #664d03; font-weight: 600; }
    </style>
</head>
<body>

    <!-- Topbar Mobile (Hanya Tampil di Layar HP < 768px) -->
    <div class="d-md-none bg-white border-bottom px-3 py-2.5 sticky-top d-flex align-items-center justify-content-between shadow-sm">
        <div class="d-flex align-items-center">
            <button class="btn btn-light border p-1 px-2 me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            <span class="fw-bold text-dark">Inventaris<span class="text-primary">Kampus</span></span>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary">FMIPA</span>
    </div>

    <!-- Offcanvas Sidebar Mobile (Drawer Menu saat Tombol Hamburger Diklik) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" style="width: 280px;">
        <div class="offcanvas-header border-bottom">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white p-2 rounded-3 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="bi bi-box-seam fs-6"></i>
                </div>
                <h6 class="fw-bold text-dark mb-0">Inventaris<span class="text-primary">Kampus</span></h6>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column justify-content-between">
            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('barang.index') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam-fill me-2"></i> Data Aset
                </a>
                <a class="nav-link {{ request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('barang.riwayat_transfer') }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat
                </a>
            </div>
            <div class="border-top pt-3">
                <div class="d-flex align-items-center">
                    <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2">
                        <i class="bi bi-person-fill text-secondary fs-5"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold fs-7">Admin FMIPA</h6>
                        <small class="text-muted fs-8">FMIPA UNIMED</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Navigation Desktop (Hanya Tampil di Screen ≥ 768px) -->
    <div class="sidebar d-none d-md-flex flex-column justify-content-between">
        <div>
            <div class="d-flex align-items-center mb-4 px-2">
                <div class="bg-primary text-white p-2 rounded-3 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Inventaris<span class="text-primary">Kampus</span></h5>
            </div>

            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('barang.index') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam-fill me-2"></i> Data Aset
                </a>
                <a class="nav-link {{ request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('barang.riwayat_transfer') }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat
                </a>
            </div>
        </div>

        <div class="border-top pt-3 px-2">
            <div class="d-flex align-items-center">
                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2">
                    <i class="bi bi-person-fill text-secondary fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold fs-7">Admin FMIPA</h6>
                    <small class="text-muted fs-8">FMIPA UNIMED</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>