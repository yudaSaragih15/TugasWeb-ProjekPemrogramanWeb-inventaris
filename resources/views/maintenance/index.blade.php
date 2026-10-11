@extends('layouts.app')

@section('title', 'Pemeliharaan & Servis')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Pemeliharaan & Servis</h3>
        <p class="text-muted mb-0">Catat perbaikan, ganti sparepart, dan pantau status aset rusak secara real-time.</p>
    </div>
</div>

<div class="row g-4">
    <!-- KOLOM KIRI: FORM INPUT SERVIS BARU -->
    <div class="col-lg-4">
        <div class="card card-custom p-4 h-100">
            <h5 class="fw-bold mb-3 text-warning">
                <i class="bi bi-wrench-adjustable-circle me-2"></i>Catat Servis Baru
            </h5>
            
            <form action="{{ route('maintenance.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Pilih Aset / Barang</label>
                    <select name="barang_id" class="form-select rounded-3" required>
                        <option value="" disabled selected>-- Pilih Barang --</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}">{{ $b->kode_barang }} - {{ $b->nama_barang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Tanggal Servis</label>
                    <input type="date" name="tanggal_servis" class="form-control rounded-3" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Jenis Layanan</label>
                    <select name="jenis_layanan" class="form-select rounded-3" required>
                        <option value="Perbaikan Umum">Perbaikan Umum</option>
                        <option value="Ganti Sparepart">Ganti Sparepart</option>
                        <option value="Pembersihan">Pembersihan / Cleaning</option>
                        <option value="Kalibrasi">Kalibrasi Alat</option>
                        <option value="Instalasi Ulang">Instalasi Ulang Software</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" class="form-control rounded-3" rows="3" placeholder="Jelaskan detail kerusakan..." required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Biaya Estimasi (Rp)</label>
                    <input type="number" name="biaya" class="form-control rounded-3" value="0" min="0" required>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 mt-2">
                    <i class="bi bi-save me-1"></i> Simpan Catatan Servis
                </button>
            </form>
        </div>
    </div>

    <!-- KOLOM KANAN: TABEL RIWAYAT PEMELIHARAAN -->
    <div class="col-lg-8">
        <div class="card card-custom p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Aset Terkait</th>
                            <th>Tanggal</th>
                            <th>Jenis Layanan</th>
                            <th>Biaya</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $rec)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold text-dark">{{ $rec->barang->nama_barang ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $rec->barang->kode_barang ?? '-' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($rec->tanggal_servis)->format('d M Y') }}</td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary rounded-pill px-3">{{ $rec->jenis_layanan }}</span></td>
                            <td class="fw-bold text-dark">Rp {{ number_format($rec->biaya, 0, ',', '.') }}</td>
                            
                            <!-- KOLOM STATUS DENGAN AUTO-SUBMIT -->
                            <td>
                                <form action="{{ route('maintenance.update-status', $rec->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm border-0 bg-transparent fw-semibold p-0" 
                                            style="width: auto; cursor: pointer; 
                                                   color: {{ $rec->status == 'Selesai' ? '#198754' : ($rec->status == 'Dalam Proses' ? '#fd7e14' : '#dc3545') }};"
                                            onchange="this.form.submit()">
                                        <option value="Dalam Proses" {{ $rec->status == 'Dalam Proses' ? 'selected' : '' }}>🟠 Dalam Proses</option>
                                        <option value="Menunggu Sparepart" {{ $rec->status == 'Menunggu Sparepart' ? 'selected' : '' }}> Tunggu Part</option>
                                        <option value="Selesai" {{ $rec->status == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                    </select>
                                </form>
                            </td>

                            <td class="text-end pe-4">
                                <form action="{{ route('maintenance.destroy', $rec->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan servis ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-tools fs-1 d-block mb-3 opacity-50"></i>
                                <p class="mb-0">Belum ada riwayat pemeliharaan tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- PAGINATION -->
            @if($records->hasPages())
            <div class="p-3 border-top bg-white">
                {{ $records->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection