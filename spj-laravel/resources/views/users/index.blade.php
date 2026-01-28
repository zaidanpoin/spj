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
                    <a href="{{ route('users.index') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Reset
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
                                <td class="px-4 py-3">
                                    @if($user->role === 'super_admin')
                                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Admin</span>
                                    @else
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">User</span>
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

                                            <form action="{{ route('users.reset-password', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Reset password user ini?')"
                                                    class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100 text-xs font-medium">
                                                    Reset PW
                                                </button>
                                            </form>
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
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
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
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Super Admin</span>
                            @elseif($user->role === 'admin')
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Admin</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">User</span>
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

                                <form action="{{ route('users.reset-password', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Reset password user ini?')"
                                        class="w-full px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 text-xs font-medium">
                                        Reset PW
                                    </button>
                                </form>
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
