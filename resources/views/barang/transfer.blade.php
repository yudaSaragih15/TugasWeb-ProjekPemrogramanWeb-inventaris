@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold mb-1">Riwayat Transfer Aset</h3>
    <p class="text-muted mb-0">Catatan log perpindahan lokasi aset inventaris kampus.</p>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Waktu Transfer</th>
                    <th>Nama Barang</th>
                    <th>Lokasi Asal</th>
                    <th>Lokasi Tujuan</th>
                    <th>Jumlah Unit</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayats as $r)
                <tr>
                    <td><small class="text-muted">{{ $r->created_at->format('d M Y, H:i') }}</small></td>
                    <td class="fw-semibold">{{ $r->barang->nama_barang ?? 'Aset Dihapus' }}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark">{{ $r->lokasi_asal }}</span></td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $r->lokasi_tujuan }}</span></td>
                    <td><span class="fw-bold">{{ $r->jumlah_ditransfer }} Unit</span></td>
                    <td><small class="text-muted">{{ $r->keterangan }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">Belum ada riwayat transfer aset yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $riwayats->links() }}
    </div>
</div>
@endsection