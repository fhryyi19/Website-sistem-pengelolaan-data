<aside class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col border-r border-slate-100 bg-white dark:bg-white dark:border-zinc-100 transition-all duration-200 lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="flex h-16 items-center gap-2.5 border-b border-slate-100 dark:border-slate-100 px-5">
        <img src="{{ asset('images/Logo BMKG.png') }}" alt="Logo BMKG" class="h-8 w-8 object-contain transition-transform duration-300 hover:rotate-12">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-tight text-slate-900 dark:text-slate-900">
            BMKG KLAS 1 BANDUNG
        </a>
    </div>

    <div class="flex-1 flex flex-col justify-between overflow-y-auto px-3 py-4">
        <div class="space-y-6">
            <div>
                <p class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Menu utama</p>
                <nav class="space-y-1">
                    @php
                        $links = [
                            [
                                'route' => 'dashboard', 
                                'label' => 'Dashboard', 
                                'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>'
                            ],
                            [
                                'route' => 'employees.index', 
                                'label' => 'Data Pegawai', 
                                'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 8.625 21c-2.14 0-4.14-.588-5.85-1.612v-.009a4.25 4.25 0 0 1 3.567-3.619 4.316 4.316 0 0 1 8.625.372a9.324 9.324 0 0 0 2.625-.372M12 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6-1.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>'
                            ],
                            [
                                'route' => 'master.index', 
                                'label' => 'Master Data', 
                                'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18m-18 0v-5.25A2.25 2.25 0 0 1 4.25 6h15.5A2.25 2.25 0 0 1 22 8.25v5.25m-18 0V18A2.25 2.25 0 0 0 6.25 20.25h11.5A2.25 2.25 0 0 0 20.25 18v-4.5m-14.25 0h12.5"/></svg>'
                            ],
                        ];
                    @endphp
                    @foreach ($links as $link)
                        @php
                            $active = request()->routeIs($link['route']) || request()->routeIs(explode('.', $link['route'])[0] . '.*');
                        @endphp
                        <a href="{{ route($link['route']) }}" 
                           class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $active ? 'bg-blue-50 text-blue-600 dark:bg-blue-50 dark:text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-600 dark:hover:bg-slate-50 dark:hover:text-slate-900' }}">
                            <span class="shrink-0 transition-transform duration-150 group-hover:scale-110 {{ $active ? 'text-blue-600 dark:text-blue-600' : 'text-slate-400 dark:text-slate-400' }}">
                                {!! $link['icon'] !!}
                            </span>
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>
            </div>

            @if (auth()->user()?->isAdmin())
                <div>
                    <div class="flex items-center justify-between px-3 pb-2">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Administrasi</p>
                        <span class="rounded bg-blue-50 dark:bg-blue-50 px-1.5 py-0.5 text-[9px] font-bold text-blue-600 dark:text-blue-600">ADMIN</span>
                    </div>
                    <nav class="space-y-1">
                        @php
                            $adminLinks = [
                                ['route' => 'users.index', 'label' => 'User', 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>'],
                                ['route' => 'roles.index', 'label' => 'Role', 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>'],
                                ['route' => 'audit-logs.index', 'label' => 'Log aktivitas', 'icon' => '<svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>'],
                            ];
                        @endphp
                        @foreach ($adminLinks as $link)
                            @php
                                $active = request()->routeIs($link['route']) || request()->routeIs(explode('.', $link['route'])[0] . '.*');
                            @endphp
                            <a href="{{ route($link['route']) }}" 
                               class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-xs font-semibold transition-all duration-150 {{ $active ? 'bg-blue-50 text-blue-600 dark:bg-blue-50 dark:text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-600 dark:hover:bg-slate-50 dark:hover:text-slate-900' }}">
                                <span class="shrink-0 transition-transform duration-150 group-hover:scale-110 {{ $active ? 'text-blue-600 dark:text-blue-600' : 'text-slate-400 dark:text-slate-400' }}">
                                    {!! $link['icon'] !!}
                                </span>
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endif
        </div>

        <div class="border-t border-slate-100 dark:border-slate-100 pt-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" 
                        class="flex w-full items-center gap-3 rounded-lg p-2.5 hover:bg-slate-50 dark:hover:bg-slate-50 transition text-left focus:outline-none">
                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-blue-600 text-[10px] font-bold text-white shadow-sm shadow-blue-500/20">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-xs font-bold text-slate-800 dark:text-slate-800">{{ auth()->user()?->name }}</span>
                        <span class="block text-[10px] text-slate-400 dark:text-slate-400">Pengguna sistem</span>
                    </span>
                    <svg class="h-4 w-4 text-slate-400 dark:text-slate-400 shrink-0 transition-transform duration-200" 
                         :class="open ? 'rotate-180' : ''" 
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>
                </button>

                <div x-show="open" 
                     @click.outside="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full left-0 z-50 mb-2 w-full rounded-xl border border-slate-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-1 shadow-lg" 
                     style="display: none;">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800">
                        <svg class="h-4 w-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                        Edit Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/20 text-left">
                            <svg class="h-4 w-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>
<div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-30 bg-slate-900/30 lg:hidden"></div>
