@extends('layouts.app')

@section('title', 'Transfer & Riwayat Aset — InventarisKampus FMIPA UNIMED')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" 
     x-data="{
        selectedBarangId: '{{ old('barang_id', '') }}',
        lokasiAsal: '',
        lokasiTujuan: '{{ old('lokasi_tujuan', '') }}',
        jumlah: {{ old('jumlah', 1) }},
        maxJumlah: 1,
        tanggal: '{{ old('tanggal', old('tanggal_transfer', date('Y-m-d'))) }}',
        keterangan: '{{ old('keterangan', '') }}',
        errors: {
            barang_id: '{{ $errors->first('barang_id') }}',
            lokasi_tujuan: '{{ $errors->first('lokasi_tujuan') }}',
            jumlah: '{{ $errors->first('jumlah') }}',
            tanggal: '{{ $errors->first('tanggal') ?: $errors->first('tanggal_transfer') }}',
            keterangan: '{{ $errors->first('keterangan') }}'
        },
        barangsMap: {{ Js::from($barangs->keyBy('id')) }},

        updateAsal() {
            this.clearError('barang_id');
            const key = this.selectedBarangId ? String(this.selectedBarangId) : '';
            if (key && (this.barangsMap[key] || this.barangsMap[Number(key)])) {
                const item = this.barangsMap[key] || this.barangsMap[Number(key)];
                this.lokasiAsal = item.lokasi || 'Lokasi tidak terdaftar di master';
                this.maxJumlah = parseInt(item.jumlah) || 1;
                if (this.jumlah > this.maxJumlah) {
                    this.jumlah = this.maxJumlah;
                }
            } else {
                this.lokasiAsal = '';
                this.maxJumlah = 1;
                this.jumlah = 1;
            }
        },

        clearError(field) {
            if (this.errors[field]) {
                this.errors[field] = '';
            }
        },

        resetForm() {
            this.selectedBarangId = '';
            this.lokasiAsal = '';
            this.lokasiTujuan = '';
            this.jumlah = 1;
            this.maxJumlah = 1;
            this.keterangan = '';
            this.tanggal = '{{ date('Y-m-d') }}';
            this.errors = {
                barang_id: '',
                lokasi_tujuan: '',
                jumlah: '',
                tanggal: '',
                keterangan: ''
            };
        }
     }" 
     x-init="updateAsal()">

    <!-- Header Breadcrumb & Judul Halaman -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 pb-2 border-bottom">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small text-muted">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" class="text-decoration-none text-secondary">FMIPA UNIMED</a></li>
                    <li class="breadcrumb-item text-secondary">Logistik & Aset</li>
                    <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Transfer & Riwayat Aset</li>
                </ol>
            </nav>
            <h2 class="h4 fw-bold text-dark mb-1">
                <i class="bi bi-arrow-repeat text-success me-2"></i>Transfer & Riwayat Mutasi Aset
            </h2>
            <p class="text-muted small mb-0">
                Pencatatan perpindahan fisik barang inventaris antar unit/laboratorium di lingkungan FMIPA UNIMED.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold">
                <i class="bi bi-shield-check me-1"></i>Transaksi Atomik (DB::transaction)
            </span>
        </div>
    </div>

    <!-- Alert Flash Tunggal (Sukses) -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill text-success fs-5 me-2 flex-shrink-0"></i>
        <div class="small">
            <strong>Mutasi Berhasil Dicatat!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Alert Flash Tunggal (Error / Pengecualian) -->
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill text-danger fs-5 me-2 flex-shrink-0"></i>
        <div class="small">
            <strong>Perhatian:</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- ============================================================ -->
    <!-- SECTION A: FORMULIR TRANSFER / MUTASI ASET (BOOTSTRAP 5)     -->
    <!-- ============================================================ -->
    <div class="card shadow-sm border-0 mb-4 rounded-3">
        <div class="card-header bg-white py-3 px-3 px-md-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">
                    <i class="bi bi-box-arrow-right text-success me-2"></i>Formulir Transfer / Mutasi Aset
                </h5>
                <small class="text-muted">Pilih aset dari master inventaris dan tentukan laboratorium/ruang tujuan baru.</small>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1 align-self-start align-self-sm-auto font-monospace small">
                Section A: Input Mutasi
            </span>
        </div>

        <div class="card-body p-3 p-md-4">
            <form action="{{ route('transfer.store') }}" method="POST" id="transferForm">
                @csrf

                <!-- Form Grid: Ditumpuk vertikal di layar ponsel (col-12), 2 kolom di layar tablet/desktop (col-md-6) -->
                <div class="row g-3">
                    
                    <!-- 1. Pilih Barang Inventaris -->
                    <div class="col-12 col-md-6">
                        <label for="barang_id" class="form-label small fw-bold text-dark text-uppercase">
                            Pilih Barang <span class="text-danger">*</span>
                        </label>
                        <select name="barang_id" 
                                id="barang_id" 
                                x-model="selectedBarangId" 
                                @change="updateAsal()" 
                                :class="errors.barang_id ? 'form-select is-invalid' : 'form-select'"
                                class="form-select shadow-none" 
                                required>
                            <option value="">-- Pilih Barang dari Master Inventaris --</option>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ old('barang_id') == $barang->id ? 'selected' : '' }}>
                                    [{{ $barang->kode_barang }}] {{ $barang->nama_barang }} (Stok Fisik: {{ $barang->jumlah }} unit)
                                </option>
                            @endforeach
                        </select>
                        <div x-show="errors.barang_id" x-text="errors.barang_id" class="invalid-feedback d-block small" style="display: none;"></div>
                        <div x-show="!errors.barang_id" class="form-text small text-muted">
                            Data aset dimuat dari tabel master <code class="font-monospace">barang</code>
                        </div>
                    </div>

                    <!-- 2. Lokasi Asal (Read-Only Preview & Auto-Detect) -->
                    <div class="col-12 col-md-6">
                        <label for="lokasi_asal_preview" class="form-label small fw-bold text-dark text-uppercase">
                            Lokasi Asal (Terdeteksi Otomatis)
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-geo-alt"></i>
                            </span>
                            <input type="text" 
                                   id="lokasi_asal_preview" 
                                   :value="lokasiAsal || 'Pilih barang terlebih dahulu'" 
                                   readonly 
                                   class="form-control bg-light border-start-0 text-secondary fw-semibold shadow-none">
                        </div>
                        <div class="form-text small text-muted">
                            Diambil otomatis dari atribut lokasi penempatan fisik master aset.
                        </div>
                    </div>

                    <!-- 3. Lokasi Tujuan Mutasi -->
                    <div class="col-12 col-md-6">
                        <label for="lokasi_tujuan" class="form-label small fw-bold text-dark text-uppercase">
                            Lokasi Tujuan Mutasi <span class="text-danger">*</span>
                        </label>
                        <select name="lokasi_tujuan" 
                                id="lokasi_tujuan" 
                                x-model="lokasiTujuan"
                                @change="clearError('lokasi_tujuan')"
                                :class="errors.lokasi_tujuan ? 'form-select is-invalid' : 'form-select'"
                                class="form-select shadow-none" 
                                required>
                            <option value="">-- Tentukan Laboratorium / Ruang Tujuan --</option>
                            @foreach($lokasiList as $lok)
                                <option value="{{ $lok }}" 
                                        :disabled="lokasiAsal && lokasiAsal === '{{ addslashes($lok) }}'"
                                        {{ old('lokasi_tujuan') == $lok ? 'selected' : '' }}>
                                    {{ $lok }}
                                </option>
                            @endforeach
                        </select>
                        <div x-show="errors.lokasi_tujuan" x-text="errors.lokasi_tujuan" class="invalid-feedback d-block small" style="display: none;"></div>
                        <div x-show="!errors.lokasi_tujuan" class="form-text small text-muted">
                            Pilihan lokasi tujuan yang sama dengan lokasi asal dinonaktifkan secara otomatis.
                        </div>
                    </div>

                    <!-- 4. Jumlah Unit & Tanggal Mutasi (Nested Grid) -->
                    <div class="col-12 col-md-6">
                        <div class="row g-2">
                            <!-- Jumlah Unit -->
                            <div class="col-12 col-sm-6">
                                <label for="jumlah" class="form-label small fw-bold text-dark text-uppercase">
                                    Jumlah Unit <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" 
                                           name="jumlah" 
                                           id="jumlah" 
                                           x-model.number="jumlah"
                                           @input="clearError('jumlah')"
                                           min="1" 
                                           :max="maxJumlah"
                                           :class="errors.jumlah ? 'form-control is-invalid' : 'form-control'"
                                           class="form-control shadow-none" 
                                           required>
                                    <span class="input-group-text bg-light text-muted small">Unit</span>
                                </div>
                                <div x-show="errors.jumlah" x-text="errors.jumlah" class="invalid-feedback d-block small" style="display: none;"></div>
                                <div x-show="!errors.jumlah" class="form-text small text-muted">
                                    Maks. <span x-text="maxJumlah"></span> unit tersedia
                                </div>
                            </div>

                            <!-- Tanggal Mutasi -->
                            <div class="col-12 col-sm-6">
                                <label for="tanggal" class="form-label small fw-bold text-dark text-uppercase">
                                    Tanggal Mutasi <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       name="tanggal" 
                                       id="tanggal" 
                                       x-model="tanggal"
                                       @input="clearError('tanggal')"
                                       :class="errors.tanggal ? 'form-control is-invalid' : 'form-control'"
                                       class="form-control shadow-none" 
                                       required>
                                <div x-show="errors.tanggal" x-text="errors.tanggal" class="invalid-feedback d-block small" style="display: none;"></div>
                                <div x-show="!errors.tanggal" class="form-text small text-muted">
                                    Tanggal efektif mutasi
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Keterangan Mutasi / Berita Acara -->
                    <div class="col-12">
                        <label for="keterangan" class="form-label small fw-bold text-dark text-uppercase">
                            Keterangan Mutasi / Dasar Pemindahan (Opsional)
                        </label>
                        <textarea name="keterangan" 
                                  id="keterangan" 
                                  x-model="keterangan"
                                  @input="clearError('keterangan')"
                                  rows="3" 
                                  placeholder="Contoh: Pemindahan sementara untuk praktikum mahasiswa Semester Ganjil berdasarkan Surat Permohonan No. 042/FMIPA/2026." 
                                  :class="errors.keterangan ? 'form-control is-invalid' : 'form-control'"
                                  class="form-control shadow-none"></textarea>
                        <div x-show="errors.keterangan" x-text="errors.keterangan" class="invalid-feedback d-block small" style="display: none;"></div>
                    </div>

                </div>

                <!-- Tombol Aksi Form -->
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 pt-3 mt-4 border-top">
                    <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-info-circle text-primary"></i>
                        <span>Perpindahan fisik otomatis memperbarui lokasi master aset di database.</span>
                    </div>

                    <div class="d-flex align-items-center gap-2 justify-content-end">
                        <!-- Tombol Reset Form (Alpine.js State Reset) -->
                        <button type="button" 
                                @click="resetForm()" 
                                class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span>Reset Form</span>
                        </button>

                        <!-- Tombol Submit Form -->
                        <button type="submit" 
                                class="btn btn-success btn-sm px-4 fw-semibold d-flex align-items-center gap-1 shadow-sm">
                            <i class="bi bi-arrow-repeat"></i>
                            <span>Proses Transfer Aset</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- SECTION B: DATA TABLE / AUDIT TRAIL LOG (BOOTSTRAP 5)        -->
    <!-- ============================================================ -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-white py-3 px-3 px-md-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">
                    <i class="bi bi-clock-history text-success me-2"></i>Riwayat Mutasi Aset (Audit Trail)
                </h5>
                <small class="text-muted">Catatan historis perpindahan lokasi fisik barang inventaris kampus.</small>
            </div>

            <!-- Form Pencarian / Filter -->
            <form method="GET" action="{{ route('transfer.index') }}" class="d-flex align-items-center gap-2 w-100 w-md-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari kode, nama, lokasi..." 
                           class="form-control shadow-none">
                </div>
                <button type="submit" class="btn btn-sm btn-outline-secondary flex-shrink-0">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('transfer.index') }}" class="btn btn-sm btn-link text-danger text-decoration-none p-0 flex-shrink-0 ms-1">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Responsive Wrapper (Scroll horizontal otomatis di layar ponsel) -->
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-secondary text-uppercase small">
                    <tr>
                        <th class="ps-3 py-3" scope="col">Tanggal Mutasi</th>
                        <th scope="col">Kode Barang</th>
                        <th scope="col">Nama Aset & Kategori</th>
                        <th scope="col">Relokasi Fisik (Asal &rarr; Tujuan)</th>
                        <th class="text-center" scope="col">Unit</th>
                        <th class="pe-3" scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="small">
                    @forelse($riwayatTransfers as $log)
                        <tr>
                            <!-- Tanggal Mutasi -->
                            <td class="ps-3 py-3">
                                <div class="fw-semibold font-monospace text-dark">
                                    {{ \Carbon\Carbon::parse($log->tanggal ?? $log->created_at)->translatedFormat('d M Y') }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i') }} WIB
                                </small>
                            </td>

                            <!-- Kode Barang -->
                            <td>
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                    {{ $log->barang->kode_barang ?? 'AST-DELETED' }}
                                </span>
                            </td>

                            <!-- Nama Barang -->
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $log->barang->nama_barang ?? 'Aset Dihapus (Soft Delete)' }}
                                </div>
                                <small class="text-muted">
                                    Kategori: {{ $log->barang->kategori ?? '-' }}
                                </small>
                            </td>

                            <!-- Relokasi (Asal -> Tujuan) -->
                            <td>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    <span class="badge bg-light text-secondary border px-2 py-1 fw-normal">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $log->lokasi_asal }}
                                    </span>
                                    <i class="bi bi-arrow-right text-success fw-bold mx-1"></i>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 fw-semibold">
                                        <i class="bi bi-geo-alt-fill me-1"></i>{{ $log->lokasi_tujuan }}
                                    </span>
                                </div>
                            </td>

                            <!-- Jumlah Unit -->
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                    {{ $log->jumlah ?? 1 }} Unit
                                </span>
                            </td>

                            <!-- Keterangan -->
                            <td class="pe-3 text-wrap" style="max-width: 250px;">
                                @if($log->keterangan)
                                    <span class="text-secondary small">{{ $log->keterangan }}</span>
                                @else
                                    <span class="text-muted fst-italic small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox text-secondary fs-1 d-block mb-2"></i>
                                <div class="fw-semibold text-dark">Belum ada riwayat mutasi aset</div>
                                <small class="text-muted">Gunakan formulir di atas untuk memindahkan lokasi fisik aset inventaris.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if($riwayatTransfers->hasPages())
        <div class="card-footer bg-white py-3 px-3 px-md-4 border-top">
            <div class="d-flex justify-content-end">
                {{ $riwayatTransfers->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

</div>
@endsection