<x-app-layout>
    <x-slot name="title">Tambah Data Pegawai</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Fira+Sans:wght@300;400;500;600;700&display=swap');
        :root { --ep-primary:#1E40AF; --ep-primary-h:#1d3a9f; --ep-secondary:#3B82F6; --ep-accent:#D97706; --ep-bg:#F8FAFC; --ep-muted:#E9EEF6; --ep-border:#DBEAFE; --ep-text:#1E3A8A; --ep-subtext:#64748B; --ep-danger:#DC2626; --ep-success:#16a34a; --ep-radius:14px; --ep-radius-sm:8px; --ep-shadow:0 1px 4px rgba(30,64,175,.08), 0 4px 24px rgba(30,64,175,.06); --ep-shadow-lg:0 8px 40px rgba(30,64,175,.12); --ep-transition:200ms cubic-bezier(.4,0,.2,1); }
        .ep-page { font-family: 'Fira Sans', sans-serif; }
        .ep-hero { background: linear-gradient(135deg, var(--ep-primary) 0%, #2563EB 60%, var(--ep-secondary) 100%); border-radius: 20px; padding: 28px 32px; margin-bottom: 28px; position: relative; overflow: hidden; box-shadow: var(--ep-shadow-lg); }
        .ep-hero h1 { font-size: 22px; font-weight: 700; color: #fff; margin: 0 0 6px; letter-spacing: -.4px; }
        .ep-hero p { font-size: 13px; color: rgba(255,255,255,.75); margin: 0; }
        .ep-card { background: #fff; border: 1px solid var(--ep-border); border-radius: 20px; padding: 28px; box-shadow: var(--ep-shadow); }
        .ep-section-title { display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--ep-subtext); margin: 0 0 20px; padding-bottom: 10px; border-bottom: 1px solid var(--ep-muted); }
        .ep-section-title span.dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--ep-primary); box-shadow: 0 0 0 3px rgba(30,64,175,.15); }
        .ep-field { display: flex; flex-direction: column; gap: 5px; }
        .ep-label { font-size: 11.5px; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 4px; }
        .ep-input, .ep-select, .ep-textarea { width: 100%; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: var(--ep-radius-sm); font-size: 13px; padding: 0 14px; height: 42px; outline: none; }
        .ep-input:focus, .ep-select:focus { border-color: var(--ep-primary); background: #fff; box-shadow: 0 0 0 3px rgba(30,64,175,.12); }
        .ep-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .ep-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
        .ep-subsection { font-size: 11px; font-weight: 700; color: var(--ep-primary); text-transform: uppercase; display: flex; align-items: center; gap: 8px; margin: 24px 0 14px; padding: 8px 12px; background: var(--ep-muted); border-left: 3px solid var(--ep-primary); border-radius: 0 6px 6px 0; }
    </style>

    <div class="ep-page">
        <div class="ep-hero">
            <h1>Tambah Data Pegawai</h1>
            <p>Lengkapi data untuk pegawai baru.</p>
        </div>

        <form method="POST" action="{{ route('employees.store') }}" class="ep-card">
            @csrf

            {{-- ── Tab Navigation ── --}}
            <div class="ep-tabs-nav" role="tablist">
                <button type="button" class="ep-tab-btn active" onclick="switchTab('identitas')">Identitas Pegawai</button>
                <button type="button" class="ep-tab-btn" onclick="switchTab('status')">Kepegawaian</button>
                <button type="button" class="ep-tab-btn" onclick="switchTab('nominatif')">Data Nominatif</button>
            </div>

            {{-- ── TAB 1: IDENTITAS ── --}}
            <div class="ep-tab-panel active" id="tab-identitas">
                <div class="ep-section-title" style="margin-top:2rem;"><span class="dot"></span>Identitas Utama</div>
                <div class="ep-grid-2">
                    <div class="ep-field"><label class="ep-label">NIP <span class="required">*</span></label><input type="text" name="nip" required class="ep-input mono"></div>
                    <div class="ep-field"><label class="ep-label">Nama Lengkap <span class="required">*</span></label><input type="text" name="full_name" required class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Gelar Depan</label><input type="text" name="prefix_title" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Gelar Belakang</label><input type="text" name="suffix_title" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Tempat Lahir <span class="required">*</span></label><input type="text" name="birth_place" required class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Tanggal Lahir <span class="required">*</span></label><input type="date" name="birth_date" required class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="gender_id" required class="ep-select">
                            @foreach ($genders as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="ep-field"><label class="ep-label">Agama <span class="required">*</span></label>
                        <select name="religion_id" required class="ep-select">
                            @foreach ($religions as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="ep-field"><label class="ep-label">Status Perkawinan <span class="required">*</span></label>
                        <select name="marital_status_id" required class="ep-select">
                            @foreach ($maritalStatuses as $ms)<option value="{{ $ms->id }}">{{ $ms->name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="ep-field"><label class="ep-label">Email</label><input type="email" name="email" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Telepon</label><input type="text" name="phone" class="ep-input"></div>
                    <div class="ep-field ep-grid-full"><label class="ep-label">Alamat</label><textarea name="address" class="ep-textarea"></textarea></div>
                    <div class="ep-field"><label class="ep-label">Jumlah Anak</label><input type="number" name="children_count" value="0" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Jumlah Anggota Keluarga</label><input type="number" name="family_count" value="0" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Keterangan Keluarga</label><input type="text" name="family_note" class="ep-input"></div>
                </div>
            </div>

            {{-- ── TAB 2: KEPEGAWAIAN ── --}}
            <div class="ep-tab-panel" id="tab-status">
                <div class="ep-section-title" style="margin-top:2rem;"><span class="dot"></span>Kepegawaian & Penempatan</div>
                <div class="ep-grid-2">
                    <div class="ep-field"><label class="ep-label">Status Kepegawaian (Aktif / Pensiun / Nonaktif) <span class="required">*</span></label>
                        <select id="employment_status_id" name="employment_status_id" required class="ep-select">
                            <optgroup label="🟢 STATUS AKTIF">
                                @foreach ($employmentStatuses->whereIn('code', ['PNS', 'CPNS', 'PPPK']) as $es)
                                    <option value="{{ $es->id }}" {{ old('employment_status_id') == $es->id ? 'selected' : '' }}>Aktif - {{ $es->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="🟡 STATUS PENSIUN">
                                @foreach ($employmentStatuses->where('code', 'PENSIUN') as $es)
                                    <option value="{{ $es->id }}" {{ old('employment_status_id') == $es->id ? 'selected' : '' }}>Pensiun - {{ $es->name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="🔴 STATUS NONAKTIF">
                                @foreach ($employmentStatuses->whereIn('code', ['NONAKTIF', 'HONORER']) as $es)
                                    <option value="{{ $es->id }}" {{ old('employment_status_id') == $es->id ? 'selected' : '' }}>Nonaktif - {{ $es->name }}</option>
                                @endforeach
                            </optgroup>
                            @foreach ($employmentStatuses->whereNotIn('code', ['PNS', 'CPNS', 'PPPK', 'PENSIUN', 'NONAKTIF', 'HONORER']) as $es)
                                <option value="{{ $es->id }}" {{ old('employment_status_id') == $es->id ? 'selected' : '' }}>{{ $es->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ep-field"><label class="ep-label">Unit Kerja <span class="required">*</span></label>
                        <select name="work_unit_id" required class="ep-select">
                            @foreach ($workUnits as $wu)<option value="{{ $wu->id }}" {{ old('work_unit_id') == $wu->id ? 'selected' : '' }}>{{ $wu->name }}</option>@endforeach
                        </select>
                    </div>
                </div>

                {{-- Golongan / Pangkat Awal --}}
                <div class="ep-subsection" style="margin-top:24px;">Golongan / Pangkat Awal</div>
                <p style="font-size:11px; color:#64748B; margin: -10px 0 16px; line-height:1.6;">
                    Pilih golongan/pangkat aktif saat ini. Data akan tersimpan ke riwayat pangkat dan dapat digunakan untuk filter pencarian.
                </p>
                <div class="ep-grid-2">
                    <div class="ep-field">
                        <label class="ep-label">Golongan / Pangkat</label>
                        <select name="rank_id" class="ep-select">
                            <option value="">— Pilih Golongan —</option>
                            @foreach ($ranks as $r)
                                <option value="{{ $r->id }}" {{ old('rank_id') == $r->id ? 'selected' : '' }}>{{ $r->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">TMT Golongan (Tgl Efektif)</label>
                        <input type="date" name="rank_effective_date" value="{{ old('rank_effective_date') }}" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">No. SK Pangkat</label>
                        <input type="text" name="rank_decree_number" value="{{ old('rank_decree_number') }}"
                               placeholder="Opsional" maxlength="100" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Tgl SK Pangkat</label>
                        <input type="date" name="rank_decree_date" value="{{ old('rank_decree_date') }}" class="ep-input">
                    </div>
                </div>

                {{-- Jabatan Awal --}}
                <div class="ep-subsection" style="margin-top:24px;">Jabatan Awal</div>
                <p style="font-size:11px; color:#64748B; margin: -10px 0 16px; line-height:1.6;">
                    Pilih jabatan yang sedang dijabat saat ini. Data akan tersimpan ke riwayat jabatan dan dapat digunakan untuk filter pencarian.
                </p>
                <div class="ep-grid-2">
                    <div class="ep-field">
                        <label class="ep-label">Jabatan</label>
                        <select name="position_id" class="ep-select">
                            <option value="">— Pilih Jabatan —</option>
                            @foreach ($positions as $pos)
                                <option value="{{ $pos->id }}" {{ old('position_id') == $pos->id ? 'selected' : '' }}>{{ $pos->display_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">TMT Jabatan (Tgl Efektif)</label>
                        <input type="date" name="position_effective_date" value="{{ old('position_effective_date') }}" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">No. SK Jabatan</label>
                        <input type="text" name="position_decree_number" value="{{ old('position_decree_number') }}"
                               placeholder="Opsional" maxlength="100" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Tgl SK Jabatan</label>
                        <input type="date" name="position_decree_date" value="{{ old('position_decree_date') }}" class="ep-input">
                    </div>
                </div>
            </div>

            {{-- ── TAB 3: NOMINATIF ── --}}
            <div class="ep-tab-panel" id="tab-nominatif">
                <div class="ep-section-title" style="margin-top:2rem;"><span class="dot"></span>Data Nominatif</div>
                <div class="ep-grid-3">
                    <div class="ep-field"><label class="ep-label">No. Karpeg</label><input type="text" name="karpeg" class="ep-input mono"></div>
                    <div class="ep-field"><label class="ep-label">Gaji Pokok</label><input type="number" name="salary" class="ep-input mono"></div>
                    <div class="ep-field"><label class="ep-label">TMT Gaji</label><input type="date" name="salary_tmt" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Golongan CPNS</label><input type="text" name="cpns_rank" class="ep-input mono"></div>
                    <div class="ep-field"><label class="ep-label">TMT CPNS</label><input type="date" name="cpns_tmt" class="ep-input"></div>
                    <div class="ep-field"><label class="ep-label">Golongan PNS</label><input type="text" name="pns_rank" class="ep-input mono"></div>
                    <div class="ep-field"><label class="ep-label">TMT PNS</label><input type="date" name="pns_tmt" class="ep-input"></div>
                </div>
                <div class="ep-subsection">Pendidikan Formal Terakhir</div>
                <p style="font-size:11px; color:#64748B; margin: -10px 0 16px; line-height:1.6;">
                    Data pendidikan di bawah akan tersimpan sebagai riwayat pendidikan pegawai dan dapat digunakan untuk filter pencarian.
                    Isi minimal <strong>Jenjang</strong> agar data terhubung ke sistem filter.
                </p>
                <div class="ep-grid-2">
                    <div class="ep-field">
                        <label class="ep-label">Jenjang Pendidikan</label>
                        <select name="education_id" class="ep-select">
                            <option value="">— Pilih Jenjang —</option>
                            @foreach ($educations as $edu)
                                <option value="{{ $edu->id }}" {{ old('education_id') == $edu->id ? 'selected' : '' }}>
                                    {{ $edu->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Tahun Lulus</label>
                        <input type="number" name="year_graduated" value="{{ old('year_graduated') }}"
                               placeholder="Cth: 2005" min="1950" max="{{ date('Y') }}" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Kampus / Institusi</label>
                        <input type="text" name="institution_name" value="{{ old('institution_name') }}"
                               placeholder="Cth: Institut Teknologi Bandung" maxlength="200" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Jurusan / Program Studi</label>
                        <input type="text" name="major" value="{{ old('major') }}"
                               placeholder="Cth: Meteorologi" maxlength="150" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Nomor Ijazah</label>
                        <input type="text" name="certificate_number" value="{{ old('certificate_number') }}"
                               placeholder="Opsional" maxlength="100" class="ep-input">
                    </div>
                </div>

                {{-- Kolom lama (edu_dinas, edu_kursus, dll) tetap ada di bawah untuk data nominatif --}}
                <div class="ep-subsection" style="margin-top:24px;">Pendidikan Tambahan & Pelatihan</div>
                <div class="ep-grid-2">
                    @foreach(['edu_dinas' => 'Pendidikan Dinas', 'edu_kursus' => 'Kursus', 'edu_ln' => 'Luar Negeri', 'edu_penjenjangan' => 'Penjenjangan'] as $name => $label)
                        <div class="ep-field"><label class="ep-label">{{ $label }}</label><input type="text" name="{{ $name }}" value="{{ old($name) }}" class="ep-input"></div>
                        <div class="ep-field"><label class="ep-label">Tahun</label><input type="number" name="{{ $name }}_year" value="{{ old($name . '_year') }}" class="ep-input"></div>
                    @endforeach
                </div>
            </div>

            <div style="margin-top:24px; display:flex; justify-content:flex-end;">
                 <button type="submit" class="h-10 px-8 bg-[#1E40AF] text-white rounded-full text-xs font-semibold">Simpan Pegawai</button>
            </div>
            
            <script>
                function switchTab(tabId) {
                    document.querySelectorAll('.ep-tab-panel').forEach(p => p.classList.remove('active'));
                    document.querySelectorAll('.ep-tab-btn').forEach(b => b.classList.remove('active'));
                    document.getElementById('tab-' + tabId).classList.add('active');
                    event.currentTarget.classList.add('active');
                }
            </script>
        </form>
    </div>
</x-app-layout>
