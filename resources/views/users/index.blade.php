@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Manajemen Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun admin fakultas dan atur akses sistem.</p>
    </div>
    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-plus-lg me-1"></i> Tambah User Baru
    </button>
</div>

<div class="card card-custom p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Nama</th>
                    <th>Email</th>
                    <th>Fakultas</th>
                    <th>Role</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td class="ps-4 fw-semibold">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3">{{ $u->fakultas }}</span></td>
                    <td><span class="badge {{ $u->role == 'super_admin' ? 'bg-danger' : 'bg-success' }} bg-opacity-10 text-dark border rounded-pill px-3">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</span></td>
                    <td class="text-end pe-4">
                        @if($u->id !== auth()->id())
                        <button class="btn btn-sm btn-outline-warning rounded-pill px-3 me-1" data-bs-toggle="modal" data-bs-target="#resetPassModal{{ $u->id }}">
                            <i class="bi bi-key"></i> Reset Pass
                        </button>
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="bi bi-trash"></i></button>
                        </form>
                        @else
                        <small class="text-muted fst-italic">Akun Anda</small>
                        @endif
                    </td>
                </tr>

                <!-- Modal Reset Password -->
                <div class="modal fade" id="resetPassModal{{ $u->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('users.reset-password', $u->id) }}" method="POST">
                                @csrf
                                <div class="modal-header"><h5 class="modal-title">Reset Password: {{ $u->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="new_password" class="form-control" required minlength="8">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">Simpan Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top">{{ $users->links() }}</div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header"><h5 class="modal-title">Tambah User Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Lengkap</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email Institusi</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Fakultas</label>
                        <select name="fakultas" class="form-select" required>
                            @foreach($fakultasList as $f)<option value="{{ $f }}">{{ $f }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label">Password Awal</label><input type="password" name="password" class="form-control" required minlength="8"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection