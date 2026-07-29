<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $cards = [
            ['Total Pegawai', $stats['total_employees'], 'bg-blue-50 text-blue-600', 'Total data terdaftar'],
            ['Pegawai Aktif', $stats['active_employees'], 'bg-emerald-50 text-emerald-600', 'PNS dan CPNS aktif'],
            ['Pengguna Sistem', $stats['total_users'], 'bg-violet-50 text-violet-600', 'Akun berotoritas'],
            ['Unit Kerja', $stats['total_work_units'], 'bg-amber-50 text-amber-600', 'Struktur organisasi'],
        ];

        $activePercentage = $stats['total_employees']
            ? round(($stats['active_employees'] / $stats['total_employees']) * 100)
            : 0;
    @endphp

    <div class="mb-5 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <h1 class="text-xl font-bold tracking-tight text-slate-900">Dashboard</h1>

        <div class="flex flex-wrap items-center gap-2">
            <button class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
                Data saat ini
            </button>

            <a id="dashboard-employees" href="{{ route('employees.index') }}" class="inline-flex h-9 items-center gap-2 rounded-lg bg-blue-600 px-4 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Kelola Pegawai
            </a>
        </div>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik utama">
        @foreach ($cards as [$label, $value, $color, $detail])
            <article class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-600">{{ $label }}</p>
                    <span class="grid h-8 w-8 place-items-center rounded-lg {{ $color }}">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($value) }}</p>
                <p class="mt-1 text-[10px] text-slate-400">{{ $detail }}</p>
            </article>
        @endforeach
    </section>

    <div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1.65fr)_minmax(280px,0.8fr)]">
        <div class="space-y-4">
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Komposisi Pegawai</h2>
                        <p class="mt-1 text-[10px] text-slate-400">Distribusi data kepegawaian saat ini</p>
                    </div>
                    <a href="{{ route('employees.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Detail →</a>
                </div>

                <div class="mt-7 flex h-36 items-end gap-3 border-b border-l border-slate-100 px-4 pb-1">
                    <span class="w-1/4 rounded-t-lg bg-blue-500" style="height: 90%"></span>
                    <span class="w-1/4 rounded-t-lg bg-emerald-400" style="height: 78%"></span>
                    <span class="w-1/4 rounded-t-lg bg-violet-400" style="height: 30%"></span>
                    <span class="w-1/4 rounded-t-lg bg-amber-400" style="height: 45%"></span>
                </div>

                <div class="mt-3 grid grid-cols-4 text-center text-[10px] font-bold text-slate-500">
                    <span>Total</span><span>Aktif</span><span>Pengguna</span><span>Unit</span>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Akses Cepat Data</h2>
                        <p class="mt-1 text-[10px] text-slate-400">Navigasi master dan pengelolaan data</p>
                    </div>
                    <span class="text-lg text-slate-300">•••</span>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left">
                        <thead class="border-b border-slate-100 text-[10px] uppercase tracking-wide text-slate-400">
                            <tr>
                                <th class="pb-3 font-bold">Data</th>
                                <th class="pb-3 font-bold">Jumlah</th>
                                <th class="pb-3 font-bold">Keterangan</th>
                                <th class="pb-3"></th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            <tr class="border-b border-slate-50">
                                <td class="py-4 font-bold text-slate-700">Data Pegawai</td>
                                <td class="py-4 font-bold text-slate-900">{{ number_format($stats['total_employees']) }}</td>
                                <td class="py-4 text-slate-400">Seluruh pegawai</td>
                                <td class="py-4 text-right"><a href="{{ route('employees.index') }}" class="font-bold text-blue-600">Buka</a></td>
                            </tr>
                            <tr class="border-b border-slate-50">
                                <td class="py-4 font-bold text-slate-700">Jabatan</td>
                                <td class="py-4 font-bold text-slate-900">{{ number_format($stats['total_positions']) }}</td>
                                <td class="py-4 text-slate-400">Master jabatan</td>
                                <td class="py-4 text-right"><a href="{{ route('master.index') }}" class="font-bold text-blue-600">Buka</a></td>
                            </tr>
                            <tr>
                                <td class="pt-4 font-bold text-slate-700">Golongan</td>
                                <td class="pt-4 font-bold text-slate-900">{{ number_format($stats['total_ranks']) }}</td>
                                <td class="pt-4 text-slate-400">Pangkat / golongan</td>
                                <td class="pt-4 text-right"><a href="{{ route('master.index') }}" class="font-bold text-blue-600">Buka</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <aside class="space-y-4">
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-900">Status Data</h2>
                    <span class="text-lg text-slate-300">•••</span>
                </div>
                <div class="mt-5 grid grid-cols-7 items-end gap-2">
                    <span class="h-10 rounded-md bg-slate-100"></span><span class="h-14 rounded-md bg-slate-100"></span><span class="h-20 rounded-md bg-blue-500"></span><span class="h-12 rounded-md bg-slate-100"></span><span class="h-8 rounded-md bg-slate-100"></span><span class="h-16 rounded-md bg-slate-100"></span><span class="h-11 rounded-md bg-slate-100"></span>
                </div>
                <div class="mt-3 grid grid-cols-7 text-center text-[9px] font-bold text-slate-400">
                    <span>Sn</span><span>Sl</span><span>Rb</span><span>Km</span><span>Jm</span><span>Sb</span><span>Mg</span>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-5 text-center shadow-sm">
                <h2 class="text-left text-sm font-bold text-slate-900">Kelengkapan Data</h2>
                <div class="mx-auto mt-5 grid h-32 w-32 place-items-center rounded-full border-[12px] border-blue-100 border-t-blue-500">
                    <div>
                        <p class="text-2xl font-bold text-slate-900">{{ $activePercentage }}%</p>
                        <p class="text-[10px] text-slate-400">pegawai aktif</p>
                    </div>
                </div>
                <p class="mt-4 text-[10px] text-slate-400">Persentase status aktif dari data terdaftar.</p>
            </section>

            <section class="rounded-2xl bg-gradient-to-br from-blue-600 to-blue-800 p-5 text-white shadow-sm">
                <p class="text-xs font-bold">Aksi cepat</p>
                <p class="mt-2 text-[11px] leading-5 text-blue-100">Tambah, perbarui, atau ekspor data pegawai.</p>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('employees.index') }}" class="rounded-lg bg-white px-3 py-2 text-[10px] font-bold text-blue-700">Buka data</a>
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('export.excel') }}" class="rounded-lg border border-white/30 px-3 py-2 text-[10px] font-bold text-white">Export</a>
                    @endif
                </div>
            </section>
        </aside>
    </div>
</x-app-layout>
