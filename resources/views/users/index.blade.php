@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Pengguna</h3>
        <p class="text-muted mb-0">Atur akses admin, staff lab, dan peran pengguna lainnya.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4"><i class="bi bi-plus-lg me-1"></i> Tambah Pengguna</button>
</div>
<div class="card card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3">{{ $user->role ?? 'Admin' }}</span></td>
                    <td><span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">Aktif</span></td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">Edit</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection