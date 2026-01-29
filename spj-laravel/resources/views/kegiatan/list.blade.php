@extends('layouts.app')

@section('title', 'Daftar Kegiatan')
@section('page-title', 'Daftar Kegiatan')
@section('page-subtitle', 'Kelola semua kegiatan yang terdaftar')

@section('content')
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Semua Kegiatan</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $kegiatans->count() }} kegiatan terdaftar</p>
            </div>
            <a href="{{ route('kegiatan.create') }}"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-primary to-purple-600 text-white text-sm font-medium rounded-lg hover:shadow-lg transition-all">
                <span class="mr-1.5">+</span> Tambah Kegiatan
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama
                            Kegiatan</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit
                            Organisasi</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Periode</th>
                        <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kegiatans as $kegiatan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</div>
                                <div class="text-sm text-gray-500 mt-0.5">{{ $kegiatan->unitKerja->nama_unit ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $kegiatan->unor->nama_unor ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $kegiatan->tanggal_mulai }}</div>
                                <div class="text-sm text-gray-500">s/d {{ $kegiatan->tanggal_selesai }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}"
                                        class="px-3 py-1.5 bg-blue-50 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                        Detail
                                    </a>
                                    <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                Belum ada kegiatan terdaftar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
