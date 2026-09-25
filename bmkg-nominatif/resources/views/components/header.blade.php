<header class="mb-5 flex h-14 items-center justify-between rounded-xl border border-slate-100 bg-white px-3 shadow-sm sm:px-4">
    <div class="flex min-w-0 items-center gap-3">
        <button id="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>

        {{-- Global Search --}}
        <div class="relative hidden w-72 sm:block" x-data="globalSearch()" @click.outside="close()">
            <label class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 text-slate-400 border border-transparent focus-within:border-blue-300 focus-within:bg-white transition">
                <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/>
                </svg>
                <input
                    id="global-search"
                    type="search"
                    placeholder="Cari data pegawai..."
                    class="w-full border-0 bg-transparent py-2 p-0 text-xs text-slate-700 placeholder:text-slate-400 focus:ring-0"
                    autocomplete="off"
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @keydown.enter.prevent="submitSearch()"
                    @keydown.escape="close()"
                    @keydown.arrow-down.prevent="moveDown()"
                    @keydown.arrow-up.prevent="moveUp()"
                >
            </label>

            {{-- Dropdown Results --}}
            <div
                x-show="open"
                x-cloak
                class="absolute left-0 top-full z-50 mt-1 w-full rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden"
            >
                <template x-if="loading">
                    <div class="px-4 py-3 text-xs text-slate-400 text-center">Mencari...</div>
                </template>
                <template x-if="!loading && results.length === 0 && query.length >= 2">
                    <div class="px-4 py-3 text-xs text-slate-400 text-center">Tidak ada hasil ditemukan</div>
                </template>
                <template x-for="(item, index) in results" :key="item.id">
                    <a
                        :href="item.url"
                        class="flex items-center gap-3 px-4 py-2.5 text-xs hover:bg-blue-50 transition-colors"
                        :class="index === activeIndex ? 'bg-blue-50' : ''"
                        @mouseenter="activeIndex = index"
                    >
                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-700" x-text="item.name.charAt(0).toUpperCase()"></span>
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 truncate" x-text="item.name"></p>
                            <p class="text-[10px] text-slate-400" x-text="item.nip ? 'NIP: ' + item.nip : 'NIP belum diisi'"></p>
                        </div>
                        <svg class="ml-auto h-3.5 w-3.5 shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </template>
                <template x-if="!loading && results.length > 0">
                    <div class="border-t border-slate-100 px-4 py-2">
                        <a :href="allResultsUrl()" class="text-[10px] font-bold text-blue-600 hover:text-blue-700">
                            Lihat semua hasil →
                        </a>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div class="flex items-center gap-1.5 sm:gap-2">

        {{-- Bell Notification --}}
        <div class="relative" x-data="bellNotification()" x-init="loadNotifications()" @click.outside="open = false">
            <button
                @click="open = !open"
                class="relative grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 transition"
                aria-label="Notifikasi data tidak lengkap"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/>
                </svg>
                {{-- Badge --}}
                <span
                    x-show="total > 0"
                    x-cloak
                    class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white"
                    x-text="total > 9 ? '9+' : total"
                ></span>
            </button>

            {{-- Notification Dropdown --}}
            <div
                x-show="open"
                x-cloak
                x-transition
                class="absolute right-0 top-full z-50 mt-2 w-80 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden"
            >
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <h3 class="text-xs font-bold text-slate-800">Data Tidak Lengkap</h3>
                    <span x-show="total > 0" class="rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-600" x-text="total + ' pegawai'"></span>
                </div>
                <template x-if="loading">
                    <div class="px-4 py-4 text-xs text-slate-400 text-center">Memuat...</div>
                </template>
                <template x-if="!loading && total === 0">
                    <div class="px-4 py-5 text-center">
                        <div class="mx-auto mb-2 grid h-10 w-10 place-items-center rounded-full bg-emerald-50">
                            <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-700">Semua data lengkap</p>
                        <p class="mt-0.5 text-[10px] text-slate-400">Tidak ada pegawai dengan data kosong</p>
                    </div>
                </template>
                <template x-if="!loading && items.length > 0">
                    <div>
                        <div class="max-h-60 overflow-y-auto divide-y divide-slate-50">
                            <template x-for="item in items" :key="item.id">
                                <a :href="item.url" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-orange-100 text-[10px] font-bold text-orange-600">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate" x-text="item.name"></p>
                                        <p class="text-[10px] text-slate-400" x-text="item.nip"></p>
                                    </div>
                                    <svg class="ml-auto h-3.5 w-3.5 shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </template>
                        </div>
                        <template x-if="total > items.length">
                            <div class="border-t border-slate-100 px-4 py-2 text-center">
                                <p class="text-[10px] text-slate-400" x-text="'dan ' + (total - items.length) + ' pegawai lainnya...'"></p>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Profile --}}
        <div class="relative" x-data="{ open: false }">
            <button id="profile-menu" @click="open = !open" class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-slate-100" aria-label="Menu profil">
                <span class="hidden max-w-36 truncate text-xs font-bold text-slate-700 sm:block">{{ auth()->user()?->name }}</span>
                <img class="w-7 h-7 rounded-full object-cover border border-slate-200 shadow-sm" src="{{ auth()->user()?->avatar_url }}" alt="Avatar">
            </button>
            <div x-cloak x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-50 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-red-600 hover:bg-red-50">Keluar</button></form>
            </div>
        </div>
    </div>
</header>

<script>
function globalSearch() {
    return {
        query: '',
        results: [],
        loading: false,
        open: false,
        activeIndex: -1,
        _timer: null,

        search() {
            clearTimeout(this._timer);
            if (this.query.length < 2) {
                this.results = [];
                this.open = false;
                return;
            }
            this.loading = true;
            this.open = true;
            this._timer = setTimeout(() => {
                fetch('/api/employees/search?q=' + encodeURIComponent(this.query), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(r => r.json())
                .then(data => {
                    this.results = data;
                    this.activeIndex = -1;
                })
                .finally(() => { this.loading = false; });
            }, 300);
        },

        submitSearch() {
            if (this.activeIndex >= 0 && this.results[this.activeIndex]) {
                window.location.href = this.results[this.activeIndex].url;
                return;
            }
            if (this.results.length === 1) {
                window.location.href = this.results[0].url;
                return;
            }
            if (this.query.length >= 2) {
                window.location.href = '/employees?search=' + encodeURIComponent(this.query);
            }
        },

        moveDown() {
            if (this.activeIndex < this.results.length - 1) this.activeIndex++;
        },

        moveUp() {
            if (this.activeIndex > 0) this.activeIndex--;
        },

        allResultsUrl() {
            return '/employees?search=' + encodeURIComponent(this.query);
        },

        close() {
            this.open = false;
            this.activeIndex = -1;
        }
    };
}

function bellNotification() {
    return {
        open: false,
        loading: false,
        total: 0,
        items: [],

        loadNotifications() {
            this.loading = true;
            fetch('/api/employees/incomplete', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                this.total = data.total;
                this.items = data.items;
            })
            .finally(() => { this.loading = false; });
        }
    };
}
</script>
