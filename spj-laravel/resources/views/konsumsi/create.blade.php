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

            <form action="{{ route('konsumsi.store') }}" method="POST" class="card-body space-y-6">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">

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
                                            <input type="number" name="makanan[{{ $index }}][harga]" class="form-input makanan-price"
                                                data-index="{{ $index }}" value="{{ $item->harga }}" min="0"
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

                    <div id="barang-container" class="space-y-3">
                        @if($draftData['barang']->count() > 0)
                            <!-- Load Draft Barang Items -->
                            @foreach($draftData['barang'] as $index => $item)
                                <div class="barang-item border border-gray-200 rounded-lg p-4 bg-yellow-50 relative">
                                    @if($index > 0)
                                        <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                            class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                            ✕ Hapus
                                        </button>
                                    @endif
                                    <div class="grid grid-cols-12 gap-3 items-end">
                                        <div class="col-span-3">
                                            <label class="form-label">Nama Barang</label>
                                            <input type="text" name="barang[{{ $index }}][nama]" class="form-input"
                                                placeholder="Contoh: Kertas HVS, Spidol, dll" value="{{ $item->nama_konsumsi }}">
                                        </div>
                                        <div class="col-span-2">
                                            <label class="form-label">No. Kwitansi</label>
                                            <input type="text" name="barang[{{ $index }}][no_kwitansi]" class="form-input"
                                                placeholder="No. Kwitansi" value="{{ $item->no_kwitansi }}">
                                        </div>
                                        <div class="col-span-2">
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
                                <div class="col-span-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="barang[0][nama]" class="form-input"
                                        placeholder="Contoh: Kertas HVS, Spidol, dll">
                                </div>
                                <div class="col-span-2">
                                    <label class="form-label">No. Kwitansi</label>
                                    <input type="text" name="barang[0][no_kwitansi]" class="form-input"
                                        placeholder="No. Kwitansi">
                                </div>
                                <div class="col-span-2">
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
                        <button type="submit" name="save_as_draft" value="0" class="btn-primary" onclick="return validateForm()">
                            💾 Simpan & Validasi
                        </button>
                        <button type="submit" name="save_as_draft" value="1" class="btn-secondary" onclick="return confirmDraft()">
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
                                                                                <button type="button" onclick="this.parentElement.remove(); calculateTotals();"
                                                                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                                                                    ✕ Hapus
                                                                                </button>
                                                                                <div class="grid grid-cols-12 gap-3 items-end">
                                                                                    <div class="col-span-3">
                                                                                        <label class="form-label">Nama Barang</label>
                                                                                        <input type="text" name="barang[${barangIndex}][nama]"
                                                                                               class="form-input"
                                                                                               placeholder="Contoh: Kertas HVS, Spidol, dll">
                                                                                    </div>
                                                                                    <div class="col-span-2">
                                                                                        <label class="form-label">No. Kwitansi</label>
                                                                                        <input type="text" name="barang[${barangIndex}][no_kwitansi]"
                                                                                               class="form-input"
                                                                                               placeholder="No. Kwitansi">
                                                                                    </div>
                                                                                    <div class="col-span-2">
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
                calculateTotals(); // Calculate totals for loaded draft data
            });
        </script>
    @endpush
@endsection
