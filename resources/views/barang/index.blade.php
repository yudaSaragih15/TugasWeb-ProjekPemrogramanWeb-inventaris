@extends('layouts.app')

@section('title', 'Daftar Inventaris Aset')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Daftar Inventaris Aset</h3>
        <p class="text-muted mb-0">Kelola dan pantau seluruh aset kampus yang terdaftar.</p>
    </div>
    
    <!-- MENU AKSI DROPDOWN -->
    <div class="dropdown">
        <button class="btn btn-dark dropdown-toggle px-4 py-2 rounded-3 fw-bold d-flex align-items-center gap-2" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list-ul"></i> Menu Aksi
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="dropdownMenuButton">
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('barang.create') }}">
                    <i class="bi bi-plus-circle-fill text-primary"></i> Tambah Aset Baru
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('barang.export.excel') }}">
                    <i class="bi bi-file-earmark-excel-fill text-success"></i> Export ke Excel
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- SEARCH & FILTER BAR -->
<div class="card card-custom p-3 mb-4 border-0 shadow-sm">
    <form action="{{ route('barang.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold small text-uppercase text-muted">Cari Aset</label>
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Nama atau Kode Barang..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small text-uppercase text-muted">Filter Kategori</label>
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small text-uppercase text-muted">Filter Kondisi</label>
            <select name="kondisi" class="form-select">
                <option value="">Semua Kondisi</option>
                @foreach($kondisis as $kon)
                    <option value="{{ $kon }}" {{ request('kondisi') == $kon ? 'selected' : '' }}>{{ $kon }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-funnel"></i> Filter</button>
            <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<!-- TABEL DATA ASET -->
<div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 50px;">No</th>
                    <th style="width: 80px;">Foto</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $index => $barang)
                <tr>
                    <td class="ps-4 text-muted">{{ $barangs->firstItem() + $index }}</td>
                    
                    <!-- KOLOM FOTO (DIPERBAIKI DENGAN CEK FISIK FILE) -->
                    <td>
                        @if($barang->foto && Storage::disk('public')->exists($barang->foto))
                            <a href="{{ Storage::url($barang->foto) }}" target="_blank" title="Lihat Foto">
                                <img src="{{ Storage::url($barang->foto) }}" 
                                     alt="{{ $barang->nama_barang }}" 
                                     class="rounded shadow-sm" 
                                     style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;">
                            </a>
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" 
                                 style="width: 50px; height: 50px;" title="Foto tidak tersedia">
                                <i class="bi bi-image fs-5"></i>
                            </div>
                        @endif
                    </td>

                    <td class="fw-bold text-dark">{{ $barang->kode_barang }}</td>
                    <td class="fw-semibold text-primary">{{ $barang->nama_barang }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">{{ $barang->kategori }}</span></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3"><i class="bi bi-geo-alt me-1"></i>{{ $barang->lokasi }}</span></td>
                    
                    <td>
                        <span class="fw-bold {{ $barang->jumlah < 5 ? 'text-danger' : 'text-dark' }}">
                            {{ $barang->jumlah }} Unit
                        </span>
                        @if($barang->jumlah < 5)
                            <i class="bi bi-exclamation-triangle-fill text-warning ms-1" title="Stok Menipis!"></i>
                        @endif
                    </td>

                    <td>
                        @if($barang->kondisi == 'Baik')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Baik</span>
                        @elseif($barang->kondisi == 'Rusak Ringan')
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">Rusak Ringan</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="text-end pe-4">
                        <div class="btn-group">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin memindahkan aset ini ke arsip?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus (Arsip)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- MODAL PREVIEW FOTO -->
                @if($barang->foto && Storage::disk('public')->exists($barang->foto))
                <div class="modal fade" id="fotoModal{{ $barang->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0">
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title fw-bold">Detail Foto: {{ $barang->nama_barang }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center p-4 bg-light">
                                <img src="{{ Storage::url($barang->foto) }}" class="img-fluid rounded shadow" style="max-height: 70vh;">
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-3 opacity-50"></i>
                        <p class="mb-0">Tidak ada data aset yang ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top bg-white d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Menampilkan {{ $barangs->firstItem() ?? 0 }} - {{ $barangs->lastItem() ?? 0 }} dari {{ $barangs->total() }} data
        </div>
        <div>
            {{ $barangs->links() }}
        </div>
    </div>
</div>

<!-- TOMBOL LIHAT ARSIP -->
<div class="mt-4 text-end">
    <a href="{{ route('barang.trashed') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
        <i class="bi bi-archive me-1"></i> Lihat Data Arsip
    </a>
</div>
@endsection