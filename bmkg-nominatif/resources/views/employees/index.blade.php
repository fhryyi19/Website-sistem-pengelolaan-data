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
    @php
        $educationLevels = [
            '1' => 'SD', '2' => 'SMP', '3' => 'SMA / SMK / STM',
            '4' => 'D1', '5' => 'D2', '6' => 'D3 / DIII',
            '7' => 'D4 / DIV', '8' => 'S1 / Sarjana',
            '9' => 'S2 / Magister', '10' => 'S3 / Doktor',
        ];

        $activeChips = [];
        if (!empty($filters['search'])) {
            $activeChips[] = ['key' => 'search', 'label' => 'Cari: "' . $filters['search'] . '"'];
        }
        if (!empty($filters['employment_status_id'])) {
            $st = $employmentStatuses->firstWhere('id', $filters['employment_status_id']);
            if ($st) $activeChips[] = ['key' => 'employment_status_id', 'label' => $st->name];
        }
        if (!empty($filters['work_unit_id'])) {
            $u = $workUnits->firstWhere('id', $filters['work_unit_id']);
            if ($u) $activeChips[] = ['key' => 'work_unit_id', 'label' => $u->name];
        }
        if (!empty($filters['rank_id'])) {
            $r = $ranksActive->firstWhere('id', $filters['rank_id']);
            if ($r) $activeChips[] = ['key' => 'rank_id', 'label' => ($r->code ?? $r->display_name)];
        }
        if (!empty($filters['position_id'])) {
            $p = $positionsActive->firstWhere('id', $filters['position_id']);
            if ($p) $activeChips[] = ['key' => 'position_id', 'label' => $p->name];
        }
        if (!empty($filters['education_level'])) {
            $lvl = $educationLevels[$filters['education_level']] ?? 'Lvl ' . $filters['education_level'];
            $activeChips[] = ['key' => 'education_level', 'label' => 'Jenjang: ' . $lvl];
        }
        if (!empty($filters['education_id'])) {
            $edu = $educations->firstWhere('id', $filters['education_id']);
            if ($edu) $activeChips[] = ['key' => 'education_id', 'label' => 'Prodi: ' . $edu->name];
        }
        if (!empty($filters['institution_name'])) {
            $activeChips[] = ['key' => 'institution_name', 'label' => 'Kampus: ' . $filters['institution_name']];
        }
        if (!empty($filters['rank_id_history'])) {
            $rh = $ranksAll->firstWhere('id', $filters['rank_id_history']);
            if ($rh) $activeChips[] = ['key' => 'rank_id_history', 'label' => 'Hist. Pangkat: ' . ($rh->code ?? $rh->display_name)];
        }
        if (!empty($filters['rank_date_from'])) {
            $activeChips[] = ['key' => 'rank_date_from', 'label' => 'Pangkat Dari: ' . $filters['rank_date_from']];
        }
        if (!empty($filters['rank_date_to'])) {
            $activeChips[] = ['key' => 'rank_date_to', 'label' => 'Pangkat Sampai: ' . $filters['rank_date_to']];
        }
        if (!empty($filters['position_id_history'])) {
            $ph = $positionsAll->firstWhere('id', $filters['position_id_history']);
            if ($ph) $activeChips[] = ['key' => 'position_id_history', 'label' => 'Hist. Jabatan: ' . $ph->name];
        }
        if (!empty($filters['position_date_from'])) {
            $activeChips[] = ['key' => 'position_date_from', 'label' => 'Jabatan Dari: ' . $filters['position_date_from']];
        }
        if (!empty($filters['position_date_to'])) {
            $activeChips[] = ['key' => 'position_date_to', 'label' => 'Jabatan Sampai: ' . $filters['position_date_to']];
        }

        $advKeys = ['education_level', 'education_id', 'institution_name', 'rank_id_history', 'rank_date_from', 'rank_date_to', 'position_id_history', 'position_date_from', 'position_date_to'];
        $advActiveCount = 0;
        foreach ($advKeys as $k) {
            if (!empty($filters[$k])) $advActiveCount++;
        }
    @endphp

    <style>
        /* ── Compact Enterprise Filter Card Styling ── */
        .filter-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .filter-card-header {
            min-height: 56px;
            padding: 12px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }
        .filter-card-body {
            padding: 16px 20px;
        }
        .filter-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 5px;
            letter-spacing: 0.01em;
        }
        .filter-input {
            width: 100%;
            height: 40px;
            padding: 0 12px;
            font-size: 12px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            color: #0f172a;
        }
        .filter-input:hover {
            border-color: #94a3b8;
        }
        .filter-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }
        .filter-input::placeholder {
            color: #94a3b8;
        }
        .select-wrap {
            position: relative;
        }
        .select-wrap select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 32px;
        }
        .select-wrap::after {
            content: '';
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 0; height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid #64748b;
            pointer-events: none;
        }
        .filter-footer {
            min-height: 56px;
            padding: 12px 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
        }
        .btn-reset {
            height: 38px;
            padding: 0 16px;
            border-radius: 10px;
            background: #ffffff;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-reset:hover {
            background: #f1f5f9;
            color: #1e293b;
            border-color: #94a3b8;
        }
        .btn-apply {
            height: 38px;
            padding: 0 20px;
            border-radius: 10px;
            background: #2563eb;
            font-size: 12px;
            font-weight: 600;
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(37, 99, 235, 0.2);
        }
        .btn-apply:hover {
            background: #1d4ed8;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
        }
        .btn-apply:active {
            transform: translateY(0);
        }
        .per-page-select {
            height: 34px;
            padding: 0 10px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            outline: none;
            cursor: pointer;
        }
        .per-page-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.12);
        }
    </style>

    <div class="mb-6 filter-card" id="filter-card-main">
        {{-- Header --}}
        <div class="filter-card-header">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-blue-600/10 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.707 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
                <div class="flex items-baseline gap-2">
                    <h2 class="text-sm font-bold text-slate-800 tracking-tight">Filter &amp; Pencarian</h2>
                    <span class="text-xs text-slate-500 font-medium">Menampilkan {{ number_format($employees->total(), 0, ',', '.') }} data</span>
                </div>
            </div>

            {{-- Collapse toggle --}}
            <button type="button" id="filter-toggle-btn" onclick="toggleFilterBody()"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 rounded-lg px-3 py-1.5 cursor-pointer transition-all shadow-sm">
                <svg id="filter-chevron" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                </svg>
                <span id="filter-toggle-label">Sembunyikan</span>
            </button>
        </div>

        {{-- Body --}}
        <div id="filter-body" class="filter-card-body transition-all duration-200">
            <form method="GET" action="{{ route('employees.index') }}" id="filter-form">

                {{-- ─── Primary Search Bar ─── --}}
                <div class="mb-3.5">
                    <label class="filter-label">Cari Pegawai</label>
                    <input type="text" name="search" id="input-search" value="{{ $filters['search'] ?? '' }}"
                           placeholder="NIP, nama, atau email..."
                           class="filter-input h-10 font-medium">
                </div>

                {{-- ─── Quick Filter Grid (4 Columns Desktop, 2 Tablet, 1 Mobile) ─── --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3.5">
                    {{-- Status --}}
                    <div>
                        <label class="filter-label">Status</label>
                        <div class="select-wrap">
                            <select name="employment_status_id" id="input-employment_status_id" class="filter-input">
                                <option value="">Semua Status</option>
                                @foreach ($employmentStatuses as $st)
                                    <option value="{{ $st->id }}" {{ ($filters['employment_status_id'] ?? '') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Unit Kerja --}}
                    <div>
                        <label class="filter-label">Unit Kerja</label>
                        <div class="select-wrap">
                            <select name="work_unit_id" id="input-work_unit_id" class="filter-input">
                                <option value="">Semua Unit</option>
                                @foreach ($workUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ ($filters['work_unit_id'] ?? '') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Golongan --}}
                    <div>
                        <label class="filter-label">Golongan</label>
                        <div class="select-wrap">
                            <select name="rank_id" id="input-rank_id" class="filter-input">
                                <option value="">Semua Golongan</option>
                                @foreach ($ranksActive as $r)
                                    <option value="{{ $r->id }}" {{ ($filters['rank_id'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label class="filter-label">Jabatan</label>
                        <div class="select-wrap">
                            <select name="position_id" id="input-position_id" class="filter-input">
                                <option value="">Semua Jabatan</option>
                                @foreach ($positionsActive as $pos)
                                    <option value="{{ $pos->id }}" {{ ($filters['position_id'] ?? '') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ─── Filter Lanjutan Toggle Bar ─── --}}
                <div class="pt-1">
                    <button type="button" id="adv-filter-toggle-btn" onclick="toggleAdvFilter()"
                            class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100/80 border border-blue-200/80 rounded-lg px-3 py-1.5 transition-colors cursor-pointer">
                        <svg id="adv-chevron" class="w-3.5 h-3.5 transition-transform duration-200 {{ $advActiveCount > 0 ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Filter Lanjutan</span>
                        @if ($advActiveCount > 0)
                            <span class="inline-flex items-center justify-center bg-blue-600 text-white rounded-full h-4 w-4 text-[10px] font-bold">{{ $advActiveCount }}</span>
                        @endif
                    </button>
                </div>

                {{-- ─── Filter Lanjutan Container (Collapsible) ─── --}}
                <div id="adv-filter-body" class="{{ $advActiveCount > 0 ? '' : 'hidden' }} mt-3 pt-3 border-t border-slate-100 space-y-3.5">

                    {{-- PENDIDIKAN --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-violet-700 bg-violet-50 border border-violet-200 rounded px-2 py-0.5 tracking-wider uppercase">Pendidikan</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <div>
                                <label class="filter-label">Jenjang</label>
                                <div class="select-wrap">
                                    <select id="filter-education-level" name="education_level" class="filter-input h-9 text-xs">
                                        <option value="">Semua Jenjang</option>
                                        @foreach ($educationLevels as $lvlVal => $lvlLabel)
                                            <option value="{{ $lvlVal }}" {{ ($filters['education_level'] ?? '') == (string)$lvlVal ? 'selected' : '' }}>{{ $lvlLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="filter-label">Program Studi</label>
                                <div class="select-wrap">
                                    <select id="filter-education-id" name="education_id" class="filter-input h-9 text-xs">
                                        <option value="">Semua Program Studi</option>
                                        @if (!empty($filters['education_id']))
                                            @php $activeEdu = $educations->firstWhere('id', $filters['education_id']); @endphp
                                            @if ($activeEdu)
                                                <option value="{{ $activeEdu->id }}" selected>{{ $activeEdu->name }}</option>
                                            @endif
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="filter-label">Kampus / Institusi</label>
                                <input type="text" name="institution_name" id="input-institution_name" value="{{ $filters['institution_name'] ?? '' }}"
                                       placeholder="Cth: ITB, UGM, Unpad..."
                                       class="filter-input h-9 text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT KEPEGAWAIAN --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[10px] font-bold text-amber-800 bg-amber-50 border border-amber-200 rounded px-2 py-0.5 tracking-wider uppercase">Riwayat Kepegawaian</span>
                        </div>

                        {{-- Pangkat / Golongan --}}
                        <div class="mb-3">
                            <p class="text-[11px] font-semibold text-slate-600 mb-1.5">Pangkat / Golongan</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <div class="select-wrap">
                                        <select name="rank_id_history" id="input-rank_id_history" class="filter-input h-9 text-xs">
                                            <option value="">Semua Pangkat</option>
                                            @foreach ($ranksAll as $r)
                                                <option value="{{ $r->id }}" {{ ($filters['rank_id_history'] ?? '') == $r->id ? 'selected' : '' }}>{{ $r->display_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <input type="date" name="rank_date_from" id="input-rank_date_from" value="{{ $filters['rank_date_from'] ?? '' }}" class="filter-input h-9 text-xs" title="Efektif Dari">
                                </div>
                                <div>
                                    <input type="date" name="rank_date_to" id="input-rank_date_to" value="{{ $filters['rank_date_to'] ?? '' }}" class="filter-input h-9 text-xs" title="Efektif Sampai">
                                </div>
                            </div>
                        </div>

                        {{-- Jabatan --}}
                        <div>
                            <p class="text-[11px] font-semibold text-slate-600 mb-1.5">Jabatan</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <div class="select-wrap">
                                        <select name="position_id_history" id="input-position_id_history" class="filter-input h-9 text-xs">
                                            <option value="">Semua Jabatan</option>
                                            @foreach ($positionsAll as $pos)
                                                <option value="{{ $pos->id }}" {{ ($filters['position_id_history'] ?? '') == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <input type="date" name="position_date_from" id="input-position_date_from" value="{{ $filters['position_date_from'] ?? '' }}" class="filter-input h-9 text-xs" title="Efektif Dari">
                                </div>
                                <div>
                                    <input type="date" name="position_date_to" id="input-position_date_to" value="{{ $filters['position_date_to'] ?? '' }}" class="filter-input h-9 text-xs" title="Efektif Sampai">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ─── Filter Aktif (Chips Section) ─── --}}
                @if (count($activeChips) > 0)
                    <div class="mt-3.5 pt-3 border-t border-slate-100 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-semibold text-slate-500 mr-1">Filter aktif:</span>
                        @foreach ($activeChips as $chip)
                            <span class="inline-flex items-center gap-1.5 h-7 px-2.5 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 font-semibold text-[11px]">
                                {{ $chip['label'] }}
                                <button type="button" onclick="clearFilterField('{{ $chip['key'] }}')" title="Hapus filter ini" class="hover:bg-blue-200/80 rounded p-0.5 text-blue-600 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </span>
                        @endforeach
                        <a href="{{ route('employees.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 underline ml-1 transition-colors">Hapus semua</a>
                    </div>
                @endif

            </form>
        </div>

        {{-- Footer --}}
        <div class="filter-footer">
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium text-slate-600">
                    Menampilkan {{ $employees->firstItem() ?? 0 }}-{{ $employees->lastItem() ?? 0 }} dari {{ number_format($employees->total(), 0, ',', '.') }} data
                </span>
                <div class="h-4 w-px bg-slate-200"></div>
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-slate-500">Tampilkan</span>
                    <form method="GET" action="{{ route('employees.index') }}" id="per-page-form" class="inline">
                        @foreach(request()->except('per_page') as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach
                        <select name="per_page" onchange="this.form.submit()" class="per-page-select">
                            <option value="10"  {{ $perPage == 10  ? 'selected' : '' }}>10</option>
                            <option value="25"  {{ $perPage == 25  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ $perPage == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <span class="text-xs text-slate-500">data per halaman</span>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('employees.index') }}" class="btn-reset">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Reset
                </a>
                <button type="submit" form="filter-form" id="btn-submit-filter" class="btn-apply">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.707 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Filter Card JS --}}
    <script>
        (function () {
            var filterOpen = true;
            function toggleFilterBody() {
                filterOpen = !filterOpen;
                var body = document.getElementById('filter-body');
                var chevron = document.getElementById('filter-chevron');
                var label = document.getElementById('filter-toggle-label');
                if (filterOpen) {
                    body.classList.remove('hidden');
                    chevron.style.transform = 'rotate(0deg)';
                    label.textContent = 'Sembunyikan';
                } else {
                    body.classList.add('hidden');
                    chevron.style.transform = 'rotate(180deg)';
                    label.textContent = 'Tampilkan Filter';
                }
            }
            window.toggleFilterBody = toggleFilterBody;

            function toggleAdvFilter() {
                var advBody = document.getElementById('adv-filter-body');
                var chevron = document.getElementById('adv-chevron');
                if (advBody.classList.contains('hidden')) {
                    advBody.classList.remove('hidden');
                    chevron.classList.add('rotate-90');
                } else {
                    advBody.classList.add('hidden');
                    chevron.classList.remove('rotate-90');
                }
            }
            window.toggleAdvFilter = toggleAdvFilter;

            function clearFilterField(key) {
                var el = document.getElementById('input-' + key) || document.querySelector('[name="' + key + '"]');
                if (el) {
                    el.value = '';
                }
                document.getElementById('filter-form').submit();
            }
            window.clearFilterField = clearFilterField;

            // Loading state on submit
            var form = document.getElementById('filter-form');
            if (form) {
                form.addEventListener('submit', function () {
                    var btn = document.getElementById('btn-submit-filter');
                    if (btn) {
                        btn.disabled = true;
                        btn.style.opacity = '0.75';
                        btn.innerHTML = '<svg class="animate-spin w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Memuat...</span>';
                    }
                });
            }
        })();
    </script>

    {{-- ═══════════════════════════════════════════════════════════════
         ADMIN-ONLY MODALS — Quick-add master data
    ═══════════════════════════════════════════════════════════════ --}}
    @if (auth()->user()?->isAdmin())
    @php $redirectBack = url()->full(); @endphp

    {{-- Shared modal backdrop + close script --}}
    <script>
        function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.body.classList.add('overflow-hidden'); }
        function closeModal(id) { document.getElementById(id).classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') document.querySelectorAll('[id^="modal-"]').forEach(m => closeModal(m.id));
        });
    </script>

    {{-- ─── Modal: Jenjang Pendidikan ─── --}}
    <div id="modal-education" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-education')"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Jenjang Pendidikan</h3>
                <button type="button" onclick="closeModal('modal-education')" class="grid h-7 w-7 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('master.educations.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectBack }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="Cth: S1, S2, D3" maxlength="20"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Strata 1, Diploma 3" maxlength="100"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Level <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(1=SD … 10=S3)</span></label>
                    <input type="number" name="level" required min="1" max="10" placeholder="Cth: 8 untuk S1"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-education')"
                            class="flex-1 h-10 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                            class="flex-1 h-10 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Modal: Pangkat / Golongan ─── --}}
    <div id="modal-rank" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-rank')"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Pangkat / Golongan</h3>
                <button type="button" onclick="closeModal('modal-rank')" class="grid h-7 w-7 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('master.ranks.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectBack }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(Cth: III/a)</span></label>
                    <input type="text" name="code" required placeholder="III/a" maxlength="10"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Pangkat <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Penata Muda" maxlength="100"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Golongan Group <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(Cth: III)</span></label>
                    <input type="text" name="group" required placeholder="III" maxlength="5"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-rank')"
                            class="flex-1 h-10 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                            class="flex-1 h-10 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Modal: Jabatan ─── --}}
    <div id="modal-position" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-position')"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Jabatan</h3>
                <button type="button" onclick="closeModal('modal-position')" class="grid h-7 w-7 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('master.positions.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectBack }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="Cth: ANALISIS-CUACA-001" maxlength="30"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Jabatan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Analis Cuaca" maxlength="200"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Level Jabatan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="level" placeholder="Cth: Ahli Pertama, Ahli Muda" maxlength="50"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-position')"
                            class="flex-1 h-10 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                            class="flex-1 h-10 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Modal: Unit Kerja ─── --}}
    <div id="modal-work-unit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-work-unit')"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Unit Kerja</h3>
                <button type="button" onclick="closeModal('modal-work-unit')" class="grid h-7 w-7 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('master.work-units.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectBack }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode <span class="text-red-500">*</span></label>
                    <input type="text" name="code" required placeholder="Cth: MET-OBS" maxlength="30"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Unit Kerja <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Seksi Meteorologi" maxlength="200"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-work-unit')"
                            class="flex-1 h-10 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                            class="flex-1 h-10 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── Modal: Status Kepegawaian ─── --}}
    <div id="modal-employment-status" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('modal-employment-status')"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900">Tambah Status Kepegawaian</h3>
                <button type="button" onclick="closeModal('modal-employment-status')" class="grid h-7 w-7 place-items-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('master.employment-statuses.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ $redirectBack }}">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kode <span class="text-red-500">*</span> <span class="text-slate-400 font-normal">(Cth: PNS, PPPK)</span></label>
                    <input type="text" name="code" required placeholder="PNS" maxlength="20"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Status <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="Cth: Pegawai Negeri Sipil" maxlength="100"
                           class="w-full h-10 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModal('modal-employment-status')"
                            class="flex-1 h-10 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                            class="flex-1 h-10 rounded-xl bg-blue-600 text-xs font-semibold text-white hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

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

@push('scripts')
<script>
(function () {
    const levelSelect = document.getElementById('filter-education-level');
    const eduSelect   = document.getElementById('filter-education-id');
    const majorUrl    = '{{ route("api.majors") }}';
    const activeEduId = @json($filters['education_id'] ?? '');

    function loadPrograms(level, selectedId) {
        const url = majorUrl + (level ? '?level=' + level : '');

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(programs => {
                const prev = selectedId || eduSelect.value;

                eduSelect.innerHTML = '<option value="">Semua Program Studi</option>';
                programs.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.name;
                    if (String(p.id) === String(prev)) opt.selected = true;
                    eduSelect.appendChild(opt);
                });
            })
            .catch(() => {});
    }

    // Load on page load
    loadPrograms(levelSelect.value, activeEduId);

    // Load saat jenjang berubah, reset program studi
    levelSelect.addEventListener('change', function () {
        loadPrograms(this.value, '');
    });
})();
</script>
@endpush

</x-app-layout>
