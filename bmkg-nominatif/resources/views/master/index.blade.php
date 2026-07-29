<x-app-layout>
    <x-slot name="title">Master Data Referensi</x-slot>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1d1d1f] tracking-tight apple-tight">Master Data Referensi</h1>
        <p class="text-xs text-[#7a7a7a] mt-1">Daftar tabel acuan referensi sistem kepegawaian BMKG Klas I Bandung.</p>
    </div>

    <div x-data="{ activeMaster: 'unit' }" class="space-y-6">

        <!-- Tabs -->
        <div class="flex items-center gap-2 bg-white p-1.5 rounded-full border border-[#e0e0e0] overflow-x-auto">
            <button @click="activeMaster = 'unit'" :class="activeMaster === 'unit' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'" class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">Unit Kerja ({{ $workUnits->count() }})</button>
            <button @click="activeMaster = 'jabatan'" :class="activeMaster === 'jabatan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'" class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">Jabatan ({{ $positions->count() }})</button>
            <button @click="activeMaster = 'golongan'" :class="activeMaster === 'golongan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'" class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">Pangkat / Golongan ({{ $ranks->count() }})</button>
            <button @click="activeMaster = 'pendidikan'" :class="activeMaster === 'pendidikan' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'" class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">Pendidikan ({{ $educations->count() }})</button>
            <button @click="activeMaster = 'status'" :class="activeMaster === 'status' ? 'bg-[#0066cc] text-white shadow-sm' : 'text-[#1d1d1f] hover:bg-gray-100'" class="px-5 py-2 rounded-full text-xs font-medium transition-all whitespace-nowrap">Status Kepegawaian ({{ $employmentStatuses->count() }})</button>
        </div>

        <!-- TAB 1: UNIT KERJA -->
        <div x-show="activeMaster === 'unit'" class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] font-semibold text-[#7a7a7a] uppercase">
                        <th class="py-3.5 px-4 w-28">KODE</th>
                        <th class="py-3.5 px-4">NAMA UNIT KERJA</th>
                        <th class="py-3.5 px-4">DESKRIPSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($workUnits as $wu)
                        <tr class="hover:bg-[#f5f5f7]/60">
                            <td class="py-3.5 px-4 font-mono text-[#0066cc] font-semibold">{{ $wu->code }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#1d1d1f]">{{ $wu->name }}</td>
                            <td class="py-3.5 px-4 text-[#7a7a7a]">{{ $wu->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TAB 2: JABATAN -->
        <div x-show="activeMaster === 'jabatan'" class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] font-semibold text-[#7a7a7a] uppercase">
                        <th class="py-3.5 px-4 w-28">KODE</th>
                        <th class="py-3.5 px-4">NAMA JABATAN</th>
                        <th class="py-3.5 px-4">JENIS / TINGKATAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($positions as $p)
                        <tr class="hover:bg-[#f5f5f7]/60">
                            <td class="py-3.5 px-4 font-mono text-[#0066cc] font-semibold">{{ $p->code }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#1d1d1f]">{{ $p->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] bg-gray-100 text-gray-700 font-medium">
                                    {{ $p->level ?? 'Umum' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TAB 3: GOLONGAN -->
        <div x-show="activeMaster === 'golongan'" class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] font-semibold text-[#7a7a7a] uppercase">
                        <th class="py-3.5 px-4 w-28">GOLONGAN</th>
                        <th class="py-3.5 px-4">NAMA PANGKAT</th>
                        <th class="py-3.5 px-4">KELOMPOK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($ranks as $r)
                        <tr class="hover:bg-[#f5f5f7]/60">
                            <td class="py-3.5 px-4 font-mono text-[#0066cc] font-bold">{{ $r->code }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#1d1d1f]">{{ $r->name }}</td>
                            <td class="py-3.5 px-4 text-[#7a7a7a]">Golongan {{ $r->group }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TAB 4: PENDIDIKAN -->
        <div x-show="activeMaster === 'pendidikan'" class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] font-semibold text-[#7a7a7a] uppercase">
                        <th class="py-3.5 px-4 w-28">KODE</th>
                        <th class="py-3.5 px-4">TINGKAT PENDIDIKAN</th>
                        <th class="py-3.5 px-4">LEVEL NUMERIK</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($educations as $e)
                        <tr class="hover:bg-[#f5f5f7]/60">
                            <td class="py-3.5 px-4 font-mono text-[#0066cc] font-semibold">{{ $e->code }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#1d1d1f]">{{ $e->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-[#7a7a7a]">Level {{ $e->level }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TAB 5: STATUS KEPEGAWAIAN -->
        <div x-show="activeMaster === 'status'" class="bg-white rounded-3xl border border-[#e0e0e0] overflow-hidden shadow-sm">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#fafafc] border-b border-[#e0e0e0] font-semibold text-[#7a7a7a] uppercase">
                        <th class="py-3.5 px-4 w-28">KODE</th>
                        <th class="py-3.5 px-4">NAMA STATUS KEPEGAWAIAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($employmentStatuses as $es)
                        <tr class="hover:bg-[#f5f5f7]/60">
                            <td class="py-3.5 px-4 font-mono text-[#0066cc] font-bold">{{ $es->code }}</td>
                            <td class="py-3.5 px-4 font-medium text-[#1d1d1f]">{{ $es->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
