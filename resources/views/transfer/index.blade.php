@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Riwayat Transfer Aset</h3>
        <p class="text-muted mb-0">Daftar rekapan pemindahan dan rotasi lokasi aset kampus.</p>
    </div>
    <div>
        <a href="{{ route('barang.index') }}" class="btn btn-outline-primary px-3 py-2 rounded-3 fw-bold">
            <i class="bi bi-box-seam me-1"></i> Kembali ke Data Aset
        </a>
    </div>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal Transfer</th>
                    <th>Nama Aset</th>
                    <th>Lokasi Asal</th>
                    <th>Lokasi Tujuan</th>
                    <th>Jumlah Unit</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $index => $trf)
                <tr>
                    <td>{{ $transfers->firstItem() + $index }}</td>
                    <td><i class="bi bi-calendar3 me-1 text-muted"></i>{{ \Carbon\Carbon::parse($trf->tanggal)->format('d M Y, H:i') }} WIB</td>
                    <td class="fw-semibold text-primary">{{ $trf->barang->nama_barang ?? 'Aset Dihapus / Tidak Ditemukan' }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark border">{{ $trf->lokasi_asal }}</span></td>
                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success">{{ $trf->lokasi_tujuan }}</span></td>
                    <td><span class="fw-bold">{{ $trf->jumlah }}</span> Unit</td>
                    <td class="text-muted small">{{ $trf->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-left-right fs-1 d-block mb-2"></i>
                        Belum ada riwayat transfer aset yang dicatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transfers->links() }}
    </div>
</div>
@endsection