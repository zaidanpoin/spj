@extends('layouts.app')

@section('title', 'Edit SPPD')
@section('page-title', 'Edit SPPD')
@section('page-subtitle', 'Edit data Surat Perintah Perjalanan Dinas')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Edit SPPD</h2>
            </div>

            <form action="{{ route('sppd.update', $sppd->id) }}" method="POST" class="card-body space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Pelaksana -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelaksana *</label>
                        <input type="text" name="nama" value="{{ old('nama', $sppd->nama) }}"
                               class="form-input w-full" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                        <input type="text" name="nip" value="{{ old('nip', $sppd->nip) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pangkat/Golongan</label>
                        <input type="text" name="pangkat_gol" value="{{ old('pangkat_gol', $sppd->pangkat_gol) }}"
                               class="form-input w-full">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $sppd->jabatan) }}"
                               class="form-input w-full">
                    </div>

                    <!-- Detail Perjalanan -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksud Perjalanan Dinas</label>
                        <textarea name="maksud" rows="2" class="form-input w-full">{{ old('maksud', $sppd->maksud) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan</label>
                        <input type="text" name="tujuan" value="{{ old('tujuan', $sppd->tujuan) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Berangkat</label>
                        <input type="text" name="tempat_berangkat" value="{{ old('tempat_berangkat', $sppd->tempat_berangkat) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berangkat</label>
                        <input type="date" name="tgl_brkt"
                               value="{{ old('tgl_brkt', $sppd->tgl_brkt ? $sppd->tgl_brkt->format('Y-m-d') : '') }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali</label>
                        <input type="date" name="tgl_kbl"
                               value="{{ old('tgl_kbl', $sppd->tgl_kbl ? $sppd->tgl_kbl->format('Y-m-d') : '') }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alat Angkut</label>
                        <input type="text" name="alat_angkut" value="{{ old('alat_angkut', $sppd->alat_angkut) }}"
                               placeholder="Pesawat, Kereta, Mobil Dinas, dll"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Biaya</label>
                        <select name="tingkat_biaya" class="form-select w-full">
                            <option value="">Pilih Tingkat Biaya</option>
                            <option value="A" {{ old('tingkat_biaya', $sppd->tingkat_biaya) == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('tingkat_biaya', $sppd->tingkat_biaya) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('tingkat_biaya', $sppd->tingkat_biaya) == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('tingkat_biaya', $sppd->tingkat_biaya) == 'D' ? 'selected' : '' }}>D</option>
                        </select>
                    </div>

                    <!-- Nomor SPPD -->
                    <div class="md:col-span-2">
                        <h4 class="font-semibold text-gray-700 mb-2 mt-4">Nomor SPPD</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SPT</label>
                        <input type="text" name="no" value="{{ old('no', $sppd->no) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal SPT</label>
                        <input type="date" name="tgl"
                               value="{{ old('tgl', $sppd->tgl ? $sppd->tgl->format('Y-m-d') : '') }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor SPD</label>
                        <input type="text" name="nospd" value="{{ old('nospd', $sppd->nospd) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal SPD</label>
                        <input type="date" name="tglspd"
                               value="{{ old('tglspd', $sppd->tglspd ? $sppd->tglspd->format('Y-m-d') : '') }}"
                               class="form-input w-full">
                    </div>

                    <!-- Pejabat -->
                    <div class="md:col-span-2">
                        <h4 class="font-semibold text-gray-700 mb-2 mt-4">Pejabat Pemberi Perintah</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" name="perintah_nama" value="{{ old('perintah_nama', $sppd->perintah_nama) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                        <input type="text" name="perintah_nip" value="{{ old('perintah_nip', $sppd->perintah_nip) }}"
                               class="form-input w-full">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="perintah_jabatan" value="{{ old('perintah_jabatan', $sppd->perintah_jabatan) }}"
                               class="form-input w-full">
                    </div>

                    <!-- PPK -->
                    <div class="md:col-span-2">
                        <h4 class="font-semibold text-gray-700 mb-2 mt-4">PPK (Pejabat Pembuat Komitmen)</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama PPK</label>
                        <input type="text" name="ppk_nama" value="{{ old('ppk_nama', $sppd->ppk_nama) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP PPK</label>
                        <input type="text" name="ppk_nip" value="{{ old('ppk_nip', $sppd->ppk_nip) }}"
                               class="form-input w-full">
                    </div>

                    <!-- Bendahara -->
                    <div class="md:col-span-2">
                        <h4 class="font-semibold text-gray-700 mb-2 mt-4">Bendahara</h4>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Bendahara</label>
                        <input type="text" name="bendahara_nama" value="{{ old('bendahara_nama', $sppd->bendahara_nama) }}"
                               class="form-input w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP Bendahara</label>
                        <input type="text" name="bendahara_nip" value="{{ old('bendahara_nip', $sppd->bendahara_nip) }}"
                               class="form-input w-full">
                    </div>

                    <!-- Status PNS -->
                    <div class="md:col-span-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_pns" value="1"
                                   {{ old('is_pns', $sppd->is_pns) ? 'checked' : '' }}
                                   class="form-checkbox">
                            <span class="ml-2 text-sm text-gray-700">Pelaksana adalah PNS</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6 border-t">
                    <a href="{{ route('kegiatan.pilih-detail', $sppd->kegiatan_id) }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        💾 Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
