@extends('layouts.app')

@section('title', 'Edit Kegiatan')
@section('page-title', 'Edit Kegiatan')
@section('page-subtitle', 'Form Edit Data Kegiatan')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Edit Informasi Kegiatan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Perbarui data yang diperlukan</p>
            </div>

            <form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nama_kegiatan') border-red-500 @enderror"
                        required>
                    @error('nama_kegiatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Uraian Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Uraian Kegiatan
                    </label>
                    <textarea name="uraian_kegiatan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('uraian_kegiatan') border-red-500 @enderror"
                        placeholder="Deskripsi singkat tentang kegiatan...">{{ old('uraian_kegiatan', $kegiatan->uraian_kegiatan) }}</textarea>
                    @error('uraian_kegiatan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Kerja - Unit Organisasi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit Kerja - Unit Organisasi <span class="text-red-500">*</span>
                    </label>
                    <select name="unit_kerja_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('unit_kerja_id') border-red-500 @enderror">
                        <option value="">Pilih Unit Kerja</option>
                        @foreach($unitKerjas as $uk)
                            <option value="{{ $uk->id }}" {{ old('unit_kerja_id', $kegiatan->unit_kerja_id) == $uk->id ? 'selected' : '' }}>
                                {{ $uk->nama_unit }} - {{ $uk->unor->nama_unor }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_kerja_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Mulai & Selesai -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $kegiatan->tanggal_mulai?->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $kegiatan->tanggal_selesai?->format('Y-m-d')) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('tanggal_selesai') border-red-500 @enderror">
                        @error('tanggal_selesai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Jumlah Peserta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jumlah Peserta <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta', $kegiatan->jumlah_peserta) }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('jumlah_peserta') border-red-500 @enderror">
                    @error('jumlah_peserta')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- PPK & MAK -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            PPK <span class="text-red-500">*</span>
                        </label>
                        <select name="ppk_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('ppk_id') border-red-500 @enderror">
                            <option value="">Pilih PPK</option>
                            @foreach($ppkData as $ppk)
                                <option value="{{ $ppk->id }}" {{ old('ppk_id', $kegiatan->ppk_id) == $ppk->id ? 'selected' : '' }}>
                                    {{ $ppk->nama }} ({{ $ppk->nip }})
                                </option>
                            @endforeach
                        </select>
                        @error('ppk_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Akun (MAK) <span class="text-red-500">*</span>
                        </label>
                        <select name="mak_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('mak_id') border-red-500 @enderror">
                            <option value="">Pilih MAK</option>
                            @foreach($makData as $mak)
                                <option value="{{ $mak->id }}" {{ old('mak_id', $kegiatan->mak_id) == $mak->id ? 'selected' : '' }}>
                                    {{ $mak->kode }} - {{ $mak->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('mak_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bendahara -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bendahara
                    </label>
                    <select name="bendahara_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('bendahara_id') border-red-500 @enderror">
                        <option value="">Pilih Bendahara</option>
                        @foreach($bendaharaData as $bendahara)
                            <option value="{{ $bendahara->id }}" {{ old('bendahara_id', $kegiatan->bendahara_id) == $bendahara->id ? 'selected' : '' }}>
                                {{ $bendahara->nama }} - {{ $bendahara->nip }}
                            </option>
                        @endforeach
                    </select>
                    @error('bendahara_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Provinsi & Detail Lokasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <select name="provinsi_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('provinsi_id') border-red-500 @enderror">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinsiData as $prov)
                                <option value="{{ $prov->id }}" {{ old('provinsi_id', $kegiatan->provinsi_id) == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nama_provinsi }}
                                </option>
                            @endforeach
                        </select>
                        @error('provinsi_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Detail Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="detail_lokasi" value="{{ old('detail_lokasi', $kegiatan->detail_lokasi) }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('detail_lokasi') border-red-500 @enderror">
                        @error('detail_lokasi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- File Laporan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        File Laporan Kegiatan
                    </label>
                    @if($kegiatan->file_laporan)
                        <p class="text-xs text-gray-500 mb-2">File saat ini: {{ basename($kegiatan->file_laporan) }}</p>
                    @endif
                    <input type="file" name="file_laporan" accept=".pdf,.doc,.docx"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white file:cursor-pointer hover:file:bg-primary-dark">
                    <p class="text-gray-500 text-xs mt-1">Format: PDF, DOC, DOCX (Maks: 10MB). Kosongkan jika tidak ingin mengganti.</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium text-center">
                        Update Data
                    </button>
                    <a href="{{ route('kegiatan.index') }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
