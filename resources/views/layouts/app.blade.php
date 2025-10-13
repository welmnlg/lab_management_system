<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ITLG Lab Management System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen relative">

    <div class="bg-white shadow-sm border-b border-gray-200 px-4 lg:px-6 py-4 sticky top-0 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-8 h-8 md:w-10 md:h-10 mr-2 md:mr-3 relative">
                    <img src="{{ asset('images/logo ITLG.png') }}" alt="ITLG Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-sm md:text-xl font-bold text-blue-900">ITLG LAB MANAGEMENT SYSTEM</h1>
            </div>

            <div class="flex items-center space-x-4">
                <button class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>
                <div class="hidden lg:flex items-center space-x-2 bg-gray-50 rounded-lg px-3 py-2">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center">
                        <span class="text-white text-sm font-semibold">AH</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Aulia Halimatusyaddiah</span>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row min-h-screen">
        <div class="hidden lg:block w-64 bg-white shadow-sm border-r border-gray-200 min-h-screen">
            <nav class="p-4 space-y-2">

                <a href="{{ route('dashboard') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg 
                  {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">BERANDA</span>
                </a>

                <a href="{{ route('scanqr') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg
                  {{ request()->routeIs('scanqr') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">SCAN QR</span>
                </a>

                <a href="{{ route('logbook') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg
                  {{ request()->routeIs('logbook') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                        <path fill-rule="evenodd"
                            d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">LOGBOOK</span>
                </a>

                <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium whitespace-nowrap">AMBIL JADWAL</span>
                </a>

               <a href="{{ route('profile.edit') }}" 
   class="flex items-center space-x-3 px-4 py-3 rounded-lg
          {{ request()->routeIs('profile.edit') ? 'bg-gradient-to-r from-blue-900 to-red-700 text-white' : 'text-gray-700 hover:bg-gray-100' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
    </svg>
    <span class="font-medium whitespace-nowrap">PROFIL</span>
</a>
            </nav>
        </div>

        <main class="flex-1 pb-24 lg:pb-6 overflow-x-hidden">
            <div class="p-4 md:p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 bg-gradient-to-r from-blue-900 to-red-700 border-t border-gray-200 z-50">
        <nav class="flex justify-around py-2">
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <span class="text-xs font-medium mt-1">Beranda</span>
            </a>
            <a href="{{ route('scanqr') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs('scanqr') ? 'text-white' : 'text-white/70' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6zM7 7H5V5h2zM19 7h-2V5h2zM7 19H5v-2h2zM19 19h-2v-2h2z" />
                </svg>
                <span class="text-xs font-medium mt-1">Scan QR</span>
            </a>
            <a href="{{ route('logbook') }}"
                class="flex flex-col items-center p-2 {{ request()->routeIs('logbook') ? 'text-white' : 'text-white/70' }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-xs font-medium mt-1">Logbook</span>
            </a>
            <a href="#" class="flex flex-col items-center p-2 text-white/70 hover:text-white">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-medium mt-1">Ambil Jadwal</span>
            </a>
              <a href="{{ route('profile.edit') }}" class="flex flex-col items-center p-2 {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-white/70' }}">
             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span class="text-xs font-medium mt-1">Profil</span>
        </a>
        </nav>
    </div>

    {{-- Ini adalah placeholder untuk script tambahan dari halaman anak --}}
    @stack('scripts')
</body>

</html>