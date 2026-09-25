<x-app-layout>
    <x-slot name="title">Akses Mobile Aplikasi Expo</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">Akses Mobile (Expo Project)</h1>
        <p class="text-xs text-[#7a7a7a] mt-1">Pindai QR Code di bawah menggunakan HP untuk membuka project aplikasi di folder <code class="bg-slate-100 px-1.5 py-0.5 rounded text-blue-600 font-mono text-[11px]">Aplikasi</code>.</p>
    </div>

    <div x-data="{ 
            protocol: 'exp',
            ip: '{{ $serverIp }}',
            port: '{{ $defaultPort }}',
            customPath: '',
            copied: false,

            get fullUrl() {
                let base = this.ip + (this.port ? ':' + this.port : '');
                if (this.protocol === 'exp') {
                    return 'exp://' + base + (this.customPath ? '/' + this.customPath : '');
                } else {
                    return 'http://' + base + (this.customPath ? '/' + this.customPath : '');
                }
            },

            copyToClipboard() {
                navigator.clipboard.writeText(this.fullUrl);
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            }
        }" 
        class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Card Kiri: QR Code & Switcher -->
        <div class="lg:col-span-1 bg-white rounded-3xl border border-[#e0e0e0] p-6 shadow-sm flex flex-col items-center justify-between space-y-6">
            <div class="w-full text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-semibold">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                    QR Code Akses Mobile
                </span>
            </div>

            <!-- Frame QR Code Image -->
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner flex flex-col items-center justify-center w-full">
                <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center">
                    <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' + encodeURIComponent(fullUrl)" 
                         alt="QR Code Akses Mobile" 
                         class="w-56 h-56 object-contain rounded-lg block">
                </div>
                <p class="text-[11px] font-mono text-slate-500 mt-3 break-all text-center px-2 font-semibold" x-text="fullUrl"></p>
            </div>

            <!-- Tombol Action -->
            <div class="w-full space-y-2">
                <button @click="copyToClipboard()" 
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a1.125 1.125 0 0 0-1.125-1.125H18.75"/></svg>
                    <span x-text="copied ? 'Berhasil Disalin!' : 'Salin URL Akses'"></span>
                </button>
            </div>
        </div>

        <!-- Card Kanan: Pengaturan Dynamic URL & Panduan Petunjuk -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Form Kustomisasi IP/Port -->
            <div class="bg-white rounded-3xl border border-[#e0e0e0] p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 1-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0m-9.75 0h9.75"/></svg>
                    Pengaturan Koneksi QR Code
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Protocol Switcher -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Mode Akses</label>
                        <select x-model="protocol" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                            <option value="exp">Expo Go (exp://)</option>
                            <option value="http">Browser Mobile (http://)</option>
                        </select>
                    </div>

                    <!-- IP Address -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Host / IP Address Server</label>
                        <input type="text" x-model="ip" placeholder="192.168.x.x" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 font-mono">
                    </div>

                    <!-- Port -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Port Server Expo</label>
                        <input type="text" x-model="port" placeholder="8081" class="w-full text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- Petunjuk Langkah-langkah Akses Mobile -->
            <div class="bg-white rounded-3xl border border-[#e0e0e0] p-6 shadow-sm space-y-4">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                    Petunjuk Menjalankan & Membuka di HP
                </h2>

                <div class="space-y-3 text-xs text-slate-600 leading-relaxed">
                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="flex shrink-0 h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-[11px]">1</span>
                        <div>
                            <p class="font-semibold text-slate-800">Jalankan Aplikasi Mobile dari File Batch</p>
                            <p class="text-slate-500 mt-0.5">Buka folder <code class="bg-slate-200 px-1 py-0.5 rounded text-slate-700">C:\xampp\htdocs\Sistem Pengelolaan Data\Aplikasi</code> lalu klik 2x file <code class="bg-blue-100 text-blue-800 font-semibold px-1 py-0.5 rounded">Run Mobile.bat</code>.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="flex shrink-0 h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-[11px]">2</span>
                        <div>
                            <p class="font-semibold text-slate-800">Sambungkan HP ke Wi-Fi / Jaringan yang Sama</p>
                            <p class="text-slate-500 mt-0.5">Pastikan smartphone Anda terhubung ke jaringan Wi-Fi lokal yang sama dengan komputer server ini agar IP <code class="font-mono text-slate-700 font-semibold" x-text="ip"></code> dapat dijangkau.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="flex shrink-0 h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700 font-bold text-[11px]">3</span>
                        <div>
                            <p class="font-semibold text-slate-800">Scan QR Code dari Aplikasi Expo Go atau Kamera HP</p>
                            <p class="text-slate-500 mt-0.5">
                                • Untuk <b>Mode Expo Go (exp://)</b>: Buka aplikasi <b>Expo Go</b> di Android/iOS, lalu pilih <i>Scan QR Code</i>.<br>
                                • Untuk <b>Mode Browser (http://)</b>: Buka Kamera bawaan HP lalu arahkan ke gambar QR Code di samping.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
