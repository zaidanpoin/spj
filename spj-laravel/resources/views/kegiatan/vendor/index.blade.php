@extends('layouts.app')

@section('title', 'Manajemen Vendor Kegiatan')
@section('page-title', 'Manajemen Vendor Kegiatan')
@section('page-subtitle', 'Kelola vendor dan nomor surat untuk kegiatan')

@section('content')
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Edit Kegiatan
            </a>
            <h2 class="text-xl font-semibold text-gray-900 mt-2">{{ $kegiatan->nama_kegiatan }}</h2>
            <p class="text-sm text-gray-500">{{ $kegiatan->unitKerja->nama_unit_kerja ?? '-' }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Form Tambah Vendor -->
        <div class="bg-white rounded-lg border border-gray-200 mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Tambah Vendor Baru</h3>
                <p class="text-sm text-gray-500 mt-0.5">Pilih vendor dan masukkan nomor surat terkait</p>
            </div>

            <form action="{{ route('kegiatan.vendor.store', $kegiatan->id) }}" method="POST" class="p-4 sm:p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <!-- Pilih Vendor -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih Vendor <span class="text-red-500">*</span>
                        </label>
                        <select name="vendor_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('vendor_id') border-red-500 @enderror">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($allVendors as $vendor)
                                <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('vendor_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Berita Acara -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Berita Acara
                        </label>
                        <input type="text" name="nomor_berita_acara" value="{{ old('nomor_berita_acara') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nomor_berita_acara') border-red-500 @enderror"
                            placeholder="Contoh: BA/001/2026">
                        @error('nomor_berita_acara')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor BAST -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor BAST
                            <span class="text-xs text-gray-500">(Berita Acara Serah Terima Barang/Pekerjaan)</span>
                        </label>
                        <input type="text" name="nomor_bast" value="{{ old('nomor_bast') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nomor_bast') border-red-500 @enderror"
                            placeholder="Contoh: BAST/001/2026">
                        @error('nomor_bast')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor Berita Pembayaran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nomor Berita Pembayaran
                        </label>
                        <input type="text" name="nomor_berita_pembayaran" value="{{ old('nomor_berita_pembayaran') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nomor_berita_pembayaran') border-red-500 @enderror"
                            placeholder="Contoh: BP/001/2026">
                        @error('nomor_berita_pembayaran')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Data Detail Vendor untuk Kegiatan Ini -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <h4 class="text-sm font-semibold text-gray-900 mb-3">Data Detail Vendor untuk Kegiatan Ini</h4>
                    <p class="text-xs text-gray-500 mb-4">Data ini spesifik untuk vendor di kegiatan ini dan bisa berbeda dengan kegiatan lain</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Direktur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Direktur
                            </label>
                            <input type="text" name="nama_direktur" value="{{ old('nama_direktur') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nama_direktur') border-red-500 @enderror"
                                placeholder="Nama lengkap direktur">
                            @error('nama_direktur')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jabatan
                            </label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('jabatan') border-red-500 @enderror"
                                placeholder="Contoh: Direktur Utama">
                            @error('jabatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NPWP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NPWP
                            </label>
                            <input type="text" name="npwp" value="{{ old('npwp') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('npwp') border-red-500 @enderror"
                                placeholder="00.000.000.0-000.000">
                            @error('npwp')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- PPN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                PPN (%)
                            </label>
                            <input type="text" name="ppn" value="{{ old('ppn') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('ppn') border-red-500 @enderror"
                                placeholder="Contoh: 11">
                            @error('ppn')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat
                            </label>
                            <textarea name="alamat" rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('alamat') border-red-500 @enderror"
                                placeholder="Alamat lengkap vendor">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bank
                            </label>
                            <input type="text" name="bank" value="{{ old('bank') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('bank') border-red-500 @enderror"
                                placeholder="Nama bank">
                            @error('bank')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rekening -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Rekening
                            </label>
                            <input type="text" name="rekening" value="{{ old('rekening') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('rekening') border-red-500 @enderror"
                                placeholder="Nomor rekening">
                            @error('rekening')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Vendor
                    </button>
                </div>
            </form>
        </div>

        <!-- Daftar Vendor yang Sudah Ditambahkan -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Vendor yang Sudah Ditambahkan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Daftar vendor dan nomor surat untuk kegiatan ini</p>
            </div>

            <div class="overflow-x-auto">
                @if($kegiatan->vendors->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Vendor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direktur / NPWP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Berita Acara</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. BAST</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Berita Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($kegiatan->vendors as $index => $vendor)
                                <tr class="hover:bg-gray-50" id="vendor-row-{{ $vendor->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $vendor->nama_vendor }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $vendor->pivot->nama_direktur ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $vendor->pivot->npwp ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span id="ba-view-{{ $vendor->id }}">{{ $vendor->pivot->nomor_berita_acara ?? '-' }}</span>
                                        <input type="text" id="ba-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->nomor_berita_acara }}" class="hidden w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span id="bast-view-{{ $vendor->id }}">{{ $vendor->pivot->nomor_bast ?? '-' }}</span>
                                        <input type="text" id="bast-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->nomor_bast }}" class="hidden w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span id="bp-view-{{ $vendor->id }}">{{ $vendor->pivot->nomor_berita_pembayaran ?? '-' }}</span>
                                        <input type="text" id="bp-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->nomor_berita_pembayaran }}" class="hidden w-full px-2 py-1 border border-gray-300 rounded text-sm">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <!-- Edit Button -->
                                            <button onclick="toggleEdit({{ $vendor->id }})" id="edit-btn-{{ $vendor->id }}"
                                                class="text-blue-600 hover:text-blue-900 font-medium" title="Edit">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>

                                            <!-- Save Button (hidden initially) -->
                                            <button onclick="saveEdit({{ $kegiatan->id }}, {{ $vendor->id }})" id="save-btn-{{ $vendor->id }}"
                                                class="hidden text-green-600 hover:text-green-900 font-medium" title="Simpan">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>

                                            <!-- Cancel Button (hidden initially) -->
                                            <button onclick="cancelEdit({{ $vendor->id }})" id="cancel-btn-{{ $vendor->id }}"
                                                class="hidden text-gray-600 hover:text-gray-900 font-medium" title="Batal">
                                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('kegiatan.vendor.destroy', [$kegiatan->id, $vendor->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus vendor {{ $vendor->nama_vendor }} dari kegiatan ini?')"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium" title="Hapus">
                                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Detail Row untuk Vendor Details (Hidden by default, shown saat edit) -->
                                <tr id="detail-row-{{ $vendor->id }}" class="hidden bg-gray-50">
                                    <td colspan="7" class="px-6 py-4">
                                        <div class="bg-white p-4 rounded border border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Detail Vendor untuk Kegiatan Ini</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <!-- Nama Direktur -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Direktur</label>
                                                    <input type="text" id="direktur-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->nama_direktur }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Nama direktur">
                                                </div>

                                                <!-- Jabatan -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jabatan</label>
                                                    <input type="text" id="jabatan-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->jabatan }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Jabatan">
                                                </div>

                                                <!-- NPWP -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">NPWP</label>
                                                    <input type="text" id="npwp-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->npwp }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="NPWP">
                                                </div>

                                                <!-- PPN -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">PPN (%)</label>
                                                    <input type="text" id="ppn-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->ppn }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="PPN">
                                                </div>

                                                <!-- Bank -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bank</label>
                                                    <input type="text" id="bank-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->bank }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Nama bank">
                                                </div>

                                                <!-- Rekening -->
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                                    <input type="text" id="rekening-edit-{{ $vendor->id }}" value="{{ $vendor->pivot->rekening }}"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Nomor rekening">
                                                </div>

                                                <!-- Alamat -->
                                                <div class="md:col-span-3">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Alamat</label>
                                                    <textarea id="alamat-edit-{{ $vendor->id }}" rows="2"
                                                        class="w-full px-2 py-1 border border-gray-300 rounded text-sm" placeholder="Alamat lengkap">{{ $vendor->pivot->alamat }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada vendor</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan vendor menggunakan form di atas.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Store original values
        const originalValues = {};

        function toggleEdit(vendorId) {
            // Store original values
            originalValues[vendorId] = {
                ba: document.getElementById('ba-edit-' + vendorId).value,
                bast: document.getElementById('bast-edit-' + vendorId).value,
                bp: document.getElementById('bp-edit-' + vendorId).value,
                // Vendor details
                direktur: document.getElementById('direktur-edit-' + vendorId).value,
                jabatan: document.getElementById('jabatan-edit-' + vendorId).value,
                npwp: document.getElementById('npwp-edit-' + vendorId).value,
                ppn: document.getElementById('ppn-edit-' + vendorId).value,
                bank: document.getElementById('bank-edit-' + vendorId).value,
                rekening: document.getElementById('rekening-edit-' + vendorId).value,
                alamat: document.getElementById('alamat-edit-' + vendorId).value
            };

            // Toggle views and inputs for nomor surat
            document.getElementById('ba-view-' + vendorId).classList.add('hidden');
            document.getElementById('ba-edit-' + vendorId).classList.remove('hidden');

            document.getElementById('bast-view-' + vendorId).classList.add('hidden');
            document.getElementById('bast-edit-' + vendorId).classList.remove('hidden');

            document.getElementById('bp-view-' + vendorId).classList.add('hidden');
            document.getElementById('bp-edit-' + vendorId).classList.remove('hidden');

            // Show detail row untuk vendor details
            document.getElementById('detail-row-' + vendorId).classList.remove('hidden');

            // Toggle buttons
            document.getElementById('edit-btn-' + vendorId).classList.add('hidden');
            document.getElementById('save-btn-' + vendorId).classList.remove('hidden');
            document.getElementById('cancel-btn-' + vendorId).classList.remove('hidden');
        }

        function cancelEdit(vendorId) {
            // Restore original values
            if (originalValues[vendorId]) {
                document.getElementById('ba-edit-' + vendorId).value = originalValues[vendorId].ba;
                document.getElementById('bast-edit-' + vendorId).value = originalValues[vendorId].bast;
                document.getElementById('bp-edit-' + vendorId).value = originalValues[vendorId].bp;
                // Vendor details
                document.getElementById('direktur-edit-' + vendorId).value = originalValues[vendorId].direktur;
                document.getElementById('jabatan-edit-' + vendorId).value = originalValues[vendorId].jabatan;
                document.getElementById('npwp-edit-' + vendorId).value = originalValues[vendorId].npwp;
                document.getElementById('ppn-edit-' + vendorId).value = originalValues[vendorId].ppn;
                document.getElementById('bank-edit-' + vendorId).value = originalValues[vendorId].bank;
                document.getElementById('rekening-edit-' + vendorId).value = originalValues[vendorId].rekening;
                document.getElementById('alamat-edit-' + vendorId).value = originalValues[vendorId].alamat;
            }

            // Toggle views and inputs back
            document.getElementById('ba-view-' + vendorId).classList.remove('hidden');
            document.getElementById('ba-edit-' + vendorId).classList.add('hidden');

            document.getElementById('bast-view-' + vendorId).classList.remove('hidden');
            document.getElementById('bast-edit-' + vendorId).classList.add('hidden');

            document.getElementById('bp-view-' + vendorId).classList.remove('hidden');
            document.getElementById('bp-edit-' + vendorId).classList.add('hidden');

            // Hide detail row
            document.getElementById('detail-row-' + vendorId).classList.add('hidden');

            // Toggle buttons
            document.getElementById('edit-btn-' + vendorId).classList.remove('hidden');
            document.getElementById('save-btn-' + vendorId).classList.add('hidden');
            document.getElementById('cancel-btn-' + vendorId).classList.add('hidden');
        }

        function saveEdit(kegiatanId, vendorId) {
            // Get nomor surat values
            const baValue = document.getElementById('ba-edit-' + vendorId).value;
            const bastValue = document.getElementById('bast-edit-' + vendorId).value;
            const bpValue = document.getElementById('bp-edit-' + vendorId).value;

            // Get vendor detail values
            const direkturValue = document.getElementById('direktur-edit-' + vendorId).value;
            const jabatanValue = document.getElementById('jabatan-edit-' + vendorId).value;
            const npwpValue = document.getElementById('npwp-edit-' + vendorId).value;
            const ppnValue = document.getElementById('ppn-edit-' + vendorId).value;
            const bankValue = document.getElementById('bank-edit-' + vendorId).value;
            const rekeningValue = document.getElementById('rekening-edit-' + vendorId).value;
            const alamatValue = document.getElementById('alamat-edit-' + vendorId).value;

            // Create form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/kegiatan/${kegiatanId}/vendor/${vendorId}`;

            // CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            // Method PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);

            // Add nomor surat values
            const baInput = document.createElement('input');
            baInput.type = 'hidden';
            baInput.name = 'nomor_berita_acara';
            baInput.value = baValue;
            form.appendChild(baInput);

            const bastInput = document.createElement('input');
            bastInput.type = 'hidden';
            bastInput.name = 'nomor_bast';
            bastInput.value = bastValue;
            form.appendChild(bastInput);

            const bpInput = document.createElement('input');
            bpInput.type = 'hidden';
            bpInput.name = 'nomor_berita_pembayaran';
            bpInput.value = bpValue;
            form.appendChild(bpInput);

            // Add vendor detail values
            const direkturInput = document.createElement('input');
            direkturInput.type = 'hidden';
            direkturInput.name = 'nama_direktur';
            direkturInput.value = direkturValue;
            form.appendChild(direkturInput);

            const jabatanInput = document.createElement('input');
            jabatanInput.type = 'hidden';
            jabatanInput.name = 'jabatan';
            jabatanInput.value = jabatanValue;
            form.appendChild(jabatanInput);

            const npwpInput = document.createElement('input');
            npwpInput.type = 'hidden';
            npwpInput.name = 'npwp';
            npwpInput.value = npwpValue;
            form.appendChild(npwpInput);

            const ppnInput = document.createElement('input');
            ppnInput.type = 'hidden';
            ppnInput.name = 'ppn';
            ppnInput.value = ppnValue;
            form.appendChild(ppnInput);

            const bankInput = document.createElement('input');
            bankInput.type = 'hidden';
            bankInput.name = 'bank';
            bankInput.value = bankValue;
            form.appendChild(bankInput);

            const rekeningInput = document.createElement('input');
            rekeningInput.type = 'hidden';
            rekeningInput.name = 'rekening';
            rekeningInput.value = rekeningValue;
            form.appendChild(rekeningInput);

            const alamatInput = document.createElement('input');
            alamatInput.type = 'hidden';
            alamatInput.name = 'alamat';
            alamatInput.value = alamatValue;
            form.appendChild(alamatInput);

            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
