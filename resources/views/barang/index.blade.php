@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Daftar Inventaris Aset</h3>
        <p class="text-muted mb-0">Kelola dan pantau seluruh aset kampus yang terdaftar.</p>
    </div>
    <div class="d-flex gap-2">
        <!-- FITUR 2: TOMBOL EXPORT -->
        <a href="{{ route('barang.export.excel') }}" class="btn btn-success px-3 py-2 rounded-3 fw-bold">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
        <a href="{{ route('barang.create') }}" class="btn btn-primary px-3 py-2 rounded-3 fw-bold">
            <i class="bi bi-plus-lg me-1"></i> Tambah Aset
        </a>
    </div>
</div>

<!-- FITUR 1: SEARCH & FILTER BAR -->
<div class="card card-custom p-3 mb-4 border-0 shadow-sm">
    <form action="{{ route('barang.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label fw-semibold small">Cari Aset</label>
            <input type="text" name="search" class="form-control" placeholder="Nama atau Kode Barang..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Filter Kategori</label>
            <select name="kategori" class="form-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold small">Filter Kondisi</label>
            <select name="kondisi" class="form-select">
                <option value="">Semua Kondisi</option>
                @foreach($kondisis as $kon)
                    <option value="{{ $kon }}" {{ request('kondisi') == $kon ? 'selected' : '' }}>{{ $kon }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i></a>
        </div>
    </form>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Foto</th> <!-- KOLOM FOTO -->
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $index => $barang)
                <tr>
                    <td>{{ $barangs->firstItem() + $index }}</td>
                    
                    <!-- FITUR 5: TAMPILAN FOTO -->
                    <td>
                        @if($barang->foto)
                            <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                        @else
                            <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>

                    <td class="fw-semibold">{{ $barang->kode_barang }}</td>
                    <td class="fw-semibold text-primary">{{ $barang->nama_barang }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark border">{{ $barang->kategori }}</span></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info border border-info">{{ $barang->lokasi }}</span></td>
                    
                    <!-- FITUR 4: INDIKATOR STOK RENDAH -->
                    <td>
                        <span class="fw-bold {{ $barang->jumlah < 5 ? 'text-danger' : '' }}">
                            {{ $barang->jumlah }} Unit
                        </span>
                        @if($barang->jumlah < 5)
                            <i class="bi bi-exclamation-triangle-fill text-danger ms-1" title="Stok Menipis!"></i>
                        @endif
                    </td>

                    <td>
                        @if($barang->kondisi == 'Baik')
                            <span class="badge bg-success">Baik</span>
                        @elseif($barang->kondisi == 'Rusak Ringan')
                            <span class="badge bg-warning text-dark">Rusak Ringan</span>
                        @else
                            <span class="badge bg-danger">Rusak Berat</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-outline-warning me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin memindahkan aset ini ke arsip?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                        Tidak ada data yang sesuai dengan filter.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        {{ $barangs->links() }}
        
        <!-- LINK KE HALAMAN ARSIP (SOFT DELETE) -->
        <a href="#" class="btn btn-sm btn-outline-dark">
            <i class="bi bi-archive"></i> Lihat Data Arsip
        </a>
    </div>
</div>
@endsection