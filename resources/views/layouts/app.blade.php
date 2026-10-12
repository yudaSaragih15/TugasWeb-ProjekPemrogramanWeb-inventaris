<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Inventaris Kampus - Professional Asset Management')</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .sidebar-desktop {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #ffffff;
            border-right: 1px solid #e9ecef;
            z-index: 1000;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            min-width: 0;
        }

        .nav-link {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.3rem;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .nav-link:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }
        .nav-link.active {
            background-color: #e7f1ff;
            color: var(--primary-color) !important;
        }

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: #ffffff;
        }
        
        .btn-settings-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            color: #6c757d;
            transition: all 0.2s;
            border: none;
            flex-shrink: 0;
        }
        .btn-settings-icon:hover {
            background-color: #e9ecef;
            color: #212529;
            transform: rotate(90deg);
        }

        /* Penyesuaian Khusus Layar HP / Mobile (< 768px) */
        @media (max-width: 767.98px) {
            .sidebar-desktop {
                display: none !important;
            }
            .main-content {
                margin-left: 0 !important;
                padding: 1rem !important;
            }
        }

        /* ==========================================
           DARK MODE - HITAM DOFF (MATTE BLACK)
           ========================================== */
        
        [data-bs-theme="dark"] body {
            background-color: #121212 !important; 
            color: #e0e0e0 !important;
        }

        [data-bs-theme="dark"] .sidebar-desktop,
        [data-bs-theme="dark"] .offcanvas,
        [data-bs-theme="dark"] .d-md-none {
            background-color: #1a1a24 !important; 
            border-color: #2b2b3c !important;
        }

        /* Paksa semua card, kontainer putih, dan elemen background putih menjadi hitam doff */
        [data-bs-theme="dark"] .bg-white,
        [data-bs-theme="dark"] .card,
        [data-bs-theme="dark"] .card-custom,
        [data-bs-theme="dark"] .bg-light {
            background-color: #1e1e2d !important;
            border-color: #2b2b3c !important;
            color: #e0e0e0 !important;
        }

        /* Memperbaiki semua warna teks utama agar terang */
        [data-bs-theme="dark"] .text-dark,
        [data-bs-theme="dark"] .text-muted,
        [data-bs-theme="dark"] p,
        [data-bs-theme="dark"] span,
        [data-bs-theme="dark"] h1, [data-bs-theme="dark"] h2, 
        [data-bs-theme="dark"] h3, [data-bs-theme="dark"] h4, 
        [data-bs-theme="dark"] h5, [data-bs-theme="dark"] h6 {
            color: #e0e0e0 !important;
        }

        /* Memperbaiki Form Input & Select */
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select,
        [data-bs-theme="dark"] textarea {
            background-color: #121212 !important;
            border-color: #34344a !important;
            color: #ffffff !important;
        }
        [data-bs-theme="dark"] .form-control::placeholder {
            color: #8b8b9f !important;
        }

        /* Memperbaiki Tabel & Header Tabel yang sering belang putih */
        [data-bs-theme="dark"] .table {
            color: #e0e0e0 !important;
            border-color: #34344a !important;
        }
        [data-bs-theme="dark"] .table>:not(caption)>*>* {
            background-color: transparent !important;
            color: #e0e0e0 !important;
            border-color: #34344a !important;
        }
        [data-bs-theme="dark"] .table thead,
        [data-bs-theme="dark"] .table th {
            background-color: #171722 !important;
            color: #ffffff !important;
            border-color: #34344a !important;
        }

        /* Navigasi & Sidebar Link Hover dalam Dark Mode */
        [data-bs-theme="dark"] .nav-link {
            color: #9e9ea7;
        }
        [data-bs-theme="dark"] .nav-link:hover {
            background-color: #2b2b3c;
            color: #0d6efd;
        }
        [data-bs-theme="dark"] .nav-link.active {
            background-color: rgba(13, 110, 253, 0.15); 
        }

        /* Tombol Pengaturan */
        [data-bs-theme="dark"] .btn-settings-icon {
            background-color: #2b2b3c;
            color: #9e9ea7;
        }
        [data-bs-theme="dark"] .btn-settings-icon:hover {
            color: #ffffff;
        }

        /* ==========================================
           TAMBAHAN SPESIFIK UNTUK KOTAK TAB MASTER DATA
           ========================================== */
        [data-bs-theme="dark"] ul.nav.nav-pills,
        [data-bs-theme="dark"] div.bg-white,
        [data-bs-theme="dark"] .rounded-pill {
            background-color: #1e1e2d !important;
            border-color: #2b2b3c !important;
        }

        [data-bs-theme="dark"] .nav-pills .nav-link {
            color: #b0b0c0 !important;
            background-color: transparent !important;
        }

        [data-bs-theme="dark"] .nav-pills .nav-link.active {
            background-color: #0d6efd !important;
            color: #ffffff !important;
        }
        
        /* Paksa kotak navigasi tab master data berubah total di dark mode */
        [data-bs-theme="dark"] .main-content .nav-pills.bg-white {
            background-color: #1e1e2d !important;
            border: 1px solid #2b2b3c !important;
        }

        /* Paksa teks tab biasa menjadi terang */
        [data-bs-theme="dark"] .main-content .nav-pills .nav-link {
            color: #d0d0d0 !important;
            background-color: transparent !important;
        }

        /* Paksa teks tab yang sedang aktif (warna biru) menjadi putih bersih */
        [data-bs-theme="dark"] .main-content .nav-pills .nav-link.active {
            color: #ffffff !important;
            background-color: #0d6efd !important;
        }
        
    </style>

    <!-- Script Dark Mode Initialization -->
    <script>
        const getPreferredTheme = () => {
            return localStorage.getItem('theme') || 'light';
        }
        const setTheme = function (theme) {
            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', theme);
            }
        }
        setTheme(getPreferredTheme());
    </script>

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
                <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam-fill me-2"></i> Data Aset
                </a>
                <a class="nav-link {{ request()->routeIs('transfer.*') || request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('transfer.index') }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat
                </a>
                <a class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }}" href="{{ route('master-data.index') }}">
                    <i class="bi bi-database-fill-gear me-2"></i> Master Data
                </a>
                <a class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}">
                    <i class="bi bi-tools me-2"></i> Pemeliharaan
                </a>
                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Manajemen Pengguna
                </a>
            </div>
            <div class="border-top pt-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="min-width: 36px; height: 36px;">
                            <i class="bi bi-person-fill text-secondary fs-5"></i>
                        </div>
                        <div class="text-truncate">
                            <h6 class="mb-0 fw-bold fs-7 text-truncate">Admin FMIPA</h6>
                            <small class="text-muted fs-8 text-truncate d-block">FMIPA UNIMED</small>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button type="button" class="btn-settings-icon ms-1 theme-toggle" title="Ganti Tema">
                            <i class="bi bi-moon-fill theme-icon"></i>
                        </button>
                        <a href="{{ route('settings.index') }}" class="btn-settings-icon ms-1" title="Pengaturan">
                            <i class="bi bi-gear-fill"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Navigation Desktop (Hanya Tampil di Screen ≥ 768px) -->
    <div class="sidebar-desktop d-none d-md-flex">
        <div>
            <!-- Logo Area -->
            <div class="d-flex align-items-center mb-4 px-2">
                <div class="bg-primary text-white p-2 rounded-3 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                    <i class="bi bi-box-seam fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Inventaris<span class="text-primary">Kampus</span></h5>
            </div>

            <!-- Menu Navigasi Desktop -->
            <div class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                </a>
                
                <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}">
                    <i class="bi bi-box-seam-fill me-2"></i> Data Aset
                </a>
                
                <a class="nav-link {{ request()->routeIs('transfer.*') || request()->routeIs('barang.riwayat_transfer') ? 'active' : '' }}" href="{{ route('transfer.index') }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Transfer & Riwayat
                </a>

                <!-- MENU BARU -->
                <a class="nav-link {{ request()->routeIs('master-data.*') ? 'active' : '' }}" href="{{ route('master-data.index') }}">
                    <i class="bi bi-database-fill-gear me-2"></i> Master Data
                </a>

                <a class="nav-link {{ request()->routeIs('maintenance.*') ? 'active' : '' }}" href="{{ route('maintenance.index') }}">
                    <i class="bi bi-tools me-2"></i> Pemeliharaan
                </a>

                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="bi bi-people-fill me-2"></i> Manajemen Pengguna
                </a>
            </div>
        </div>

        <!-- User Profile & Settings Icon Desktop -->
        <div class="border-top pt-3 px-2 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center overflow-hidden">
                <div class="bg-secondary bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="min-width: 36px; height: 36px;">
                    <i class="bi bi-person-fill text-secondary fs-5"></i>
                </div>
                <div class="text-truncate">
                    <h6 class="mb-0 fw-bold fs-7 text-truncate" title="Admin FMIPA">Admin FMIPA</h6>
                    <small class="text-muted fs-8 text-truncate d-block">FMIPA UNIMED</small>
                </div>
            </div>
            
            <div class="d-flex">
                <button type="button" class="btn-settings-icon ms-1 theme-toggle" title="Ganti Tema">
                    <i class="bi bi-moon-fill theme-icon"></i>
                </button>
                <a href="{{ route('settings.index') }}" class="btn-settings-icon ms-1" title="Pengaturan Aplikasi">
                    <i class="bi bi-gear-fill"></i>
                </a>
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
    <!-- Script Toggle Tombol Tema -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtns = document.querySelectorAll('.theme-toggle');
            
            const updateIcon = (theme) => {
                const icons = document.querySelectorAll('.theme-icon');
                icons.forEach(icon => {
                    if(theme === 'dark') {
                        icon.classList.remove('bi-moon-fill');
                        icon.classList.add('bi-sun-fill');
                    } else {
                        icon.classList.remove('bi-sun-fill');
                        icon.classList.add('bi-moon-fill');
                    }
                });
            }

            // Set ikon pertama kali
            updateIcon(getPreferredTheme());

            // Saat tombol diklik
            toggleBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    let currentTheme = document.documentElement.getAttribute('data-bs-theme');
                    let newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    
                    localStorage.setItem('theme', newTheme);
                    document.documentElement.setAttribute('data-bs-theme', newTheme);
                    updateIcon(newTheme);
                });
            });
        });
    </script>
    @yield('scripts')
</body>
</html>