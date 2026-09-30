@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard Utama</h3>
        <p class="text-muted mb-0">Ringkasan real-time statistik inventaris aset kampus.</p>
    </div>
    <a href="{{ route('barang.index') }}" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">
        <i class="bi bi-plus-lg me-1"></i> Kelola Data Aset
    </a>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-custom p-3 border-start border-primary border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Total Unit Aset</span>
                    <h2 class="fw-bold my-1 text-dark">{{ $total_aset }}</h2>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 text-primary">
                    <i class="bi bi-boxes fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom p-3 border-start border-info border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Kategori / Jenis</span>
                    <h2 class="fw-bold my-1 text-info">{{ $total_jenis }}</h2>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded-4 text-info">
                    <i class="bi bi-tags-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom p-3 border-start border-success border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Kondisi Baik</span>
                    <h2 class="fw-bold my-1 text-success">{{ $kondisi_baik }}</h2>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-4 text-success">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card card-custom p-3 border-start border-danger border-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small fw-medium">Rusak / Perbaikan</span>
                    <h2 class="fw-bold my-1 text-danger">{{ $kondisi_rusak }}</h2>
                </div>
                <div class="bg-danger bg-opacity-10 p-3 rounded-4 text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Items Table & Recent Transfers -->
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Aset Terbaru Ditambahkan</h5>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-light text-primary fw-bold">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Lokasi</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($barang_terbaru as $item)
                        <tr>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark font-monospace">{{ $item->kode_barang }}</span></td>
                            <td class="fw-semibold">{{ $item->nama_barang }}</td>
                            <td><small class="text-muted">{{ $item->lokasi }}</small></td>
                            <td><span class="fw-bold">{{ $item->jumlah }}</span></td>
                            <td>
                                @if($item->kondisi == 'Baik')
                                    <span class="badge badge-baik px-2 py-1">Baik</span>
                                @elseif($item->kondisi == 'Rusak Ringan')
                                    <span class="badge badge-ringan px-2 py-1">Rusak Ringan</span>
                                @else
                                    <span class="badge badge-rusak px-2 py-1">Rusak Berat</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data aset. Silakan tambahkan barang baru!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Aktivitas Transfer Terakhir</h5>
            <div class="list-group list-group-flush">
                @forelse($riwayat_terbaru as $r)
                <div class="list-group-item px-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark">{{ $r->barang->nama_barang ?? 'Aset' }}</strong>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $r->jumlah_ditransfer }} Unit</span>
                    </div>
                    <small class="text-muted d-block mb-1">
                        <i class="bi bi-geo-alt"></i> {{ $r->lokasi_asal }} <i class="bi bi-arrow-right"></i> {{ $r->lokasi_tujuan }}
                    </small>
                    <small class="text-muted fs-8">{{ $r->created_at->diffForHumans() }}</small>
                </div>
                @empty
                <p class="text-muted text-center py-4">Belum ada riwayat transfer aset.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection