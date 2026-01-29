@extends('layouts.app')

@section('title', 'Daftar Kegiatan')
@section('page-title', 'Daftar Kegiatan')
@section('page-subtitle', 'Kelola semua kegiatan yang terdaftar')

@section('content')
    <div class="space-y-4">
        <!-- Search & Filter Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <form action="{{ route('kegiatan.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-3">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Cari kegiatan...">
                </div>

                <!-- Filter Unor -->
                <div class="w-full sm:w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Organisasi</label>
                    <select name="unor_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Unor</option>
                        @foreach($unors as $unor)
                            <option value="{{ $unor->id }}" {{ request('unor_id') == $unor->id ? 'selected' : '' }}>
                                {{ $unor->nama_unor }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Cari
                    </button>
                    <a href="{{ route('kegiatan.index') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Reset
                    </a>
                    <a href="{{ route('kegiatan.create') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center">
                        + Tambah
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Daftar Kegiatan</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    {{ $kegiatans->total() }} kegiatan
                </span>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">KEGIATAN</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">UNOR / UNIT KERJA</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-20">PESERTA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-36">PERIODE</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-36">PEMBUAT</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-28">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kegiatans as $index => $kegiatan)
                            <tr class="hover:bg-gray-50 cursor-pointer transition"
                                onclick="if(event.target.tagName !== 'BUTTON' && event.target.tagName !== 'A' && !event.target.closest('form') && !event.target.closest('.aksi-col')) window.location='{{ route('kegiatan.pilih-detail', $kegiatan->id) }}'">
                                <td class="px-4 py-3 text-gray-500">{{ $kegiatans->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-gray-900">{{ $kegiatan->unor->nama_unor ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $kegiatan->unitKerja->nama_unit ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">
                                        {{ $kegiatan->jumlah_peserta ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    @if($kegiatan->tanggal_mulai)
                                        {{ $kegiatan->tanggal_mulai->format('d/m/Y') }}
                                        @if($kegiatan->tanggal_selesai)
                                            <span class="text-gray-400">-</span> {{ $kegiatan->tanggal_selesai->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-left text-xs text-gray-600">
                                    {{ $kegiatan->user->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 aksi-col">
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                            class="px-2 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-xs font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Yakin hapus kegiatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 text-xs font-medium">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada kegiatan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($kegiatans as $index => $kegiatan)
                    <div class="p-4">
                        <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}" class="block mb-3">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <span class="font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</span>
                                    <p class="text-xs text-gray-600">{{ $kegiatan->unor->nama_unor ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $kegiatan->unitKerja->nama_unit ?? '-' }}</p>
                                </div>
                                <span class="text-xs text-gray-400">#{{ $kegiatans->firstItem() + $index }}</span>
                            </div>

                            <div class="flex items-center gap-3 text-xs">
                                <div class="flex items-center gap-1">
                                    <span class="text-gray-400">Peserta:</span>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded font-medium">{{ $kegiatan->jumlah_peserta ?? '-' }}</span>
                                </div>
                                <div class="text-gray-600">
                                    @if($kegiatan->tanggal_mulai)
                                        {{ $kegiatan->tanggal_mulai->format('d/m/Y') }}
                                        @if($kegiatan->tanggal_selesai && $kegiatan->tanggal_selesai != $kegiatan->tanggal_mulai)
                                            - {{ $kegiatan->tanggal_selesai->format('d/m/Y') }}
                                        @endif
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </a>

                        <!-- Mobile Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                class="flex-1 px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs font-medium text-center">
                                Edit
                            </a>
                            <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('Yakin hapus kegiatan ini?')">
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
                        Belum ada kegiatan
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $kegiatans->firstItem() ?? 0 }}</span>
                        - <span class="font-medium">{{ $kegiatans->lastItem() ?? 0 }}</span>
                        dari <span class="font-medium">{{ $kegiatans->total() }}</span> kegiatan
                    </div>
                    @if($kegiatans->hasPages())
                        <div class="flex items-center justify-center gap-1 sm:gap-2 flex-wrap">
                            @if($kegiatans->onFirstPage())
                                <span class="px-2 sm:px-3 py-1 text-gray-400 bg-gray-100 rounded text-xs sm:text-sm cursor-not-allowed">←</span>
                            @else
                                <a href="{{ $kegiatans->previousPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">←</a>
                            @endif

                            @php
                                $start = max(1, $kegiatans->currentPage() - 2);
                                $end = min($kegiatans->lastPage(), $kegiatans->currentPage() + 2);
                            @endphp

                            @if($start > 1)
                                <a href="{{ $kegiatans->url(1) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">1</a>
                                @if($start > 2)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $kegiatans->currentPage())
                                    <span class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $kegiatans->url($page) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endfor

                            @if($end < $kegiatans->lastPage())
                                @if($end < $kegiatans->lastPage() - 1)
                                    <span class="px-1 text-gray-400">...</span>
                                @endif
                                <a href="{{ $kegiatans->url($kegiatans->lastPage()) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $kegiatans->lastPage() }}</a>
                            @endif

                            @if($kegiatans->hasMorePages())
                                <a href="{{ $kegiatans->nextPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">→</a>
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
