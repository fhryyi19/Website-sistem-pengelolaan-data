<x-app-layout>
    <x-slot name="title">Audit Log System</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">Audit Log Aktivitas System</h1>
        <p class="text-xs text-[#7a7a7a] mt-1">Catatan lengkap seluruh aktivitas pengguna, perubahan data, login, dan histori keamanan.</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-3xl border border-[#e0e0e0] p-5 mb-6">
        <form method="GET" action="{{ route('audit-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <div>
                <label class="block font-semibold text-[#7a7a7a] uppercase mb-1">Cari Log</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari user, IP, atau deskripsi..." class="w-full h-10 px-4 bg-[#f5f5f7] border border-[#e0e0e0] rounded-full focus:ring-2 focus:ring-[#0066cc]">
            </div>

            <div>
                <label class="block font-semibold text-[#7a7a7a] uppercase mb-1">Aksi</label>
                <select name="action" onchange="this.form.submit()" class="w-full h-10 px-3 bg-[#f5f5f7] border border-[#e0e0e0] rounded-full focus:ring-2 focus:ring-[#0066cc]">
                    <option value="">Semua Aksi</option>
                    <option value="LOGIN" {{ ($filters['action'] ?? '') == 'LOGIN' ? 'selected' : '' }}>LOGIN</option>
                    <option value="LOGOUT" {{ ($filters['action'] ?? '') == 'LOGOUT' ? 'selected' : '' }}>LOGOUT</option>
                    <option value="CREATE" {{ ($filters['action'] ?? '') == 'CREATE' ? 'selected' : '' }}>CREATE</option>
                    <option value="UPDATE" {{ ($filters['action'] ?? '') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                    <option value="DELETE" {{ ($filters['action'] ?? '') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                    <option value="RESET_PASSWORD" {{ ($filters['action'] ?? '') == 'RESET_PASSWORD' ? 'selected' : '' }}>RESET_PASSWORD</option>
                </select>
            </div>

            <div>
                <label class="block font-semibold text-[#7a7a7a] uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full h-10 px-3 bg-[#f5f5f7] border border-[#e0e0e0] rounded-full focus:ring-2 focus:ring-[#0066cc]">
            </div>

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="cool" class="h-10 px-5">Filter</x-button>
                <x-button href="{{ route('audit-logs.index') }}" variant="outline" class="h-10 px-4">Reset</x-button>
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] text-[11px] font-semibold text-[#7a7a7a] uppercase tracking-wider">
                        <th class="py-3.5 px-4 w-44">TANGGAL & WAKTU</th>
                        <th class="py-3.5 px-4">USER</th>
                        <th class="py-3.5 px-4">AKSI</th>
                        <th class="py-3.5 px-4">DESKRIPSI AKTIVITAS</th>
                        <th class="py-3.5 px-4">IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-[#f5f5f7]/60 transition-colors">
                            <td class="py-3.5 px-4 font-mono text-[#7a7a7a]">
                                {{ $log->created_at?->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#1d1d1f]">
                                {{ $log->user_name ?? 'System' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    {{ $log->action === 'CREATE' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    {{ $log->action === 'UPDATE' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $log->action === 'DELETE' ? 'bg-red-50 text-red-700 border border-red-200' : '' }}
                                    {{ $log->action === 'LOGIN' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $log->action === 'LOGOUT' ? 'bg-gray-100 text-gray-700' : '' }}
                                    {{ $log->action === 'RESET_PASSWORD' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-[#1d1d1f]">
                                {{ $log->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[#7a7a7a]">
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[#7a7a7a]">
                                Belum ada log aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-[#e0e0e0] bg-[#fafafc]">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
