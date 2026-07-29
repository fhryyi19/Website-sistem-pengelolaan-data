<header class="mb-5 flex h-14 items-center justify-between rounded-xl border border-slate-100 bg-white px-3 shadow-sm sm:px-4">
    <div class="flex min-w-0 items-center gap-3">
        <button id="sidebar-toggle" @click="sidebarOpen = !sidebarOpen" class="grid h-9 w-9 shrink-0 place-items-center rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Buka menu">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <label class="hidden w-72 items-center gap-2 rounded-lg bg-slate-50 px-3 text-slate-400 sm:flex">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.1-5.4a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z"/></svg>
            <input id="global-search" type="search" placeholder="Cari data pegawai..." class="w-full border-0 bg-transparent p-0 text-xs text-slate-700 placeholder:text-slate-400 focus:ring-0">
        </label>
    </div>
    <div class="flex items-center gap-1.5 sm:gap-2" x-data="{ open: false }">
        <button class="grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100" aria-label="Notifikasi">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9"/></svg>
        </button>
        <div class="relative">
            <button id="profile-menu" @click="open = !open" class="flex items-center gap-2 rounded-lg p-1.5 hover:bg-slate-100" aria-label="Menu profil">
                <span class="hidden max-w-36 truncate text-xs font-bold text-slate-700 sm:block">{{ auth()->user()?->name }}</span>
                <span class="grid h-7 w-7 place-items-center rounded-full bg-blue-600 text-[10px] font-bold text-white">{{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}</span>
            </button>
            <div x-cloak x-show="open" @click.outside="open = false" x-transition class="absolute right-0 z-50 mt-2 w-44 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg">
                <a href="{{ route('profile.edit') }}" class="block rounded-lg px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">Profil</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-xs font-medium text-red-600 hover:bg-red-50">Keluar</button></form>
            </div>
        </div>
    </div>
</header>
