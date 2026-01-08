<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Akademik Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="w-100" style="max-width: 450px;">
            <div class="card login-card border-0 p-4">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-primary">Sistem Akademik</h3>
                            <p class="text-muted">Silakan masuk untuk melanjutkan</p>
                        </div>

                        <form id="loginForm" action="{{ route('web.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label fw-medium">Username</label>
                                <input 
                                    type="text" 
                                    id="username" 
                                    name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Masukkan username"
                                    value="{{ old('username') }}"
                                    required 
                                    autofocus
                                >
                                @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-medium">Password</label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Error Message for Credentials --}}
                            @if($errors->has('credential'))
                                <div class="alert alert-danger mb-3">
                                    {{ $errors->first('credential') }}
                                </div>
                            @endif

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                    Masuk
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('password.request') }}" class="text-decoration-none text-muted small">Lupa Password?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    </script>
</body>
</html>
