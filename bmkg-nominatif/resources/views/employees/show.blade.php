<x-app-layout>
    <x-slot name="title">Detail Pegawai — {{ $employee->full_name }}</x-slot>

    <!-- Header & Breadcrumb -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-xs text-[#7a7a7a] mb-2">
                <a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a>
                <span>/</span>
                <a href="{{ route('employees.index') }}" class="hover:underline">Pegawai</a>
                <span>/</span>
                <span class="text-[#1d1d1f] font-medium">{{ $employee->full_name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">Profil Pegawai</h1>
        </div>

        @if (auth()->user()?->isAdmin())
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.edit', $employee->id) }}"
                   class="h-10 px-5 bg-white border border-[#e0e0e0] hover:border-gray-400 text-[#1d1d1f] font-medium text-xs rounded-full transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit Data</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Employee Hero Card -->
    <div class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 mb-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-[#0066cc]/10 text-[#0066cc] font-bold text-xl flex items-center justify-center border border-[#0066cc]/20">
                {{ strtoupper(substr($employee->full_name, 0, 2)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-[#1d1d1f] tracking-tight">{{ $employee->full_name_with_title }}</h2>
                <p class="text-xs text-[#7a7a7a] font-mono mt-0.5">NIP. {{ $employee->nip }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="px-3 py-1 rounded-full text-[11px] font-medium bg-[#0066cc]/10 text-[#0066cc]">
                        {{ $employee->workUnit?->name ?? 'Unit Belum Set' }}
                    </span>
                    @if ($employee->currentRank?->rank)
                        <span class="px-3 py-1 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                            Gol. {{ $employee->currentRank->rank->code }} — {{ $employee->currentRank->rank->name }}
                        </span>
                    @endif
                    <span class="px-3 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-700">
                        {{ $employee->employmentStatus?->name }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Component -->
    <div x-data="{ activeTab: 'identitas' }" class="space-y-6">

        <!-- Tab Bar Pill -->
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-full border border-[#e0e0e0] overflow-x-auto">
            <button @click="activeTab = 'identitas'"
                    :class="activeTab === 'identitas' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'"
                    class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">
                Identitas Pribadi
            </button>
            <button @click="activeTab = 'jabatan'"
                    :class="activeTab === 'jabatan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'"
                    class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">
                Riwayat Jabatan ({{ $employee->positions->count() }})
            </button>
            <button @click="activeTab = 'golongan'"
                    :class="activeTab === 'golongan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'"
                    class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">
                Riwayat Golongan ({{ $employee->ranks->count() }})
            </button>
            <button @click="activeTab = 'pendidikan'"
                    :class="activeTab === 'pendidikan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'"
                    class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">
                Pendidikan ({{ $employee->educations->count() }})
            </button>
            <button @click="activeTab = 'keluarga'"
                    :class="activeTab === 'keluarga' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'"
                    class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">
                Keluarga ({{ $employee->families->count() }})
            </button>
        </div>

        <!-- TAB 1: IDENTITAS -->
        <div x-show="activeTab === 'identitas'" class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 space-y-6">
            <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider border-b border-gray-100 pb-3">Rincian Informasi Pribadi</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-6 text-xs">
                <div>
                    <dt class="text-[#1d1d1f] font-bold">NIP</dt>
                    <dd class="text-[#7a7a7a] font-mono mt-0.5">{{ $employee->nip }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">No. Karpeg</dt>
                    <dd class="text-[#7a7a7a] font-mono mt-0.5">{{ $employee->karpeg ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Nama Lengkap</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->full_name_with_title }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Tempat, Tanggal Lahir</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->birth_place }}, {{ $employee->formatted_birth_date }} ({{ $employee->age }} Tahun)</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Jenis Kelamin</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->gender?->name }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Agama</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->religion?->name }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Status Perkawinan</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->maritalStatus?->name }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Jumlah Anak</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->children_count }} Anak</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Jumlah Anggota Keluarga</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->family_count }} Orang</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Gaji Pokok</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">
                        {{ $employee->salary ? 'Rp ' . number_format($employee->salary, 0, ',', '.') : '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">TMT Gaji Pokok</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->salary_tmt ? $employee->salary_tmt->translatedFormat('d F Y') : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Golongan Capeg (CPNS) & TMT</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">
                        {{ $employee->cpns_rank ? 'Gol. ' . $employee->cpns_rank : '-' }}
                        @if($employee->cpns_tmt)
                            — {{ $employee->cpns_tmt->translatedFormat('d F Y') }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Golongan PNS & TMT</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">
                        {{ $employee->pns_rank ? 'Gol. ' . $employee->pns_rank : '-' }}
                        @if($employee->pns_tmt)
                            — {{ $employee->pns_tmt->translatedFormat('d F Y') }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Keterangan Keluarga</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->family_note ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Alamat Email</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->email ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Nomor Telepon</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->phone ?? '-' }}</dd>
                </div>
                <div class="md:col-span-2 lg:col-span-3">
                    <dt class="text-[#1d1d1f] font-bold">Alamat Tempat Tinggal</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->address ?? '-' }}</dd>
                </div>
            </dl>

            <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider border-b border-gray-100 pb-3 pt-4">Pendidikan Khusus / Dinas / Sertifikasi</h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-6 text-xs">
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Pendidikan Dinas</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->edu_dinas ?? '-' }} @if($employee->edu_dinas_year) ({{ $employee->edu_dinas_year }}) @endif</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Kursus</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->edu_kursus ?? '-' }} @if($employee->edu_kursus_year) ({{ $employee->edu_kursus_year }}) @endif</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Luar Negeri</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->edu_ln ?? '-' }} @if($employee->edu_ln_year) ({{ $employee->edu_ln_year }}) @endif</dd>
                </div>
                <div>
                    <dt class="text-[#1d1d1f] font-bold">Penjenjangan</dt>
                    <dd class="text-[#7a7a7a] mt-0.5">{{ $employee->edu_penjenjangan ?? '-' }} @if($employee->edu_penjenjangan_year) ({{ $employee->edu_penjenjangan_year }}) @endif</dd>
                </div>
            </dl>
        </div>

        <!-- TAB 2: JABATAN -->
        <div x-show="activeTab === 'jabatan'" class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 space-y-6" x-data="{ showModal: false }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider">Riwayat Jabatan & Penempatan</h3>
                @if (auth()->user()?->isAdmin())
                    <button @click="showModal = true" class="h-8 px-4 bg-[#0066cc] text-white rounded-full text-xs font-medium hover:bg-[#0071e3] transition">
                        + Tambah Jabatan
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($employee->positions as $pos)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-[#fafafc] flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-xs text-[#1d1d1f]">{{ $pos->position?->name }}</span>
                                @if ($pos->is_current)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">JABATAN AKTIF</span>
                                @endif
                            </div>
                            <p class="text-xs text-[#7a7a7a] mt-0.5">{{ $pos->workUnit?->name }}</p>
                            <p class="text-[11px] text-[#7a7a7a] mt-1 font-mono">TMT: {{ $pos->formatted_effective_date }} | SK: {{ $pos->decree_number ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#7a7a7a] text-center py-6">Belum ada riwayat jabatan recorded.</p>
                @endforelse
            </div>

            <!-- Modal Tambah Jabatan -->
            <div x-show="showModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                <div @click.outside="showModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl">
                    <h3 class="text-base font-bold text-[#1d1d1f]">Tambah Riwayat Jabatan</h3>
                    <form method="POST" action="{{ route('employees.positions.store', $employee->id) }}" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-medium mb-1">Jabatan</label>
                            <select name="position_id" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                @foreach ($positionsList as $p)
                                    <option value="{{ $p->id }}">{{ $p->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Unit Kerja</label>
                            <select name="work_unit_id" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                @foreach ($workUnitsList as $wu)
                                    <option value="{{ $wu->id }}">{{ $wu->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Nomor SK</label>
                            <input type="text" name="decree_number" class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">TMT Jabatan</label>
                            <input type="date" name="effective_date" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_current" value="1" id="is_curr_pos" checked class="rounded border-gray-300">
                            <label for="is_curr_pos">Set sebagai Jabatan Aktif</label>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 rounded-full">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#0066cc] text-white rounded-full">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 3: GOLONGAN -->
        <div x-show="activeTab === 'golongan'" class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 space-y-6" x-data="{ showModalRank: false }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider">Riwayat Pangkat / Golongan</h3>
                @if (auth()->user()?->isAdmin())
                    <button @click="showModalRank = true" class="h-8 px-4 bg-[#0066cc] text-white rounded-full text-xs font-medium hover:bg-[#0071e3] transition">
                        + Tambah Golongan
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($employee->ranks as $rnk)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-[#fafafc] flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-semibold text-xs text-[#1d1d1f]">Gol. {{ $rnk->rank?->code }} — {{ $rnk->rank?->name }}</span>
                                @if ($rnk->is_current)
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">GOLONGAN AKTIF</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-[#7a7a7a] mt-1 font-mono">TMT: {{ $rnk->formatted_effective_date }} | SK: {{ $rnk->decree_number ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#7a7a7a] text-center py-6">Belum ada riwayat golongan recorded.</p>
                @endforelse
            </div>

            <!-- Modal Tambah Golongan -->
            <div x-show="showModalRank" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                <div @click.outside="showModalRank = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl">
                    <h3 class="text-base font-bold text-[#1d1d1f]">Tambah Riwayat Golongan</h3>
                    <form method="POST" action="{{ route('employees.ranks.store', $employee->id) }}" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-medium mb-1">Golongan / Pangkat</label>
                            <select name="rank_id" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                @foreach ($ranksList as $r)
                                    <option value="{{ $r->id }}">{{ $r->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Nomor SK</label>
                            <input type="text" name="decree_number" class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">TMT Golongan</label>
                            <input type="date" name="effective_date" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_current" value="1" id="is_curr_rnk" checked class="rounded border-gray-300">
                            <label for="is_curr_rnk">Set sebagai Golongan Aktif</label>
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showModalRank = false" class="px-4 py-2 bg-gray-100 rounded-full">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#0066cc] text-white rounded-full">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 4: PENDIDIKAN -->
        <div x-show="activeTab === 'pendidikan'" class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 space-y-6" x-data="{ showModalEdu: false }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider">Riwayat Pendidikan Formal</h3>
                @if (auth()->user()?->isAdmin())
                    <button @click="showModalEdu = true" class="h-8 px-4 bg-[#0066cc] text-white rounded-full text-xs font-medium hover:bg-[#0071e3] transition">
                        + Tambah Pendidikan
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($employee->educations as $edu)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-[#fafafc]">
                        <p class="font-semibold text-xs text-[#1d1d1f]">{{ $edu->education?->name }} — {{ $edu->institution_name }}</p>
                        <p class="text-xs text-[#7a7a7a] mt-0.5">Jurusan: {{ $edu->major ?? '-' }} | Lulus Tahun {{ $edu->year_graduated ?? '-' }}</p>
                    </div>
                @empty
                    <p class="text-xs text-[#7a7a7a] text-center py-6">Belum ada data riwayat pendidikan.</p>
                @endforelse
            </div>

            <!-- Modal Tambah Pendidikan -->
            <div x-show="showModalEdu" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                <div @click.outside="showModalEdu = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl">
                    <h3 class="text-base font-bold text-[#1d1d1f]">Tambah Riwayat Pendidikan</h3>
                    <form method="POST" action="{{ route('employees.educations.store', $employee->id) }}" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-medium mb-1">Tingkat Pendidikan</label>
                            <select name="education_id" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                @foreach ($educationsList as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Nama Institusi / Universitas</label>
                            <input type="text" name="institution_name" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Jurusan / Program Studi</label>
                            <input type="text" name="major" class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Tahun Lulus</label>
                            <input type="number" name="year_graduated" class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showModalEdu = false" class="px-4 py-2 bg-gray-100 rounded-full">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#0066cc] text-white rounded-full">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- TAB 5: KELUARGA -->
        <div x-show="activeTab === 'keluarga'" class="bg-white rounded-3xl border border-[#e0e0e0] p-6 sm:p-8 space-y-6" x-data="{ showModalFam: false }">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="text-sm font-semibold text-[#1d1d1f] uppercase tracking-wider">Data Anggota Keluarga</h3>
                @if (auth()->user()?->isAdmin())
                    <button @click="showModalFam = true" class="h-8 px-4 bg-[#0066cc] text-white rounded-full text-xs font-medium hover:bg-[#0071e3] transition">
                        + Tambah Keluarga
                    </button>
                @endif
            </div>

            <div class="space-y-4">
                @forelse ($employee->families as $fam)
                    <div class="p-4 rounded-2xl border border-gray-100 bg-[#fafafc] flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-xs text-[#1d1d1f]">{{ $fam->name }} ({{ $fam->relationship }})</p>
                            <p class="text-xs text-[#7a7a7a] mt-0.5">Tgl Lahir: {{ $fam->formatted_birth_date }} | Pekerjaan: {{ $fam->occupation ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-[#7a7a7a] text-center py-6">Belum ada data anggota keluarga recorded.</p>
                @endforelse
            </div>

            <!-- Modal Tambah Keluarga -->
            <div x-show="showModalFam" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                <div @click.outside="showModalFam = false" class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-xl">
                    <h3 class="text-base font-bold text-[#1d1d1f]">Tambah Anggota Keluarga</h3>
                    <form method="POST" action="{{ route('employees.families.store', $employee->id) }}" class="space-y-4 text-xs">
                        @csrf
                        <div>
                            <label class="block font-medium mb-1">Nama Anggota Keluarga</label>
                            <input type="text" name="name" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Hubungan Keluarga</label>
                            <select name="relationship" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                <option value="Suami">Suami</option>
                                <option value="Istri">Istri</option>
                                <option value="Anak">Anak</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Jenis Kelamin</label>
                            <select name="gender_id" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                @foreach ($gendersList as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="showModalFam = false" class="px-4 py-2 bg-gray-100 rounded-full">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#0066cc] text-white rounded-full">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
