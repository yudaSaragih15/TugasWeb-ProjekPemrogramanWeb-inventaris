@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Dashboard Inventaris</h3>
    <p class="text-muted">Ringkasan data dan statistik inventaris aset kampus FMIPA UNIMED.</p>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 rounded-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Jenis Aset</span>
                    <h3 class="fw-bold mb-0">{{ $totalBarang }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 rounded-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                    <i class="bi bi-layers fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Unit Barang</span>
                    <h3 class="fw-bold mb-0">{{ $totalJumlah }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Aset Terbaru -->
<div class="card border-0 shadow-sm p-4 rounded-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Aset Terbaru Ditambahkan</h5>
        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Data Aset</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted">
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangTerbaru as $barang)
                    <tr>
                        <td><code>{{ $barang->kode_barang }}</code></td>
                        <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->kategori }}</td>
                        <td>{{ $barang->lokasi }}</td>
                        <td>{{ $barang->jumlah }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada aset terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection