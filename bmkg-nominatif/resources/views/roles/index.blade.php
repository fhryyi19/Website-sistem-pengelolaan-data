<x-app-layout>
    <x-slot name="title">Role Management</x-slot>

    <!-- Header & Action -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" x-data="{ createRoleModal: false }">
        <div>
            <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">Role & Permission Management</h1>
            <p class="text-xs text-[#7a7a7a] mt-1">Daftar peran pengguna dan jumlah user terdaftar pada tiap role.</p>
        </div>

        <button @click="createRoleModal = true"
                class="h-10 px-5 bg-[#0066cc] hover:bg-[#0071e3] text-white font-medium text-xs rounded-full transition-all active:scale-[0.98] shadow-sm flex items-center gap-2 self-start">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Tambah Role Baru</span>
        </button>

        <!-- Modal Tambah Role -->
        <div x-show="createRoleModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
            <div @click.outside="createRoleModal = false" class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-xl">
                <h3 class="text-lg font-bold text-[#1d1d1f]">Tambah Role Baru</h3>

                <form method="POST" action="{{ route('roles.store') }}" class="space-y-4 text-xs">
                    @csrf
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Nama Role (slug)</label>
                        <input type="text" name="name" placeholder="e.g. supervisor" required class="w-full h-10 px-4 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]">
                    </div>
                    <div>
                        <label class="block font-medium text-[#1d1d1f] mb-1">Deskripsi Hak Akses</label>
                        <textarea name="description" rows="3" class="w-full p-3 border border-[#e0e0e0] rounded-xl focus:ring-2 focus:ring-[#0066cc]" placeholder="Jelaskan wewenang role ini..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="createRoleModal = false" class="h-10 px-5 bg-gray-100 rounded-full font-medium">Batal</button>
                        <button type="submit" class="h-10 px-6 bg-[#0066cc] text-white rounded-full font-medium">Simpan Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Role Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($roles as $role)
            <div class="bg-white rounded-3xl border border-[#e0e0e0] p-6 space-y-4 hover:border-gray-300 transition-all flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ $role->name }}
                        </span>
                        <span class="text-xs text-[#7a7a7a] font-medium">
                            {{ $role->users_count }} User Terdaftar
                        </span>
                    </div>
                    <h3 class="text-base font-bold text-[#1d1d1f]">{{ $role->label }}</h3>
                    <p class="text-xs text-[#7a7a7a] mt-2 leading-relaxed">
                        {{ $role->description ?? 'Tidak ada deskripsi khusus.' }}
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                    <span class="text-[#7a7a7a] italic text-[11px]">
                        {{ in_array($role->name, ['admin', 'user']) ? 'Role bawaan sistem' : 'Custom Role' }}
                    </span>

                    @if (!in_array($role->name, ['admin', 'user']))
                        <form id="delete-role-form-{{ $role->id }}" action="{{ route('roles.destroy', $role->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('delete-role-form-{{ $role->id }}', 'Hapus Role?', 'Role ini akan dihapus jika tidak memiliki user.')"
                                    class="text-red-600 hover:underline font-medium">
                                Hapus Role
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
