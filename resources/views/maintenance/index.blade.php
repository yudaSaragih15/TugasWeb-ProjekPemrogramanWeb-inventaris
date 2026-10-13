@extends('layouts.app')

@section('title', 'Pemeliharaan & Servis')

@section('content')
<div class="row">
    <!-- FORM INPUT PEMELIHARAAN -->
    <div class="col-lg-4 mb-4">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
            <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-tools me-2"></i>Catat Servis Baru</h5>
            
            <form action="{{ route('maintenance.store') }}" method="POST">
                @csrf
                
                <!-- Pilih Aset -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Pilih Aset / Barang</label>
                    <select name="barang_id" class="form-select @error('barang_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangs as $b)
                            <option value="{{ $b->id }}" {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                {{ $b->kode_barang }} - {{ $b->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                    @error('barang_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Tanggal Servis -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Tanggal Servis</label>
                    <input type="date" name="tanggal_servis" class="form-control @error('tanggal_servis') is-invalid @enderror" 
                           value="{{ old('tanggal_servis', date('Y-m-d')) }}" required>
                    @error('tanggal_servis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Jenis Layanan (VALUE HARUS PERSIS DENGAN DATABASE) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Jenis Layanan</label>
                    <select name="jenis_layanan" class="form-select @error('jenis_layanan') is-invalid @enderror" required>
                        <option value="">-- Pilih Jenis --</option>
                        <!-- Pastikan value ini SAMA PERSIS dengan opsi ENUM di database -->
                        <option value="Perbaikan Umum" {{ old('jenis_layanan') == 'Perbaikan Umum' ? 'selected' : '' }}>Perbaikan Umum</option>
                        <option value="Ganti Sparepart" {{ old('jenis_layanan') == 'Ganti Sparepart' ? 'selected' : '' }}>Ganti Sparepart</option>
                        <option value="Servis Berkala" {{ old('jenis_layanan') == 'Servis Berkala' ? 'selected' : '' }}>Servis Berkala</option>
                        <option value="Lainnya" {{ old('jenis_layanan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_layanan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Deskripsi Kerusakan -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" class="form-control @error('deskripsi_kerusakan') is-invalid @enderror" 
                              rows="3" placeholder="Jelaskan detail kerusakan..." required>{{ old('deskripsi_kerusakan') }}</textarea>
                    @error('deskripsi_kerusakan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Tindakan Perbaikan (Opsional) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Tindakan Perbaikan (Opsional)</label>
                    <textarea name="tindakan_perbaikan" class="form-control" rows="2" placeholder="Apa yang dilakukan?">{{ old('tindakan_perbaikan') }}</textarea>
                </div>

                <!-- Biaya Estimasi -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Biaya Estimasi (Rp)</label>
                    <input type="number" name="biaya" class="form-control @error('biaya') is-invalid @enderror" 
                           value="{{ old('biaya', 0) }}" min="0" required>
                    @error('biaya') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Status (VALUE HARUS PERSIS DENGAN DATABASE) -->
                <div class="mb-4">
                    <label class="form-label fw-semibold small text-uppercase text-muted">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <!-- Pastikan value ini SAMA PERSIS dengan opsi ENUM di database -->
                        <option value="Dalam Proses" {{ old('status', 'Dalam Proses') == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Menunggu Sparepart" {{ old('status') == 'Menunggu Sparepart' ? 'selected' : '' }}>Menunggu Sparepart</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3">
                    <i class="bi bi-save me-1"></i> Simpan Catatan Servis
                </button>
            </form>
        </div>
    </div>

    <!-- TABEL RIWAYAT PEMELIHARAAN -->
    <div class="col-lg-8">
        <div class="card card-custom p-0 border-0 shadow-sm overflow-hidden">
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
                        @forelse($records as $record)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ $record->barang->nama_barang ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $record->barang->kode_barang ?? '-' }}</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($record->tanggal_servis)->format('d M Y') }}</td>
                            <td>{{ $record->jenis_layanan }}</td>
                            <td>Rp {{ number_format($record->biaya, 0, ',', '.') }}</td>
                            <td>
                                @if($record->status == 'Selesai')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Selesai</span>
                                @elseif($record->status == 'Dalam Proses')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">Dalam Proses</span>
                                @else
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">Menunggu Sparepart</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ route('maintenance.destroy', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus catatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
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
            
            @if($records->hasPages())
            <div class="p-3 border-top bg-white">
                {{ $records->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection