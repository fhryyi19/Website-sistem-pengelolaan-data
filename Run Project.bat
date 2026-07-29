@echo off
title BMKG Nominatif Pegawai — Run Project
color 0A

echo ========================================================
echo   MENJALANKAN SISTEM INFORMASI NOMINATIF PEGAWAI BMKG
echo ========================================================
echo.

:: Pindah ke direktori project Laravel
cd /d "%~dp0bmkg-nominatif"

echo [1/3] Memeriksa & membersihkan cache...
call php artisan config:clear >nul 2>&1
call php artisan route:clear >nul 2>&1
call php artisan view:clear >nul 2>&1

echo [2/3] Membuka aplikasi di browser...
start http://127.0.0.1:8000

echo [3/4] Menjalankan Vite Dev Server...
start cmd /k "npm run dev"

echo [4/4] Menjalankan Development Server di port 8000...
echo.
echo --------------------------------------------------------
echo   Akses via Apache XAMPP : http://localhost/Website%%20Pengelolaan%%20Data/bmkg-nominatif/public/
echo   Akses via Artisan Serve: http://192.168.157.134:8000
echo --------------------------------------------------------
echo   Tekan Ctrl+C di jendela ini untuk menghentikan server.
echo ========================================================
echo.

php artisan serve --host=0.0.0.0 --port=8000
