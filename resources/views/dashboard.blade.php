@extends('layouts.app')
@section('title', 'Dashboard - Inventaris Kampus')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Dashboard Inventaris</h3>
    <p class="text-muted mb-0">Ringkasan data dan statistik inventaris aset kampus FMIPA UNIMED.</p>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-custom border-0 shadow-sm p-3 rounded-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Jenis Aset</span>
                    <h3 class="fw-bold mb-0">{{ $totalBarang ?? $total_jenis ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-custom border-0 shadow-sm p-3 rounded-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                    <i class="bi bi-layers fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small">Total Unit Barang</span>
                    <h3 class="fw-bold mb-0">{{ $totalJumlah ?? $total_aset ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Baris untuk Grafik Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card card-custom p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3">Statistik Kondisi Aset</h5>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="kondisiChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Aset Terbaru -->
<div class="card card-custom border-0 shadow-sm p-4 rounded-3">
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
                @forelse($barangTerbaru ?? $barang_terbaru ?? [] as $barang)
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

@section('scripts')
<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvasElement = document.getElementById('kondisiChart');
        if (canvasElement) {
            const ctx = canvasElement.getContext('2d');
            
            // Mengambil variabel dari controller, jika kosong jadikan 0
            const dataBaik = {{ $kondisiBaik ?? 0 }};
            const dataRingan = {{ $kondisiRingan ?? 0 }};
            const dataBerat = {{ $kondisiBerat ?? 0 }};

            console.log("Data Tes Grafik:", dataBaik, dataRingan, dataBerat);

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Kondisi Baik', 'Rusak Ringan', 'Rusak Berat'],
                    datasets: [{
                        label: 'Jumlah Aset',
                        data: [dataBaik, dataRingan, dataBerat],
                        backgroundColor: [
                            '#0d6efd', // Biru
                            '#ffc107', // Kuning
                            '#dc3545'  // Merah
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#e0e0e0' // Warna teks legend
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection