@extends('layouts.app')
@section('title', 'Master Data')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Master Data</h3>
        <p class="text-muted mb-0">Kelola referensi Lokasi Ruangan dan Kategori Aset agar input data lebih rapi.</p>
    </div>
</div>

<!-- Navigasi Tab -->
<ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm d-inline-flex">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab == 'lokasi' ? 'active' : '' }}" 
           href="{{ route('master-data.index', ['tab' => 'lokasi']) }}">
           <i class="bi bi-geo-alt me-2"></i>Data Lokasi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab == 'kategori' ? 'active' : '' }}" 
           href="{{ route('master-data.index', ['tab' => 'kategori']) }}">
           <i class="bi bi-tags me-2"></i>Data Kategori
        </a>
    </li>
</ul>

@if($activeTab == 'lokasi')
<div class="row g-4">
    <!-- Form Tambah Lokasi -->
    <div class="col-md-4">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Lokasi Baru</h5>
            <form action="{{ route('master-data.lokasi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lokasi / Ruangan</label>
                    <input type="text" name="nama_lokasi" class="form-control" placeholder="Cth: Lab Komputer 1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Gedung (Opsional)</label>
                    <input type="text" name="gedung" class="form-control" placeholder="Cth: Gedung A">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Lokasi</button>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Lokasi -->
    <div class="col-md-8">
        <div class="card card-custom p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light"><tr><th class="ps-4">Nama Lokasi</th><th>Gedung</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                        @forelse($lokasis as $lokasi)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $lokasi->nama_lokasi }}</td>
                            <td>{{ $lokasi->gedung ?? '-' }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('master-data.lokasi.destroy', $lokasi->id) }}" method="POST" onsubmit="return confirm('Hapus lokasi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data lokasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@else 
<!-- KONTEN TAB KATEGORI -->
<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle me-2 text-success"></i>Tambah Kategori Baru</h5>
            <form action="{{ route('master-data.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Cth: Elektronik" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold">Simpan Kategori</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card card-custom p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light"><tr><th class="ps-4">Nama Kategori</th><th>Deskripsi</th><th class="text-end pe-4">Aksi</th></tr></thead>
                    <tbody>
                        @forelse($kategoris as $kat)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $kat->nama_kategori }}</td>
                            <td class="text-muted small">{{ Str::limit($kat->deskripsi, 50) }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('master-data.kategori.destroy', $kat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-4 text-muted">Belum ada data kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

@endsection