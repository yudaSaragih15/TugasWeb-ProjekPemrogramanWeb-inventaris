<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventaris Kampus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 450px; width: 100%; border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .brand-logo { color: #0d6efd; font-weight: 800; font-size: 1.5rem; letter-spacing: -0.5px; }
    </style>
</head>
<body>
    <div class="card login-card p-4">
        <div class="text-center mb-4">
            <i class="bi bi-box-seam-fill fs-1 text-primary"></i>
            <h3 class="fw-bold mt-2 brand-logo">Inventaris<span class="text-dark">Kampus</span></h3>
            <p class="text-muted small">Portal Inventaris Aset UNIMED</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success rounded-3">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Institusi</label>
                <input type="email" name="email" class="form-control form-control-lg rounded-3" value="{{ old('email') }}" required autofocus placeholder="nama@unimed.ac.id">
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control form-control-lg rounded-3" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3">Masuk ke Dashboard</button>
        </form>

        <div class="text-center mt-4">
            <small class="text-muted">Lupa password? Hubungi Bagian Logistik / Super Admin.</small>
        </div>
    </div>
</body>
</html>