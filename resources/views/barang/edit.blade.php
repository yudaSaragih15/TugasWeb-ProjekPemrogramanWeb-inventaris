@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Edit Data Aset</h4>
                <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- PENTING: enctype="multipart/form-data" WAJIB ADA UNTUK UPLOAD FILE -->
            <!-- PENTING: action mengarah ke route update dengan ID barang -->
            <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Ini wajib agar Laravel tahu ini request UPDATE, bukan STORE -->

                <div class="mb-3">
                    <label for="kode_barang" class="form-label fw-semibold">Kode Barang</label>
                    <input type="text" class="form-control @error('kode_barang') is-invalid @enderror" 
                           id="kode_barang" name="kode_barang" 
                           value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                    @error('kode_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="nama_barang" class="form-label fw-semibold">Nama Barang</label>
                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                           id="nama_barang" name="nama_barang" 
                           value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                    @error('nama_barang') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label fw-semibold">Kategori</label>
                        <input type="text" class="form-control @error('kategori') is-invalid @enderror" 
                               id="kategori" name="kategori" 
                               value="{{ old('kategori', $barang->kategori) }}" required>
                        @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lokasi" class="form-label fw-semibold">Lokasi</label>
                        <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                               id="lokasi" name="lokasi" 
                               value="{{ old('lokasi', $barang->lokasi) }}" required>
                        @error('lokasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="jumlah" class="form-label fw-semibold">Jumlah</label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                               id="jumlah" name="jumlah" 
                               value="{{ old('jumlah', $barang->jumlah) }}" min="1" required>
                        @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kondisi" class="form-label fw-semibold">Kondisi</label>
                        <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik" {{ old('kondisi', $barang->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak Ringan" {{ old('kondisi', $barang->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            <option value="Rusak Berat" {{ old('kondisi', $barang->kondisi) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        </select>
                        @error('kondisi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- BAGIAN UPLOAD FOTO DI EDIT -->
                <div class="mb-4">
                    <label for="foto" class="form-label fw-semibold">Foto Dokumentasi</label>
                    
                    <!-- Tampilkan Foto Lama Jika Ada -->
                    @if($barang->foto)
                        <div class="mb-2">
                            <p class="small text-muted mb-1">Foto Saat Ini:</p>
                            <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Lama" 
                                 style="max-height: 150px; border-radius: 8px; border: 1px solid #ddd;">
                        </div>
                    @endif

                    <!-- Input Ganti Foto -->
                    <input type="file" class="form-control @error('foto') is-invalid @enderror" 
                           id="foto" name="foto" accept="image/*">
                    <div class="form-text text-muted">
                        Kosongkan jika tidak ingin mengubah foto. Format: JPG, PNG, JPEG (Max 2MB).
                    </div>
                    @error('foto') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('barang.index') }}" class="btn btn-light border">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection