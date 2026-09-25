<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    @php
        $cards = [
            ['Total Pegawai', $stats['total_employees'], 'bg-blue-50 text-blue-600', 'Total data terdaftar', route('employees.index')],
            ['Pegawai Aktif', $stats['active_employees'], 'bg-emerald-50 text-emerald-600', 'PNS dan CPNS aktif', route('employees.index')],
            ['Pegawai Naik Gaji', $stats['total_salary_increases'], 'bg-violet-50 text-violet-600', 'Riwayat kenaikan gaji', route('salary-history.index')],
            ['Unit Kerja', $stats['total_work_units'], 'bg-amber-50 text-amber-600', 'Struktur organisasi', route('master.index')],
        ];

        $activePercentage = $stats['total_employees']
            ? round(($stats['active_employees'] / $stats['total_employees']) * 100)
            : 0;
    @endphp

    <div class="mb-5 flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
        <h1 class="text-xl font-bold tracking-tight text-slate-900">Dashboard</h1>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('employees.index') }}" class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-600 transition hover:bg-slate-50 hover:border-slate-300">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                </svg>
                Data saat ini
            </a>

            <a id="dashboard-employees" href="{{ route('employees.index') }}" class="inline-flex h-9 items-center gap-2 rounded-lg bg-blue-600 px-4 text-xs font-bold text-white shadow-sm transition hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Kelola Pegawai
            </a>
        </div>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Statistik utama">
        @foreach ($cards as [$label, $value, $color, $detail, $link])
            <a href="{{ $link }}" class="group block rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:border-blue-200 hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold text-slate-600 group-hover:text-blue-600 transition">{{ $label }}</p>
                    <span class="grid h-8 w-8 place-items-center rounded-lg {{ $color }} transition-transform group-hover:scale-110">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($value) }}</p>
                <p class="mt-1 text-[10px] text-slate-400 flex items-center justify-between">
                    <span>{{ $detail }}</span>
                    <span class="text-blue-600 font-bold opacity-0 group-hover:opacity-100 transition">Lihat →</span>
                </p>
            </a>
        @endforeach
    </section>

    <div class="mt-4 grid gap-4 xl:grid-cols-[minmax(0,1.65fr)_minmax(280px,0.8fr)]">
        <div class="space-y-4">
            {{-- Distribusi Golongan / Pangkat --}}
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Distribusi Golongan & Pangkat</h2>
                        <p class="mt-1 text-[10px] text-slate-400">Komposisi pegawai berdasarkan jenjang kepangkatan (Golongan I — IV)</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700">
                        {{ $stats['total_rank_count'] ?? 0 }} Pegawai
                    </span>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($stats['rank_stats'] ?? [] as $groupKey => $rg)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold text-slate-700 text-[11px]">{{ $rg['label'] }}</span>
                                    <span class="text-[10px] text-slate-400">({{ $rg['percentage'] }}%)</span>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700 min-w-[2.5rem] justify-center">
                                        {{ $rg['count'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $rg['bar_width'] }}%; background-color: {{ $rg['color_hex'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- Demografi Gender & Status Kepegawaian --}}
            <div class="grid gap-4 sm:grid-cols-2">
                {{-- Gender Ratio --}}
                <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Komposisi Gender</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">Rasio Pria vs Wanita</p>
                        </div>
                        <span class="text-xs font-bold text-slate-400">Total: {{ $stats['gender_stats']['total'] ?? 0 }}</span>
                    </div>

                    {{-- Split Visual Bar --}}
                    <div class="mt-4 flex h-3 w-full overflow-hidden rounded-full bg-slate-100">
                        <div class="bg-blue-500 transition-all duration-500" style="width: {{ $stats['gender_stats']['male_pct'] ?? 50 }}%" title="Laki-laki: {{ $stats['gender_stats']['male_pct'] ?? 0 }}%"></div>
                        <div class="bg-rose-400 transition-all duration-500" style="width: {{ $stats['gender_stats']['female_pct'] ?? 50 }}%" title="Perempuan: {{ $stats['gender_stats']['female_pct'] ?? 0 }}%"></div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="flex items-center gap-2.5 rounded-xl border border-slate-50 bg-blue-50/50 p-2.5">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-blue-100 text-blue-600 font-bold text-xs">♂</span>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-slate-500">Laki-laki</p>
                                <p class="text-xs font-bold text-slate-900">{{ $stats['gender_stats']['male_count'] ?? 0 }} <span class="text-[10px] font-medium text-slate-400">({{ $stats['gender_stats']['male_pct'] ?? 0 }}%)</span></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 rounded-xl border border-slate-50 bg-rose-50/50 p-2.5">
                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg bg-rose-100 text-rose-600 font-bold text-xs">♀</span>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-slate-500">Perempuan</p>
                                <p class="text-xs font-bold text-slate-900">{{ $stats['gender_stats']['female_count'] ?? 0 }} <span class="text-[10px] font-medium text-slate-400">({{ $stats['gender_stats']['female_pct'] ?? 0 }}%)</span></p>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Status Kepegawaian --}}
                <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Status Kepegawaian</h2>
                            <p class="mt-0.5 text-[10px] text-slate-400">Jenis status dinas</p>
                        </div>
                        <a href="{{ route('employees.index') }}" class="text-[11px] font-bold text-blue-600 hover:underline">Kelola →</a>
                    </div>

                    <div class="mt-3.5 space-y-2">
                        @forelse ($stats['status_stats'] ?? [] as $st)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full" style="background-color: {{ $st['color_hex'] }}"></span>
                                    <span class="font-semibold text-slate-700 text-[11px]">{{ $st['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <span class="font-bold text-slate-900 text-xs">{{ $st['count'] }}</span>
                                    <span class="text-[10px] text-slate-400">({{ $st['percentage'] }}%)</span>
                                </div>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $st['bar_width'] }}%; background-color: {{ $st['color_hex'] }};"></div>
                            </div>
                        @empty
                            <p class="text-center text-xs text-slate-400 py-3">Tidak ada data status.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Distribusi Pendidikan</h2>
                        <p class="mt-1 text-[10px] text-slate-400">Jumlah pegawai berdasarkan tingkat pendidikan (SMA, D1, D2, D3, S1, S2, S3)</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700">
                        {{ array_sum(array_column($stats['education_stats'] ?? [], 'count')) }} Pegawai
                    </span>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($stats['education_stats'] ?? [] as $item)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700 text-[11px] truncate pr-2" title="{{ $item['full_label'] }}">{{ $item['label'] }}</span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700 min-w-[2.5rem] justify-center">
                                        {{ $item['count'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $item['bar_width'] }}%; background-color: {{ $item['color_hex'] }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Distribusi Jabatan (Semua Jabatan)</h2>
                        <p class="mt-1 text-[10px] text-slate-400">Jumlah pegawai pada seluruh jabatan terdaftar</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-700">
                        {{ count($stats['position_stats'] ?? []) }} Jabatan
                    </span>
                </div>

                <div class="mt-5 space-y-3">
                    @foreach ($stats['position_stats'] ?? [] as $item)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-slate-700 text-[11px] truncate pr-2">{{ $item['label'] }}</span>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700 min-w-[2.5rem] justify-center">
                                        {{ $item['count'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full transition-all duration-500" style="width: {{ $item['bar_width'] }}%; background-color: {{ $item['color_hex'] }};"></div>
                            </div>
                        </div>
                    @endforeach
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
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Riwayat Kenaikan Gaji</h2>
                        <p class="mt-1 text-[10px] text-slate-400">5 perubahan gaji terbaru</p>
                    </div>
                    <a href="{{ route('salary-history.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Detail →</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($salaryHistories as $history)
                        <div class="flex items-center justify-between gap-3 border-b border-slate-50 pb-3 last:border-0 last:pb-0">
                            <div class="min-w-0">
                                <p class="truncate text-xs font-bold text-slate-700">{{ $history->employee?->full_name_with_title ?? 'Pegawai dihapus' }}</p>
                                <p class="mt-0.5 text-[10px] text-slate-400">{{ $history->formatted_effective_date }}</p>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-xs font-bold text-emerald-600">+ Rp{{ number_format($history->increase_amount, 0, ',', '.') }}</p>
                                <p class="mt-0.5 text-[10px] text-slate-400">Rp{{ number_format($history->new_salary, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-3 py-4 text-center text-xs text-slate-400">Belum ada riwayat kenaikan gaji.</p>
                    @endforelse
                </div>
            </section>

            {{-- Aktivitas Sistem / Live Audit Feed --}}
            <section class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Aktivitas Sistem Terbaru</h2>
                        <p class="mt-0.5 text-[10px] text-slate-400">Log audit riil penggunaan aplikasi</p>
                    </div>
                    @if (auth()->user()?->isAdmin())
                        <a href="{{ route('audit-logs.index') }}" class="text-xs font-bold text-blue-600 hover:underline">Semua →</a>
                    @endif
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($recentAuditLogs ?? [] as $log)
                        @php
                            $badgeClass = match($log->action) {
                                'CREATE' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'UPDATE' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'DELETE' => 'bg-red-50 text-red-700 border-red-200',
                                'LOGIN'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'LOGOUT' => 'bg-slate-100 text-slate-600 border-slate-200',
                                default  => 'bg-slate-100 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <div class="flex items-start justify-between gap-2.5 border-b border-slate-50 pb-2.5 last:border-0 last:pb-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="inline-flex rounded px-1.5 py-0.5 text-[9px] font-bold border {{ $badgeClass }}">
                                        {{ $log->action_label }}
                                    </span>
                                    <span class="truncate text-[11px] font-bold text-slate-700">{{ $log->user_name ?? 'Sistem' }}</span>
                                </div>
                                <p class="truncate text-[10px] text-slate-500" title="{{ $log->description }}">{{ $log->description }}</p>
                            </div>
                            <span class="shrink-0 text-[9px] text-slate-400 whitespace-nowrap mt-0.5">
                                {{ $log->created_at?->diffForHumans(null, true) }}
                            </span>
                        </div>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-3 py-4 text-center text-xs text-slate-400">Belum ada aktivitas tercatat.</p>
                    @endforelse
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
