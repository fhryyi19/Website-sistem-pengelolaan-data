<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f5f5f7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 — Sesi Berakhir</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#f5f5f7] flex items-center justify-center p-4 font-sans text-[#1d1d1f]">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-16 h-16 bg-amber-100 text-amber-700 rounded-3xl mx-auto flex items-center justify-center font-bold text-xl">419</div>
        <h1 class="text-2xl font-bold tracking-tight">Sesi Halaman Berakhir</h1>
        <p class="text-xs text-[#7a7a7a]">Sesi keamanan Anda telah habis karena tidak ada aktivitas. Silakan muat ulang halaman.</p>
        <a href="{{ route('login') }}" class="inline-block h-10 px-6 bg-[#0066cc] text-white text-xs font-medium rounded-full leading-10 hover:bg-[#0071e3] transition">Masuk Kembali</a>
    </div>
</body>
</html>
