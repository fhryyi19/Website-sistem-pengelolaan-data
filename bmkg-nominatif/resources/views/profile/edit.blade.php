<x-app-layout>
    <x-slot name="title">Pengaturan Profil</x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Fira+Sans:wght@300;400;500;600;700&display=swap');
        :root { --ep-primary:#1E40AF; --ep-primary-h:#1d3a9f; --ep-secondary:#3B82F6; --ep-accent:#D97706; --ep-bg:#F8FAFC; --ep-muted:#E9EEF6; --ep-border:#DBEAFE; --ep-text:#1E3A8A; --ep-subtext:#64748B; --ep-danger:#DC2626; --ep-success:#16a34a; --ep-radius:14px; --ep-radius-sm:8px; --ep-shadow:0 1px 4px rgba(30,64,175,.08), 0 4px 24px rgba(30,64,175,.06); --ep-shadow-lg:0 8px 40px rgba(30,64,175,.12); --ep-transition:200ms cubic-bezier(.4,0,.2,1); }
        .ep-page { font-family: 'Fira Sans', sans-serif; }
        .ep-hero { background: linear-gradient(135deg, var(--ep-primary) 0%, #2563EB 60%, var(--ep-secondary) 100%); border-radius: 20px; padding: 28px 32px; margin-bottom: 24px; position: relative; overflow: hidden; box-shadow: var(--ep-shadow-lg); }
        .ep-hero h1 { font-size: 22px; font-weight: 700; color: #fff; margin: 0 0 6px; letter-spacing: -.4px; }
        .ep-hero p { font-size: 13px; color: rgba(255,255,255,.75); margin: 0; }
        .ep-card { background: #fff; border: 1px solid var(--ep-border); border-radius: 20px; padding: 28px; box-shadow: var(--ep-shadow); }
        .ep-section-title { display: flex; align-items: center; gap: 10px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: var(--ep-subtext); margin: 0 0 20px; padding-bottom: 10px; border-bottom: 1px solid var(--ep-muted); }
        .ep-section-title span.dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--ep-primary); box-shadow: 0 0 0 3px rgba(30,64,175,.15); }
        .ep-field { display: flex; flex-direction: column; gap: 5px; }
        .ep-label { font-size: 11.5px; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 4px; }
        .ep-input, .ep-select, .ep-textarea { width: 100%; background: #F8FAFC; border: 1.5px solid #E2E8F0; border-radius: var(--ep-radius-sm); font-size: 13px; padding: 0 14px; height: 42px; outline: none; }
        .ep-input:focus { border-color: var(--ep-primary); background: #fff; box-shadow: 0 0 0 3px rgba(30,64,175,.12); }
        .ep-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0 22px; height: 42px; border-radius: 100px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 200ms; text-decoration: none; background: var(--ep-primary); color: #fff; }
        .ep-btn:hover { background: var(--ep-primary-h); transform: translateY(-1px); }
    </style>

    <div class="ep-page">
        <div class="ep-hero">
            <h1>Pengaturan Profil</h1>
            <p>Kelola data profil, foto, dan keamanan akun Anda.</p>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs font-semibold text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-xs font-semibold text-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full space-y-6">
            <div class="ep-card">
                <div class="ep-section-title"><span class="dot"></span>Informasi Utama</div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PATCH')
                    <div class="flex items-center gap-6">
                        <div class="relative group">
                            <img id="avatar-preview-img" class="w-20 h-20 rounded-full border-2 border-slate-200 object-cover shadow-sm transition-all group-hover:opacity-90" src="{{ $user->avatar_url }}" alt="Avatar">
                            <label for="avatar-file-input" class="absolute inset-0 flex items-center justify-center bg-slate-900/40 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </label>
                        </div>
                        <div class="flex-1">
                            <label class="ep-label">Ganti Foto Profil</label>
                            <div class="mt-1 flex items-center gap-3">
                                <label for="avatar-file-input" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 cursor-pointer transition">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    Pilih Foto
                                </label>
                                <span id="avatar-filename" class="text-xs text-slate-500">Belum ada file dipilih</span>
                            </div>
                            <input type="file" id="avatar-file-input" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp" class="hidden" onchange="previewAvatarImage(event)">
                            <p class="text-[11px] text-slate-400 mt-1.5">Format: JPG, PNG, WEBP. Ukuran maks: 2MB.</p>
                        </div>
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="ep-input">
                    </div>
                    <button type="submit" class="ep-btn">Simpan Profil</button>
                </form>
            </div>

            <div class="ep-card">
                <div class="ep-section-title"><span class="dot"></span>Ubah Kata Sandi</div>
                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="ep-field">
                        <label class="ep-label">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="ep-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="ep-field">
                            <label class="ep-label">Password Baru</label>
                            <input type="password" name="password" required class="ep-input">
                        </div>
                        <div class="ep-field">
                            <label class="ep-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required class="ep-input">
                        </div>
                    </div>
                    <button type="submit" class="ep-btn" style="background:#334155;">Perbarui Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewAvatarImage(event) {
            const input = event.target;
            const filenameSpan = document.getElementById('avatar-filename');
            const previewImg = document.getElementById('avatar-preview-img');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                filenameSpan.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
