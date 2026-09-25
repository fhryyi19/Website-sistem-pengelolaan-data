<x-app-layout>
    <x-slot name="title">User Management</x-slot>

    <!-- Header & Action -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" x-data="{ createModal: {{ ($errors->any() && !old('_method')) ? 'true' : 'false' }} }">
        <div>
            <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">User Management</h1>
            <p class="text-xs text-[#7a7a7a] mt-1">Kelola pengguna sistem, peran (role), dan hak akses aplikasi.</p>
        </div>

        <button @click="createModal = true"
                class="h-10 px-5 bg-[#0066cc] hover:bg-[#0071e3] text-white font-medium text-xs rounded-full transition-all active:scale-[0.98] shadow-sm flex items-center gap-2 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah User Baru</span>
        </button>

        <!-- Modal Tambah User -->
        <div x-show="createModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
            <div @click.outside="createModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-[#1d1d1f]">Tambah User Baru</h3>
                    <button type="button" @click="createModal = false" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
                </div>

                <form method="POST" action="{{ route('users.store') }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full h-10 px-4 border @error('name') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Contoh: Budi Santoso">
                        @error('name')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" required class="w-full h-10 px-4 border @error('username') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Contoh: budi_santoso">
                        @error('username')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Alamat Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full h-10 px-4 border @error('email') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Contoh: budi@bmkg.go.id">
                        @error('email')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">NIP (Opsional)</label>
                        <input type="text" name="nip" value="{{ old('nip') }}" class="w-full h-10 px-4 border @error('nip') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl font-mono focus:ring-2 focus:ring-[#0066cc]" placeholder="19XXXXXXXXXXXXXX">
                        @error('nip')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Role / Peran <span class="text-red-500">*</span></label>
                        <select name="role_id" required class="w-full h-10 px-4 border @error('role_id') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                            <option value="">-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium text-[#1d1d1f] mb-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required class="w-full h-10 px-4 border @error('password') border-red-500 @else border-[#e0e0e0] @enderror rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Minimal 8 karakter">
                            @error('password')
                                <p class="text-[11px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block font-medium text-[#1d1d1f] mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Ulangi password">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="createModal = false" class="h-10 px-5 bg-gray-100 hover:bg-gray-200 rounded-full font-medium transition-colors">Batal</button>
                        <button type="submit" class="h-10 px-6 bg-[#0066cc] hover:bg-[#0071e3] text-white rounded-full font-medium transition-colors">Simpan User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- User Table Container -->
    <div class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] text-[11px] font-semibold text-[#7a7a7a] uppercase tracking-wider">
                        <th class="py-3.5 px-4">USER</th>
                        <th class="py-3.5 px-4">USERNAME & EMAIL</th>
                        <th class="py-3.5 px-4">ROLE</th>
                        <th class="py-3.5 px-4">LAST LOGIN</th>
                        <th class="py-3.5 px-4">STATUS</th>
                        <th class="py-3.5 px-4 text-center w-36">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @foreach ($users as $user)
                        <tr class="hover:bg-[#f5f5f7]/60 transition-colors" x-data="{ editModal: false, resetModal: false }">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <img class="w-8 h-8 rounded-full object-cover border border-gray-200" src="{{ $user->avatar_url }}" alt="{{ $user->name }}">
                                    <div>
                                        <p class="font-semibold text-[#1d1d1f]">{{ $user->name }}</p>
                                        @if ($user->nip)
                                            <p class="text-[11px] text-[#7a7a7a] font-mono">NIP. {{ $user->nip }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-mono text-[#1d1d1f]">{{ $user->username }}</p>
                                <p class="text-[11px] text-[#7a7a7a]">{{ $user->email }}</p>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-medium {{ $user->isAdmin() ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $user->role?->label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#7a7a7a]">
                                {{ $user->last_login?->diffForHumans() ?? 'Belum Pernah' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('users.toggle-active', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-0.5 rounded-full text-[10px] font-medium {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button @click="editModal = true" title="Edit User" class="p-1.5 text-gray-600 hover:text-blue-600 rounded-lg hover:bg-blue-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    <!-- Reset Password Button -->
                                    <button @click="resetModal = true" title="Reset Password" class="p-1.5 text-gray-600 hover:text-amber-600 rounded-lg hover:bg-amber-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    @if ($user->id !== auth()->id())
                                        <form id="delete-user-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('delete-user-form-{{ $user->id }}', 'Hapus User?', 'User {{ $user->name }} akan dihapus permanen.')"
                                                    title="Hapus User" class="p-1.5 text-gray-600 hover:text-red-600 rounded-lg hover:bg-red-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Modal Edit User -->
                                <div x-show="editModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                                    <div @click.outside="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-6 shadow-xl">
                                        <h3 class="text-lg font-bold text-[#1d1d1f]">Edit User: {{ $user->name }}</h3>
                                        <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-4 text-xs">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label class="block font-medium text-[#1d1d1f] mb-1">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ $user->name }}" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                                            </div>
                                            <div>
                                                <label class="block font-medium text-[#1d1d1f] mb-1">Username</label>
                                                <input type="text" name="username" value="{{ $user->username }}" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                                            </div>
                                            <div>
                                                <label class="block font-medium text-[#1d1d1f] mb-1">Alamat Email</label>
                                                <input type="email" name="email" value="{{ $user->email }}" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                                            </div>
                                            <div>
                                                <label class="block font-medium text-[#1d1d1f] mb-1">NIP</label>
                                                <input type="text" name="nip" value="{{ $user->nip }}" class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl font-mono focus:ring-2 focus:ring-[#0066cc]">
                                            </div>
                                            <div>
                                                <label class="block font-medium text-[#1d1d1f] mb-1">Role / Peran</label>
                                                <select name="role_id" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                                <button type="button" @click="editModal = false" class="h-10 px-5 bg-gray-100 rounded-full font-medium">Batal</button>
                                                <button type="submit" class="h-10 px-6 bg-[#0066cc] text-white rounded-full font-medium">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- Modal Reset Password -->
                                <div x-show="resetModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
                                    <div @click.outside="resetModal = false" class="bg-white rounded-3xl max-w-sm w-full p-6 space-y-4 text-left shadow-xl">
                                        <h3 class="text-base font-bold text-[#1d1d1f]">Reset Password User</h3>
                                        <p class="text-xs text-[#7a7a7a]">Masukkan password baru untuk {{ $user->name }}.</p>
                                        <form method="POST" action="{{ route('users.reset-password', $user->id) }}" class="space-y-4 text-xs">
                                            @csrf
                                            <div>
                                                <label class="block font-medium mb-1">Password Baru</label>
                                                <input type="password" name="password" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                            </div>
                                            <div>
                                                <label class="block font-medium mb-1">Konfirmasi Password</label>
                                                <input type="password" name="password_confirmation" required class="w-full h-10 px-3 border border-[#e0e0e0] rounded-xl">
                                            </div>
                                            <div class="flex justify-end gap-2 pt-2">
                                                <button type="button" @click="resetModal = false" class="px-4 py-2 bg-gray-100 rounded-full">Batal</button>
                                                <button type="submit" class="px-5 py-2 bg-[#0066cc] text-white rounded-full">Simpan Password</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#e0e0e0] bg-[#fafafc]">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
