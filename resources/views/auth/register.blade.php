<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Inventaris Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0; }
        .register-card { max-width: 500px; width: 100%; border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .brand-logo { color: #0d6efd; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; }
    </style>
</head>
<body>
    <div class="card register-card p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-plus-fill fs-1 text-success"></i>
            <h3 class="fw-bold mt-2 brand-logo">Daftar Akun Baru</h3>
            <p class="text-muted small">Pilih fakultas sesuai unit kerja Anda</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Email Institusi</label>
                    <input type="email" name="email" class="form-control rounded-3" value="{{ old('email') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Fakultas / Unit Kerja</label>
                    <select name="fakultas" class="form-select rounded-3" required>
                        <option value="" disabled selected>-- Pilih Fakultas --</option>
                        @foreach($fakultasList as $f)
                            <option value="{{ $f }}" {{ old('fakultas') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control rounded-3" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-3" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 mt-4">Buat Akun</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Masuk di sini</a></small>
        </div>
    </div>
</body>
</html>