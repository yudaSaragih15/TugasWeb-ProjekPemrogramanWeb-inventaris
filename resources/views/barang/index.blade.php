@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Data Aset</h3>
        <p class="text-muted mb-0">Kelola daftar inventaris, lokasi, dan kondisi aset kampus.</p>
    </div>
    <button class="btn btn-primary px-4 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Aset Baru
    </button>
</div>

<!-- Filter & Search Bar -->
<div class="card card-custom p-3 mb-4">
    <form action="{{ route('barang.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-10">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama barang, kode, atau lokasi..." value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-dark w-100 fw-bold">Cari Data</button>
        </div>
    </form>
</div>

<!-- Table Data -->
<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                    <th>Kondisi</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                <tr>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark font-monospace">{{ $barang->kode_barang }}</span></td>
                    <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                    <td><span class="badge bg-info bg-opacity-10 text-info">{{ $barang->kategori }}</span></td>
                    <td><i class="bi bi-geo-alt me-1 text-muted"></i>{{ $barang->lokasi }}</td>
                    <td><span class="fw-bold fs-6">{{ $barang->jumlah }}</span></td>
                    <td>
                        @if($barang->kondisi == 'Baik')
                            <span class="badge badge-baik px-3 py-2">Baik</span>
                        @elseif($barang->kondisi == 'Rusak Ringan')
                            <span class="badge badge-ringan px-3 py-2">Rusak Ringan</span>
                        @else
                            <span class="badge badge-rusak px-3 py-2">Rusak Berat</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTransfer{{ $barang->id }}" title="Transfer Aset">
                                <i class="bi bi-arrow-left-right"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $barang->id }}" title="Edit Data">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset ini?');" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Modal Transfer Aset -->
                <div class="modal fade" id="modalTransfer{{ $barang->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-left-right me-2 text-primary"></i>Transfer Aset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('barang.transfer', $barang->id) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="mb-2">Aset: <strong>{{ $barang->nama_barang }}</strong></p>
                                    <p class="text-muted small">Lokasi Saat Ini: <strong>{{ $barang->lokasi }}</strong> (Stok: {{ $barang->jumlah }})</p>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi Tujuan</label>
                                        <input type="text" name="lokasi_tujuan" class="form-control" required placeholder="Contoh: Lab Komputer 2">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Unit Ditransfer</label>
                                        <input type="number" name="jumlah_ditransfer" class="form-control" max="{{ $barang->jumlah }}" value="1" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Keterangan / Alasan Transfer</label>
                                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Pemindahan untuk praktikum"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Konfirmasi Transfer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Aset -->
                <div class="modal fade" id="modalEdit{{ $barang->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">Edit Data Aset</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('barang.update', $barang->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" name="nama_barang" class="form-control" value="{{ $barang->nama_barang }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Kategori</label>
                                        <input type="text" name="kategori" class="form-control" value="{{ $barang->kategori }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi</label>
                                        <input type="text" name="lokasi" class="form-control" value="{{ $barang->lokasi }}" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Jumlah Unit</label>
                                            <input type="number" name="jumlah" class="form-control" value="{{ $barang->jumlah }}" required>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="form-label">Kondisi</label>
                                            <select name="kondisi" class="form-select" required>
                                                <option value="Baik" {{ $barang->kondisi == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Rusak Ringan" {{ $barang->kondisi == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                                <option value="Rusak Berat" {{ $barang->kondisi == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning text-white w-100 fw-bold">Update Data</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada data aset ditemukan. Klik tombol <strong>Tambah Aset Baru</strong>!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $barangs->links() }}
    </div>
</div>

<!-- Modal Tambah Aset Baru -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="bi bi-box-seam me-2 text-primary"></i>Tambah Aset Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Barang (Unik)</label>
                        <input type="text" name="kode_barang" class="form-control" placeholder="Contoh: KMP-PC-001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang / Aset</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: PC Desktop Asus Pro" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" name="kategori" class="form-control" placeholder="Contoh: Elektronik / Furniture" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Ruangan</label>
                        <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Lab Multimedia FMIPA" required>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Jumlah Unit</label>
                            <input type="number" name="jumlah" class="form-control" value="1" min="1" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak Ringan">Rusak Ringan</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Simpan Aset Baru</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection