@extends('layouts.app')

@section('title', 'Input SPPD')
@section('page-title', 'SPPD BARU')
@section('page-subtitle', 'Surat Perintah Perjalanan Dinas')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h2 class="card-title">SPPD BARU</h2>
                <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}" class="btn btn-sm btn-secondary">
                    Close
                </a>
            </div>

            <form action="{{ route('sppd.store') }}" method="POST" class="card-body">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">

                @php
                    $draft = $draftData && $draftData->count() > 0 ? $draftData->first() : null;
                @endphp

                <div class="space-y-4">
                    <!-- Row: Lembar & Kode No -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lembar</label>
                            <input type="text" name="sppd[0][lembar]"
                                class="form-input w-full" placeholder="-">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode No</label>
                            <input type="text" name="sppd[0][kode_no]"
                                class="form-input w-full" placeholder="-">
                        </div>
                    </div>

                    <!-- Row: Nomor SPT & Tanggal SPT -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SPT</label>
                            <input type="text" name="sppd[0][no]"
                                class="form-input w-full" placeholder="/SPT/Ms/II/2026">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SPT</label>
                            <input type="date" name="sppd[0][tgl]"
                                class="form-input w-full">
                        </div>
                    </div>

                    <!-- Row: Nomor SPD & Tanggal SPD -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SPD</label>
                            <input type="text" name="sppd[0][nospd]"
                                class="form-input w-full" placeholder="/SPD/Ms/II/2026">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal SPD</label>
                            <input type="date" name="sppd[0][tglspd]"
                                class="form-input w-full">
                        </div>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                           <input type="text" name="sppd[0][nama]"
                               class="form-input w-full" required>
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                           <input type="text" name="sppd[0][nip]"
                               class="form-input w-full">
                    </div>

                    <!-- Pangkat/Golongan/PGPS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pangkat/Golongan/PGPS</label>
                           <input type="text" name="sppd[0][pangkat_gol]"
                               class="form-input w-full">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                        <textarea name="sppd[0][jabatan]" rows="3" class="form-input w-full"></textarea>
                    </div>

                    <!-- Eselon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eselon</label>
                           <input type="text" name="sppd[0][eselon]"
                               class="form-input w-full">
                    </div>

                    <!-- Maksud -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maksud</label>
                        <textarea name="sppd[0][maksud]" rows="3" class="form-input w-full"></textarea>
                    </div>

                    <!-- Tempat Berangkat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Berangkat</label>
                           <input type="text" name="sppd[0][tempat_berangkat]"
                               class="form-input w-full" placeholder="Jakarta">
                    </div>

                    <!-- Tujuan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan</label>
                           <input type="text" name="sppd[0][tujuan]"
                               class="form-input w-full">
                    </div>

                    <!-- JeniTujuan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">JeniTujuan</label>
                        <select name="sppd[0][jenis_tujuan]" class="form-select w-full">
                            <option value="0">Dalam Kota</option>
                            <option value="1">Luar Kota</option>
                        </select>
                    </div>

                    <!-- Alat Angkut -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alat Angkut</label>
                        <select name="sppd[0][alat_angkut]" class="form-select w-full">
                            <option value="">Pilih Alat Angkut</option>
                            <option value="Pesawat">Pesawat</option>
                            <option value="Kereta">Kereta</option>
                            <option value="Mobil Dinas">Mobil Dinas</option>
                            <option value="Bus">Bus</option>
                            <option value="Kapal Laut">Kapal Laut</option>
                        </select>
                    </div>

                    <!-- Kendaraan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan</label>
                           <input type="text" name="sppd[0][kendaraan]"
                               class="form-input w-full">
                    </div>

                    <!-- Tgl Berangkat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Berangkat</label>
                           <input type="date" name="sppd[0][tgl_brkt]"
                               class="form-input w-full">
                    </div>

                    <!-- Tgl (harus kembali) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tgl</label>
                        <div class="flex gap-2">
                            <select name="sppd[0][kembali_baru]" class="form-select" style="width: 150px;">
                                <option value="harus kembali">harus kembali</option>
                                <option value="telah kembali">telah kembali</option>
                            </select>
                            <input type="date" name="sppd[0][tgl_kbl]"
                                class="form-input flex-1">
                        </div>
                    </div>

                    <!-- Nama Pemberi Perintah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemberi Perintah</label>
                           <input type="text" name="sppd[0][perintah_nama]"
                               class="form-input w-full">
                    </div>

                    <!-- NIP Pemberi Perintah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pemberi Perintah</label>
                           <input type="text" name="sppd[0][perintah_nip]"
                               class="form-input w-full">
                    </div>

                    <!-- Jabatan Pemberi Perintah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan Pemberi Perintah</label>
                        <textarea name="sppd[0][perintah_jabatan]" rows="3" class="form-input w-full"></textarea>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                           <input type="text" name="sppd[0][catatan]"
                               class="form-input w-full">
                    </div>

                    <!-- Nama PPK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama PPK</label>
                           <input type="text" name="sppd[0][ppk_nama]"
                               class="form-input w-full">
                    </div>

                    <!-- NIP PPK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP PPK</label>
                           <input type="text" name="sppd[0][ppk_nip]"
                               class="form-input w-full">
                    </div>

                    <!-- Nama Bendahara -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bendahara</label>
                           <input type="text" name="sppd[0][bendahara_nama]"
                               class="form-input w-full" placeholder="Endah Anggun Ningsih, S.E.">
                    </div>

                    <!-- NIP Bendahara -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP Bendahara</label>
                           <input type="text" name="sppd[0][bendahara_nip]"
                               class="form-input w-full" placeholder="198701162015032001">
                    </div>

                    <!-- Nama Pembuat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pembuat</label>
                           <input type="text" name="sppd[0][pembuat_nama]"
                               class="form-input w-full">
                    </div>

                    <!-- NIP Pembuat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIP Pembuat</label>
                           <input type="text" name="sppd[0][pembuat_nip]"
                               class="form-input w-full">
                    </div>

                    <!-- Tingkat Biaya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Biaya</label>
                           <input type="text" name="sppd[0][tingkat_biaya]"
                               class="form-input w-full">
                    </div>

                    <!-- Akun -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Akun</label>
                           <input type="text" name="sppd[0][akun]"
                               class="form-input w-full" placeholder="- -">
                    </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Pelaksana -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pelaksana *</label>
                                        <input type="text" name="sppd[0][nama]"
                                               class="form-input w-full" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                                             <input type="text" name="sppd[0][nip]"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pangkat/Golongan</label>
                                             <input type="text" name="sppd[0][pangkat_gol]"
                                                 class="form-input w-full">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Jabatan</label>
                                             <input type="text" name="sppd[0][jabatan]"
                                                 class="form-input w-full">
                                    </div>

                                    <!-- Detail Perjalanan -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksud Perjalanan Dinas</label>
                                        <textarea name="sppd[0][maksud]" rows="2"
                                                  class="form-input w-full"></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan</label>
                                             <input type="text" name="sppd[0][tujuan]"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Berangkat</label>
                                             <input type="text" name="sppd[0][tempat_berangkat]"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berangkat</label>
                                             <input type="date" name="sppd[0][tgl_brkt]"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kembali</label>
                                             <input type="date" name="sppd[0][tgl_kbl]"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alat Angkut</label>
                                             <input type="text" name="sppd[0][alat_angkut]"
                                                 placeholder="Pesawat, Kereta, Mobil Dinas, dll"
                                                 class="form-input w-full">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Biaya</label>
                                            <select name="sppd[0][tingkat_biaya]" class="form-select w-full">
                                            <option value="">Pilih Tingkat Biaya</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>


                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6 border-t mt-6">
                    <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <div class="flex gap-3">
                        <button type="submit" name="save_as_draft" value="1"
                                class="btn btn-warning">
                            💾 Simpan sebagai Draft
                        </button>
                        <button type="submit" name="save_as_draft" value="0"
                                class="btn btn-primary">
                            ✅ Simpan & Kembali
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
