@extends('layouts.app')

@section('title', 'Tambah Kegiatan')
@section('page-title', 'Tambah Kegiatan')
@section('page-subtitle', 'Form Input Data Kegiatan Baru')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Informasi Kegiatan</h3>
                <p class="text-sm text-gray-500 mt-0.5">Lengkapi data di bawah ini dengan benar</p>
            </div>

            <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data"
                class="p-4 sm:p-6 space-y-4">
                @csrf

                <!-- Nama Kegiatan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('nama_kegiatan') border-red-500 @enderror"
                        placeholder="Masukkan nama kegiatan" required>
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
                        placeholder="Deskripsi singkat tentang kegiatan...">{{ old('uraian_kegiatan') }}</textarea>
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
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('unit_kerja_id') border-red-500 @enderror {{ !$isSuperAdmin ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ !$isSuperAdmin ? 'disabled' : '' }}>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach($unitKerjas as $uk)
                            <option value="{{ $uk->id }}" {{ (old('unit_kerja_id') == $uk->id || (!$isSuperAdmin && $user->id_unker == $uk->id)) ? 'selected' : '' }}>
                                {{ $uk->nama_unit }} - {{ $uk->unor->nama_unor }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$isSuperAdmin)
                        <!-- Hidden input to submit value when select is disabled -->
                        <input type="hidden" name="unit_kerja_id" value="{{ $user->id_unker }}">
                    @endif
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
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
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
                    <input type="number" name="jumlah_peserta" value="{{ old('jumlah_peserta') }}" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary @error('jumlah_peserta') border-red-500 @enderror"
                        placeholder="25">
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
                        <input type="hidden" name="ppk_id" id="ppk_id" value="{{ old('ppk_id') }}" required>
                        <button type="button" id="ppkSelectorBtn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-left bg-white hover:bg-gray-50 @error('ppk_id') border-red-500 @enderror">
                            <span id="ppkSelectedText" class="text-gray-500">Pilih PPK</span>
                        </button>
                        @error('ppk_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Akun (MAK) <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="mak_id" id="mak_id" value="{{ old('mak_id') }}" required>
                        <button type="button" id="makSelectorBtn"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-left bg-white hover:bg-gray-50 @error('mak_id') border-red-500 @enderror">
                            <span id="makSelectedText" class="text-gray-500">Pilih MAK</span>
                        </button>
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
                    <input type="hidden" name="bendahara_id" id="bendahara_id" value="{{ old('bendahara_id') }}">
                    <button type="button" id="bendaharaSelectorBtn"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-left bg-white hover:bg-gray-50 @error('bendahara_id') border-red-500 @enderror">
                        <span id="bendaharaSelectedText" class="text-gray-500">Pilih Bendahara</span>
                    </button>
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
                                <option value="{{ $prov->id }}" {{ old('provinsi_id') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nama_provinsi }}
                                </option>
                            @endforeach
                        </select>
                        @error('provinsi_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Menentukan tarif SBM</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Detail Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="detail_lokasi" value="{{ old('detail_lokasi') }}" required
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
                    <input type="file" name="file_laporan" accept=".pdf,.doc,.docx"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-sm file:bg-primary file:text-white file:cursor-pointer hover:file:bg-primary-dark">
                    <p class="text-gray-500 text-xs mt-1">Format: PDF, DOC, DOCX (Maks: 10MB)</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition font-medium text-center">
                        Simpan & Lanjutkan
                    </button>
                    <a href="{{ route('kegiatan.index') }}"
                        class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- PPK Selector Modal -->
    <div id="ppkModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">DAFTAR PPK</h3>
                    <button type="button" id="closePpkModal"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kriteria:</label>
                <input type="text" id="ppkSearchInput" placeholder="Cari nama atau NIP PPK..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                    <thead class="bg-teal-600 text-white">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NO</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NAMA</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NIP</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                SATKER</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">KDPPK</th>
                        </tr>
                    </thead>
                    <tbody id="ppkTableBody"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-200">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mb-4">
                <button type="button" id="ppkFirstPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&lt;&lt;</button>
                <button type="button" id="ppkPrevPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&lt;</button>
                <span id="ppkPageInfo" class="px-3 py-1 dark:text-gray-300">Hal: 1/1</span>
                <button type="button" id="ppkNextPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&gt;</button>
                <button type="button" id="ppkLastPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&gt;&gt;</button>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                <button type="button" id="cancelPpkSelection"
                    class="px-6 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Bendahara Selector Modal -->
    <div id="bendaharaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div
            class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white dark:bg-gray-800 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">DAFTAR BENDAHARA</h3>
                    <button type="button" id="closeBendaharaModal"
                        class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-4">
                <div class="flex gap-2 items-center">
                    <select id="bendaharaTypeSelect"
                        class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="PNS">PNS</option>
                        <option value="PPPK">PPPK</option>
                    </select>
                    <input type="text" id="bendaharaSearchInput" placeholder="Masukkan NIP 18 digit..."
                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="button" id="btnSearchEhrmBendahara"
                        class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                        Cari
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto mb-4">
                <table
                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
                    <thead class="bg-teal-600 text-white">
                        <tr>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                No</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                Nama</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                Jabatan</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                Gol/Pangkat</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NIP</th>
                            <th
                                class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                Eselon</th>
                            <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider">Tgl Lahir</th>
                        </tr>
                    </thead>
                    <tbody id="bendaharaTableBody"
                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 dark:text-gray-200">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mb-4">
                <button type="button" id="bendaharaFirstPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&lt;&lt;</button>
                <button type="button" id="bendaharaPrevPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&lt;</button>
                <span id="bendaharaPageInfo" class="px-3 py-1 dark:text-gray-300">Hal: 1/1</span>
                <button type="button" id="bendaharaNextPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&gt;</button>
                <button type="button" id="bendaharaLastPage"
                    class="px-3 py-1 border border-gray-300 dark:border-gray-600 rounded hover:bg-gray-50 dark:hover:bg-gray-700 dark:text-gray-300">&gt;&gt;</button>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
                <button type="button" id="cancelBendaharaSelection"
                    class="px-6 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- MAK Selector Modal -->
    <div id="makModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-lg bg-white">
            <div class="border-b border-gray-200 pb-3 mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">DAFTAR MAK</h3>
                    <button type="button" id="closeMakModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Search -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kriteria:</label>
                <input type="text" id="makSearchInput" placeholder="Cari kode atau nama MAK..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <!-- Table -->
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                    <thead class="bg-teal-600 text-white">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NO</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                KODE</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider border-r border-teal-500">
                                NAMA</th>
                            <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider">TAHUN</th>
                        </tr>
                    </thead>
                    <tbody id="makTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mb-4">
                <button type="button" id="makFirstPage"
                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">&lt;&lt;</button>
                <button type="button" id="makPrevPage"
                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">&lt;</button>
                <span id="makPageInfo" class="px-3 py-1">Hal: 1/1</span>
                <button type="button" id="makNextPage"
                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">&gt;</button>
                <button type="button" id="makLastPage"
                    class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50">&gt;&gt;</button>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-2 border-t border-gray-200 pt-4">
                <button type="button" id="cancelMakSelection"
                    class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                    Close
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const ppkData = @json($ppkData);
        let makData = @json($makData);
        let filteredPpkData = [...ppkData];
        let selectedPpkId = {{ old('ppk_id', 'null') }};
        const itemsPerPage = 10;
        let currentPage = 1;

        // Open Modal
        document.getElementById('ppkSelectorBtn').addEventListener('click', function () {
            document.getElementById('ppkModal').classList.remove('hidden');
            renderPpkTable();
        });

        // Close Modal
        function closePpkModal() {
            document.getElementById('ppkModal').classList.add('hidden');
        }

        document.getElementById('closePpkModal').addEventListener('click', closePpkModal);
        document.getElementById('cancelPpkSelection').addEventListener('click', closePpkModal);

        // Real-time Search on input
        document.getElementById('ppkSearchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.trim() === '') {
                filteredPpkData = [...ppkData];
            } else {
                filteredPpkData = ppkData.filter(ppk =>
                    ppk.nama.toLowerCase().includes(searchTerm) ||
                    ppk.nip.toLowerCase().includes(searchTerm)
                );
            }
            currentPage = 1;
            renderPpkTable();
        });

        // Render Table
        function renderPpkTable() {
            const tbody = document.getElementById('ppkTableBody');
            const totalPages = Math.ceil(filteredPpkData.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredPpkData.slice(startIndex, endIndex);

            tbody.innerHTML = '';

            if (pageData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td></tr>';
            } else {
                pageData.forEach((ppk, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer';
                    row.innerHTML = `
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${startIndex + index + 1}.</td>
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${ppk.nama}</td>
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${ppk.nip}</td>
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${ppk.satker || '-'}</td>
                                                                                                    <td class="px-4 py-3 text-sm dark:text-gray-200">${ppk.kode || '-'}</td>
                                                                                                `;
                    row.addEventListener('click', function () {
                        // Set selection immediately
                        selectedPpkId = ppk.id;
                        document.getElementById('ppk_id').value = ppk.id;
                        document.getElementById('ppkSelectedText').textContent = `${ppk.nama} (${ppk.nip})`;
                        document.getElementById('ppkSelectedText').classList.remove('text-gray-500');
                        document.getElementById('ppkSelectedText').classList.add('text-gray-900');

                        // Fetch MAK by PPK NIP
                        fetchMakByPpk(ppk.nip);

                        // Close modal
                        closePpkModal();
                    });
                    tbody.appendChild(row);
                });
            }

            // Update pagination
            document.getElementById('ppkPageInfo').textContent = `Hal: ${currentPage}/${totalPages || 1}`;
            document.getElementById('ppkFirstPage').disabled = currentPage === 1;
            document.getElementById('ppkPrevPage').disabled = currentPage === 1;
            document.getElementById('ppkNextPage').disabled = currentPage === totalPages || totalPages === 0;
            document.getElementById('ppkLastPage').disabled = currentPage === totalPages || totalPages === 0;
        }

        // Pagination
        document.getElementById('ppkFirstPage').addEventListener('click', function () {
            currentPage = 1;
            renderPpkTable();
        });

        document.getElementById('ppkPrevPage').addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderPpkTable();
            }
        });

        document.getElementById('ppkNextPage').addEventListener('click', function () {
            const totalPages = Math.ceil(filteredPpkData.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderPpkTable();
            }
        });

        document.getElementById('ppkLastPage').addEventListener('click', function () {
            currentPage = Math.ceil(filteredPpkData.length / itemsPerPage);
            renderPpkTable();
        });

        // Set initial value if old value exists
        if (selectedPpkId) {
            const selectedPpk = ppkData.find(p => p.id === selectedPpkId);
            if (selectedPpk) {
                document.getElementById('ppkSelectedText').textContent = `${selectedPpk.nama} (${selectedPpk.nip})`;
                document.getElementById('ppkSelectedText').classList.remove('text-gray-500');
                document.getElementById('ppkSelectedText').classList.add('text-gray-900');
            }
        }

        // ========== Bendahara Selector ==========
        const bendaharaData = @json($bendaharaData ?? []);
        let filteredBendaharaData = [...bendaharaData];
        let selectedBendaharaId = {{ old('bendahara_id', 'null') }};
        let currentBendaharaPage = 1;

        document.getElementById('bendaharaSelectorBtn').addEventListener('click', function () {
            document.getElementById('bendaharaModal').classList.remove('hidden');
            renderBendaharaTable();
        });

        // Close Modal
        function closeBendaharaModal() {
            document.getElementById('bendaharaModal').classList.add('hidden');
        }

        document.getElementById('closeBendaharaModal').addEventListener('click', closeBendaharaModal);
        document.getElementById('cancelBendaharaSelection').addEventListener('click', closeBendaharaModal);

        // Real-time Search
        document.getElementById('bendaharaSearchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.trim() === '') {
                filteredBendaharaData = [...bendaharaData];
            } else {
                filteredBendaharaData = bendaharaData.filter(b =>
                    b.nama.toLowerCase().includes(searchTerm) ||
                    b.nip.toLowerCase().includes(searchTerm)
                );
            }
            currentBendaharaPage = 1;
            renderBendaharaTable();
        });

        // EHRM API Search for Bendahara
        document.getElementById('btnSearchEhrmBendahara').addEventListener('click', async function () {
            const nip = document.getElementById('bendaharaSearchInput').value.trim();
            const btn = this;

            if (!nip || nip.length < 18) {
                alert('Masukkan NIP 18 digit untuk cari dari EHRM');
                return;
            }

            // Show loading
            btn.disabled = true;
            btn.textContent = 'Mencari...';

            try {
                const response = await fetch(`/api/ehrm/pegawai/${nip}`);
                const data = await response.json();

                if (data.success && data.data) {
                    const pegawai = data.data;
                    // Add EHRM result to filtered data and render in table
                    filteredBendaharaData = [{
                        id: 'ehrm_' + pegawai.nip,
                        nama: pegawai.nama,
                        nip: pegawai.nip,
                        jabatan: pegawai.jabatan || '-',
                        golongan: pegawai.golongan || '-',
                        eselon: pegawai.eselon || '-',
                        tgl_lahir: pegawai.tgllahir || '-',
                        satker: pegawai.unker || pegawai.nama_unit || '-',
                        isEhrm: true
                    }];
                    currentBendaharaPage = 1;
                    renderBendaharaTable();
                } else {
                    alert('Pegawai tidak ditemukan di EHRM');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Gagal menghubungi server EHRM');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cari';
            }
        });


        // Render Table
        function renderBendaharaTable() {
            const tbody = document.getElementById('bendaharaTableBody');
            const totalPages = Math.ceil(filteredBendaharaData.length / itemsPerPage);
            const startIndex = (currentBendaharaPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredBendaharaData.slice(startIndex, endIndex);

            tbody.innerHTML = '';

            if (pageData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data</td></tr>';
            } else {
                pageData.forEach((bendahara, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer';
                    row.innerHTML = `
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${startIndex + index + 1}.</td>
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200 text-teal-600">${bendahara.nama}</td>
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${bendahara.jabatan || '-'}</td>
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${bendahara.golongan || '-'}</td>
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${bendahara.nip}</td>
                                        <td class="px-3 py-2 text-sm border-r border-gray-200 dark:border-gray-700 dark:text-gray-200">${bendahara.eselon || '-'}</td>
                                        <td class="px-3 py-2 text-sm dark:text-gray-200">${bendahara.tgl_lahir || '-'}</td>
                                    `;
                    row.addEventListener('click', function () {
                        document.getElementById('bendahara_id').value = bendahara.id;
                        document.getElementById('bendaharaSelectedText').textContent = `${bendahara.nama} (${bendahara.nip})`;
                        document.getElementById('bendaharaSelectedText').classList.remove('text-gray-500');
                        document.getElementById('bendaharaSelectedText').classList.add('text-gray-900');
                        closeBendaharaModal();
                    });
                    tbody.appendChild(row);
                });
            }

            // Update pagination
            document.getElementById('bendaharaPageInfo').textContent = `Hal: ${currentBendaharaPage}/${totalPages || 1}`;
            document.getElementById('bendaharaFirstPage').disabled = currentBendaharaPage === 1;
            document.getElementById('bendaharaPrevPage').disabled = currentBendaharaPage === 1;
            document.getElementById('bendaharaNextPage').disabled = currentBendaharaPage === totalPages || totalPages === 0;
            document.getElementById('bendaharaLastPage').disabled = currentBendaharaPage === totalPages || totalPages === 0;
        }

        // Pagination
        document.getElementById('bendaharaFirstPage').addEventListener('click', function () {
            currentBendaharaPage = 1;
            renderBendaharaTable();
        });

        document.getElementById('bendaharaPrevPage').addEventListener('click', function () {
            if (currentBendaharaPage > 1) {
                currentBendaharaPage--;
                renderBendaharaTable();
            }
        });

        document.getElementById('bendaharaNextPage').addEventListener('click', function () {
            const totalPages = Math.ceil(filteredBendaharaData.length / itemsPerPage);
            if (currentBendaharaPage < totalPages) {
                currentBendaharaPage++;
                renderBendaharaTable();
            }
        });

        document.getElementById('bendaharaLastPage').addEventListener('click', function () {
            currentBendaharaPage = Math.ceil(filteredBendaharaData.length / itemsPerPage);
            renderBendaharaTable();
        });

        // Set initial value if old value exists
        if (selectedBendaharaId) {
            const selectedBendahara = bendaharaData.find(b => b.id === selectedBendaharaId);
            if (selectedBendahara) {
                document.getElementById('bendaharaSelectedText').textContent = `${selectedBendahara.nama} (${selectedBendahara.nip})`;
                document.getElementById('bendaharaSelectedText').classList.remove('text-gray-500');
                document.getElementById('bendaharaSelectedText').classList.add('text-gray-900');
            }
        }

        // ========== MAK Selector ==========
        let filteredMakData = [...makData];
        let selectedMakId = {{ old('mak_id', 'null') }};
        let currentMakPage = 1;

        // Function to fetch MAK by PPK NIP
        async function fetchMakByPpk(nipPpk) {
            try {
                const response = await fetch(`/api/mak-by-ppk/${nipPpk}`);
                const result = await response.json();
                
                if (result.success && result.data.length > 0) {
                    makData = result.data;
                    filteredMakData = [...makData];
                    
                    // Reset MAK selection
                    document.getElementById('mak_id').value = '';
                    document.getElementById('makSelectedText').textContent = 'Pilih MAK';
                    document.getElementById('makSelectedText').classList.add('text-gray-500');
                    document.getElementById('makSelectedText').classList.remove('text-gray-900');
                    
                    currentMakPage = 1;
                    console.log(`Loaded ${makData.length} MAK for PPK NIP: ${nipPpk}`);
                } else {
                    makData = [];
                    filteredMakData = [];
                    document.getElementById('mak_id').value = '';
                    document.getElementById('makSelectedText').textContent = 'Tidak ada MAK untuk PPK ini';
                    document.getElementById('makSelectedText').classList.add('text-gray-500');
                    console.log(`No MAK found for PPK NIP: ${nipPpk}`);
                }
            } catch (error) {
                console.error('Error fetching MAK by PPK:', error);
            }
        }

        document.getElementById('makSelectorBtn').addEventListener('click', function () {
            document.getElementById('makModal').classList.remove('hidden');
            renderMakTable();
        });

        function closeMakModal() {
            document.getElementById('makModal').classList.add('hidden');
        }

        document.getElementById('closeMakModal').addEventListener('click', closeMakModal);
        document.getElementById('cancelMakSelection').addEventListener('click', closeMakModal);

        document.getElementById('makSearchInput').addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase();
            if (searchTerm.trim() === '') {
                filteredMakData = [...makData];
            } else {
                filteredMakData = makData.filter(mak =>
                    mak.kode.toLowerCase().includes(searchTerm) ||
                    mak.nama.toLowerCase().includes(searchTerm)
                );
            }
            currentMakPage = 1;
            renderMakTable();
        });

        function renderMakTable() {
            const tbody = document.getElementById('makTableBody');
            const totalPages = Math.ceil(filteredMakData.length / itemsPerPage);
            const startIndex = (currentMakPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const pageData = filteredMakData.slice(startIndex, endIndex);

            tbody.innerHTML = '';

            if (pageData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td></tr>';
            } else {
                pageData.forEach((mak, index) => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 cursor-pointer';
                    row.innerHTML = `
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200">${startIndex + index + 1}.</td>
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200">${mak.kode}</td>
                                                                                                    <td class="px-4 py-3 text-sm border-r border-gray-200">${mak.nama}</td>
                                                                                                    <td class="px-4 py-3 text-sm">${mak.tahun || '-'}</td>
                                                                                                `;
                    row.addEventListener('click', function () {
                        selectedMakId = mak.id;
                        document.getElementById('mak_id').value = mak.id;
                        document.getElementById('makSelectedText').textContent = `${mak.kode} - ${mak.nama}`;
                        document.getElementById('makSelectedText').classList.remove('text-gray-500');
                        document.getElementById('makSelectedText').classList.add('text-gray-900');
                        closeMakModal();
                    });
                    tbody.appendChild(row);
                });
            }

            document.getElementById('makPageInfo').textContent = `Hal: ${currentMakPage}/${totalPages || 1}`;
            document.getElementById('makFirstPage').disabled = currentMakPage === 1;
            document.getElementById('makPrevPage').disabled = currentMakPage === 1;
            document.getElementById('makNextPage').disabled = currentMakPage === totalPages || totalPages === 0;
            document.getElementById('makLastPage').disabled = currentMakPage === totalPages || totalPages === 0;
        }

        document.getElementById('makFirstPage').addEventListener('click', function () {
            currentMakPage = 1;
            renderMakTable();
        });

        document.getElementById('makPrevPage').addEventListener('click', function () {
            if (currentMakPage > 1) {
                currentMakPage--;
                renderMakTable();
            }
        });

        document.getElementById('makNextPage').addEventListener('click', function () {
            const totalPages = Math.ceil(filteredMakData.length / itemsPerPage);
            if (currentMakPage < totalPages) {
                currentMakPage++;
                renderMakTable();
            }
        });

        document.getElementById('makLastPage').addEventListener('click', function () {
            currentMakPage = Math.ceil(filteredMakData.length / itemsPerPage);
            renderMakTable();
        });

        if (selectedMakId) {
            const selectedMak = makData.find(m => m.id === selectedMakId);
            if (selectedMak) {
                document.getElementById('makSelectedText').textContent = `${selectedMak.kode} - ${selectedMak.nama}`;
                document.getElementById('makSelectedText').classList.remove('text-gray-500');
                document.getElementById('makSelectedText').classList.add('text-gray-900');
            }
        }
    </script>
@endpush