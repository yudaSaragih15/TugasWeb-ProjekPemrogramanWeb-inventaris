@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold">Transfer & Riwayat Aset</h3>
    <p class="text-muted">Catatan riwayat pemindahan dan transfer lokasi aset kampus FMIPA UNIMED.</p>
</div>

<div class="card border-0 shadow-sm p-4 rounded-3">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr class="text-muted">
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Lokasi Asal</th>
                    <th>Lokasi Tujuan</th>
                    <th>Tanggal Transfer</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-arrow-left-right fs-1 d-block mb-2 text-secondary"></i>
                        Belum ada riwayat transfer aset.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection