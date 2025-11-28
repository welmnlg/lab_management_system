<!-- Mobile Sidebar Overlay -->
<div id="mobileSidebarOverlay" class="mobile-sidebar-overlay"></div>

<!-- Mobile Sidebar -->
<div id="mobileSidebar" class="mobile-sidebar lg:hidden">
    <div class="h-full flex flex-col mobile-sidebar-content">
        <!-- Mobile Sidebar Header -->
        <div class="bg-gradient-to-r from-blue-900 to-red-700 px-5 py-5 flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 mr-3 relative">
                    <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">ITLG LAB</h1>
                    <p class="text-xs text-blue-200 opacity-90">Management System</p>
                </div>
            </div>
            <button id="closeMobileSidebar" class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
        
        <!-- Mobile Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                      {{ request()->routeIs('dashboard') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span class="font-medium whitespace-nowrap text-base">BERANDA</span>
            </a>

            <a href="{{ route('scanqr') }}" 
               class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                      {{ request()->routeIs('scanqr') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                </svg>
                <span class="font-medium whitespace-nowrap text-base">SCAN QR</span>
            </a>

            <a href="{{ route('logbook') }}" 
               class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                      {{ request()->routeIs('logbook') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap text-base">LOGBOOK</span>
            </a>

            <a href="{{ route('ambil-jadwal') }}" 
               class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                      {{ request()->routeIs('ambil-jadwal') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap text-base">AMBIL JADWAL</span>
            </a>

            <a href="{{ route('profil') }}" 
               class="flex items-center space-x-4 mobile-nav-item rounded-xl transition-all duration-200
                      {{ request()->routeIs('profil') ? 'mobile-nav-active' : 'text-gray-700 hover:bg-gray-100 active:bg-gray-200' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap text-base">PROFIL</span>
            </a>
        </nav>
        
        <!-- Mobile User Info -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center space-x-3 bg-gray-50 rounded-xl p-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-600 to-red-600 flex items-center justify-center shadow-sm">
                    <span class="text-white font-semibold text-base">
                        {{ collect(explode(' ', Auth::user()->name))->map(fn($n) => strtoupper(substr($n,0,1)))->join('') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <button id="closeMobileSidebar" class="text-white p-2 rounded-full hover:bg-white hover:bg-opacity-10 transition-colors duration-200">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>
    </div>
</div>

<!-- Navbar (Fixed untuk semua device) -->
<div class="bg-white shadow-sm border-b border-gray-200 px-4 lg:px-6 py-4 navbar-fixed">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <!-- Mobile Menu Button -->
            <button id="openMobileSidebar" class="lg:hidden mr-3 p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors duration-200">
                <i class="bi bi-list text-2xl text-gray-700"></i>
            </button>
            
            <div class="w-8 h-8 md:w-10 md:h-10 mr-2 md:mr-3 relative">
                <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-sm md:text-xl font-bold text-blue-900">ITLG LAB MANAGEMENT SYSTEM</h1>
        </div>

        <div class="flex items-center space-x-4">
            <a href="{{ route('notifikasi') }}" 
               class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 active:bg-gray-100 transition-colors duration-200 relative
                      {{ request()->routeIs('notifikasi') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white border-transparent' : 'text-gray-600' }}"
               id="notification-link">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                    </path>
                </svg>
                <!-- Badge Counter -->
                <span id="notification-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
            </a>
            
            <!-- User Info -->
            <div class="hidden lg:flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                    <span class="text-white text-sm font-semibold">
                        {{ collect(explode(' ', Auth::user()->name))->map(fn($n) => strtoupper(substr($n,0,1)))->join('') }}
                    </span>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Sidebar (Fixed Position) -->
<div class="hidden lg:block sidebar-desktop-fixed sidebar-desktop">
    <div class="bg-white shadow-sm border-r border-gray-200 h-full overflow-y-auto">
        <nav class="p-4 space-y-2">                
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                      {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span class="font-medium whitespace-nowrap">BERANDA</span>
            </a>

            <a href="{{ route('scanqr') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg
                      {{ request()->routeIs('scanqr') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                </svg>
                <span class="font-medium whitespace-nowrap">SCAN QR</span>
            </a>

            <a href="{{ route('logbook') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg
                      {{ request()->routeIs('logbook') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap">LOGBOOK</span>
            </a>

            <a href="{{ route('ambil-jadwal') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg
                      {{ request()->routeIs('ambil-jadwal') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap">AMBIL JADWAL</span>
            </a>

            <a href="{{ route('profil') }}" 
               class="flex items-center space-x-3 px-4 py-3 rounded-lg
                      {{ request()->routeIs('profil') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium whitespace-nowrap">PROFIL</span>
            </a>
        </nav>
    </div>
</div>