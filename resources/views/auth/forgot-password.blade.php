<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Akademik Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #51A2FF 0%, #AD46FF 50%, #51A2FF 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(173, 70, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            top: -150px;
            left: -150px;
            animation: float 8s ease-in-out infinite;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(81, 162, 255, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            animation: float 10s ease-in-out infinite reverse;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.1); }
        }

        .forgot-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .forgot-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                        0 0 80px rgba(173, 70, 255, 0.2);
            max-width: 480px;
            width: 100%;
            position: relative;
            overflow: hidden;
            border: none;
        }

        .forgot-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(173, 70, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        .card-body {
            padding: 48px 40px;
            position: relative;
            z-index: 1;
        }

        .forgot-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #51A2FF 0%, #AD46FF 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(173, 70, 255, 0.3);
        }

        .icon-wrapper i {
            font-size: 36px;
            color: white;
        }

        .forgot-title {
            font-size: 26px;
            font-weight: 700;
            background: linear-gradient(135deg, #51A2FF 0%, #AD46FF 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .forgot-subtitle {
            font-size: 14px;
            color: #64748b;
            font-weight: 400;
            margin: 0;
            line-height: 1.6;
        }

        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            padding: 14px 18px;
            font-size: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            border-color: #AD46FF;
            box-shadow: 0 0 0 4px rgba(173, 70, 255, 0.1),
                        0 0 20px rgba(173, 70, 255, 0.15);
            transform: translateY(-1px);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
            background: linear-gradient(135deg, #51A2FF 0%, #AD46FF 100%);
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(173, 70, 255, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(173, 70, 255, 0.4),
                        0 0 40px rgba(81, 162, 255, 0.3);
            color: #ffffff;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .back-to-login {
            text-align: center;
            margin-top: 24px;
        }

        .back-to-login a {
            font-size: 14px;
            color: #AD46FF;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-to-login a:hover {
            color: #51A2FF;
            text-shadow: 0 0 8px rgba(81, 162, 255, 0.3);
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 36px 28px;
            }

            .forgot-title {
                font-size: 22px;
            }

            .icon-wrapper {
                width: 70px;
                height: 70px;
            }

            .icon-wrapper i {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="card forgot-card">
            <div class="card-body">
                <div class="forgot-header">
                    <div class="icon-wrapper">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <h1 class="forgot-title">Lupa Password?</h1>
                    <p class="forgot-subtitle">
                        Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password Anda
                    </p>
                </div>

                <form>
                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="contoh@email.com"
                            required 
                            autofocus
                        >
                    </div>

                    <button type="submit" class="btn btn-submit">
                        Kirim Link Reset Password
                    </button>

                    <div class="back-to-login">
                        <a href="/login">
                            <i class="bi bi-arrow-left"></i>
                            Kembali ke Halaman Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
