<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Takoyaki Mini House | Professional POS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-item-active { background: #ea580c; color: white; box-shadow: 0 10px 15px -3px rgba(234, 88, 12, 0.3); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        #mainSidebar { transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), width 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .sidebar-collapsed #mainSidebar { width: 0; transform: translateX(-100%); }
        .sidebar-collapsed main { margin-left: 0; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased overflow-hidden">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside id="mainSidebar" class="w-60 bg-slate-900 flex flex-col z-50 shrink-0 relative overflow-hidden">
            <div class="p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-600 rounded-xl flex items-center justify-center shadow-lg shadow-orange-900/50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"></path><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"></path><path d="M2 7h20"></path></svg>
                    </div>
                    <div>
                        <h1 class="text-white font-extrabold tracking-tight leading-tight text-sm">Takoyaki</h1>
                        <p class="text-orange-500 font-bold text-[8px] uppercase tracking-[0.2em]">Mini House</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 py-4">
                @if(auth()->user()->isAdmin())
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('dashboard') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    <span>Dashboard</span>
                </a>
                @endif
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('pos.*') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span>Terminal</span>
                </a>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('inventory.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('inventory.*') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.27 6.96 8.73 5.04 8.73-5.04"/><path d="M12 22.08V12"/></svg>
                    <span>Inventory</span>
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('categories.*') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v10c0 1.1.9 2 2 2Z"/></svg>
                    <span>Categories</span>
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('users.*') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="19" cy="11" r="3"/></svg>
                    <span>Staff</span>
                </a>
                <a href="{{ route('reports.index') }}" class="flex items-center gap-3.5 px-4 py-2.5 rounded-xl transition-all font-semibold text-xs {{ request()->routeIs('reports.*') ? 'sidebar-item-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>History</span>
                </a>
                @endif
            </nav>

            <div class="p-6 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-xl bg-red-600/10 text-red-500 hover:bg-red-600 hover:text-white transition-all font-bold text-[10px] uppercase tracking-widest">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col h-full bg-slate-50 overflow-hidden relative transition-all duration-400">
            <!-- Top Header -->
            <header class="h-14 flex items-center justify-between px-6 border-b border-slate-200 bg-white shadow-sm z-40">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="p-2 hover:bg-slate-100 rounded-lg text-slate-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                    <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">
                        @yield('header_title', 'Takoyaki Mini House')
                    </h2>
                </div>
                <div class="flex items-center gap-6">
                    <div id="liveClock" class="text-[10px] font-black text-slate-400 bg-slate-50 px-3 py-1 rounded-full uppercase tracking-tighter"></div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-6 scrollbar-hide">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-collapsed');
            // Save state to localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        }

        // Restore state on load
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }

        function updateClock() {
            const now = new Date();
            const clock = document.getElementById('liveClock');
            if (clock) clock.innerText = now.toLocaleString('en-US', { 
                weekday: 'short', month: 'short', day: 'numeric', 
                hour: '2-digit', minute: '2-digit', second: '2-digit' 
            });
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
    @stack('scripts')
</body>
</html>
