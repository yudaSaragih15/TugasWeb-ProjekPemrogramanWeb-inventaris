@extends('layouts.app')

@section('title', 'Pengaturan - Inventaris Kampus')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-7">
        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1"><i class="bi bi-gear-fill me-2 text-primary"></i>Pengaturan Aplikasi</h4>
                    <p class="text-muted mb-0 small">Kelola identitas institusi dan preferensi sistem inventaris.</p>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Nama Aplikasi -->
                <div class="mb-4">
                    <label for="app_name" class="form-label fw-semibold">Nama Aplikasi</label>
                    <input type="text" class="form-control rounded-3 @error('app_name') is-invalid @enderror" 
                           id="app_name" name="app_name" 
                           value="{{ old('app_name', $settings['app_name'] ?? 'Inventaris Kampus') }}" required>
                    <div class="form-text text-muted">Nama yang ditampilkan pada judul browser dan header sidebar.</div>
                    @error('app_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Logo Institusi -->
                <div class="mb-4">
                    <label for="institution_logo" class="form-label fw-semibold">Logo Institusi</label>
                    
                    @if(!empty($settings['institution_logo']))
                        <div class="mb-2 p-2 bg-light rounded-3 d-inline-block">
                            <img src="{{ asset('storage/' . $settings['institution_logo']) }}" 
                                 alt="Logo Saat Ini" 
                                 style="max-height: 50px; max-width: 150px; object-fit: contain;">
                        </div>
                    @endif

                    <input type="file" class="form-control rounded-3 @error('institution_logo') is-invalid @enderror" 
                           id="institution_logo" name="institution_logo" accept="image/png,image/jpeg,image/jpg">
                    <div class="form-text text-muted">Format PNG/JPG, maksimal 1MB. Kosongkan jika tidak ingin mengganti logo.</div>
                    @error('institution_logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row mb-4">
                    <!-- Threshold Stok -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="low_stock_threshold" class="form-label fw-semibold">Batas Stok Menipis</label>
                        <div class="input-group">
                            <input type="number" class="form-control rounded-start-3 @error('low_stock_threshold') is-invalid @enderror" 
                                   id="low_stock_threshold" name="low_stock_threshold" 
                                   value="{{ old('low_stock_threshold', $settings['low_stock_threshold'] ?? 5) }}" 
                                   min="1" max="100" required>
                            <span class="input-group-text bg-white border-start-0 rounded-end-3">Unit</span>
                        </div>
                        <div class="form-text text-muted">Peringatan muncul jika jumlah aset kurang dari nilai ini.</div>
                        @error('low_stock_threshold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Format Kode Barang -->
                    <div class="col-md-6">
                        <label for="auto_code_format" class="form-label fw-semibold">Format Kode Otomatis</label>
                        <input type="text" class="form-control rounded-3 @error('auto_code_format') is-invalid @enderror" 
                               id="auto_code_format" name="auto_code_format" 
                               value="{{ old('auto_code_format', $settings['auto_code_format'] ?? 'BRG-{YYYY}-{####}') }}" required>
                        <div class="form-text text-muted">Placeholder: {YYYY}=Tahun, {####}=Urutan angka.</div>
                        @error('auto_code_format') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ url()->previous() }}" class="btn btn-light border rounded-pill px-4">Batal</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="bi bi-check-lg me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection