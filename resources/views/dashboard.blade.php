@extends('layouts.app')
@section('title', 'Dashboard - Inventaris Kampus')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Dashboard Inventaris</h3>
    <!-- Subtitle dinamis sesuai fakultas user -->
    <p class="text-muted mb-0">Ringkasan data dan statistik inventaris aset kampus {{ auth()->user()->fakultas ?? 'UNIMED' }}.</p>
</div>

<!-- Kartu Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-6">
        <div class="card card-custom border-0 shadow-sm p-3 rounded-3 h-100">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Jenis Aset</span>
                    <!-- VARIABEL SUDAH DISINKRONKAN DENGAN CONTROLLER -->
                    <h3 class="fw-bold mb-0">{{ $totalJenisAset ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="card card-custom border-0 shadow-sm p-3 rounded-3 h-100">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                    <i class="bi bi-layers fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Unit Barang</span>
                    <!-- VARIABEL SUDAH DISINKRONKAN DENGAN CONTROLLER -->
                    <h3 class="fw-bold mb-0">{{ $totalUnitBarang ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Baris untuk Grafik Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-8 col-lg-6">
        <div class="card card-custom p-4 border-0 shadow-sm h-100">
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
        <a href="{{ route('barang.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Lihat Semua Data Aset</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-muted">
                    <th class="ps-3">Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Lokasi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <!-- VARIABEL SUDAH DISINKRONKAN MENJADI $asetTerbaru -->
                @forelse($asetTerbaru ?? [] as $barang)
                    <tr>
                        <td class="ps-3"><code>{{ $barang->kode_barang }}</code></td>
                        <td class="fw-semibold">{{ $barang->nama_barang }}</td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-dark border">{{ $barang->kategori }}</span></td>
                        <td>{{ $barang->lokasi }}</td>
                        <td>{{ $barang->jumlah }} Unit</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            Belum ada aset terdaftar di fakultas Anda.
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
            
            // MENGAMBIL DATA DARI ARRAY $kondisiStats YANG DIKIRIM CONTROLLER
            // Ini memperbaiki masalah grafik kosong sebelumnya
            const stats = @json($kondisiStats ?? ['Baik' => 0, 'Rusak Ringan' => 0, 'Rusak Berat' => 0]);
            
            const dataBaik = stats['Baik'] || 0;
            const dataRingan = stats['Rusak Ringan'] || 0;
            const dataBerat = stats['Rusak Berat'] || 0;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Kondisi Baik', 'Rusak Ringan', 'Rusak Berat'],
                    datasets: [{
                        label: 'Jumlah Aset',
                        data: [dataBaik, dataRingan, dataBerat],
                        backgroundColor: [
                            '#0d6efd', // Biru (Baik)
                            '#ffc107', // Kuning (Rusak Ringan)
                            '#dc3545'  // Merah (Rusak Berat)
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%', // Membuat donut lebih tipis & modern
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { family: "'Plus Jakarta Sans', sans-serif" }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection