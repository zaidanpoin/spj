<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8">
    <!-- Prevent flash of light mode -->
    <script>
        (function () {
            const darkMode = localStorage.getItem('darkMode');
            if (darkMode === 'true' || (!darkMode && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SPJ Laravel')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: 'var(--color-primary)',
                            dark: 'var(--color-primary-dark)',
                            light: 'var(--color-primary-light)',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Open Sans', sans-serif;
        }

        /* Loading Spinner */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .page-loader.active {
            display: flex;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .spinner {
            position: relative;
            width: 60px;
            height: 60px;
        }

        .spinner::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 4px solid #f3f4f6;
            border-top-color: var(--color-primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, 50%);
            margin-top: 20px;
            color: var(--color-primary);
            font-weight: 600;
            white-space: nowrap;
        }

        /* ========== GLOBAL DARK MODE ========== */
        html.dark body {
            background-color: #1a1a2e !important;
            color: #f1f5f9 !important;
        }

        html.dark .bg-white {
            background-color: #1a1a2e !important;
        }

        html.dark .bg-gray-50,
        html.dark .bg-gray-100 {
            background-color: #252542 !important;
        }

        html.dark .text-gray-900 {
            color: #f1f5f9 !important;
        }

        html.dark .text-gray-700,
        html.dark .text-gray-600 {
            color: #94a3b8 !important;
        }

        html.dark .text-gray-500,
        html.dark .text-gray-400 {
            color: #64748b !important;
        }

        html.dark .border-gray-200,
        html.dark .border-gray-100 {
            border-color: #2d2d4a !important;
        }

        html.dark .divide-gray-200>*+* {
            border-color: #2d2d4a !important;
        }

        html.dark .hover\:bg-gray-50:hover {
            background-color: #252542 !important;
        }

        html.dark footer {
            background-color: #1a1a2e !important;
            border-color: #2d2d4a !important;
        }

        html.dark .page-loader {
            background: rgba(26, 26, 46, 0.95) !important;
        }

        /* Calendar */
        html.dark .fc,
        html.dark .fc td,
        html.dark .fc th,
        html.dark .fc table {
            background-color: #1a1a2e !important;
        }

        html.dark .fc-col-header-cell {
            background-color: #252542 !important;
        }

        html.dark .fc-daygrid-day-number,
        html.dark .fc-col-header-cell-cushion,
        html.dark .fc-toolbar-title {
            color: #f1f5f9 !important;
        }

        html.dark .fc-theme-standard td,
        html.dark .fc-theme-standard th {
            border-color: #2d2d4a !important;
        }
    </style>

    @stack('styles')
</head>

<body class="flex flex-col min-h-screen transition-colors duration-300">
    <!-- Page Loading Spinner -->
    <div id="pageLoader" class="page-loader">
        <div class="text-center">
            <div class="spinner"></div>
            <p class="spinner-text">Loading...</p>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 bg-primary z-50">
        <!-- Top Bar: Brand + Hamburger + User -->
        <div class="border-b border-teal-600">
            <div class="container mx-auto px-4 sm:px-6 lg:px-12">
                <div class="flex items-center justify-between h-14">
                    <!-- Brand -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <div class="w-7 h-7 bg-white rounded flex items-center justify-center">
                            <span class="text-primary font-bold text-sm">S</span>
                        </div>
                        <span class="text-white font-bold text-lg">SPJ</span>
                    </a>

                    <!-- User Info + Hamburger -->
                    @auth
                        <div class="flex items-center space-x-3 sm:space-x-4">
                            <!-- User Avatar & Name (hidden on very small screens) -->
                            <div class="hidden sm:flex items-center space-x-3">
                                <div class="text-right hidden md:block">
                                    <div class="text-white text-sm font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-teal-200 text-xs">{{ ucfirst(Auth::user()->role) }}</div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center">
                                    <span
                                        class="text-primary font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </div>

                            <!-- Dark Mode Toggle -->
                            <button type="button" id="darkModeToggle" class="dark-mode-toggle text-white"
                                title="Toggle Dark Mode">
                                <!-- Sun Icon (shown in dark mode) -->
                                <svg id="sunIcon" class="hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <!-- Moon Icon (shown in light mode) -->
                                <svg id="moonIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                                    </path>
                                </svg>
                            </button>

                            <!-- Logout (always visible) -->
                            <form action="{{ route('logout') }}" method="POST" class="inline" data-no-loader>
                                @csrf
                                <button type="submit" class="text-white hover:text-teal-200 transition text-sm">
                                    Logout
                                </button>
                            </form>

                            <!-- Hamburger Button (mobile only) -->
                            <button type="button" id="hamburgerBtn"
                                class="md:hidden p-2 text-white hover:bg-white/10 rounded-lg transition">
                                <svg id="hamburgerIcon" class="w-6 h-6" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <svg id="closeIcon" class="w-6 h-6 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Desktop Menu Bar (hidden on mobile) -->
        <div class="hidden md:block bg-white border-b border-gray-200">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="flex items-center justify-center space-x-6 h-12">
                    <!-- Dashboard -->
                    <a href="{{ route('home') }}"
                        class="flex items-center px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary border-b-2 border-primary' : 'text-gray-600 hover:text-primary' }}">
                        Dashboard
                    </a>

                    <!-- Master Dropdown (Admin + Super Admin only) -->
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
                        <div class="relative group">
                            <button
                                class="flex items-center px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('master.*') || request()->is('users*') ? 'text-primary border-b-2 border-primary' : 'text-gray-600 hover:text-primary' }}">
                                Master
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div
                                class="absolute left-0 top-full mt-0 w-48 bg-white rounded-b-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all border border-gray-200">
                                <a href="{{ route('master.unor.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Unit
                                    Organisasi</a>
                                <a href="{{ route('master.unit-kerja.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Unit
                                    Kerja</a>
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('master.waktu-konsumsi.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Waktu
                                    Konsumsi</a>
                                <a href="{{ route('master.mak.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">MAK
                                    (Akun)</a>
                                <a href="{{ route('master.ppk.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">PPK</a>
                                <a href="{{ route('master.bendahara.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Bendahara</a>
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('users.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">User
                                    Management</a>
                                <a href="{{ route('activity-logs.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Activiy
                                    Logs</a>


                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('master.sbm-konsumsi.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">SBM
                                    Konsumsi</a>
                                <a href="{{ route('master.sbm-honorarium.index') }}"
                                    class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary rounded-b-lg">SBM
                                    Honorarium</a>
                            </div>
                        </div>
                    @endif

                    <!-- Transaksi Dropdown -->
                    <div class="relative group">
                        <button
                            class="flex items-center px-4 py-2 text-sm font-medium transition-colors {{ request()->routeIs('kegiatan.*') ? 'text-primary border-b-2 border-primary' : 'text-gray-600 hover:text-primary' }}">
                            Transaksi
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 top-full mt-0 w-48 bg-white rounded-b-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all border border-gray-200">
                            <a href="{{ route('kegiatan.index') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Daftar
                                Kegiatan</a>
                            <a href="{{ route('kegiatan.create') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary rounded-b-lg">Tambah
                                Kegiatan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu (hidden by default) -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-b border-gray-200 max-h-[70vh] overflow-y-auto">
            <div class="py-2">
                <!-- Dashboard -->
                <a href="{{ route('home') }}"
                    class="block px-4 py-3 text-sm font-medium {{ request()->routeIs('home') ? 'text-primary bg-primary/5 border-l-4 border-primary' : 'text-gray-700 hover:bg-gray-50' }}">
                    Dashboard
                </a>

                <!-- Master Section (Admin only) -->
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']))
                    <div class="border-t border-gray-100 mt-2 pt-2">
                        <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Master Data</div>
                        <a href="{{ route('master.unor.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.unor.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Unit
                            Organisasi</a>
                        <a href="{{ route('master.unit-kerja.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.unit-kerja.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Unit
                            Kerja</a>
                        <a href="{{ route('master.waktu-konsumsi.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.waktu-konsumsi.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Waktu
                            Konsumsi</a>
                        <a href="{{ route('master.mak.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.mak.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">MAK
                            (Akun)</a>
                        <a href="{{ route('master.ppk.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.ppk.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">PPK</a>
                        <a href="{{ route('master.bendahara.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.bendahara.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Bendahara</a>
                        <a href="{{ route('users.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->is('users*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">User
                            Management</a>
                        <a href="{{ route('master.sbm-konsumsi.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.sbm-konsumsi.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">SBM
                            Konsumsi</a>
                        <a href="{{ route('master.sbm-honorarium.index') }}"
                            class="block px-4 py-2.5 text-sm {{ request()->routeIs('master.sbm-honorarium.*') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">SBM
                            Honorarium</a>
                    </div>
                @endif

                <!-- Transaksi Section -->
                <div class="border-t border-gray-100 mt-2 pt-2">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase">Transaksi</div>
                    <a href="{{ route('kegiatan.index') }}"
                        class="block px-4 py-2.5 text-sm {{ request()->routeIs('kegiatan.index') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Daftar
                        Kegiatan</a>
                    <a href="{{ route('kegiatan.create') }}"
                        class="block px-4 py-2.5 text-sm {{ request()->routeIs('kegiatan.create') ? 'text-primary bg-primary/5' : 'text-gray-700 hover:bg-gray-50' }}">Tambah
                        Kegiatan</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="pt-16 md:pt-28 px-4 sm:px-6 lg:px-12 pb-8">
        <div class="container max-w-6xl mx-auto">
            <!-- Breadcrumbs -->
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                <x-breadcrumbs :items="$breadcrumbs" />
            @endif

            <!-- Page Header -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-primary">
                    @yield('page-title', 'Dashboard')
                </h1>
                <p class="text-gray-600 mt-1">
                    @yield('page-subtitle', 'Sistem Pengelolaan Kegiatan & Belanja')
                </p>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="container max-w-6xl mx-auto px-6 lg:px-12 py-6">
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    &copy; {{ date('Y') }} Kementerian Pekerjaan Umum
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Sistem Pengelolaan Keuangan & Belanja
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
        // Page loader - only for form submissions
        const pageLoader = document.getElementById('pageLoader');

        // Show loader on form submissions (unless marked to skip)
        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!form.hasAttribute('data-no-loader')) {
                pageLoader.classList.add('active');
            }
        });

        // Hide loader when page loads
        window.addEventListener('load', function () {
            pageLoader.classList.remove('active');
        });

        // Also hide on pageshow (back/forward cache)
        window.addEventListener('pageshow', function () {
            pageLoader.classList.remove('active');
        });

        // Hamburger Menu Toggle
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        const closeIcon = document.getElementById('closeIcon');

        if (hamburgerBtn && mobileMenu) {
            hamburgerBtn.addEventListener('click', function () {
                mobileMenu.classList.toggle('hidden');
                hamburgerIcon.classList.toggle('hidden');
                closeIcon.classList.toggle('hidden');
            });

            // Close menu when clicking on a link
            mobileMenu.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    mobileMenu.classList.add('hidden');
                    hamburgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                });
            });
        }

        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const sunIcon = document.getElementById('sunIcon');
        const moonIcon = document.getElementById('moonIcon');
        const html = document.documentElement;

        // Function to update icons based on current mode
        function updateDarkModeIcons() {
            if (html.classList.contains('dark')) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Initialize icons on page load
        updateDarkModeIcons();

        // Toggle dark mode on button click
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', function () {
                html.classList.toggle('dark');

                // Save preference to localStorage
                const isDark = html.classList.contains('dark');
                localStorage.setItem('darkMode', isDark);

                // Update icons
                updateDarkModeIcons();
            });
        }
    </script>

    @stack('scripts')
</body>

</html>