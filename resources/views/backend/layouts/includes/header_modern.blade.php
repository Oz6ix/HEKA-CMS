<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-10">
    
    <!-- Left: Mobile Toggle & Breadcrumbs -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
            <i class="fas fa-bars text-xl"></i>
        </button>
        
        <nav class="hidden sm:flex text-sm font-medium text-slate-500">
             <span class="hover:text-slate-800 cursor-pointer">Admin</span>
             <span class="mx-2 text-slate-300">/</span>
             <span class="text-slate-800">{{ $page_title ?? 'Dashboard' }}</span>
        </nav>
    </div>

    <!-- Right: Actions & Profile -->
    <div class="flex items-center gap-4">
        
        <!-- Search (Hidden on mobile) -->
        <div class="hidden md:flex items-center relative">
            <i class="fas fa-search absolute left-3 text-slate-400 text-sm"></i>
            <input type="text" placeholder="Search..." class="pl-9 pr-4 py-2 bg-slate-50 border-none rounded-full text-sm focus:ring-2 focus:ring-primary-500 focus:bg-white transition-all w-64 placeholder-slate-400">
        </div>

        <!-- Notifications -->
        <button class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
            <div class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></div>
            <i class="far fa-bell text-xl"></i>
        </button>

        <!-- Profile Dropdown -->
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="flex items-center gap-3 hover:bg-slate-50 p-1.5 pr-3 rounded-full transition-colors border border-transparent hover:border-slate-100">
                <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold text-sm">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="hidden md:block text-left">
                    <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name ?? 'Admin' }}</div>
                </div>
                <i class="fas fa-chevron-down text-xs text-slate-400"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 z-50">
                
                <div class="px-4 py-3 border-b border-slate-50">
                    <p class="text-sm text-slate-500">Signed in as</p>
                    <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-user-circle mr-2 text-slate-400"></i> My Profile
                </a>
                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                    <i class="fas fa-cog mr-2 text-slate-400"></i> Settings
                </a>
                
                <div class="border-t border-slate-50 mt-1 pt-1">
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>
