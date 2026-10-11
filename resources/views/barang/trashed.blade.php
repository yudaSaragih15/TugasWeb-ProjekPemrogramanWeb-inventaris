@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1 text-danger">
            <i class="bi bi-archive me-2"></i>Data Arsip Aset
        </h3>
        <p class="text-muted mb-0">Daftar aset yang telah dihapus sementara. Anda dapat mengembalikannya atau menghapus permanen.</p>
    </div>
    <div>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-dark px-3 py-2 rounded-3 fw-bold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Aktif
        </a>
    </div>
</div>

<div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th>Tanggal Dihapus</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $index => $barang)
                <tr class="table-light opacity-75">
                    <td class="ps-4 text-muted">{{ $barangs->firstItem() + $index }}</td>
                    <td class="fw-bold text-secondary">{{ $barang->kode_barang }}</td>
                    <td class="fw-semibold text-secondary">{{ $barang->nama_barang }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">{{ $barang->kategori }}</span></td>
                    <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">{{ $barang->lokasi }}</span></td>
                    <td class="text-secondary">{{ $barang->jumlah }} Unit</td>
                    <td>
                        @if($barang->kondisi == 'Baik')
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">Baik</span>
                        @elseif($barang->kondisi == 'Rusak Ringan')
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">Rusak Ringan</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $barang->deleted_at->format('d M Y H:i') }}</td>
                    <td class="text-end pe-4">
                        <!-- Tombol Restore -->
                        <form action="{{ route('barang.restore', $barang->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" title="Kembalikan Aset" onclick="return confirm('Yakin ingin mengembalikan aset ini?')">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restore
                            </button>
                        </form>
                        
                        <!-- Opsional: Tombol Hapus Permanen (Force Delete) -->
                        <!-- 
                        <form action="{{ route('barang.forceDelete', $barang->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('PERINGATAN: Data akan dihapus PERMANEN dan tidak bisa dikembalikan!')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger rounded-pill px-3" title="Hapus Permanen">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form> 
                        -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="bi bi-archive fs-1 d-block mb-3 opacity-50"></i>
                        <p class="mb-0">Tidak ada data di arsip.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-3 border-top bg-white">
        {{ $barangs->links() }}
    </div>
</div>
@endsection