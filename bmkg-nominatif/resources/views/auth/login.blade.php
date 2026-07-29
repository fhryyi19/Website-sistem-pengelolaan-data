<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login — {{ config('nominatif.name', 'BMKG Klas I Bandung') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
        }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-center: center;
            padding: 12px;
            margin-bottom: 16px;
            background: white;
            border-radius: 24px;
            border: 1px solid white;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }
        
        .logo-wrapper img {
            height: 64px;
            width: auto;
            object-fit: contain;
            display: block;
        }
        
        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #64748b;
        }
        
        .login-card { 
            backdrop-filter: blur(30px); 
            background: rgba(255, 255, 255, 0.6); 
            border: 1px solid rgba(255,255,255,.5); 
            box-shadow: 0 20px 40px rgba(0,0,0,.06);
            border-radius: 24px;
            padding: 32px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            margin-left: 4px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            height: 48px;
            padding: 0 20px;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            outline: none;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #0066cc;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        
        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        input[type="checkbox"] {
            width: 16px;
            height: 16px;
            margin-right: 8px;
            cursor: pointer;
        }
        
        .remember-label {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        .btn-submit {
            width: 100%;
            height: 48px;
            background: #0066cc;
            color: white;
            font-weight: 600;
            font-size: 14px;
            border: none;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background: #005bb5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,102,204,.3);
        }
        
        .btn-submit:active {
            transform: translateY(0);
        }
        
        .register-link {
            text-align: center;
            font-size: 13px;
            color: #64748b;
            margin-top: 24px;
        }
        
        .register-link a {
            color: #0066cc;
            font-weight: 700;
            text-decoration: none;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <div class="logo-wrapper">
                <img src="{{ asset('images/Logo BMKG.png') }}" alt="BMKG Logo" onerror="this.style.display='none'">
            </div>
            <h1>Selamat Datang</h1>
            <p class="subtitle">Sistem Nominatif Pegawai BMKG</p>
        </div>

        <div class="login-card">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="login">Username / Email</label>
                    <input type="text" id="login" name="login" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="remember-section">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="remember-label">Ingat saya</label>
                </div>

                <button type="submit" class="btn-submit">Masuk Sekarang</button>
            </form>
        </div>

        <p class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
        </p>
    </div>
</body>
</html>
