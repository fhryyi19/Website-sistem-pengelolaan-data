<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun — Sistem Nominatif Pegawai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: #f0f2f5; }
        .register-card { backdrop-filter: blur(30px); background: rgba(255, 255, 255, 0.4); border: 1px solid rgba(255,255,255,.5); box-shadow: 0 20px 40px rgba(0,0,0,.06); }
        .btn-hover:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,102,204,.2); }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-50 to-blue-50">

    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Buat Akun Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi data untuk mendaftar</p>
        </div>

        <div class="register-card rounded-3xl p-8">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                @if ($errors->any())
                    <div class="p-3 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border-l-4 border-red-500">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-12 px-5 text-sm bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-12 px-5 text-sm bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required class="w-full h-12 px-5 text-sm bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Password</label>
                    <input type="password" name="password" required class="w-full h-12 px-5 text-sm bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required class="w-full h-12 px-5 text-sm bg-white/50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <button type="submit" class="w-full h-12 bg-[#0066cc] hover:bg-[#005bb5] text-white font-semibold text-sm rounded-2xl transition-all btn-hover mt-4">Daftar Sekarang</button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-[#0066cc] font-bold hover:underline">Masuk</a>
        </p>
    </div>
</body>
</html>
