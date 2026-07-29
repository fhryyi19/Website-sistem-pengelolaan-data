<x-app-layout>
    <x-slot name="title">Pengaturan Profil</x-slot>

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
        .ep-input:focus { border-color: var(--ep-primary); background: #fff; box-shadow: 0 0 0 3px rgba(30,64,175,.12); }
        .ep-btn { display: inline-flex; align-items: center; gap: 8px; padding: 0 22px; height: 42px; border-radius: 100px; font-size: 13px; font-weight: 600; border: none; cursor: pointer; transition: all 200ms; text-decoration: none; background: var(--ep-primary); color: #fff; }
        .ep-btn:hover { background: var(--ep-primary-h); transform: translateY(-1px); }
    </style>

    <div class="ep-page">
        <div class="ep-hero">
            <h1>Pengaturan Profil</h1>
            <p>Kelola data profil, foto, dan keamanan akun Anda.</p>
        </div>

        <div class="w-full space-y-6">
            <div class="ep-card">
                <div class="ep-section-title"><span class="dot"></span>Informasi Utama</div>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf @method('PATCH')
                    <div class="flex items-center gap-6">
                        <img class="w-20 h-20 rounded-full border border-slate-200 object-cover" src="{{ $user->avatar_url }}" alt="Avatar">
                        <div class="flex-1">
                            <label class="ep-label">Ganti Foto Profil</label>
                            <input type="file" name="avatar" class="text-xs text-slate-500">
                        </div>
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="ep-input">
                    </div>
                    <div class="ep-field">
                        <label class="ep-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="ep-input">
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
                        <input type="password" name="current_password" class="ep-input">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="ep-field">
                            <label class="ep-label">Password Baru</label>
                            <input type="password" name="password" class="ep-input">
                        </div>
                        <div class="ep-field">
                            <label class="ep-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="ep-input">
                        </div>
                    </div>
                    <button type="submit" class="ep-btn" style="background:#334155;">Perbarui Password</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
