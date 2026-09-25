<x-app-layout>
    <x-slot name="title">Riwayat Kenaikan Gaji Pegawai</x-slot>

    {{-- ─── Page Header ─── --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('dashboard') }}" class="text-xs font-semibold text-slate-400 hover:text-blue-600 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Dashboard
                </a>
                <span class="text-xs text-slate-300">/</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-100 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-600 uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Kenaikan Gaji Berkala / Pangkat
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Pegawai Naik Gaji</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh riwayat perubahan dan kenaikan gaji pegawai Stasiun Geofisika Klas I Bandung.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('employees.index') }}"
               class="inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 shadow-sm transition-all hover:border-slate-300 hover:shadow-md active:scale-[.98]">
                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Semua Data Pegawai
            </a>
        </div>
    </div>

    {{-- ─── Stat Cards ─── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500">Pegawai Naik Gaji</p>
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-blue-50 text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($uniqueEmployees) }} <span class="text-xs font-normal text-slate-400">Pegawai</span></p>
            <p class="mt-1 text-[10px] text-slate-400">Jumlah pegawai unik yang pernah naik gaji</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500">Total Kenaikan Nominal</p>
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tracking-tight text-emerald-600">Rp {{ number_format($totalNominalIncrease, 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-slate-400">Akumulasi seluruh selisih kenaikan</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500">Rata-Rata Kenaikan</p>
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-violet-50 text-violet-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">Rp {{ number_format($avgIncrease, 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-slate-400">Per riwayat kenaikan gaji tercatat</p>
        </div>

        <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500">Total Riwayat SK</p>
                <span class="grid h-8 w-8 place-items-center rounded-lg bg-amber-50 text-amber-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tracking-tight text-slate-900">{{ number_format($totalRecords) }} <span class="text-xs font-normal text-slate-400">Data</span></p>
            <p class="mt-1 text-[10px] text-slate-400">Kenaikan tertinggi: Rp {{ number_format($maxIncrease, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ─── Filter & Search Bar ─── --}}
    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm mb-6">
        <form method="GET" action="{{ route('salary-history.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
            {{-- Search Pegawai --}}
            <div class="lg:col-span-4">
                <label for="search" class="block text-[11px] font-bold text-slate-500 mb-1">Cari Pegawai / SK</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ $search }}"
                           placeholder="Nama pegawai, NIP, atau perihal SK..."
                           class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Filter Tahun TMT --}}
            <div class="lg:col-span-2">
                <label for="year" class="block text-[11px] font-bold text-slate-500 mb-1">Tahun Berlaku (TMT)</label>
                <select id="year" name="year" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Tahun</option>
                    @foreach ($availableYears as $y)
                        <option value="{{ $y }}" {{ (string)$year === (string)$y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Alasan --}}
            <div class="lg:col-span-3">
                <label for="reason" class="block text-[11px] font-bold text-slate-500 mb-1">Jenis Kenaikan</label>
                <select id="reason" name="reason" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Jenis</option>
                    <option value="Berkala" {{ $reason === 'Berkala' ? 'selected' : '' }}>Kenaikan Gaji Berkala (KGB)</option>
                    <option value="Pangkat" {{ $reason === 'Pangkat' ? 'selected' : '' }}>Kenaikan Pangkat</option>
                    <option value="Penyesuaian" {{ $reason === 'Penyesuaian' ? 'selected' : '' }}>Penyesuaian / Regulasi Baru</option>
                </select>
            </div>

            {{-- Urutan --}}
            <div class="lg:col-span-2">
                <label for="sort" class="block text-[11px] font-bold text-slate-500 mb-1">Urutan</label>
                <select id="sort" name="sort" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                    <option value="latest_effective" {{ $sortBy === 'latest_effective' ? 'selected' : '' }}>TMT Terbaru</option>
                    <option value="oldest_effective" {{ $sortBy === 'oldest_effective' ? 'selected' : '' }}>TMT Terlama</option>
                    <option value="highest_increase" {{ $sortBy === 'highest_increase' ? 'selected' : '' }}>Nominal Naik Tertinggi</option>
                    <option value="highest_salary" {{ $sortBy === 'highest_salary' ? 'selected' : '' }}>Gaji Pokok Baru Tertinggi</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="lg:col-span-1 flex items-end gap-1 pt-5">
                <button type="submit" class="w-full h-10 rounded-xl bg-blue-600 text-white text-xs font-bold hover:bg-blue-700 transition flex items-center justify-center">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- ─── Table Riwayat Kenaikan Gaji ─── --}}
    <div class="rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-900">Daftar Riwayat Perubahan Gaji</h2>
            <span class="text-xs text-slate-500">Menampilkan {{ $salaryHistories->total() }} data</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                        <th class="py-3 px-4">Pegawai</th>
                        <th class="py-3 px-4">Jabatan / Golongan</th>
                        <th class="py-3 px-4">Tanggal TMT</th>
                        <th class="py-3 px-4">Gaji Lama</th>
                        <th class="py-3 px-4">Gaji Baru</th>
                        <th class="py-3 px-4">Kenaikan</th>
                        <th class="py-3 px-4">Alasan / SK</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($salaryHistories as $history)
                        @php
                            $emp = $history->employee;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            {{-- Pegawai Info --}}
                            <td class="py-3 px-4">
                                @if ($emp)
                                    <a href="{{ route('employees.show', $emp->id) }}" class="font-bold text-slate-900 hover:text-blue-600 transition flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600 shrink-0">
                                            {{ strtoupper(substr($emp->full_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold leading-snug">{{ $emp->full_name_with_title }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $emp->nip ? 'NIP: ' . $emp->nip : '-' }}</p>
                                        </div>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Pegawai telah dihapus</span>
                                @endif
                            </td>

                            {{-- Jabatan / Golongan --}}
                            <td class="py-3 px-4">
                                <p class="font-semibold text-slate-700">{{ $emp?->currentPosition?->position?->name ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400">{{ $emp?->currentRank?->rank?->name ?? '-' }}</p>
                            </td>

                            {{-- Tanggal TMT --}}
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                                    </svg>
                                    {{ $history->formatted_effective_date }}
                                </span>
                            </td>

                            {{-- Gaji Lama --}}
                            <td class="py-3 px-4 text-slate-500 font-mono">
                                {{ $history->old_salary ? 'Rp ' . number_format($history->old_salary, 0, ',', '.') : '-' }}
                            </td>

                            {{-- Gaji Baru --}}
                            <td class="py-3 px-4 font-bold text-slate-900 font-mono">
                                Rp {{ number_format($history->new_salary, 0, ',', '.') }}
                            </td>

                            {{-- Kenaikan --}}
                            <td class="py-3 px-4">
                                @if ($history->increase_amount > 0)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-100">
                                        <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        </svg>
                                        + Rp {{ number_format($history->increase_amount, 0, ',', '.') }}
                                        @if ($history->increase_percentage)
                                            <span class="text-[9px] text-emerald-500 font-normal">({{ number_format($history->increase_percentage, 1) }}%)</span>
                                        @endif
                                    </span>
                                @elseif ($history->increase_amount < 0)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[11px] font-bold text-rose-700 border border-rose-100">
                                        - Rp {{ number_format(abs($history->increase_amount), 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400">Rp 0</span>
                                @endif
                            </td>

                            {{-- Alasan --}}
                            <td class="py-3 px-4 max-w-[200px]">
                                <p class="font-semibold text-slate-700 truncate" title="{{ $history->reason }}">{{ $history->reason ?? '-' }}</p>
                                @if ($history->notes)
                                    <p class="text-[10px] text-slate-400 truncate" title="{{ $history->notes }}">{{ $history->notes }}</p>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="py-3 px-4 text-center">
                                @if ($emp)
                                    <a href="{{ route('employees.show', $emp->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition">
                                        Lihat
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Tidak ada data riwayat kenaikan gaji ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($salaryHistories->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $salaryHistories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
