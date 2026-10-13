@extends('layouts.app')
@section('title', 'Tambah Aset Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Aset Baru</h4>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-3">
                    <!-- Kode Barang -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" name="kode_barang" class="form-control rounded-3 @error('kode_barang') is-invalid @enderror" 
                               value="{{ old('kode_barang') }}" placeholder="Contoh: BRG-001" required>
                        @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Nama Barang -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control rounded-3 @error('nama_barang') is-invalid @enderror" 
                               value="{{ old('nama_barang') }}" placeholder="Contoh: Proyektor Epson" required>
                        @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- KATEGORI (DROPDOWN DARI MASTER DATA) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select rounded-3 @error('kategori') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @forelse($kategoris as $kat)
                                <option value="{{ $kat->nama_kategori }}" {{ old('kategori') == $kat->nama_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @empty
                                <option value="" disabled>Belum ada kategori. Silakan tambah di Master Data.</option>
                            @endforelse
                        </select>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- LOKASI (DROPDOWN DARI MASTER DATA) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Lokasi Penempatan</label>
                        <select name="lokasi" class="form-select rounded-3 @error('lokasi') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Lokasi --</option>
                            @forelse($lokasis as $lok)
                                <option value="{{ $lok->nama_lokasi }}" {{ old('lokasi') == $lok->nama_lokasi ? 'selected' : '' }}>
                                    {{ $lok->nama_lokasi }} 
                                    @if($lok->gedung) ({{ $lok->gedung }}) @endif
                                </option>
                            @empty
                                <option value="" disabled>Belum ada lokasi. Silakan tambah di Master Data.</option>
                            @endforelse
                        </select>
                        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Jumlah -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jumlah Unit</label>
                        <input type="number" name="jumlah" class="form-control rounded-3 @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah', 1) }}" min="1" required>
                        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Kondisi -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kondisi Awal</label>
                        <select name="kondisi" class="form-select rounded-3 @error('kondisi') is-invalid @enderror" required>
                            <option value="" disabled selected>-- Pilih Kondisi --</option>
                            <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Foto Dokumentasi -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Foto Dokumentasi (Opsional)</label>
                        <input type="file" name="foto" class="form-control rounded-3 @error('foto') is-invalid @enderror" accept="image/*">
                        <small class="text-muted d-block mt-1">Format: JPG, PNG. Maksimal 2MB.</small>
                        @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('barang.index') }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-save me-1"></i> Simpan Aset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection