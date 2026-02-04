@extends('layouts.app')

@section('title', 'Master SBM SPPD')
@section('page-title', 'Master SBM SPPD')
@section('page-subtitle', 'Satuan Biaya SPPD')

@section('content')
    <div class="space-y-4">
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <form method="GET" action="{{ route('master.sbm-sppd.index') }}" class="flex flex-col md:flex-row md:items-end gap-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="thang" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Semua</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}" {{ request('thang') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
                        <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Semua</option>
                            @foreach($jenisList as $j)
                                <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ $j }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sub</label>
                        <select name="sub" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="">Semua</option>
                            @foreach($subList as $s)
                                <option value="{{ $s }}" {{ request('sub') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button type="submit" class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Cari
                    </button>
                    <a href="{{ route('master.sbm-sppd.index') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Reset
                    </a>
                    <a href="{{ route('master.sbm-sppd.create') }}"
                        class="flex-1 sm:flex-none px-3 sm:px-4 py-1.5 sm:py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center">
                        + Tambah
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Daftar SBM SPPD</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    {{ $sbm->total() }} data
                </span>
            </div>

            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">JENIS</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">KELAS</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">ITEM</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">SATUAN</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-600">NILAI</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600">TAHUN</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-32">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sbm as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-500">{{ $sbm->firstItem() + $index }}</td>
                                <td class="px-4 py-3"><div class="font-medium text-gray-900">{{ $item->jenis }}</div></td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->kelas }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $item->item }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $item->satuan_sing }}</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded">
                                        {{ 'Rp ' . number_format($item->nilai, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center"><span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded">{{ $item->thang }}</span></td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('master.sbm-sppd.edit', $item->id) }}" class="px-3 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-xs font-medium">Edit</a>
                                        <form action="{{ route('master.sbm-sppd.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100 text-xs font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-500">Belum ada data SBM SPPD</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="text-sm text-gray-600 text-center sm:text-left">
                        Menampilkan <span class="font-medium">{{ $sbm->firstItem() ?? 0 }}</span>
                        - <span class="font-medium">{{ $sbm->lastItem() ?? 0 }}</span>
                        dari <span class="font-medium">{{ $sbm->total() }}</span> data
                    </div>
                    @if($sbm->hasPages())
                        <div class="flex items-center justify-center gap-1 sm:gap-2 flex-wrap">
                            @if($sbm->onFirstPage())
                                <span class="px-2 sm:px-3 py-1 text-gray-400 bg-gray-100 rounded text-xs sm:text-sm cursor-not-allowed">←</span>
                            @else
                                <a href="{{ $sbm->previousPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">←</a>
                            @endif

                            @php
                                $start = max(1, $sbm->currentPage() - 2);
                                $end = min($sbm->lastPage(), $sbm->currentPage() + 2);
                            @endphp

                            @for($page = $start; $page <= $end; $page++)
                                @if($page == $sbm->currentPage())
                                    <span class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm font-medium">{{ $page }}</span>
                                @else
                                    <a href="{{ $sbm->url($page) }}" class="px-2 sm:px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs sm:text-sm hover:bg-gray-200 transition">{{ $page }}</a>
                                @endif
                            @endfor

                            @if($sbm->hasMorePages())
                                <a href="{{ $sbm->nextPageUrl() }}" class="px-2 sm:px-3 py-1 bg-primary text-white rounded text-xs sm:text-sm hover:bg-primary-dark transition">→</a>
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
