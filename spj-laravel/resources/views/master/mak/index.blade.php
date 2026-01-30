@extends('layouts.app')

@section('title', 'Master MAK')
@section('page-title', 'Master MAK')
@section('page-subtitle', 'Mata Anggaran Kegiatan')

@section('content')
    <div class="space-y-4">
        <!-- Success Message -->
        <!-- @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif -->

        <!-- Search & Filter Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <form method="GET" action="{{ route('master.mak.index') }}" class="flex flex-col md:flex-row md:items-end gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Cari kode, nama, satker...">
                </div>

                <!-- Filter Tahun -->
                <div class="w-full sm:w-32">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Cari
                    </button>
                    <a href="{{ route('master.mak.sync') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center flex items-center justify-center gap-1"
                        onclick="return confirm('Sync data MAK dari API IEMON?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sync
                    </a>
                    <a href="{{ route('master.mak.create') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center">
                        + Tambah
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Daftar MAK</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    {{ $makData->total() }} item
                </span>
            </div>

            <!-- Desktop Table (Hidden on mobile) -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">TAHUN</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">KODE</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">NAMA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">SATKER</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">AKUN</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">PAKET</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-32">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($makData as $index => $mak)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $makData->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $mak->tahun }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $mak->kode }}</code>
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ Str::limit($mak->nama, 30) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ Str::limit($mak->satker, 20) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $mak->akun }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ Str::limit($mak->paket, 15) }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('master.mak.edit', $mak->id) }}"
                                            class="px-3 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-xs font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('master.mak.destroy', $mak->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus MAK ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 text-xs font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada data MAK
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards (Hidden on desktop) -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($makData as $index => $mak)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                    {{ $mak->tahun }}
                                </span>
                                <code class="bg-gray-100 px-2 py-0.5 rounded text-xs">{{ $mak->kode }}</code>
                            </div>
                            <span class="text-xs text-gray-400">#{{ $makData->firstItem() + $index }}</span>
                        </div>
                        
                        <h4 class="font-medium text-gray-900 mb-2">{{ $mak->nama }}</h4>
                        
                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 mb-3">
                            <div>
                                <span class="text-gray-400">Satker:</span>
                                <p class="font-medium text-gray-700">{{ Str::limit($mak->satker, 25) }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400">Akun:</span>
                                <p class="font-medium text-gray-700">{{ $mak->akun }}</p>
                            </div>
                            <div class="col-span-2">
                                <span class="text-gray-400">Paket:</span>
                                <p class="font-medium text-gray-700">{{ $mak->paket }}</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('master.mak.edit', $mak->id) }}"
                                class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs font-medium text-center">
                                Edit
                            </a>
                            <form action="{{ route('master.mak.destroy', $mak->id) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Yakin hapus MAK ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-3 py-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 text-xs font-medium">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Belum ada data MAK
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $makData->firstItem() ?? 0 }}</span> 
                        - <span class="font-medium">{{ $makData->lastItem() ?? 0 }}</span> 
                        dari <span class="font-medium">{{ $makData->total() }}</span> data
                    </div>
                    @if($makData->hasPages())
                        <div class="flex items-center justify-center gap-1 sm:gap-2 flex-wrap">
                            {{-- Previous --}}
                            @if($makData->onFirstPage())
                                <span class="px-2 sm:px-3 py-1 text-gray-400 bg-gray-100 rounded text-xs sm:text-sm cursor-not-allowed">←</span>
                            @else
                                <a href="{{ $makData->previousPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">←</a>
                            @endif

                            {{-- Page Numbers (limit displayed) --}}
                            @php
                                $start = max(1, $makData->currentPage() - 2);
                                $end = min($makData->lastPage(), $makData->currentPage() + 2);
                            @endphp
                            
                            @if($start > 1)
                                <a href="{{ $makData->url(1) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">1</a>
                                @if($start > 2)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $makData->currentPage())
                                    <span class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $makData->url($page) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endfor

                            @if($end < $makData->lastPage())
                                @if($end < $makData->lastPage() - 1)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                                <a href="{{ $makData->url($makData->lastPage()) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $makData->lastPage() }}</a>
                            @endif

                            {{-- Next --}}
                            @if($makData->hasMorePages())
                                <a href="{{ $makData->nextPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">→</a>
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