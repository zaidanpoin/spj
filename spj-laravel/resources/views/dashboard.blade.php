@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Sistem Pengelolaan Kegiatan & Belanja')

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Kegiatan -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Kegiatan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalKegiatan ?? 0 }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-xl font-bold">K</span>
                </div>
            </div>
        </div>

        <!-- Total Anggaran -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Anggaran</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Rp
                        {{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-xl font-bold">Rp</span>
                </div>
            </div>
        </div>

        <!-- Kwitansi -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Kwitansi</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalKwitansi ?? 0 }}</p>
                </div>
                <div
                    class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-xl font-bold">D</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Aksi Cepat</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pilih aksi yang ingin dilakukan</p>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('kegiatan.create') }}"
                class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-all group">
                <div
                    class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                    <span class="text-blue-600 font-bold text-lg">+</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Tambah Kegiatan</h3>
                <p class="text-sm text-gray-600">Buat kegiatan baru</p>
            </a>

            <a href="{{ route('kegiatan.index') }}"
                class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-all group">
                <div
                    class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                    <span class="text-green-600 font-bold text-lg">≡</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Daftar Kegiatan</h3>
                <p class="text-sm text-gray-600">Lihat semua kegiatan</p>
            </a>

            <a href="{{ route('master.sbm-konsumsi.index') }}"
                class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-all group">
                <div
                    class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                    <span class="text-purple-600 font-bold text-lg">$</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Master SBM</h3>
                <p class="text-sm text-gray-600">Kelola SBM konsumsi</p>
            </a>

            <a href="#"
                class="p-4 border-2 border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-all group">
                <div
                    class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-amber-200 transition-colors">
                    <span class="text-amber-600 font-bold text-lg">📊</span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Laporan</h3>
                <p class="text-sm text-gray-600">Lihat laporan</p>
            </a>
        </div>
    </div>

    <!-- Calendar -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Kalender Kegiatan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Jadwal kegiatan yang sedang berlangsung</p>
        </div>
        <div class="p-6">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Kegiatan Terbaru</h2>
            <p class="text-sm text-gray-500 mt-0.5" id="activity-count">{{ $recentActivities->total() ?? 0 }} kegiatan</p>
        </div>
        <div class="divide-y divide-gray-200" id="activities-list">
            @forelse($recentActivities as $activity)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $activity->nama_kegiatan }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $activity->unor->nama_unor ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ $activity->tanggal_mulai }}</p>
                            <span class="inline-block mt-1 px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                Aktif
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500">Belum ada aktivitas</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 bg-white" id="pagination-links">
            {{ $recentActivities->links() }}
        </div>
    </div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    /* Custom FullCalendar styling with primary color */
    .fc .fc-button-primary {
        background-color: #00e508 !important;
        border-color: #00e508 !important;
        color: white !important;
    }
    .fc .fc-button-primary:hover {
        background-color: #0f766e !important;
        border-color: #0f766e !important;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: #0f766e !important;
        border-color: #0f766e !important;
    }
    .fc .fc-button-primary:disabled {
        background-color: #cccccc !important;
        border-color: #cccccc !important;
    }
    .fc-theme-standard .fc-scrollgrid {
        border-color: #e5e7eb;
    }
    .fc-theme-standard td,
    .fc-theme-standard th {
        border-color: #e5e7eb;
    }
    /* Event styling */
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 2px 4px;
        font-size: 0.85rem;
    }
    .fc-event-main {
        color: white !important;
    }
    .fc-daygrid-event {
        border-radius: 4px;
        margin-bottom: 1px;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                list: 'Daftar'
            },
            locale: 'id',
            height: 'auto',
            events: '/api/calendar-events',
            eventClick: function(info) {
                // Redirect to kegiatan detail
                window.location.href = '/daftar-kegiatan/' + info.event.id + '/pilih-detail';
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        });
        calendar.render();
    });

    // AJAX Pagination for Recent Activities
    document.addEventListener('click', function(e) {
        // Check if clicked element is a pagination link
        const paginationLink = e.target.closest('#pagination-links a');
        if (!paginationLink) return;

        e.preventDefault();

        // Hide global page loader (prevent showing on AJAX)
        const pageLoader = document.getElementById('pageLoader');
        if (pageLoader) {
            pageLoader.classList.remove('active');
        }

        const url = paginationLink.href;
        const activitiesList = document.getElementById('activities-list');
        const paginationContainer = document.getElementById('pagination-links');
        const activityCount = document.getElementById('activity-count');

        // Show loading state
        activitiesList.style.opacity = '0.5';

        // Fetch data via AJAX
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse HTML response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extract activities list
            const newActivitiesList = doc.getElementById('activities-list');
            const newPaginationLinks = doc.getElementById('pagination-links');
            const newActivityCount = doc.getElementById('activity-count');

            // Update DOM
            if (newActivitiesList) {
                activitiesList.innerHTML = newActivitiesList.innerHTML;
            }
            if (newPaginationLinks) {
                paginationContainer.innerHTML = newPaginationLinks.innerHTML;
            }
            if (newActivityCount) {
                activityCount.textContent = newActivityCount.textContent;
            }

            // Restore opacity
            activitiesList.style.opacity = '1';

            // Smooth scroll to top of activities
            const activitiesSection = document.querySelector('.bg-white.rounded-xl.shadow-sm.overflow-hidden:last-of-type');
            if (activitiesSection) {
                activitiesSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            activitiesList.style.opacity = '1';
            alert('Gagal memuat data. Silakan coba lagi.');
        });
    });
</script>
@endpush
