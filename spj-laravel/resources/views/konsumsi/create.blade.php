@extends('layouts.app')

@section('title', 'Input Konsumsi')
@section('page-title', 'Data Konsumsi Rapat')
@section('page-subtitle', 'Input data konsumsi untuk kegiatan')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Input Konsumsi Kegiatan</h2>
            </div>

            <!-- Draft Info Box -->
            @php
                $hasDraft = \App\Models\Konsumsi::where('kegiatan_id', $kegiatan->id)
                    ->where('status', 'draft')
                    ->exists();
            @endphp

            @if($hasDraft)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">
                                ⚠️ Ada data draft yang belum difinalisasi untuk kegiatan ini
                            </p>
                            <p class="mt-1 text-xs text-yellow-700">
                                Data yang Anda simpan akan menambah/menimpa data draft yang ada
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- SBM Info Box -->
            @if($tarifSBM)
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">
                                Tarif SBM untuk {{ $tarifSBM['provinsi'] }}
                            </p>
                            <div class="mt-2 text-sm text-blue-700 space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">Makan:</span>
                                    <span class="bg-blue-100 px-2 py-0.5 rounded">Max Rp
                                        {{ number_format($tarifSBM['makan'], 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">Snack:</span>
                                    <span class="bg-blue-100 px-2 py-0.5 rounded">Max Rp
                                        {{ number_format($tarifSBM['snack'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-blue-600">
                                ⚠️ Harga yang melebihi tarif SBM tidak dapat disimpan
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Tabs -->

            <div class="border-b border-gray-200">
                <div class="flex">
                    <button type="button" onclick="switchTab('snack')" id="tab-snack"
                        class="px-6 py-3 text-sm font-medium border-b-2 border-primary text-primary">
                        🍪 Snack / Kudapan
                    </button>
                    <button type="button" onclick="switchTab('makanan')" id="tab-makanan"
                        class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-primary">
                        🍱 Makanan
                    </button>
                    <button type="button" onclick="switchTab('barang')" id="tab-barang"
                        class="px-6 py-3 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-primary">
                        📦 Barang
                    </button>
                </div>
            </div>

            <form id="konsumsi-form" action="{{ route('konsumsi.store') }}" method="POST" class="card-body space-y-6">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">

                <!-- Pre-populate vendor data from draft as hidden inputs -->
                @if(isset($vendorDataFromDraft) && count($vendorDataFromDraft) > 0)
                    @foreach($vendorDataFromDraft as $vendorNama => $vendorInfo)
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][nama_direktur]" value="{{ $vendorInfo['nama_direktur'] }}">
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][jabatan]" value="{{ $vendorInfo['jabatan'] }}">
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][npwp]" value="{{ $vendorInfo['npwp'] }}">
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][alamat]" value="{{ $vendorInfo['alamat'] }}">
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][bank]" value="{{ $vendorInfo['bank'] ?? '' }}">
                        <input type="hidden" name="vendor_data[{{ $vendorNama }}][rekening]" value="{{ $vendorInfo['rekening'] ?? '' }}">
                    @endforeach
                @endif

                <!-- Tab Content: Snack -->
                <div id="content-snack" class="tab-content">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Daftar Snack / Kudapan</h3>
                        <p class="text-sm text-gray-500">Tambahkan item snack yang disediakan</p>
                    </div>

                    <div id="snack-container" class="space-y-3">
                        @if($draftData['snack']->count() > 0)
                            <!-- Load Draft Snack Items -->
                            @foreach($draftData['snack'] as $index => $item)
                                <div class="snack-item border border-gray-200 rounded-lg p-4 bg-yellow-50 relative">
                                    @if($index > 0)
                                        <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                            class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                            ✕ Hapus
                                        </button>
                                    @endif
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-2">
                                            <label class="form-label">Waktu</label>
                                            <select name="snack[{{ $index }}][waktu_konsumsi_id]" class="form-input">
                                                @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'snack')) as $wk)
                                                    <option value="{{ $wk->id }}" {{ $item->waktu_konsumsi_id == $wk->id ? 'selected' : '' }}>
                                                        {{ $wk->nama_waktu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">Nama Snack</label>
                                            <input type="text" name="snack[{{ $index }}][nama]" class="form-input"
                                                placeholder="Contoh: Kue Basah, dll" value="{{ $item->nama_konsumsi }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">No. Kwitansi</label>
                                            <input type="text" name="snack[{{ $index }}][no_kwitansi]" class="form-input"
                                                placeholder="No. Kwitansi" value="{{ $item->no_kwitansi }}">
                                        </div>
                                        <div class="col-span-1">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="snack[{{ $index }}][jumlah]" class="form-input snack-qty"
                                                data-index="{{ $index }}" value="{{ $item->jumlah }}" min="1">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="form-label">Harga Satuan (Rp)</label>
                                            <input type="number" name="snack[{{ $index }}][harga]" class="form-input snack-price"
                                                data-index="{{ $index }}" value="{{ $item->harga }}" min="0"
                                                onchange="validateSBM('snack', {{ $index }}); calculateTotals();">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label text-xs">Subtotal</label>
                                            <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium snack-subtotal"
                                                data-index="{{ $index }}">
                                                Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default Snack Item Template -->
                            <div class="snack-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="grid grid-cols-12 gap-3 items-end">
                                    <div class="col-span-2">
                                        <label class="form-label">Waktu</label>
                                        <select name="snack[0][waktu_konsumsi_id]" class="form-input">
                                            @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'snack')) as $wk)
                                                <option value="{{ $wk->id }}">{{ $wk->nama_waktu }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Nama Snack</label>
                                        <input type="text" name="snack[0][nama]" class="form-input"
                                            placeholder="Contoh: Kue Basah, dll">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">No. Kwitansi</label>
                                        <input type="text" name="snack[0][no_kwitansi]" class="form-input"
                                            placeholder="No. Kwitansi">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="snack[0][jumlah]" class="form-input snack-qty" data-index="0"
                                            value="{{ $kegiatan->jumlah_peserta ?? 1 }}" min="1">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">Harga Satuan (Rp)</label>
                                        <input type="number" name="snack[0][harga]" class="form-input snack-price"
                                            data-index="0" value="0" min="0"
                                            onchange="validateSBM('snack', 0); calculateTotals();">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">Subtotal</label>
                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium snack-subtotal"
                                            data-index="0">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <button type="button" onclick="addSnackItem()"
                            class="text-sm text-primary hover:text-primary-dark font-medium">
                            + Tambah Snack
                        </button>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total Snack:</div>
                            <div id="total-snack" class="text-xl font-bold text-primary">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Makanan -->
                <div id="content-makanan" class="tab-content hidden">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Daftar Makanan</h3>
                        <p class="text-sm text-gray-500">Tambahkan item makanan yang disediakan</p>
                    </div>

                    <div id="makanan-container" class="space-y-3">
                        @if($draftData['makanan']->count() > 0)
                            <!-- Load Draft Makanan Items -->
                            @foreach($draftData['makanan'] as $index => $item)
                                <div class="makanan-item border border-gray-200 rounded-lg p-4 bg-yellow-50 relative">
                                    @if($index > 0)
                                        <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                            class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                            ✕ Hapus
                                        </button>
                                    @endif
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-2">
                                            <label class="form-label">Waktu</label>
                                            <select name="makanan[{{ $index }}][waktu_konsumsi_id]" class="form-input">
                                                @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'makan')) as $wk)
                                                    <option value="{{ $wk->id }}" {{ $item->waktu_konsumsi_id == $wk->id ? 'selected' : '' }}>
                                                        {{ $wk->nama_waktu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">Nama Makanan</label>
                                            <input type="text" name="makanan[{{ $index }}][nama]" class="form-input"
                                                placeholder="Contoh: Nasi Box, dll" value="{{ $item->nama_konsumsi }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">No. Kwitansi</label>
                                            <input type="text" name="makanan[{{ $index }}][no_kwitansi]" class="form-input"
                                                placeholder="No. Kwitansi" value="{{ $item->no_kwitansi }}">
                                        </div>
                                        <div class="col-span-1">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="makanan[{{ $index }}][jumlah]" class="form-input makanan-qty"
                                                data-index="{{ $index }}" value="{{ $item->jumlah }}" min="1">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="form-label">Harga Satuan (Rp)</label>
                                            <input type="number" name="makanan[{{ $index }}][harga]"
                                                class="form-input makanan-price" data-index="{{ $index }}"
                                                value="{{ $item->harga }}" min="0"
                                                onchange="validateSBM('makanan', {{ $index }}); calculateTotals();">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label text-xs">Subtotal</label>
                                            <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium makanan-subtotal"
                                                data-index="{{ $index }}">
                                                Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default Makanan Item Template -->
                            <div class="makanan-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="grid grid-cols-12 gap-3 items-end">
                                    <div class="col-span-2">
                                        <label class="form-label">Waktu</label>
                                        <select name="makanan[0][waktu_konsumsi_id]" class="form-input">
                                            @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'makan')) as $wk)
                                                <option value="{{ $wk->id }}">{{ $wk->nama_waktu }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Nama Makanan</label>
                                        <input type="text" name="makanan[0][nama]" class="form-input"
                                            placeholder="Contoh: Nasi Box, dll">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">No. Kwitansi</label>
                                        <input type="text" name="makanan[0][no_kwitansi]" class="form-input"
                                            placeholder="No. Kwitansi">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="makanan[0][jumlah]" class="form-input makanan-qty"
                                            data-index="0" value="{{ $kegiatan->jumlah_peserta ?? 1 }}" min="1">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">Harga Satuan (Rp)</label>
                                        <input type="number" name="makanan[0][harga]" class="form-input makanan-price"
                                            data-index="0" value="0" min="0"
                                            onchange="validateSBM('makanan', 0); calculateTotals();">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">Subtotal</label>
                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium makanan-subtotal"
                                            data-index="0">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <button type="button" onclick="addMakananItem()"
                            class="text-sm text-primary hover:text-primary-dark font-medium">
                            + Tambah Makanan
                        </button>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total Makanan:</div>
                            <div id="total-makanan" class="text-xl font-bold text-primary">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Barang -->
                <div id="content-barang" class="tab-content hidden">
                    <div class="mb-4">
                        <h3 class="text-base font-semibold text-gray-900 mb-2">Daftar Barang</h3>
                        <p class="text-sm text-gray-500">Tambahkan item barang (ATK, Perlengkapan, dll)</p>
                    </div>

                    <!-- Vendor Warning Banner (shown dynamically when needed) -->
                    <div id="vendor-warning-banner" class="hidden bg-orange-50 border-l-4 border-orange-500 p-4 mb-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-orange-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-orange-800">
                                    ⚠️ Vendor dengan total belanja ≥ Rp 10.000.000 wajib melengkapi data!
                                </p>
                                <p class="mt-1 text-xs text-orange-700">
                                    Klik tombol "Lengkapi Data Vendor" untuk mengisi data direktur, jabatan, NPWP, dan
                                    alamat.
                                </p>
                                <div id="vendor-incomplete-list" class="mt-2 text-xs text-orange-700"></div>
                            </div>
                        </div>
                    </div>

                    <div id="barang-container" class="space-y-3">
                        @if($draftData['barang']->count() > 0)
                            <!-- Load Draft Barang Items -->
                            @foreach($draftData['barang'] as $index => $item)
                                <div class="barang-item border border-gray-200 rounded-lg p-4 bg-yellow-50 relative">
                                    @if($index > 0)
                                        <button type="button"
                                            onclick="this.parentElement.remove(); calculateTotals(); calculateVendorTotals();"
                                            class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                            ✕ Hapus
                                        </button>
                                    @endif
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-2">
                                            <label class="form-label">Vendor/Toko</label>
                                            <input type="text" name="barang[{{ $index }}][vendor_nama]"
                                                class="form-input barang-vendor" data-index="{{ $index }}" placeholder="Nama Vendor"
                                                value="{{ $item->vendor->nama_vendor ?? '' }}" list="vendor-list">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">Nama Barang</label>
                                            <input type="text" name="barang[{{ $index }}][nama]" class="form-input"
                                                placeholder="Contoh: Kertas HVS, dll" value="{{ $item->nama_konsumsi }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">No. Kwitansi</label>
                                            <input type="text" name="barang[{{ $index }}][no_kwitansi]" class="form-input"
                                                placeholder="No. Kwitansi" value="{{ $item->no_kwitansi }}">
                                        </div>
                                        <div class="col-span-1">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" name="barang[{{ $index }}][jumlah]" class="form-input barang-qty"
                                                data-index="{{ $index }}" value="{{ $item->jumlah }}" min="1">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="form-label">Harga Satuan (Rp)</label>
                                            <input type="number" name="barang[{{ $index }}][harga]" class="form-input barang-price"
                                                data-index="{{ $index }}" value="{{ $item->harga }}" min="0">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label text-xs">Subtotal</label>
                                            <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium barang-subtotal"
                                                data-index="{{ $index }}">
                                                Rp {{ number_format($item->jumlah * $item->harga, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Default Barang Item Template -->
                            <div class="barang-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="grid grid-cols-12 gap-3 items-end">
                                    <div class="col-span-2">
                                        <label class="form-label">Vendor/Toko</label>
                                        <input type="text" name="barang[0][vendor_nama]" class="form-input barang-vendor"
                                            data-index="0" placeholder="Nama Vendor" list="vendor-list">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" name="barang[0][nama]" class="form-input"
                                            placeholder="Contoh: Kertas HVS, dll">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">No. Kwitansi</label>
                                        <input type="text" name="barang[0][no_kwitansi]" class="form-input"
                                            placeholder="No. Kwitansi">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="barang[0][jumlah]" class="form-input barang-qty"
                                            data-index="0" value="1" min="1">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">Harga Satuan (Rp)</label>
                                        <input type="number" name="barang[0][harga]" class="form-input barang-price"
                                            data-index="0" value="0" min="0">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">Subtotal</label>
                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium barang-subtotal"
                                            data-index="0">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Vendor Datalist for Autocomplete -->
                    <datalist id="vendor-list">
                        @foreach($vendors ?? [] as $vendor)
                            <option value="{{ $vendor->nama_vendor }}">
                        @endforeach
                    </datalist>

                    <!-- Vendor Summary Section -->
                    <div id="vendor-summary" class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200 hidden">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">📊 Ringkasan per Vendor</h4>
                        <div id="vendor-summary-list" class="space-y-2"></div>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                        <button type="button" onclick="addBarangItem()"
                            class="text-sm text-primary hover:text-primary-dark font-medium">
                            + Tambah Barang
                        </button>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total Barang:</div>
                            <div id="total-barang" class="text-xl font-bold text-primary">Rp 0</div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Detail Modal -->
                <div id="vendor-modal"
                    class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 flex items-center justify-center">
                    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">📋 Lengkapi Data Vendor</h3>
                            <button type="button" onclick="closeVendorModal()" class="text-gray-500 hover:text-gray-700">
                                ✕
                            </button>
                        </div>
                        <div id="vendor-modal-content" class="space-y-4">
                            <!-- Vendor forms will be inserted here dynamically -->
                        </div>
                        <div class="mt-6 flex gap-2 justify-end">
                            <button type="button" onclick="closeVendorModal()" class="btn-secondary">
                                Tutup
                            </button>
                            <button type="button" onclick="saveVendorData()" class="btn-primary">
                                💾 Simpan Data Vendor
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grand Total & Actions -->
                <div class="pt-4 border-t-2 border-gray-300">
                    <!-- Error/Success Messages -->
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                            <p class="text-red-700 font-medium">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-4">
                        <div class="text-lg font-semibold text-gray-900">Grand Total Konsumsi:</div>
                        <div id="grand-total" class="text-2xl font-bold text-primary">Rp 0</div>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" name="save_as_draft" value="0" class="btn-primary"
                            onclick="updateVendorHiddenInputs(); return validateForm();">
                            💾 Simpan & Validasi
                        </button>
                        <button type="submit" name="save_as_draft" value="1" class="btn-secondary"
                            onclick="updateVendorHiddenInputs(); return confirmDraft();">
                            📝 Simpan sebagai Draft
                        </button>
                        <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}" class="btn-secondary">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let snackIndex = {{ $draftData['snack']->count() > 0 ? $draftData['snack']->count() : 1 }};
            let makananIndex = {{ $draftData['makanan']->count() > 0 ? $draftData['makanan']->count() : 1 }};
            let barangIndex = {{ $draftData['barang']->count() > 0 ? $draftData['barang']->count() : 1 }};
            const jumlahPeserta = {{ $kegiatan->jumlah_peserta ?? 1 }};

            // SBM Tarif
            const tarifSBM = {
                makan: {{ $tarifSBM['makan'] ?? 0 }},
                snack: {{ $tarifSBM['snack'] ?? 0 }}
                                            };

            // Existing vendors data from server (keyed by vendor name)
            const vendorsData = @json($vendorsData ?? []);
            // Banks list from config
            const banksList = @json(config('banks')) || [];

            // Tab Switching
            function switchTab(tab) {
                // Update tab buttons
                document.querySelectorAll('[id^="tab-"]').forEach(btn => {
                    btn.classList.remove('border-primary', 'text-primary');
                    btn.classList.add('border-transparent', 'text-gray-600');
                });
                document.getElementById('tab-' + tab).classList.add('border-primary', 'text-primary');
                document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-600');

                // Update content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById('content-' + tab).classList.remove('hidden');
            }

            // Add Snack Item
            function addSnackItem() {
                const container = document.getElementById('snack-container');
                const template = `
                                                            <div class="snack-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                                                                <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                                                    ✕ Hapus
                                                                </button>
                                                                <div class="grid grid-cols-12 gap-3 items-end">
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">Waktu</label>
                                                                        <select name="snack[${snackIndex}][waktu_konsumsi_id]" class="form-input">
                                                                            @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'snack')) as $wk)
                                                                                <option value="{{ $wk->id }}">{{ $wk->nama_waktu }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">Nama Snack</label>
                                                                        <input type="text" name="snack[${snackIndex}][nama]"
                                                                               class="form-input"
                                                                               placeholder="Contoh: Kue Basah, dll">
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">No. Kwitansi</label>
                                                                        <input type="text" name="snack[${snackIndex}][no_kwitansi]"
                                                                               class="form-input"
                                                                               placeholder="No. Kwitansi">
                                                                    </div>
                                                                    <div class="col-span-1">
                                                                        <label class="form-label">Jumlah</label>
                                                                        <input type="number" name="snack[${snackIndex}][jumlah]"
                                                                               class="form-input snack-qty"
                                                                               data-index="${snackIndex}"
                                                                               value="${jumlahPeserta}" min="1">
                                                                    </div>
                                                                    <div class="col-span-3">
                                                                        <label class="form-label">Harga Satuan (Rp)</label>
                                                                        <input type="number" name="snack[${snackIndex}][harga]"
                                                                               class="form-input snack-price"
                                                                               data-index="${snackIndex}"
                                                                               value="0" min="0">
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label text-xs">Subtotal</label>
                                                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium snack-subtotal" data-index="${snackIndex}">
                                                                            Rp 0
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;
                container.insertAdjacentHTML('beforeend', template);
                snackIndex++;
                attachCalculationEvents();
            }

            // Add Makanan Item
            function addMakananItem() {
                const container = document.getElementById('makanan-container');
                const template = `
                                                            <div class="makanan-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                                                                <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                                                    ✕ Hapus
                                                                </button>
                                                                <div class="grid grid-cols-12 gap-3 items-end">
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">Waktu</label>
                                                                        <select name="makanan[${makananIndex}][waktu_konsumsi_id]" class="form-input">
                                                                            @foreach($waktuKonsumsi->filter(fn($w) => str_contains($w->kode_waktu, 'makan')) as $wk)
                                                                                <option value="{{ $wk->id }}">{{ $wk->nama_waktu }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">Nama Makanan</label>
                                                                        <input type="text" name="makanan[${makananIndex}][nama]"
                                                                               class="form-input"
                                                                               placeholder="Contoh: Nasi Box, dll">
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label">No. Kwitansi</label>
                                                                        <input type="text" name="makanan[${makananIndex}][no_kwitansi]"
                                                                               class="form-input"
                                                                               placeholder="No. Kwitansi">
                                                                    </div>
                                                                    <div class="col-span-1">
                                                                        <label class="form-label">Jumlah</label>
                                                                        <input type="number" name="makanan[${makananIndex}][jumlah]"
                                                                               class="form-input makanan-qty"
                                                                               data-index="${makananIndex}"
                                                                               value="${jumlahPeserta}" min="1">
                                                                    </div>
                                                                    <div class="col-span-3">
                                                                        <label class="form-label">Harga Satuan (Rp)</label>
                                                                        <input type="number" name="makanan[${makananIndex}][harga]"
                                                                               class="form-input makanan-price"
                                                                               data-index="${makananIndex}"
                                                                               value="0" min="0">
                                                                    </div>
                                                                    <div class="col-span-2">
                                                                        <label class="form-label text-xs">Subtotal</label>
                                                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium makanan-subtotal" data-index="${makananIndex}">
                                                                            Rp 0
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        `;
                container.insertAdjacentHTML('beforeend', template);
                makananIndex++;
                attachCalculationEvents();
            }

            // Add Barang Item
            function addBarangItem() {
                const container = document.getElementById('barang-container');
                const template = `
                                                                                    <div class="barang-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                                                                                        <button type="button" onclick="this.parentElement.remove(); calculateTotals();calculateVendorTotals();"
                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                    ✕ Hapus
                                </button>
                                <div class="grid grid-cols-12 gap-3 items-end">
                                    <div class="col-span-2">
                                        <label class="form-label">Vendor/Toko</label>
                                        <input type="text" name="barang[${barangIndex}][vendor_nama]"
                                               class="form-input barang-vendor"
                                               data-index="${barangIndex}"
                                               placeholder="Nama Vendor" list="vendor-list">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">Nama Barang</label>
                                        <input type="text" name="barang[${barangIndex}][nama]"
                                               class="form-input"
                                               placeholder="Contoh: Kertas HVS, dll">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label">No. Kwitansi</label>
                                        <input type="text" name="barang[${barangIndex}][no_kwitansi]"
                                               class="form-input"
                                               placeholder="No. Kwitansi">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" name="barang[${barangIndex}][jumlah]"
                                               class="form-input barang-qty"
                                               data-index="${barangIndex}"
                                               value="1" min="1">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">Harga Satuan (Rp)</label>
                                        <input type="number" name="barang[${barangIndex}][harga]"
                                               class="form-input barang-price"
                                               data-index="${barangIndex}"
                                               value="0" min="0">
                                    </div>
                                    <div class="col-span-2">
                                        <label class="form-label text-xs">Subtotal</label>
                                        <div class="px-3 py-2 bg-gray-100 rounded text-sm font-medium barang-subtotal" data-index="${barangIndex}">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', template);
                        barangIndex++;
                        attachCalculationEvents();
                        attachVendorEvents();
                    }

                    // Calculate Totals
                    function calculateTotals() {
                        let totalSnack = 0;
                        let totalMakanan = 0;

                        // Calculate Snack
                        document.querySelectorAll('.snack-qty').forEach(input => {
                            const index = input.dataset.index;
                            const qty = parseInt(input.value) || 0;
                            const price = parseInt(document.querySelector(`.snack-price[data-index="${index}"]`).value) || 0;
                            const subtotal = qty * price;

                            document.querySelector(`.snack-subtotal[data-index="${index}"]`).textContent =
                                'Rp ' + subtotal.toLocaleString('id-ID');
                            totalSnack += subtotal;
                        });

                        // Calculate Makanan
                        document.querySelectorAll('.makanan-qty').forEach(input => {
                            const index = input.dataset.index;
                            const qty = parseInt(input.value) || 0;
                            const price = parseInt(document.querySelector(`.makanan-price[data-index="${index}"]`).value) || 0;
                            const subtotal = qty * price;

                            document.querySelector(`.makanan-subtotal[data-index="${index}"]`).textContent =
                                'Rp ' + subtotal.toLocaleString('id-ID');
                            totalMakanan += subtotal;
                        });

                        // Calculate Barang
                        let totalBarang = 0;
                        document.querySelectorAll('.barang-qty').forEach(input => {
                            const index = input.dataset.index;
                            const qty = parseInt(input.value) || 0;
                            const price = parseInt(document.querySelector(`.barang-price[data-index="${index}"]`)?.value) || 0;
                            const subtotal = qty * price;

                            const subtotalEl = document.querySelector(`.barang-subtotal[data-index="${index}"]`);
                            if (subtotalEl) {
                                subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                            }
                            totalBarang += subtotal;
                        });

                        // Update totals
                        document.getElementById('total-snack').textContent = 'Rp ' + totalSnack.toLocaleString('id-ID');
                        document.getElementById('total-makanan').textContent = 'Rp ' + totalMakanan.toLocaleString('id-ID');
                        if (document.getElementById('total-barang')) {
                            document.getElementById('total-barang').textContent = 'Rp ' + totalBarang.toLocaleString('id-ID');
                        }
                        document.getElementById('grand-total').textContent = 'Rp ' + (totalSnack + totalMakanan + totalBarang).toLocaleString('id-ID');
                    }

                    // Validate SBM
                    function validateSBM(type, index) {
                        const priceInput = document.querySelector(`.${type}-price[data-index="${index}"]`);
                        if (!priceInput) return;

                        const price = parseInt(priceInput.value) || 0;
                        const maxTarif = type === 'snack' ? tarifSBM.snack : tarifSBM.makan;

                        // Remove existing warning
                        const existingWarning = priceInput.parentElement.querySelector('.sbm-warning');
                        if (existingWarning) existingWarning.remove();

                        // Check if exceeds SBM
                        if (price > maxTarif && maxTarif > 0) {
                            priceInput.classList.add('border-red-500', 'bg-red-50');
                            const warning = document.createElement('p');
                            warning.className = 'sbm-warning text-red-600 mt-1 text-xs font-medium';
                            warning.textContent = `⚠️ Melebihi tarif SBM! Max: Rp ${maxTarif.toLocaleString('id-ID')}`;
                            priceInput.parentElement.appendChild(warning);
                        } else {
                            priceInput.classList.remove('border-red-500', 'bg-red-50');
                        }
                    }

                    // Validate Form
                    function validateForm() {
                        let hasData = false;

                        // Check snack items
                        document.querySelectorAll('input[name^="snack"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        // Check makanan items
                        document.querySelectorAll('input[name^="makanan"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        // Check barang items
                        document.querySelectorAll('input[name^="barang"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        if (!hasData) {
                            alert('⚠️ Minimal isi 1 item (Snack, Makanan, atau Barang) dengan nama yang lengkap!');
                            return false;
                        }

                        return true;
                    }

                    // Confirm Draft
                    function confirmDraft() {
                        let hasData = false;

                        // Check snack items
                        document.querySelectorAll('input[name^="snack"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        // Check makanan items
                        document.querySelectorAll('input[name^="makanan"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        // Check barang items
                        document.querySelectorAll('input[name^="barang"][name$="[nama]"]').forEach(input => {
                            if (input.value.trim() !== '') {
                                hasData = true;
                            }
                        });

                        if (!hasData) {
                            alert('⚠️ Minimal isi 1 item (Snack, Makanan, atau Barang) dengan nama yang lengkap!');
                            return false;
                        }

                        return confirm('💾 Data akan disimpan sebagai DRAFT dan dapat diedit kembali. Lanjutkan?');
                    }

                    // Attach events
                    function attachCalculationEvents() {
                        document.querySelectorAll('.snack-qty, .snack-price, .makanan-qty, .makanan-price, .barang-qty, .barang-price').forEach(input => {
                            input.removeEventListener('input', calculateTotals);
                            input.addEventListener('input', calculateTotals);
                        });
                    }

                    // Initialize
                    document.addEventListener('DOMContentLoaded', function () {
                        attachCalculationEvents();
                        attachVendorEvents();

                        // Load any vendor_data hidden inputs (from draft) into vendorData
                        const vendorHiddenInputs = document.querySelectorAll('input[name^="vendor_data"]');
                        if (vendorHiddenInputs && vendorHiddenInputs.length > 0) {
                            vendorHiddenInputs.forEach(inp => {
                                const match = inp.name.match(/^vendor_data\[(.+?)\]\[(.+?)\]$/);
                                if (!match) return;
                                const vendorNama = match[1];
                                const field = match[2];
                                vendorData[vendorNama] = vendorData[vendorNama] || { isComplete: false };
                                vendorData[vendorNama][field] = inp.value;
                            });

                            // Mark complete if all required fields present (including bank + rekening)
                            Object.keys(vendorData).forEach(vn => {
                                const v = vendorData[vn];
                                if (v.nama_direktur && v.jabatan && v.npwp && v.alamat && v.bank && v.rekening) v.isComplete = true;
                            });
                        }

                        // Hydrate vendorData for any existing vendor inputs (from draft) using DB autofill as fallback
                        document.querySelectorAll('.barang-vendor').forEach(input => {
                            const val = (input.value || '').trim();
                            if (val) {
                                try {
                                    // if vendorData not already populated from hidden inputs, try DB autofill
                                    if (!vendorData[val] && vendorsData && vendorsData[val]) {
                                        handleVendorChange({ target: input });
                                    }
                                } catch (e) { console.error(e); }
                            }
                        });

                        calculateTotals();
                        calculateVendorTotals();

                        // Ensure vendor hidden inputs are created before form submission
                        const form = document.querySelector('form');
                        if (form) {
                            form.addEventListener('submit', function(e) {
                                // Update hidden inputs with latest vendor data
                                updateVendorHiddenInputs();
                                console.log('Vendor data being submitted:', vendorData);
                                console.log('Hidden inputs:', document.querySelectorAll('input[name^="vendor_data"]'));
                            });
                        }
                    });

                    // Vendor data storage
                    let vendorData = {};
                    const VENDOR_THRESHOLD = 10000000; // 10 juta

                    // Calculate vendor totals
                    function calculateVendorTotals() {
                        const vendorTotals = {};

                        document.querySelectorAll('.barang-item').forEach(item => {
                            const vendorInput = item.querySelector('.barang-vendor');
                            const qtyInput = item.querySelector('.barang-qty');
                            const priceInput = item.querySelector('.barang-price');
                            const namaInput = item.querySelector('input[name$="[nama]"]');

                            if (vendorInput && qtyInput && priceInput && namaInput) {
                                const vendor = vendorInput.value.trim();
                                const nama = namaInput.value.trim();
                                const qty = parseInt(qtyInput.value) || 0;
                                const price = parseInt(priceInput.value) || 0;
                                const subtotal = qty * price;

                                if (vendor && nama) {
                                    if (!vendorTotals[vendor]) {
                                        vendorTotals[vendor] = 0;
                                    }
                                    vendorTotals[vendor] += subtotal;
                                }
                            }
                        });

                        updateVendorSummary(vendorTotals);
                        checkVendorThreshold(vendorTotals);
                    }

                    // Update vendor summary display
                    function updateVendorSummary(vendorTotals) {
                        const summaryContainer = document.getElementById('vendor-summary');
                        const summaryList = document.getElementById('vendor-summary-list');

                        if (Object.keys(vendorTotals).length === 0) {
                            summaryContainer.classList.add('hidden');
                            return;
                        }

                        summaryContainer.classList.remove('hidden');
                        summaryList.innerHTML = '';

                        for (const [vendor, total] of Object.entries(vendorTotals)) {
                            const isOverThreshold = total >= VENDOR_THRESHOLD;
                            const hasCompleteData = vendorData[vendor] && vendorData[vendor].isComplete;

                            const div = document.createElement('div');
                            div.className = `flex justify-between items-center p-2 rounded ${isOverThreshold ? (hasCompleteData ? 'bg-green-100' : 'bg-orange-100') : 'bg-gray-100'}`;
                            div.innerHTML = `
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">${vendor}</span>
                                    ${isOverThreshold && !hasCompleteData ? '<span class="text-xs text-orange-600">⚠️ Perlu data lengkap</span>' : ''}
                                    ${isOverThreshold && hasCompleteData ? '<span class="text-xs text-green-600">✓ Data lengkap</span>' : ''}
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold ${isOverThreshold ? 'text-orange-600' : ''}">Rp ${total.toLocaleString('id-ID')}</span>
                                    ${isOverThreshold ? `<button type="button" onclick="openVendorModal('${vendor}')" class="text-xs text-primary hover:underline">Edit Data</button>` : ''}
                                </div>
                            `;
                            summaryList.appendChild(div);
                        }
                    }

                    // Check if any vendor exceeds threshold
                    function checkVendorThreshold(vendorTotals) {
                        const vendorsNeedingData = [];

                        for (const [vendor, total] of Object.entries(vendorTotals)) {
                            if (total >= VENDOR_THRESHOLD) {
                                const hasCompleteData = vendorData[vendor] && vendorData[vendor].isComplete;
                                if (!hasCompleteData) {
                                    vendorsNeedingData.push(`${vendor} (Rp ${total.toLocaleString('id-ID')})`);
                                }
                            }
                        }

                        const warningBanner = document.getElementById('vendor-warning-banner');
                        const incompleteList = document.getElementById('vendor-incomplete-list');

                        if (vendorsNeedingData.length > 0) {
                            warningBanner.classList.remove('hidden');
                            incompleteList.innerHTML = '<strong>Vendor:</strong> ' + vendorsNeedingData.join(', ');
                        } else {
                            warningBanner.classList.add('hidden');
                        }
                    }

                    // Attach vendor input events
                    function attachVendorEvents() {
                        document.querySelectorAll('.barang-vendor').forEach(input => {
                            input.removeEventListener('change', handleVendorChange);
                            input.addEventListener('change', handleVendorChange);
                        });
                        document.querySelectorAll('.barang-qty, .barang-price').forEach(input => {
                            input.removeEventListener('input', calculateVendorTotals);
                            input.addEventListener('input', calculateVendorTotals);
                        });
                    }

                    // Handle vendor name change: autofill vendorData if vendor exists in DB
                    function handleVendorChange(e) {
                        const input = e.target;
                        const vendorNama = (input.value || '').trim();

                        if (!vendorNama) {
                            // vendor cleared
                            calculateVendorTotals();
                            return;
                        }

                        // If vendor exists in vendorsData (from server), populate vendorData
                        if (vendorsData && vendorsData[vendorNama]) {
                            const data = vendorsData[vendorNama];
                            vendorData[vendorNama] = Object.assign({}, data, { isComplete: true });
                            // Ensure hidden inputs are updated so draft/save will include vendor details
                            updateVendorHiddenInputs();
                        }

                        // Always recalculate vendor totals (and UI)
                        calculateVendorTotals();
                    }

                    // Open vendor modal
                    function openVendorModal(vendorName) {
                        const modal = document.getElementById('vendor-modal');
                        const content = document.getElementById('vendor-modal-content');

                        const data = vendorData[vendorName] || {};

                        content.innerHTML = `
                            <div class="space-y-3">
                                <div>
                                    <label class="form-label">Nama Vendor</label>
                                    <input type="text" id="modal-vendor-nama" class="form-input" value="${vendorName}" readonly>
                                </div>
                                <div>
                                    <label class="form-label">Nama Direktur <span class="text-red-500">*</span></label>
                                    <input type="text" id="modal-vendor-direktur" class="form-input"
                                        placeholder="Nama lengkap direktur" value="${data.nama_direktur || ''}">
                                </div>

                                <div>
                                    <label class="form-label">Jabatan <span class="text-red-500">*</span></label>
                                    <input type="text" id="modal-vendor-jabatan" class="form-input"
                                        placeholder="Contoh: Direktur Utama" value="${data.jabatan || ''}">
                                </div>
                                <div>
                                    <label class="form-label">NPWP <span class="text-red-500">*</span></label>
                                    <input type="text" id="modal-vendor-npwp" class="form-input"
                                        placeholder="00.000.000.0-000.000" value="${data.npwp || ''}">
                                </div>
                                <div>
                                    <label class="form-label">Bank <span class="text-red-500">*</span></label>
                                    <input list="banks-datalist" id="modal-vendor-bank" class="form-input" placeholder="Ketik untuk mencari bank">
                                    <datalist id="banks-datalist"></datalist>
                                </div>
                                <div>
                                    <label class="form-label">Nomor Rekening <span class="text-red-500">*</span></label>
                                    <input type="text" id="modal-vendor-rekening" class="form-input"
                                        placeholder="Nomor Rekening" value="${data.rekening || ''}">
                                </div>
                                <div>
                                    <label class="form-label">Alamat <span class="text-red-500">*</span></label>
                                    <textarea id="modal-vendor-alamat" class="form-input" rows="2"
                                        placeholder="Alamat lengkap vendor">${data.alamat || ''}</textarea>
                                </div>
                            </div>
                        `;

                        // Populate bank select dynamically from config
                        try {
                            const bankInput = document.getElementById('modal-vendor-bank');
                            const datalist = document.getElementById('banks-datalist');
                            if (datalist && Array.isArray(banksList)) {
                                let opts = '';
                                banksList.forEach(b => {
                                    const name = b.nama || b.name || b;
                                    opts += `<option value="${name}"></option>`;
                                });
                                datalist.innerHTML = opts;
                                if (bankInput && data.bank) bankInput.value = data.bank;
                            }
                        } catch (e) { console.error('Error populating banks:', e); }

                        modal.classList.remove('hidden');
                    }

                    // Close vendor modal
                    function closeVendorModal() {
                        document.getElementById('vendor-modal').classList.add('hidden');
                    }

                    // Save vendor data from modal
            function saveVendorData() {
                const vendorNama = document.getElementById('modal-vendor-nama').value.trim();
                const direktur = document.getElementById('modal-vendor-direktur').value.trim();
                const jabatan = document.getElementById('modal-vendor-jabatan').value.trim();
                const npwp = document.getElementById('modal-vendor-npwp').value.trim();
                const bank = document.getElementById('modal-vendor-bank').value.trim();
                const rekening = document.getElementById('modal-vendor-rekening').value.trim();
                const alamat = document.getElementById('modal-vendor-alamat').value.trim();

                console.log('Saving vendor:', vendorNama);
                console.log('Data:', { direktur, jabatan, npwp, bank, rekening, alamat });

                if (!direktur || !jabatan || !npwp || !bank || !rekening || !alamat) {
                    alert('⚠️ Semua field wajib diisi!');
                    return;
                }

                // Store vendor data
                vendorData[vendorNama] = {
                    nama_direktur: direktur,
                    jabatan: jabatan,
                    npwp: npwp,
                    bank: bank,
                    rekening: rekening,
                    alamat: alamat,
                    isComplete: true
                };

                console.log('vendorData after save:', JSON.stringify(vendorData));

                // Create/update hidden inputs for vendor data immediately
                updateVendorHiddenInputs();

                // Verify hidden inputs were created
                const hiddenInputs = document.querySelectorAll('input[name^="vendor_data"]');
                console.log('Hidden inputs created:', hiddenInputs.length);
                hiddenInputs.forEach(inp => console.log(inp.name, '=', inp.value));

                closeVendorModal();
                calculateVendorTotals();

                alert('✓ Data vendor "' + vendorNama + '" berhasil disimpan!');
            }

            // Update hidden inputs to submit vendor data
            function updateVendorHiddenInputs() {
                console.log('updateVendorHiddenInputs called');
                console.log('Current vendorData:', JSON.stringify(vendorData));

                // Remove existing vendor data inputs
                document.querySelectorAll('input[name^="vendor_data"]').forEach(el => {
                    console.log('Removing:', el.name);
                    el.remove();
                });

                // Find the form - try multiple selectors
                let form = document.getElementById('konsumsi-form') || document.querySelector('form');
                console.log('Form found:', form ? 'YES' : 'NO');

                if (!form) {
                    console.error('FORM NOT FOUND!');
                    return;
                }

                for (const [vendorNama, data] of Object.entries(vendorData)) {
                    console.log('Processing vendor:', vendorNama, 'isComplete:', data.isComplete);
                        if (data.isComplete) {
                        const fields = ['nama_direktur', 'jabatan', 'npwp', 'bank', 'rekening', 'alamat'];
                        fields.forEach(field => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = `vendor_data[${vendorNama}][${field}]`;
                            input.value = data[field];
                            form.appendChild(input);
                            console.log('Added hidden input:', input.name, '=', input.value);
                        });
                    }
                }

                // Final verification
                const allHidden = document.querySelectorAll('input[name^="vendor_data"]');
                console.log('Total hidden inputs after update:', allHidden.length);
            }
                </script>
    @endpush
@endsection
