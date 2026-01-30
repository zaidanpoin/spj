@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')
@section('page-subtitle', 'Kelola pengguna sistem')

@section('content')
    <div class="space-y-4">
        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 font-medium">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Users -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3">
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Users</p>
                </div>
            </div>

            <!-- Super Admin -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3">
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $superAdminCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Super Admin</p>
                </div>
            </div>

            <!-- Admin -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3">
                <div class="p-3 bg-orange-100 dark:bg-orange-900 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $adminCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Admin</p>
                </div>
            </div>

            <!-- User -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex items-center gap-3">
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $userCount }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">User</p>
                </div>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-col md:flex-row md:items-end gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Cari nama atau email...">
                </div>

                <!-- Filter Role -->
                <div class="w-full sm:w-36">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <!-- Filter Status -->
                <div class="w-full sm:w-36">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Cari
                    </button>
                       <a href="{{ route('roles.index') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Roles
                    </a>
                    <a href="{{ route('users.create') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center">
                        + Tambah
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Daftar User</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    {{ $users->total() }} user
                </span>
            </div>

            <!-- Desktop Table (Hidden on mobile) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">NAMA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">EMAIL</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">UNKER</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">UNOR</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">ROLE</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">STATUS</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-56">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $users->firstItem() + $index }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $user->unitKerja->nama_unit ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $user->unitKerja->unor->nama_unor ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->role === 'super_admin')
                                        <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full text-xs font-medium">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-full text-xs font-medium">Admin</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium">User</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->status === 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">Suspended</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1 flex-wrap">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="px-2 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-xs font-medium">
                                            Edit
                                        </a>

                                        @if(Auth::user()->role === 'super_admin')
                                            @if($user->status === 'active')
                                                <form action="{{ route('users.suspend', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Suspend user ini?')"
                                                        class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 text-xs font-medium">
                                                        Suspend
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('users.activate', $user->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-2 py-1 bg-green-50 text-green-600 rounded hover:bg-green-100 text-xs font-medium">
                                                        Activate
                                                    </button>
                                                </form>
                                            @endif
                                        @endif

                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Yakin hapus user ini?')"
                                                    class="px-2 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 text-xs font-medium">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards (Hidden on desktop) -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($users as $index => $user)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $user->email }}</p>
                            </div>
                            <span class="text-xs text-gray-400">#{{ $users->firstItem() + $index }}</span>
                        </div>

                        <div class="flex items-center gap-2 mb-3">
                            @if($user->role === 'super_admin')
                                <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300 rounded-full text-xs font-medium">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="px-2 py-0.5 bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300 rounded-full text-xs font-medium">Admin</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-full text-xs font-medium">User</span>
                            @endif

                            @if($user->status === 'active')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">Suspended</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs font-medium text-center">
                                Edit
                            </a>

                            @if(Auth::user()->role === 'super_admin')
                                @if($user->status === 'active')
                                    <form action="{{ route('users.suspend', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Suspend user ini?')"
                                            class="w-full px-3 py-1.5 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 text-xs font-medium">
                                            Suspend
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('users.activate', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-3 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 text-xs font-medium">
                                            Activate
                                        </button>
                                    </form>
                                @endif
                            @endif

                            @if($user->id !== Auth::id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus user ini?')"
                                        class="w-full px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-xs font-medium">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Belum ada data user
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                        - <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                        dari <span class="font-medium">{{ $users->total() }}</span> data
                    </div>
                    @if($users->hasPages())
                        <div class="flex items-center justify-center gap-1 sm:gap-2 flex-wrap">
                            @if($users->onFirstPage())
                                <span class="px-2 sm:px-3 py-1 text-gray-400 bg-gray-100 rounded text-xs sm:text-sm cursor-not-allowed">←</span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">←</a>
                            @endif

                            @php
                                $start = max(1, $users->currentPage() - 2);
                                $end = min($users->lastPage(), $users->currentPage() + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $users->url(1) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">1</a>
                                @if($start > 2)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $users->currentPage())
                                    <span class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $users->url($page) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endfor

                            @if($end < $users->lastPage())
                                @if($end < $users->lastPage() - 1)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                                <a href="{{ $users->url($users->lastPage()) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $users->lastPage() }}</a>
                            @endif

                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">→</a>
                            @else
                                <span class="px-2 sm:px-3 py-1 text-gray-400 bg-gray-100 rounded text-xs sm:text-sm cursor-not-allowed">→</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
