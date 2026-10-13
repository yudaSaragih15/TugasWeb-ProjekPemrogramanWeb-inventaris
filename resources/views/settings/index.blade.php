@extends('layouts.app')

@section('title', 'Pengaturan Preferensi')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-sliders me-2 text-primary"></i>Preferensi Tampilan</h4>
                    <p class="text-muted mb-0 small">Sesuaikan tampilan aplikasi agar nyaman digunakan.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success rounded-4">{{ session('success') }}</div>
            @endif

            <form id="preferencesForm">
                <!-- 1. DARK MODE TOGGLE -->
                <div class="d-flex justify-content-between align-items-center p-3 mb-3 border rounded-3 bg-opacity-10">
                    <div>
                        <h6 class="fw-bold mb-1">Mode Gelap (Dark Mode)</h6>
                        <small class="text-muted">Nyaman untuk mata saat bekerja di ruangan minim cahaya.</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggleDarkMode" onchange="toggleTheme(this.checked)" style="width: 3em; height: 1.5em;">
                    </div>
                </div>

                <!-- 2. UKURAN FONT -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Ukuran Teks Interface</label>
                    <select class="form-select rounded-3" id="selectFontSize" onchange="changeFontSize(this.value)">
                        <option value="small">Kecil (Muat lebih banyak data)</option>
                        <option value="normal">Normal (Standar)</option>
                        <option value="large">Besar (Lebih mudah dibaca)</option>
                    </select>
                </div>

                <!-- 3. ITEMS PER PAGE -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Default Data Per Halaman</label>
                    <select class="form-select rounded-3" id="selectPerPage" onchange="localStorage.setItem('perPage', this.value)">
                        <option value="10">10 Baris</option>
                        <option value="25">25 Baris</option>
                        <option value="50">50 Baris</option>
                        <option value="100">100 Baris</option>
                    </select>
                    <small class="text-muted">*Berlaku saat reload halaman tabel.</small>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div class="d-flex gap-2">
                        <button type="button" onclick="resetPreferences()" class="btn btn-light border rounded-pill px-4">Reset Default</button>
                        <button type="button" onclick="alert('Preferensi tersimpan otomatis!')" class="btn btn-primary rounded-pill px-4 fw-bold">
                            <i class="bi bi-check-lg me-1"></i> Selesai
                        </button>
                    </div>

                    <!-- TOMBOL LOGOUT SINGLE-TAB SAFE -->
                    <button type="button" onclick="hardLogout()" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Load saved perPage value on init
    document.addEventListener('DOMContentLoaded', () => {
        const perPage = localStorage.getItem('perPage') || '10';
        if(document.getElementById('selectPerPage')) {
            document.getElementById('selectPerPage').value = perPage;
        }
    });

    function resetPreferences() {
        localStorage.removeItem('theme');
        localStorage.removeItem('fontSize');
        localStorage.removeItem('perPage');
        location.reload();
    }

    /**
     * SINGLE-TAB SAFE LOGOUT
     * Menggunakan Fetch API untuk logout di background, 
     * lalu memaksa browser mengganti history state ke /login
     */
    function hardLogout() {
        if(!confirm('Yakin ingin keluar dari aplikasi?')) return;

        // Ambil token CSRF dari meta tag atau input hidden
        const token = document.querySelector('meta[name="csrf-token"]')?.content 
                   || document.querySelector('input[name="_token"]')?.value;

        // Panggil route logout via AJAX
        fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin' // Penting: mengirim cookie session
        })
        .then(response => {
            if (response.ok) {
                // Hapus localStorage preferensi
                localStorage.clear();
                
                // GANTI HISTORY STATE (Bukan sekadar redirect)
                // Ini membuat browser menganggap halaman saat ini ADALAH /login
                window.history.replaceState(null, '', '/login');
                
                // Muat ulang halaman /login dari server (bypass cache)
                window.location.reload(true); 
            } else {
                // Fallback jika AJAX gagal
                window.location.href = '/login';
            }
        })
        .catch(err => {
            console.error('Logout error:', err);
            window.location.href = '/login';
        });
    }
</script>
@endsection