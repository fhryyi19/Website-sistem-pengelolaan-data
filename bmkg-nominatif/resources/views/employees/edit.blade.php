<x-app-layout>
    <x-slot name="title">Edit Data Pegawai</x-slot>

    {{-- ─────────────── INLINE STYLES ─────────────── --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Fira+Sans:wght@300;400;500;600;700&display=swap');

        /* ── Design Tokens ── */
        :root {
            --ep-primary:    #1E40AF;
            --ep-primary-h:  #1d3a9f;
            --ep-secondary:  #3B82F6;
            --ep-accent:     #D97706;
            --ep-bg:         #F8FAFC;
            --ep-muted:      #E9EEF6;
            --ep-border:     #DBEAFE;
            --ep-text:       #1E3A8A;
            --ep-subtext:    #64748B;
            --ep-danger:     #DC2626;
            --ep-success:    #16a34a;
            --ep-radius:     14px;
            --ep-radius-sm:  8px;
            --ep-shadow:     0 1px 4px rgba(30,64,175,.08), 0 4px 24px rgba(30,64,175,.06);
            --ep-shadow-lg:  0 8px 40px rgba(30,64,175,.12);
            --ep-transition: 200ms cubic-bezier(.4,0,.2,1);
        }

        /* ── Page wrapper ── */
        .ep-page { font-family: 'Fira Sans', sans-serif; }

        /* ── Hero header card ── */
        .ep-hero {
            background: linear-gradient(135deg, var(--ep-primary) 0%, #2563EB 60%, var(--ep-secondary) 100%);
            border-radius: 20px;
            padding: 28px 32px;
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--ep-shadow-lg);
        }
        .ep-hero::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.06);
            border-radius: 50%;
        }
        .ep-hero::after {
            content: '';
            position: absolute;
            bottom: -60px; right: 60px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }
        .ep-hero-breadcrumb { display: flex; align-items: center; gap: 6px; font-size: 11px; color: rgba(255,255,255,.65); margin-bottom: 14px; }
        .ep-hero-breadcrumb a { color: rgba(255,255,255,.65); text-decoration: none; transition: color var(--ep-transition); }
        .ep-hero-breadcrumb a:hover { color: #fff; }
        .ep-hero-breadcrumb svg { width: 12px; height: 12px; opacity: .5; }
        .ep-hero h1 { font-size: 22px; font-weight: 700; color: #fff; margin: 0 0 6px; letter-spacing: -.4px; }
        .ep-hero p  { font-size: 13px; color: rgba(255,255,255,.75); margin: 0; }
        .ep-hero p strong { color: rgba(255,255,255,.95); font-weight: 600; }
        .ep-hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,.15); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 100px; padding: 4px 12px;
            font-size: 11px; color: rgba(255,255,255,.9);
            margin-bottom: 16px;
        }
        .ep-hero-badge span { width: 6px; height: 6px; border-radius: 50%; background: #86efac; box-shadow: 0 0 6px #86efac; animation: ep-pulse 2s infinite; }
        @keyframes ep-pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.2)} }

        /* ── Tab Nav ── */
        .ep-tabs-nav {
            display: flex; gap: 4px;
            background: #fff;
            border: 1px solid var(--ep-border);
            border-radius: 16px;
            padding: 6px;
            margin-bottom: 20px;
            box-shadow: var(--ep-shadow);
            overflow-x: auto;
        }
        .ep-tab-btn {
            display: flex; align-items: center; gap: 8px;
            flex: 1; min-width: 140px;
            padding: 10px 16px;
            border: none; background: transparent;
            border-radius: 11px;
            font-size: 12px; font-weight: 500;
            color: var(--ep-subtext);
            cursor: pointer;
            transition: all var(--ep-transition);
            white-space: nowrap;
            position: relative;
        }
        .ep-tab-btn svg { width: 16px; height: 16px; flex-shrink: 0; }
        .ep-tab-btn:hover { color: var(--ep-primary); background: var(--ep-muted); }
        .ep-tab-btn.active {
            background: var(--ep-primary);
            color: #fff;
            box-shadow: 0 2px 12px rgba(30,64,175,.3);
            font-weight: 600;
        }
        .ep-tab-btn.has-error::after {
            content: '';
            position: absolute;
            top: 8px; right: 8px;
            width: 7px; height: 7px;
            background: var(--ep-danger);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        /* ── Tab panels ── */
        .ep-tab-panel { display: none; animation: ep-fadein .25s ease; }
        .ep-tab-panel.active { display: block; }
        @keyframes ep-fadein { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }

        /* ── Form card ── */
        .ep-card {
            background: #fff;
            border: 1px solid var(--ep-border);
            border-radius: 20px;
            padding: 28px 28px;
            box-shadow: var(--ep-shadow);
        }

        /* ── Section label inside tab ── */
        .ep-section-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .8px;
            color: var(--ep-subtext);
            margin: 0 0 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--ep-muted);
        }
        .ep-section-title svg { width: 14px; height: 14px; color: var(--ep-primary); }
        .ep-section-title span.dot {
            display: inline-block; width: 8px; height: 8px;
            border-radius: 50%; background: var(--ep-primary);
            box-shadow: 0 0 0 3px rgba(30,64,175,.15);
        }

        /* ── Sub-section divider ── */
        .ep-subsection {
            font-size: 11px; font-weight: 700;
            color: var(--ep-primary);
            text-transform: uppercase; letter-spacing: .6px;
            display: flex; align-items: center; gap: 8px;
            margin: 24px 0 14px;
            padding: 8px 12px;
            background: var(--ep-muted);
            border-left: 3px solid var(--ep-primary);
            border-radius: 0 6px 6px 0;
        }
        .ep-subsection svg { width: 13px; height: 13px; }

        /* ── Field group ── */
        .ep-field { display: flex; flex-direction: column; gap: 5px; }
        .ep-label {
            font-size: 11.5px; font-weight: 600;
            color: #374151; letter-spacing: .1px;
            display: flex; align-items: center; gap: 4px;
        }
        .ep-label .required { color: var(--ep-danger); font-size: 13px; line-height: 1; }
        .ep-label .optional { font-size: 10px; font-weight: 400; color: var(--ep-subtext); margin-left: 2px; }

        /* ── Inputs ── */
        .ep-input, .ep-select, .ep-textarea {
            width: 100%;
            background: #F8FAFC;
            border: 1.5px solid #E2E8F0;
            border-radius: var(--ep-radius-sm);
            font-size: 13px; font-family: 'Fira Sans', sans-serif;
            color: #1e293b;
            transition: border-color var(--ep-transition), box-shadow var(--ep-transition), background var(--ep-transition);
            outline: none;
        }
        .ep-input:hover, .ep-select:hover, .ep-textarea:hover { border-color: #93C5FD; background: #fff; }
        .ep-input:focus, .ep-select:focus, .ep-textarea:focus {
            border-color: var(--ep-primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(30,64,175,.12);
        }
        .ep-input, .ep-select { height: 42px; padding: 0 14px; }
        .ep-input.mono { font-family: 'Fira Code', monospace; font-size: 12.5px; letter-spacing: .5px; }
        .ep-textarea { padding: 12px 14px; resize: vertical; min-height: 90px; line-height: 1.6; }
        .ep-input.is-error, .ep-select.is-error, .ep-textarea.is-error {
            border-color: var(--ep-danger);
            background: #fef2f2;
        }
        .ep-input.is-error:focus, .ep-select.is-error:focus { box-shadow: 0 0 0 3px rgba(220,38,38,.12); }
        .ep-error-msg { font-size: 11px; color: var(--ep-danger); display: flex; align-items: center; gap: 4px; }
        .ep-error-msg svg { width: 12px; height: 12px; flex-shrink: 0; }

        /* ── Input with icon prefix ── */
        .ep-input-wrap { position: relative; }
        .ep-input-wrap .ep-input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #94A3B8; pointer-events: none;
        }
        .ep-input-wrap .ep-input-icon svg { width: 15px; height: 15px; }
        .ep-input-wrap .ep-input { padding-left: 38px; }

        /* ── Grid helpers ── */
        .ep-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .ep-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
        .ep-grid-full { grid-column: 1 / -1; }
        @media (max-width: 768px) {
            .ep-grid-2, .ep-grid-3 { grid-template-columns: 1fr; }
        }

        /* ── Actions footer ── */
        .ep-actions {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 24px;
            background: #fff;
            border: 1px solid var(--ep-border);
            border-radius: 16px;
            margin-top: 20px;
            box-shadow: var(--ep-shadow);
        }
        .ep-actions-info { font-size: 11.5px; color: var(--ep-subtext); display: flex; align-items: center; gap: 6px; }
        .ep-actions-info svg { width: 14px; height: 14px; color: var(--ep-accent); }
        .ep-btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 0 22px; height: 42px;
            border-radius: 100px;
            font-size: 13px; font-weight: 600;
            border: none; cursor: pointer;
            transition: all var(--ep-transition);
            text-decoration: none;
        }
        .ep-btn svg { width: 15px; height: 15px; }
        .ep-btn-ghost {
            background: var(--ep-muted);
            color: #374151;
        }
        .ep-btn-ghost:hover { background: #dde5f0; color: #1e293b; }
        .ep-btn-primary {
            background: var(--ep-primary);
            color: #fff;
            box-shadow: 0 2px 12px rgba(30,64,175,.3);
        }
        .ep-btn-primary:hover { background: var(--ep-primary-h); box-shadow: 0 4px 20px rgba(30,64,175,.4); transform: translateY(-1px); }
        .ep-btn-primary:active { transform: scale(.98); }

        /* ── Progress indicator ── */
        .ep-progress-bar {
            display: flex; gap: 6px; align-items: center;
        }
        .ep-progress-step {
            height: 4px; flex: 1; border-radius: 100px;
            background: var(--ep-muted);
            transition: background var(--ep-transition);
        }
        .ep-progress-step.done { background: var(--ep-success); }
        .ep-progress-step.active { background: var(--ep-primary); }

        /* ── Navigation arrows ── */
        .ep-tab-nav-btns { display: flex; gap: 8px; }
        .ep-tab-nav-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 0 16px; height: 36px; border-radius: 100px;
            font-size: 12px; font-weight: 500;
            border: 1.5px solid var(--ep-border);
            background: #fff; cursor: pointer;
            color: var(--ep-subtext);
            transition: all var(--ep-transition);
        }
        .ep-tab-nav-btn:hover { border-color: var(--ep-primary); color: var(--ep-primary); background: var(--ep-muted); }
        .ep-tab-nav-btn svg { width: 14px; height: 14px; }
        .ep-tab-nav-btn:disabled { opacity: .4; cursor: not-allowed; }

        /* ── Floating alert bar ── */
        @keyframes ep-slidedown { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
        .ep-alert {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 18px; border-radius: 14px;
            margin-bottom: 20px; font-size: 12.5px;
            animation: ep-slidedown .25s ease;
        }
        .ep-alert-error { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
        .ep-alert svg { width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px; }
        .ep-alert ul { margin: 4px 0 0; padding-left: 18px; }
        .ep-alert ul li { margin-bottom: 2px; }
    </style>

    <div class="ep-page">

        {{-- ── Hero Header ── --}}
        <div class="ep-hero">
            <div class="ep-hero-breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('employees.index') }}">Pegawai</a>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('employees.show', $employee->id) }}">{{ $employee->full_name }}</a>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span>Edit</span>
            </div>
            <div class="ep-hero-badge">
                <span></span>
                Mode Edit Aktif
            </div>
            <h1>Edit Data Pegawai</h1>
            <p>Perbarui informasi untuk <strong>{{ $employee->full_name_with_title }}</strong></p>
        </div>

        {{-- ── Validation Error Alert ── --}}
        @if ($errors->any())
        <div class="ep-alert ep-alert-error">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
                <strong>Terdapat kesalahan pada form:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('employees.update', $employee->id) }}" id="ep-form">
            @csrf
            @method('PUT')

            {{-- ── Tab Navigation ── --}}
            <div class="ep-tabs-nav" role="tablist" aria-label="Bagian form edit pegawai">
                <button type="button" class="ep-tab-btn active {{ $errors->hasAny(['nip','full_name','prefix_title','suffix_title','birth_place','birth_date','gender_id','religion_id','marital_status_id','email','phone','address']) ? 'has-error' : '' }}"
                    id="tab-btn-identitas" role="tab" aria-selected="true" aria-controls="tab-identitas"
                    onclick="switchTab('identitas')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Identitas Pegawai
                </button>
                <button type="button" class="ep-tab-btn {{ $errors->hasAny(['employment_status_id','work_unit_id']) ? 'has-error' : '' }}"
                    id="tab-btn-status" role="tab" aria-selected="false" aria-controls="tab-status"
                    onclick="switchTab('status')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kepegawaian
                </button>
                <button type="button" class="ep-tab-btn"
                    id="tab-btn-nominatif" role="tab" aria-selected="false" aria-controls="tab-nominatif"
                    onclick="switchTab('nominatif')">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Data Nominatif
                </button>
            </div>

            {{-- ── Progress Bar ── --}}
            <div class="ep-progress-bar" style="margin-bottom:18px;" id="ep-progress">
                <div class="ep-progress-step active" id="prog-1"></div>
                <div class="ep-progress-step" id="prog-2"></div>
                <div class="ep-progress-step" id="prog-3"></div>
            </div>

            {{-- ═══════════════════════════════════════════════ --}}
            {{-- TAB 1: Identitas Pegawai                        --}}
            {{-- ═══════════════════════════════════════════════ --}}
            <div class="ep-card ep-tab-panel active" id="tab-identitas" role="tabpanel" aria-labelledby="tab-btn-identitas">

                <div class="ep-section-title">
                    <span class="dot"></span>
                    Identitas Utama Pegawai
                </div>

                <div class="ep-grid-2">
                    {{-- NIP --}}
                    <div class="ep-field">
                        <label for="nip" class="ep-label">
                            NIP <span class="optional">(18 Digit)</span>
                            <span class="required">*</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2"/></svg>
                            </span>
                            <input type="text" id="nip" name="nip" value="{{ old('nip', $employee->nip) }}" required
                                   maxlength="18" placeholder="18 digit NIP"
                                   class="ep-input mono {{ $errors->has('nip') ? 'is-error' : '' }}">
                        </div>
                        @error('nip')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="ep-field">
                        <label for="full_name" class="ep-label">
                            Nama Lengkap <span class="optional">(Tanpa Gelar)</span>
                            <span class="required">*</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $employee->full_name) }}" required
                                   placeholder="Nama tanpa gelar"
                                   class="ep-input {{ $errors->has('full_name') ? 'is-error' : '' }}">
                        </div>
                        @error('full_name')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Gelar Depan --}}
                    <div class="ep-field">
                        <label for="prefix_title" class="ep-label">
                            Gelar Depan
                            <span class="optional">(opsional)</span>
                        </label>
                        <input type="text" id="prefix_title" name="prefix_title" value="{{ old('prefix_title', $employee->prefix_title) }}"
                               placeholder="mis. Dr. / Prof."
                               class="ep-input {{ $errors->has('prefix_title') ? 'is-error' : '' }}">
                        @error('prefix_title')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Gelar Belakang --}}
                    <div class="ep-field">
                        <label for="suffix_title" class="ep-label">
                            Gelar Belakang
                            <span class="optional">(opsional)</span>
                        </label>
                        <input type="text" id="suffix_title" name="suffix_title" value="{{ old('suffix_title', $employee->suffix_title) }}"
                               placeholder="mis. S.T., M.T."
                               class="ep-input {{ $errors->has('suffix_title') ? 'is-error' : '' }}">
                        @error('suffix_title')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="ep-field">
                        <label for="birth_place" class="ep-label">
                            Tempat Lahir <span class="required">*</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $employee->birth_place) }}" required
                                   placeholder="Kota kelahiran"
                                   class="ep-input {{ $errors->has('birth_place') ? 'is-error' : '' }}">
                        </div>
                        @error('birth_place')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="ep-field">
                        <label for="birth_date" class="ep-label">
                            Tanggal Lahir <span class="required">*</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}" required
                                   class="ep-input {{ $errors->has('birth_date') ? 'is-error' : '' }}">
                        </div>
                        @error('birth_date')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="ep-field">
                        <label for="gender_id" class="ep-label">
                            Jenis Kelamin <span class="required">*</span>
                        </label>
                        <select id="gender_id" name="gender_id" required class="ep-select">
                            @foreach ($genders as $g)
                                <option value="{{ $g->id }}" {{ old('gender_id', $employee->gender_id) == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Agama --}}
                    <div class="ep-field">
                        <label for="religion_id" class="ep-label">
                            Agama <span class="required">*</span>
                        </label>
                        <select id="religion_id" name="religion_id" required class="ep-select">
                            @foreach ($religions as $r)
                                <option value="{{ $r->id }}" {{ old('religion_id', $employee->religion_id) == $r->id ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Perkawinan --}}
                    <div class="ep-field">
                        <label for="marital_status_id" class="ep-label">
                            Status Perkawinan <span class="required">*</span>
                        </label>
                        <select id="marital_status_id" name="marital_status_id" required class="ep-select">
                            @foreach ($maritalStatuses as $ms)
                                <option value="{{ $ms->id }}" {{ old('marital_status_id', $employee->marital_status_id) == $ms->id ? 'selected' : '' }}>{{ $ms->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Email --}}
                    <div class="ep-field">
                        <label for="email" class="ep-label">
                            Alamat Email
                            <span class="optional">(opsional)</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email', $employee->email) }}"
                                   placeholder="nama@bmkg.go.id"
                                   class="ep-input {{ $errors->has('email') ? 'is-error' : '' }}">
                        </div>
                        @error('email')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Telepon --}}
                    <div class="ep-field">
                        <label for="phone" class="ep-label">
                            Nomor Telepon / HP
                            <span class="optional">(opsional)</span>
                        </label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <input type="text" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}"
                                   placeholder="08xx-xxxx-xxxx"
                                   class="ep-input {{ $errors->has('phone') ? 'is-error' : '' }}">
                        </div>
                        @error('phone')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="ep-field ep-grid-full">
                        <label for="address" class="ep-label">
                            Alamat Tempat Tinggal
                            <span class="optional">(opsional)</span>
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  placeholder="Jl. ... No. ... Kota ..."
                                  class="ep-textarea">{{ old('address', $employee->address) }}</textarea>
                    </div>

                    {{-- Jumlah Anak --}}
                    <div class="ep-field">
                        <label for="children_count" class="ep-label">Jumlah Anak</label>
                        <input type="number" id="children_count" name="children_count" value="{{ old('children_count', $employee->children_count) }}"
                               placeholder="0" class="ep-input">
                    </div>

                    {{-- Jumlah Anggota Keluarga --}}
                    <div class="ep-field">
                        <label for="family_count" class="ep-label">Jumlah Anggota Keluarga</label>
                        <input type="number" id="family_count" name="family_count" value="{{ old('family_count', $employee->family_count) }}"
                               placeholder="0" class="ep-input">
                    </div>

                    {{-- Keterangan Keluarga --}}
                    <div class="ep-field">
                        <label for="family_note" class="ep-label">Keterangan Keluarga</label>
                        <input type="text" id="family_note" name="family_note" value="{{ old('family_note', $employee->family_note) }}"
                               placeholder="-" class="ep-input">
                    </div>
                </div>

                {{-- Tab nav buttons --}}
                <div style="display:flex;justify-content:flex-end;margin-top:24px;">
                    <button type="button" class="ep-tab-nav-btn" onclick="switchTab('status')">
                        Lanjut: Kepegawaian
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════ --}}
            {{-- TAB 2: Status Kepegawaian & Penempatan          --}}
            {{-- ═══════════════════════════════════════════════ --}}
            <div class="ep-card ep-tab-panel" id="tab-status" role="tabpanel" aria-labelledby="tab-btn-status">

                <div class="ep-section-title">
                    <span class="dot"></span>
                    Status Kepegawaian & Penempatan
                </div>

                <div class="ep-grid-2">
                    {{-- Status Kepegawaian --}}
                    <div class="ep-field">
                        <label for="employment_status_id" class="ep-label">
                            Status Kepegawaian <span class="required">*</span>
                        </label>
                        <select id="employment_status_id" name="employment_status_id" required class="ep-select {{ $errors->has('employment_status_id') ? 'is-error' : '' }}">
                            @foreach ($employmentStatuses as $es)
                                <option value="{{ $es->id }}" {{ old('employment_status_id', $employee->employment_status_id) == $es->id ? 'selected' : '' }}>{{ $es->name }}</option>
                            @endforeach
                        </select>
                        @error('employment_status_id')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Unit Kerja --}}
                    <div class="ep-field">
                        <label for="work_unit_id" class="ep-label">
                            Unit Kerja <span class="required">*</span>
                        </label>
                        <select id="work_unit_id" name="work_unit_id" required class="ep-select {{ $errors->has('work_unit_id') ? 'is-error' : '' }}">
                            @foreach ($workUnits as $wu)
                                <option value="{{ $wu->id }}" {{ old('work_unit_id', $employee->work_unit_id) == $wu->id ? 'selected' : '' }}>{{ $wu->name }}</option>
                            @endforeach
                        </select>
                        @error('work_unit_id')
                            <span class="ep-error-msg">
                                <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                </div>

                <div style="display:flex;justify-content:space-between;margin-top:24px;">
                    <button type="button" class="ep-tab-nav-btn" onclick="switchTab('identitas')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </button>
                    <button type="button" class="ep-tab-nav-btn" onclick="switchTab('nominatif')">
                        Lanjut: Data Nominatif
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            {{-- ═══════════════════════════════════════════════ --}}
            {{-- TAB 3: Data Nominatif                           --}}
            {{-- ═══════════════════════════════════════════════ --}}
            <div class="ep-card ep-tab-panel" id="tab-nominatif" role="tabpanel" aria-labelledby="tab-btn-nominatif">

                <div class="ep-section-title">
                    <span class="dot"></span>
                    Informasi Kepegawaian (Nominatif)
                </div>

                {{-- Sub-section: Informasi Dasar --}}
                <div class="ep-subsection">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Informasi Dasar
                </div>
                <div class="ep-grid-3">
                    <div class="ep-field">
                        <label for="karpeg" class="ep-label">No. Karpeg</label>
                        <input type="text" id="karpeg" name="karpeg" value="{{ old('karpeg', $employee->karpeg) }}"
                               placeholder="—" class="ep-input mono">
                    </div>
                    <div class="ep-field">
                        <label for="salary" class="ep-label">Gaji Pokok</label>
                        <div class="ep-input-wrap">
                            <span class="ep-input-icon" style="left:12px;font-size:11px;font-family:'Fira Code',mono;color:#94A3B8;font-weight:600;width:auto;">Rp</span>
                            <input type="number" id="salary" name="salary" value="{{ old('salary', $employee->salary) }}"
                                   placeholder="0" style="padding-left:38px"
                                   class="ep-input mono">
                        </div>
                    </div>
                    <div class="ep-field">
                        <label for="salary_tmt" class="ep-label">TMT Gaji</label>
                        <input type="date" id="salary_tmt" name="salary_tmt" value="{{ old('salary_tmt', $employee->salary_tmt?->format('Y-m-d')) }}"
                               class="ep-input">
                    </div>
                </div>

                {{-- Sub-section: Pangkat & Golongan --}}
                <div class="ep-subsection" style="margin-top:28px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Pangkat & Golongan
                </div>
                <div class="ep-grid-3">
                    <div class="ep-field">
                        <label for="cpns_rank" class="ep-label">Golongan CPNS</label>
                        <input type="text" id="cpns_rank" name="cpns_rank" value="{{ old('cpns_rank', $employee->cpns_rank) }}"
                               placeholder="mis. II/a" class="ep-input mono">
                    </div>
                    <div class="ep-field">
                        <label for="cpns_tmt" class="ep-label">TMT CPNS</label>
                        <input type="date" id="cpns_tmt" name="cpns_tmt" value="{{ old('cpns_tmt', $employee->cpns_tmt?->format('Y-m-d')) }}"
                               class="ep-input">
                    </div>
                    <div class="ep-field" style="grid-column:1;">
                        <label for="pns_rank" class="ep-label">Golongan PNS</label>
                        <input type="text" id="pns_rank" name="pns_rank" value="{{ old('pns_rank', $employee->pns_rank) }}"
                               placeholder="mis. III/b" class="ep-input mono">
                    </div>
                    <div class="ep-field">
                        <label for="pns_tmt" class="ep-label">TMT PNS</label>
                        <input type="date" id="pns_tmt" name="pns_tmt" value="{{ old('pns_tmt', $employee->pns_tmt?->format('Y-m-d')) }}"
                               class="ep-input">
                    </div>
                </div>

                {{-- Sub-section: Pendidikan --}}
                <div class="ep-subsection" style="margin-top:28px;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" fill="none"/></svg>
                    Pendidikan
                </div>

                {{-- Pendidikan rows: each type + tahun --}}
                @php
                    $eduRows = [
                        ['name' => 'edu_dinas',        'year_name' => 'edu_dinas_year',        'label' => 'Pendidikan Dinas',  'value' => $employee->edu_dinas,        'year' => $employee->edu_dinas_year],
                        ['name' => 'edu_kursus',       'year_name' => 'edu_kursus_year',       'label' => 'Kursus',            'value' => $employee->edu_kursus,       'year' => $employee->edu_kursus_year],
                        ['name' => 'edu_ln',           'year_name' => 'edu_ln_year',           'label' => 'Luar Negeri',       'value' => $employee->edu_ln,           'year' => $employee->edu_ln_year],
                        ['name' => 'edu_penjenjangan', 'year_name' => 'edu_penjenjangan_year', 'label' => 'Penjenjangan',      'value' => $employee->edu_penjenjangan, 'year' => $employee->edu_penjenjangan_year],
                    ];
                @endphp

                <div style="display:flex;flex-direction:column;gap:12px;margin-top:4px;">
                    @foreach ($eduRows as $row)
                    <div style="display:grid;grid-template-columns:1fr 140px;gap:12px;align-items:end;background:#F8FAFC;border:1px solid var(--ep-border);border-radius:10px;padding:14px 16px;">
                        <div class="ep-field" style="gap:4px;">
                            <label class="ep-label" style="font-size:11px;">{{ $row['label'] }}</label>
                            <input type="text" name="{{ $row['name'] }}" value="{{ old($row['name'], $row['value']) }}"
                                   placeholder="—"
                                   class="ep-input" style="height:38px;font-size:12.5px;">
                        </div>
                        <div class="ep-field" style="gap:4px;">
                            <label class="ep-label" style="font-size:11px;">Tahun</label>
                            <input type="number" name="{{ $row['year_name'] }}" value="{{ old($row['year_name'], $row['year']) }}"
                                   placeholder="{{ date('Y') }}" min="1950" max="{{ date('Y') }}"
                                   class="ep-input mono" style="height:38px;font-size:12.5px;">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div style="display:flex;justify-content:space-between;margin-top:24px;">
                    <button type="button" class="ep-tab-nav-btn" onclick="switchTab('status')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali
                    </button>
                    {{-- placeholder for layout --}}
                    <span></span>
                </div>
            </div>

            {{-- ── Actions Footer ── --}}
            <div class="ep-actions">
                <div class="ep-actions-info">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Semua tab akan tersimpan sekaligus saat klik <strong>Simpan</strong></span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;">
                    <a href="{{ route('employees.show', $employee->id) }}" class="ep-btn ep-btn-ghost">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    </a>
                    <button type="submit" class="ep-btn ep-btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Tab switching script ── --}}
    <script>
    const TAB_ORDER = ['identitas', 'status', 'nominatif'];
    const TAB_INDEX = { identitas: 0, status: 1, nominatif: 2 };

    function switchTab(id) {
        // Panels
        document.querySelectorAll('.ep-tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + id).classList.add('active');

        // Buttons
        document.querySelectorAll('.ep-tab-btn').forEach(b => {
            b.classList.remove('active');
            b.setAttribute('aria-selected', 'false');
        });
        const activeBtn = document.getElementById('tab-btn-' + id);
        activeBtn.classList.add('active');
        activeBtn.setAttribute('aria-selected', 'true');

        // Progress bar
        const idx = TAB_INDEX[id];
        document.querySelectorAll('.ep-progress-step').forEach((s, i) => {
            s.classList.remove('active', 'done');
            if (i < idx) s.classList.add('done');
            else if (i === idx) s.classList.add('active');
        });

        // Scroll to top of form area
        document.getElementById('ep-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Auto-open tab with errors on load
    (function() {
        const errorTabs = ['identitas', 'status'];
        for (const t of errorTabs) {
            const btn = document.getElementById('tab-btn-' + t);
            if (btn && btn.classList.contains('has-error')) {
                switchTab(t);
                break;
            }
        }
    })();
    </script>
</x-app-layout>
