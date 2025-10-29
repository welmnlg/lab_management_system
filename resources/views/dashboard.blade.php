@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    {{-- Denah dan Jadwal Ruangan --}}
    <div class="flex flex-col xl:flex-row gap-6">
        <div class="flex-1">
            {{-- Grid Denah Ruangan --}}
            <div class="grid grid-cols-2 gap-4 lg:gap-6 mb-6">
                {{-- Card Ruang Lab Jaringan 1 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 1</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab1', true)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab1-room" style="background-color: #22C55E;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 2 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 2</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab2', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab2-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 3 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 3</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab3', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab3-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>

                {{-- Card Ruang Lab Jaringan 4 --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 lg:py-3">
                        <h3 class="font-semibold text-sm lg:text-base">Ruang Lab Jaringan 4</h3>
                    </div>
                    <div class="p-3 lg:p-4">
                        <button onclick="showSchedule('lab4', false)"
                            class="w-full h-24 lg:h-32 border-4 border-black rounded-lg transition-all duration-300 hover:shadow-lg"
                            id="lab4-room" style="background-color: #9CA3AF;"></button>
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="bg-gradient-to-r from-gray-700 to-red-700 text-white text-center py-2 rounded-t-lg -mx-4 -mt-4 mb-4">
                    <h3 class="font-semibold">Keterangan:</h3>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-y-3 sm:gap-y-0 sm:gap-x-6">
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-green-500 rounded flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Ruangan Digunakan</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-4 h-4 bg-gray-400 rounded flex-shrink-0"></div>
                        <span class="text-xs sm:text-sm font-medium text-gray-700">Ruangan Kosong</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Penggunaan Ruangan --}}
        <div class="w-full xl:w-96">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="bg-blue-800 text-white text-center py-3 rounded-t-lg">
                    <h3 class="font-semibold text-sm lg:text-base" id="schedule-title">Jadwal Penggunaan Ruang Lab Jaringan 1</h3>
                </div>
                <div class="p-4 space-y-4 max-h-96 overflow-y-auto" id="schedule-content">
                    {{-- Konten jadwal akan diisi oleh JavaScript --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Kalender Mingguan --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
        <div class="p-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-y-4 sm:gap-y-0">
                <div class="flex flex-wrap gap-2">
                    <button onclick="selectLab('lab1')" class="px-4 py-2 rounded-full text-sm font-medium bg-blue-900 text-white" id="tab-lab1">Lab Jaringan 1</button>
                    <button onclick="selectLab('lab2')" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab2">Lab Jaringan 2</button>
                    <button onclick="selectLab('lab3')" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab3">Lab Jaringan 3</button>
                    <button onclick="selectLab('lab4')" class="px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400" id="tab-lab4">Lab Jaringan 4</button>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-4">
                    <button onclick="previousWeek()" class="p-2 rounded-full bg-blue-900 text-white hover:bg-blue-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <span class="text-sm font-medium text-gray-700" id="date-range">22 September 2025 - 26 September 2025</span>
                    <button onclick="nextWeek()" class="p-2 rounded-full bg-blue-900 text-white hover:bg-blue-800">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <button onclick="addClass()" class="px-3 py-2 sm:px-4 text-xs sm:text-sm bg-gradient-to-r from-blue-900 to-red-700 text-white rounded-lg font-medium hover:opacity-90">
                        + Kelas Ganti
                    </button>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-2 py-3 text-left text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200">Hari/Jam</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">SENIN</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">SELASA</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">RABU</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 min-w-32">KAMIS</th>
                        <th class="px-2 py-3 text-center text-xs sm:text-sm font-medium text-gray-700 min-w-32">JUMAT</th>
                    </tr>
                </thead>
                <tbody id="calendar-body">
                    {{-- Konten kalender akan diisi oleh JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>

    {{-- ### KODE MODAL YANG HILANG SEBELUMNYA, DITARUH DI SINI ### --}}
    <div id="kelas-ganti-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50 hidden p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl transform transition-all flex flex-col max-h-[90vh] overflow-hidden">
            <div class="bg-gradient-to-r from-blue-900 to-red-700 p-4 text-center flex-shrink-0">
                <h2 class="text-2xl font-bold text-white">Kelas Ganti</h2>
            </div>
            <div class="overflow-y-auto p-6 md:p-8">
                <form id="kelas-ganti-form" action="#" method="POST">
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="nama-lengkap" class="block text-sm font-medium text-gray-800 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama-lengkap" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            <div>
                                <label for="nim" class="block text-sm font-medium text-gray-800 mb-2">NIM</label>
                                <input type="text" name="nim" class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan NIM Anda">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="mata-kuliah" class="block text-sm font-medium text-gray-800 mb-2">Mata Kuliah</label>
                                <select name="mata-kuliah" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Mata Kuliah</option>
                                    <option value="kb">Praktikum Kecerdasan Buatan</option>
                                    <option value="di">Praktikum Desain Interaksi</option>
                                    <option value="pw">Praktikum Pemrograman Web</option>
                                    <option value="ws">Praktikum Web Semantik</option>
                                </select>
                            </div>
                            <div>
                                <label for="ruangan" class="block text-sm font-medium text-gray-800 mb-2">Ruangan yang Digunakan</label>
                                <select name="ruangan" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Ruangan</option>
                                    <option value="jaringan1">Lab Jaringan 1</option>
                                    <option value="jaringan2">Lab Jaringan 2</option>
                                    <option value="jaringan3">Lab Jaringan 3</option>
                                    <option value="jaringan4">Lab Jaringan 4</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-800 mb-2">Kelas</label>
                                <select name="kelas" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Kelas</option>
                                    <option value="a1">KOM A1</option>
                                    <option value="a2">KOM A2</option>
                                    <option value="b1">KOM B1</option>
                                    <option value="b2">KOM B2</option>
                                </select>
                            </div>
                            <div>
                                <label for="hari" class="block text-sm font-medium text-gray-800 mb-2">Hari</label>
                                <select name="hari" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Hari</option>
                                    <option value="senin">Senin</option>
                                    <option value="selasa">Selasa</option>
                                    <option value="rabu">Rabu</option>
                                    <option value="kamis">Kamis</option>
                                </select>
                            </div>
                            <div>
                                <label for="waktu" class="block text-sm font-medium text-gray-800 mb-2">Waktu</label>
                                <select name="waktu" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    <option selected disabled>Pilih Waktu</option>
                                    <option value="slot1">08:00 - 09:40</option>
                                    <option value="slot2">09:40 - 11:20</option>
                                    <option value="slot3">11:20 - 13:00</option>
                                    <option value="slot4">13:00 - 14:40</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-4 border-t border-gray-200 flex justify-end space-x-4 flex-shrink-0 bg-white">
                <button type="button" onclick="closeKelasGantiModal()" class="px-8 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit" form="kelas-ganti-form" class="px-8 py-3 bg-gradient-to-r from-blue-900 to-red-700 text-white font-semibold rounded-lg hover:opacity-90">
                    Simpan
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // ### SEMUA KODE JAVASCRIPT DARI FILE DASHBOARD LAMA, SEKARANG DI SINI ###
    const scheduleData = {
        lab1: {
            title: "Jadwal Penggunaan Ruang Lab Jaringan 1",
            isOccupied: true,
            schedules: [
                { subject: "Mata Kuliah: Praktikum Web Semantik", lecturer: "Aulia Halimatusyaddiah", time: "08:00 - 09:40", date: "29 September 2025", status: "Sedang Berlangsung", statusColor: "green" },
                { subject: "Mata Kuliah: Praktikum PBOL", lecturer: "Immanuel Manulang", time: "09:40 - 10:30", date: "29 September 2025", status: "Batal", statusColor: "red" },
                { subject: "Mata Kuliah: Praktikum Pemrograman Web", lecturer: "Nurul Aini", time: "10:30 - 11:20", date: "29 September 2025", status: "Akan Berlangsung", statusColor: "gray" },
                { subject: "Mata Kuliah: Praktikum Kecerdasan Buatan", lecturer: "David Hartono", time: "11:20 - 12:10", date: "29 September 2025", status: "Akan Berlangsung", statusColor: "gray" }
            ]
        },
        lab2: {
            title: "Jadwal Penggunaan Ruang Lab Jaringan 2",
            isOccupied: false,
            schedules: [ { subject: "Tidak ada jadwal hari ini", lecturer: "-", time: "-", date: "29 September 2025", status: "Kosong", statusColor: "gray" } ]
        },
        lab3: {
            title: "Jadwal Penggunaan Ruang Lab Jaringan 3",
            isOccupied: false,
            schedules: [ { subject: "Tidak ada jadwal hari ini", lecturer: "-", time: "-", date: "29 September 2025", status: "Kosong", statusColor: "gray" } ]
        },
        lab4: {
            title: "Jadwal Penggunaan Ruang Lab Jaringan 4",
            isOccupied: false,
            schedules: [ { subject: "Tidak ada jadwal hari ini", lecturer: "-", time: "-", date: "29 September 2025", status: "Kosong", statusColor: "gray" } ]
        }
    };
    const calendarData = {
        lab1: {
            "08:00 - 09:40": { "SENIN": null, "SELASA": { subject: "Prak MSBD III", lecturer: "Aulia Halimatusyaddiah" }, "RABU": null, "KAMIS": null, "JUMAT": null },
            "09:40 - 11:20": { "SENIN": null, "SELASA": null, "RABU": null, "KAMIS": { subject: "Prak SDA III", lecturer: "Cut Sissrialdi" }, "JUMAT": null },
            "11:20 - 13:00": { "SENIN": null, "SELASA": { subject: "Prak Pemro Web AI", lecturer: "Nurul Aini" }, "RABU": null, "KAMIS": null, "JUMAT": null },
            "13:00 - 14:40": { "SENIN": null, "SELASA": null, "RABU": { subject: "Prak IMK C1", lecturer: "Indah Azzahra" }, "KAMIS": null, "JUMAT": null },
            "14:40 - 16:20": { "SENIN": null, "SELASA": null, "RABU": null, "KAMIS": null, "JUMAT": { subject: "Prak SBD C2", lecturer: "David Hartono" } }
        }
    };
    let currentLab = 'lab1';
    function showSchedule(labId, isOccupied) {
        const data = scheduleData[labId];
        const scheduleTitle = document.getElementById('schedule-title');
        const scheduleContent = document.getElementById('schedule-content');
        scheduleTitle.innerText = data.title;
        let contentHTML = '';
        data.schedules.forEach(schedule => {
            const borderColor = schedule.statusColor === 'green' ? 'border-green-500 bg-green-50' : schedule.statusColor === 'red' ? 'border-red-500 bg-red-50' : 'border-gray-400 bg-gray-50';
            const statusBadge = schedule.statusColor === 'green' ? 'bg-green-500' : schedule.statusColor === 'red' ? 'bg-red-500' : 'bg-gray-500';
            contentHTML += `
                <div class="border-l-4 ${borderColor} p-3 lg:p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2 text-sm lg:text-base">${schedule.subject}</h4>
                    ${schedule.lecturer !== '-' ? `
                    <div class="flex items-center text-xs lg:text-sm text-gray-600 mb-2">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        <span>${schedule.lecturer}</span>
                        <svg class="w-4 h-4 ml-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                        <span>${schedule.time}</span>
                    </div>
                    ` : ''}
                    <div class="flex items-center text-xs lg:text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        <span>${schedule.date}</span>
                        <span class="ml-auto ${statusBadge} text-white px-2 py-1 rounded-full text-xs">${schedule.status}</span>
                    </div>
                </div>
            `;
        });
        scheduleContent.innerHTML = contentHTML;
        updateRoomColors();
    }
    function updateRoomColors() {
        Object.keys(scheduleData).forEach(labId => {
            const roomElement = document.getElementById(`${labId}-room`);
            const data = scheduleData[labId];
            if (data.isOccupied) {
                roomElement.style.backgroundColor = '#22C55E';
                roomElement.innerHTML = '';
            } else {
                roomElement.style.backgroundColor = '#9CA3AF';
                roomElement.innerHTML = '';
            }
        });
    }
    function selectLab(labId) {
        currentLab = labId;
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.className = 'px-4 py-2 rounded-full text-sm font-medium bg-gray-300 text-gray-700 hover:bg-gray-400';
        });
        document.getElementById(`tab-${labId}`).className = 'px-4 py-2 rounded-full text-sm font-medium bg-blue-900 text-white';
        updateCalendar();
    }
    function updateCalendar() {
        const calendarBody = document.getElementById('calendar-body');
        const timeSlots = ["08:00 - 09:40", "09:40 - 11:20", "11:20 - 13:00", "13:00 - 14:40", "14:40 - 16:20"];
        const days = ["SENIN", "SELASA", "RABU", "KAMIS", "JUMAT"];
        let calendarHTML = '';
        timeSlots.forEach(timeSlot => {
            calendarHTML += `<tr class="border-t border-gray-200">`;
            calendarHTML += `<td class="px-2 py-4 sm:px-4 sm:py-6 text-xs sm:text-sm font-medium text-gray-700 border-r border-gray-200 bg-gray-50">${timeSlot}</td>`;
            days.forEach(day => {
                const schedule = calendarData[currentLab] && calendarData[currentLab][timeSlot] && calendarData[currentLab][timeSlot][day];
                if (schedule) {
                    calendarHTML += `
                        <td class="px-2 py-2 border-r border-gray-200 align-top">
                            <div class="bg-blue-100 rounded-lg p-1.5 text-xs">
                                <div class="font-bold text-blue-900 mb-1">${schedule.subject}</div>
                                <div class="text-blue-700">${schedule.lecturer}</div>
                            </div>
                        </td>
                    `;
                } else {
                    calendarHTML += `<td class="px-2 py-6 border-r border-gray-200"></td>`;
                }
            });
            calendarHTML += `</tr>`;
        });
        calendarBody.innerHTML = calendarHTML;
    }
    function previousWeek() { console.log('Previous week clicked'); }
    function nextWeek() { console.log('Next week clicked'); }
    function addClass() {
        const modal = document.getElementById('kelas-ganti-modal');
        modal.classList.remove('hidden');
    }
    function closeKelasGantiModal() {
        const modal = document.getElementById('kelas-ganti-modal');
        modal.classList.add('hidden');
    }
    document.addEventListener('DOMContentLoaded', function() {
        showSchedule('lab1', true);
        updateCalendar();
    });
</script>
@endpush