@extends('layouts.main')

@section('title', 'Logbook')

@section('content')

    {{-- Header Halaman Logbook --}}
    <div class="flex flex-col mb-6 space-y-4">
        {{-- Top Row: Title and Main Actions --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-900">Logbook</h2>
            
            <div class="flex flex-wrap gap-2">
                {{-- Filter Period Buttons --}}
                <div class="flex bg-gray-100 p-1 rounded-lg">
                    <button onclick="setPeriod('day')" id="btn-day" class="px-3 py-1.5 text-sm font-medium rounded-md bg-white shadow-sm text-gray-900 transition">Hari Ini</button>
                    <button onclick="setPeriod('week')" id="btn-week" class="px-3 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition">Minggu Ini</button>
                    <button onclick="setPeriod('month')" id="btn-month" class="px-3 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition">Bulan Ini</button>
                </div>

                {{-- Download Button --}}
                <button onclick="downloadLogbook()" class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline">Download Excel</span>
                    <span class="sm:hidden">Excel</span>
                </button>
            </div>
        </div>

        {{-- Second Row: Search and Filter Toggle --}}
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Search Bar --}}
            <div class="relative flex-1">
                <input type="text" id="searchInput" placeholder="Cari Nama atau NIM..."
                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            {{-- Filter Toggle Button --}}
            <button onclick="toggleFilters()" id="filterToggleBtn" class="flex items-center justify-center space-x-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 bg-white transition whitespace-nowrap">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                </svg>
                <span>Filter Lanjutan</span>
                <svg id="filterChevron" class="w-4 h-4 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        {{-- Advanced Filters (Collapsible) --}}
        <div id="filterSection" class="hidden overflow-hidden transition-all duration-300">
            <div class="bg-white p-6 rounded-lg border-2 border-blue-100 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Filter Lanjutan
                    </h3>
                    <button onclick="clearFilters()" class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset Filter
                    </button>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Mata Kuliah</label>
                        <select id="filterCourse" class="w-full px-3 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 transition shadow-sm">
                            <option value="">Semua</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select id="filterClass" class="w-full px-3 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 transition shadow-sm">
                            <option value="">Semua</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Ruangan</label>
                        <select id="filterRoom" class="w-full px-3 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 transition shadow-sm">
                            <option value="">Semua</option>
                        </select>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="filterStatus" class="w-full px-3 py-2.5 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:bg-gray-50 transition shadow-sm">
                            <option value="">Semua</option>
                            <option value="AKTIF">AKTIF</option>
                            <option value="SELESAI">SELESAI</option>
                            <option value="GANTI RUANGAN">GANTI RUANGAN</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Logbook --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200" style="min-width: 1200px;">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIM</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Kuliah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Log Off</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="logbookTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        {{-- Standard Pagination Controls --}}
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <span class="text-sm text-gray-700">
                Menampilkan <span id="pageStart" class="font-medium">0</span> sampai <span id="pageEnd" class="font-medium">0</span> dari <span id="pageTotal" class="font-medium">0</span> data
            </span>
            <div id="paginationLinks" class="flex gap-1">
                {{-- Pagination links will be injected here --}}
            </div>
        </div>
    </div>

    <script>
        let currentPeriod = 'day';
        let currentPage = 1;
        let searchTimeout = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadFilterOptions();
            loadLogbookData();

            // Search Listener
            document.getElementById('searchInput').addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentPage = 1;
                    loadLogbookData();
                }, 500);
            });

            // Filter Listeners
            const filters = ['filterCourse', 'filterClass', 'filterRoom', 'filterStatus'];
            filters.forEach(id => {
                document.getElementById(id).addEventListener('change', function() {
                    currentPage = 1;
                    loadLogbookData();
                });
            });
        });

        function toggleFilters() {
            const section = document.getElementById('filterSection');
            const chevron = document.getElementById('filterChevron');
            section.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');
        }

        async function loadFilterOptions() {
            try {
                const response = await fetch('/api/logbook/filters');
                const result = await response.json();
                
                if(result.success) {
                    populateDropdown('filterCourse', result.courses, 'Semua');
                    populateDropdown('filterClass', result.classes, 'Semua');
                    populateDropdown('filterRoom', result.rooms, 'Semua');
                }
            } catch(e) {
                console.error("Failed to load filters", e);
            }
        }

        function populateDropdown(id, items, defaultText) {
            const select = document.getElementById(id);
            select.innerHTML = `<option value="">${defaultText}</option>`;
            items.forEach(item => {
                select.innerHTML += `<option value="${item}">${item}</option>`;
            });
        }

        function clearFilters() {
            document.getElementById('filterCourse').value = '';
            document.getElementById('filterClass').value = '';
            document.getElementById('filterRoom').value = '';
            document.getElementById('filterStatus').value = '';
            currentPage = 1;
            loadLogbookData();
        }

        function setPeriod(period) {
            currentPeriod = period;
            currentPage = 1;
            
            ['day', 'week', 'month'].forEach(p => {
                const btn = document.getElementById(`btn-${p}`);
                if (p === period) {
                    btn.className = "px-3 py-1.5 text-sm font-medium rounded-md bg-white shadow-sm text-gray-900 transition";
                } else {
                    btn.className = "px-3 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-900 transition";
                }
            });

            loadLogbookData();
        }

        function goToPage(page) {
            currentPage = page;
            loadLogbookData();
        }

        async function loadLogbookData() {
            const tbody = document.getElementById('logbookTableBody');
            
            // Get Values
            const search = document.getElementById('searchInput').value;
            const course = document.getElementById('filterCourse').value;
            const className = document.getElementById('filterClass').value;
            const room = document.getElementById('filterRoom').value;
            const status = document.getElementById('filterStatus').value;

            // Build Query
            const params = new URLSearchParams({
                page: currentPage,
                period: currentPeriod,
                search: search, // Consolidated search
                course: course,
                class: className,
                room: room,
                status: status
            });

            tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-gray-500">Memuat data...</td></tr>`;

            try {
                const response = await fetch(`/api/logbook/data?${params.toString()}`);
                const result = await response.json();

                if (result.success) {
                    renderTable(result.data);
                    renderPagination(result.pagination);
                } else {
                    tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-red-500">Gagal memuat data</td></tr>`;
                }
            } catch (error) {
                console.error('Error:', error);
                tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-red-500">Terjadi kesalahan sistem</td></tr>`;
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('logbookTableBody');
            
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-gray-500">Tidak ada data logbook</td></tr>`;
                return;
            }

            tbody.innerHTML = data.map(item => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.date}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.nim}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.course}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.class}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.room}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.schedule}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">${item.login}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">${item.logout}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            ${item.activity}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(item.status)}">
                            ${item.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(pagination) {
            const start = (pagination.current_page - 1) * pagination.per_page + 1;
            const end = Math.min(pagination.current_page * pagination.per_page, pagination.total);
            
            document.getElementById('pageStart').innerText = pagination.total === 0 ? 0 : start;
            document.getElementById('pageEnd').innerText = end;
            document.getElementById('pageTotal').innerText = pagination.total;

            const container = document.getElementById('paginationLinks');
            let html = '';

            // Previous Button
            html += `<button onclick="goToPage(${pagination.current_page - 1})" ${pagination.prev_page_url ? '' : 'disabled'} 
                class="px-3 py-1 border rounded-md text-sm ${pagination.prev_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'}">Previous</button>`;

            // Page Numbers
            pagination.links.forEach(link => {
                if (link.label === '&laquo; Previous' || link.label === 'Next &raquo;') return;
                
                const activeClass = link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 hover:bg-gray-50';
                
                if (link.url) {
                    // Extract page number from URL
                    const pageNum = new URL(link.url).searchParams.get('page');
                    html += `<button onclick="goToPage(${pageNum})" class="px-3 py-1 border rounded-md text-sm ${activeClass}">${link.label}</button>`;
                } else {
                    html += `<span class="px-3 py-1 border rounded-md text-sm bg-white text-gray-700">...</span>`;
                }
            });

            // Next Button
            html += `<button onclick="goToPage(${pagination.current_page + 1})" ${pagination.next_page_url ? '' : 'disabled'} 
                class="px-3 py-1 border rounded-md text-sm ${pagination.next_page_url ? 'hover:bg-gray-50' : 'opacity-50 cursor-not-allowed'}">Next</button>`;

            container.innerHTML = html;
        }

        function getStatusColor(status) {
            if (status === 'AKTIF') return 'bg-green-100 text-green-800 animate-pulse';
            if (status === 'SELESAI') return 'bg-gray-100 text-gray-800';
            if (status === 'GANTI RUANGAN') return 'bg-yellow-100 text-yellow-800';
            return 'bg-gray-100 text-gray-800';
        }

        function downloadLogbook() {
            const search = document.getElementById('searchInput').value;
            const course = document.getElementById('filterCourse').value;
            const className = document.getElementById('filterClass').value;
            const room = document.getElementById('filterRoom').value;
            const status = document.getElementById('filterStatus').value;

            const params = new URLSearchParams({
                period: currentPeriod,
                search: search,
                course: course,
                class: className,
                room: room,
                status: status
            });

            window.location.href = `/logbook/export?${params.toString()}`;
        }
    </script>

@endsection