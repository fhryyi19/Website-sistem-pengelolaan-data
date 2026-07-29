<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f7f8fa]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem pengelolaan data nominatif pegawai BMKG Stasiun Meteorologi Kelas I Bandung.">
    <title>{{ config('nominatif.name', 'BMKG Klas I Bandung') }} — {{ $title ?? 'Sistem Nominatif Pegawai' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#f7f8fa] font-sans text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen lg:flex">
        @include('components.sidebar')
        <div class="min-w-0 flex-1 lg:pl-64">
            @include('components.header')
            <main class="mx-auto w-full max-w-[1500px] px-4 py-5 sm:px-6 lg:px-7">
                @if (session('success'))
                    <div class="mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-900" x-data="{ show: true }" x-show="show">
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-emerald-600 text-xs text-white">✓</span>
                        {{ session('success') }}
                    </div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
    <script>
        function confirmDelete(formId, title = 'Apakah Anda yakin?', text = 'Data yang dihapus akan dipindahkan ke tempat sampah.') {
            Swal.fire({ title, text, icon: 'warning', showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#64748b', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal', customClass: { popup: 'rounded-xl border border-slate-200', confirmButton: 'rounded-lg px-5 py-2.5 text-sm font-bold', cancelButton: 'rounded-lg px-5 py-2.5 text-sm font-bold' } }).then((result) => { if (result.isConfirmed) document.getElementById(formId).submit(); });
        }
    </script>
    @stack('scripts')
</body>
</html>
