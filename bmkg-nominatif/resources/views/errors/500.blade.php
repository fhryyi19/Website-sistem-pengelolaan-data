<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f5f5f7]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 — Kesalahan Server</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#f5f5f7] flex items-center justify-center p-4 font-sans text-[#1d1d1f]">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="w-16 h-16 bg-red-100 text-red-600 rounded-3xl mx-auto flex items-center justify-center font-bold text-xl">500</div>
        <h1 class="text-2xl font-bold tracking-tight">Terjadi Kesalahan Server</h1>
        <p class="text-xs text-[#7a7a7a]">Maaf, terjadi kesalahan internal pada server kami. Masalah ini telah dicatat oleh sistem audit log.</p>
        <a href="{{ route('dashboard') }}" class="inline-block h-10 px-6 bg-[#0066cc] text-white text-xs font-medium rounded-full leading-10 hover:bg-[#0071e3] transition">Kembali ke Dashboard</a>
    </div>
</body>
</html>
