<x-app-layout>
    <x-slot name="title">Data Pegawai</x-slot>

    {{-- ─── Page Header ─── --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 border border-blue-100 px-2.5 py-0.5 text-[10px] font-semibold text-blue-600 uppercase tracking-wider">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Aktif
                </span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Nominatif Pegawai</h1>
            <p class="text-xs text-slate-500 mt-1">Daftar seluruh pegawai Stasiun Meteorologi Klas I Bandung.</p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            {{-- Export Excel --}}
            <a href="{{ route('export.excel', request()->all()) }}"
               class="group inline-flex h-9 items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-xs font-semibold text-slate-700 shadow-sm transition-all hover:border-slate-300 hover:shadow-md active:scale-[.98]">
                <svg class="w-3.5 h-3.5 text-emerald-500 transition-transform group-hover:-translate-y-px" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export Excel
            </a>

            {{-- Tambah Pegawai (Admin Only) --}}
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('employees.create') }}"
                   class="inline-flex h-9 items-center gap-2 rounded-xl bg-blue-600 px-4 text-xs font-semibold text-white shadow-sm shadow-blue-500/30 transition-all hover:bg-blue-700 hover:shadow-blue-500/40 hover:shadow-md active:scale-[.98]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pegawai
                </a>
            @endif
        </div>
    </div>

    {{-- ─── Filter & Search Card ─── --}}
    <div class="mb-8 rounded-3xl border border-[#e0e0e0] bg-white p-6 sm:p-8 shadow-sm">
        <div class="flex items-center gap-2 mb-6 border-b border-gray-100 pb-4">
            <svg class="w-5 h-5 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.707 7.293A1 1 0 013 6.586V4z"/></svg>
            <h2 class="text-sm font-bold text-[#1d1d1f] uppercase tracking-wider">Filter Pencarian</h2>
        </div>

        <form method="GET" action="{{ route('employees.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- Search --}}
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Kata Kunci</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="NIP, nama, atau email..."
                           class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Status</label>
                    <select name="employment_status_id" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Semua Status</option>
                        @foreach ($employmentStatuses as $st)
                            <option value="{{ $st->id }}" {{ ($filters['employment_status_id'] ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Unit --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Unit Kerja</label>
                    <select name="work_unit_id" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Semua Unit</option>
                        @foreach ($workUnits as $unit)
                            <option value="{{ $unit->id }}" {{ ($filters['work_unit_id'] ?? '') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Golongan --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5 ml-1">Golongan</label>
                    <select name="rank_id" class="w-full h-11 px-4 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="">Semua Golongan</option>
                        @foreach ($ranks as $r)
                            <option value="{{ $r->id }}" {{ ($filters['rank_id'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->display_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="font-semibold text-slate-700">Tampilkan:</span>
                    <div class="relative">
                        <style>
                            .no-arrow { -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: none !important; }
                        </style>
                        <select name="per_page" onchange="this.form.submit()"
                                class="no-arrow h-10 px-4 rounded-2xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="25"  {{ $perPage == 25  ? 'selected' : '' }}>25 data</option>
                            <option value="50"  {{ $perPage == 50  ? 'selected' : '' }}>50 data</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 data</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('employees.index') }}" class="h-10 px-6 flex items-center justify-center rounded-full bg-slate-100 text-xs font-semibold text-slate-600 hover:bg-slate-200">Reset</a>
                    <button type="submit" class="h-10 px-6 flex items-center justify-center rounded-full bg-[#0066cc] text-white font-semibold text-xs hover:bg-[#005bb5]">Terapkan Filter</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ─── Table Card ─── --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80">
                        <th class="py-4 px-6 w-12 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">No</th>
                        <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-slate-400">NIP &amp; Nama Pegawai</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Unit Kerja</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Jabatan Saat Ini</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Gol / Pangkat</th>
                        <th class="py-4 px-6 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Status</th>
                        <th class="py-4 px-6 w-28 text-center text-[10px] font-bold uppercase tracking-widest text-slate-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse ($employees as $index => $employee)
                        <tr class="group transition-colors hover:bg-blue-50/30">
                            {{-- No --}}
                            <td class="py-5 px-6 text-center text-slate-400 font-mono text-[11px]">
                                {{ $employees->firstItem() + $index }}
                            </td>

                            {{-- NIP & Nama --}}
                            <td class="py-5 px-6">
                                <a href="{{ route('employees.show', $employee->id) }}"
                                   class="font-semibold text-blue-600 hover:text-blue-800 hover:underline underline-offset-2 block leading-snug transition-colors">
                                    {{ $employee->full_name_with_title }}
                                </a>
                                <div class="mt-0.5 flex flex-wrap gap-x-2.5 gap-y-0.5 text-[11px] text-slate-400 font-mono">
                                    <span>NIP. {{ $employee->nip }}</span>
                                    @if ($employee->karpeg)
                                        <span class="text-slate-300">·</span>
                                        <span>Karpeg. {{ $employee->karpeg }}</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Unit Kerja --}}
                            <td class="py-5 px-6 text-slate-700 max-w-[180px] text-center">
                                <span class="line-clamp-2 leading-snug">{{ $employee->workUnit?->name ?? '—' }}</span>
                            </td>

                            {{-- Jabatan --}}
                            <td class="py-5 px-6 text-slate-700 max-w-[180px] text-center">
                                <span class="line-clamp-2 leading-snug">{{ $employee->currentPosition?->position?->name ?? '—' }}</span>
                            </td>

                            {{-- Gol / Pangkat --}}
                            <td class="py-5 px-6 text-center">
                                @if ($employee->currentRank?->rank)
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 border border-indigo-100 px-2.5 py-1 text-[10px] font-bold text-indigo-700">
                                        <svg class="w-2.5 h-2.5 opacity-70" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                        {{ $employee->currentRank->rank->code }}
                                    </span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="py-5 px-6 text-center">
                                @php
                                    $code = $employee->employmentStatus?->code;
                                    $statusStyle = match(true) {
                                        $code === 'PNS'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        $code === 'PPPK' => 'bg-violet-50 text-violet-700 border-violet-200',
                                        default          => 'bg-slate-100 text-slate-600 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-[10px] font-bold {{ $statusStyle }}">
                                    <span class="h-1.5 w-1.5 rounded-full
                                        {{ $code === 'PNS' ? 'bg-emerald-500' : ($code === 'PPPK' ? 'bg-violet-500' : 'bg-slate-400') }}"></span>
                                    {{ $employee->employmentStatus?->name ?? '—' }}
                                </span>
                            </td>

                            {{-- Aksi --}}
                            <td class="py-5 px-6">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- View --}}
                                    <a href="{{ route('employees.show', $employee->id) }}"
                                       title="Lihat Detail"
                                       class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-blue-50 hover:text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    @if (auth()->user()?->isAdmin())
                                        {{-- Edit --}}
                                        <a href="{{ route('employees.edit', $employee->id) }}"
                                           title="Edit Data"
                                           class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-amber-50 hover:text-amber-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        {{-- Delete --}}
                                        <form id="delete-form-{{ $employee->id }}"
                                              action="{{ route('employees.destroy', $employee->id) }}"
                                              method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    onclick="confirmDelete('delete-form-{{ $employee->id }}', 'Hapus Data Pegawai?', 'Pegawai {{ $employee->full_name }} akan dihapus dari sistem.')"
                                                    title="Hapus Data"
                                                    class="grid h-8 w-8 place-items-center rounded-lg text-slate-500 transition hover:bg-red-50 hover:text-red-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <div class="mx-auto flex flex-col items-center gap-3">
                                    <div class="grid h-14 w-14 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Tidak ada data pegawai ditemukan</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                                    </div>
                                    <a href="{{ route('employees.index') }}"
                                       class="mt-1 inline-flex h-8 items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3.5 text-xs font-semibold text-slate-600 shadow-sm hover:border-slate-300 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ─── Pagination ─── --}}
        <div class="border-t border-slate-100 bg-slate-50/60 px-6 py-3.5">
            {{ $employees->links() }}
        </div>
    </div>
</x-app-layout>
