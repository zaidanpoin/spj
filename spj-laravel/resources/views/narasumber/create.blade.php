@extends('layouts.app')

@section('title', 'Input Narasumber')
@section('page-title', 'Input Jasa Profesi')
@section('page-subtitle', $kegiatan->nama_kegiatan)

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Input Jasa Profesi / Narasumber</h2>
            </div>

            <form action="{{ route('narasumber.store') }}" method="POST" id="narasumberForm" class="card-body space-y-6">
                @csrf
                <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">

                <!-- Draft Info Box -->
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
                                    ⚠️ Ada {{ $draftData->count() }} data draft narasumber yang belum difinalisasi
                                </p>
                                <p class="mt-1 text-xs text-yellow-700">
                                    Data yang Anda simpan akan menimpa data draft yang ada
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Info Box -->
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
                                Kegiatan: {{ $kegiatan->nama_kegiatan }}
                            </p>
                            <p class="mt-1 text-xs text-blue-700">
                                Isi data narasumber/profesi untuk kegiatan ini. Anda dapat menambahkan beberapa narasumber
                                sekaligus.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Narasumber Container -->
                <div id="narasumber-container" class="space-y-4">
                    @if($hasDraft && $draftData->count() > 0)
                        <!-- Load Draft Data -->
                        @foreach($draftData as $index => $draft)
                            <div class="narasumber-item border border-gray-200 rounded-lg p-4 bg-yellow-50 relative">
                                @if($index > 0)
                                    <button type="button" onclick="removeNarasumberItem(this)"
                                        class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                                        ✕ Hapus
                                    </button>
                                @endif
                                <div class="mb-4 flex justify-between items-center">
                                    <h3 class="text-base font-semibold text-gray-900">👤 Narasumber #{{ $index + 1 }} <span
                                            class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded">DRAFT</span></h3>
                                </div>

                                <div class="space-y-4">
                                    <!-- Row 1: Jenis & Golongan Jabatan -->
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-6">
                                            <label class="form-label">Jenis <span class="text-red-600">*</span></label>
                                            <select name="narasumber[{{ $index }}][jenis]" class="form-input jenis-select" required>
                                                <option value="">-- Pilih Jenis --</option>
                                                <option value="narasumber" {{ $draft->jenis == 'narasumber' ? 'selected' : '' }}>
                                                    Narasumber</option>
                                                <option value="moderator" {{ $draft->jenis == 'moderator' ? 'selected' : '' }}>
                                                    Moderator</option>
                                                <option value="pembawa_acara" {{ $draft->jenis == 'pembawa_acara' ? 'selected' : '' }}>Pembawa Acara</option>
                                                <option value="panitia" {{ $draft->jenis == 'panitia' ? 'selected' : '' }}>Panitia
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-span-6">
                                            <label class="form-label">Golongan Jabatan <span class="text-red-600">*</span></label>
                                            <select name="narasumber[{{ $index }}][golongan_jabatan]"
                                                class="form-input golongan-select" required data-index="{{ $index }}">
                                                <option value="">-- Pilih Golongan --</option>
                                                @foreach($sbmHonorarium as $sbm)
                                                    <option value="{{ $sbm->golongan_jabatan }}"
                                                        data-tarif="{{ $sbm->tarif_honorarium }}" {{ $draft->golongan_jabatan == $sbm->golongan_jabatan ? 'selected' : '' }}>
                                                        {{ $sbm->golongan_jabatan }} (Max: Rp
                                                        {{ number_format($sbm->tarif_honorarium, 0, ',', '.') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p class="text-xs text-gray-500 mt-1 tarif-info" data-index="{{ $index }}">Tarif SBM
                                                akan ditampilkan</p>
                                        </div>
                                    </div>

                                    <!-- Row 2: Nama & NPWP -->
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-5">
                                            <label class="form-label">Nama Narasumber <span class="text-red-600">*</span></label>
                                            <input type="text" name="narasumber[{{ $index }}][nama_narasumber]" class="form-input"
                                                required placeholder="Masukkan nama narasumber"
                                                value="{{ $draft->nama_narasumber }}">
                                        </div>
                                        <div class="col-span-4">
                                            <label class="form-label">NPWP <span class="text-red-600">*</span></label>
                                            <input type="text" name="narasumber[{{ $index }}][npwp]" class="form-input" required
                                                placeholder="01.234.567.8-901.000" maxlength="20" value="{{ $draft->npwp }}">
                                        </div>
                                        <div class="col-span-3">
                                            <label class="form-label">Jumlah OJ <span class="text-red-600">*</span></label>
                                            <input type="number" name="narasumber[{{ $index }}][jumlah_jam]" class="form-input jumlah-jam-input"
                                                required min="1" value="{{ $draft->jumlah_jam ?? 1 }}" data-index="{{ $index }}">
                                            <p class="text-xs text-gray-500 mt-1">Orang Jam</p>
                                        </div>
                                    </div>

                                    <!-- Row 3: Tarif PPh & Honorarium -->
                                    <div class="grid grid-cols-12 gap-3">
                                        <div class="col-span-4">
                                            <label class="form-label">Tarif PPh 21 <span class="text-red-600">*</span></label>
                                            <select name="narasumber[{{ $index }}][tarif_pph21]" class="form-input tarif-select"
                                                required data-index="{{ $index }}">
                                                <option value="">-- Pilih --</option>
                                                <option value="0" {{ $draft->tarif_pph21 == '0' ? 'selected' : '' }}>0%</option>
                                                <option value="5" {{ $draft->tarif_pph21 == '5' ? 'selected' : '' }}>5%</option>
                                                <option value="6" {{ $draft->tarif_pph21 == '6' ? 'selected' : '' }}>6%</option>
                                                <option value="15" {{ $draft->tarif_pph21 == '15' ? 'selected' : '' }}>15%</option>
                                            </select>
                                        </div>
                                        <div class="col-span-5">
                                            <label class="form-label">Honorarium Bruto <span class="text-red-600">*</span></label>
                                            <input type="number" name="narasumber[{{ $index }}][honorarium_bruto]"
                                                class="form-input honor-input" required min="0" data-index="{{ $index }}"
                                                placeholder="Nominal honorarium" value="{{ $draft->honorarium_bruto }}">
                                            <p class="text-xs text-red-600 mt-1 hidden error-honorarium" data-index="{{ $index }}">
                                            </p>
                                        </div>
                                        <div class="col-span-3">
                                            <label class="form-label text-xs">Netto</label>
                                            <div class="px-3 py-2 bg-green-100 rounded text-sm font-bold text-green-700 netto-display"
                                                data-index="{{ $index }}">
                                                Rp {{ number_format($draft->honorarium_netto, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Default Empty Item -->
                        <div class="narasumber-item border border-gray-200 rounded-lg p-4 bg-gray-50">
                            <div class="mb-4 flex justify-between items-center">
                                <h3 class="text-base font-semibold text-gray-900">👤 Narasumber #1</h3>
                            </div>

                            <div class="space-y-4">
                                <!-- Row 1: Jenis & Golongan Jabatan -->
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-6">
                                        <label class="form-label">Jenis <span class="text-red-600">*</span></label>
                                        <select name="narasumber[0][jenis]" class="form-input jenis-select" required>
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="narasumber">Narasumber</option>
                                            <option value="moderator">Moderator</option>
                                            <option value="pembawa_acara">Pembawa Acara</option>
                                            <option value="panitia">Panitia</option>
                                        </select>
                                    </div>
                                    <div class="col-span-6">
                                        <label class="form-label">Golongan Jabatan <span class="text-red-600">*</span></label>
                                        <select name="narasumber[0][golongan_jabatan]" class="form-input golongan-select"
                                            required data-index="0">
                                            <option value="">-- Pilih Golongan --</option>
                                            @foreach($sbmHonorarium as $sbm)
                                                <option value="{{ $sbm->golongan_jabatan }}"
                                                    data-tarif="{{ $sbm->tarif_honorarium }}">
                                                    {{ $sbm->golongan_jabatan }} (Max: Rp
                                                    {{ number_format($sbm->tarif_honorarium, 0, ',', '.') }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="text-xs text-gray-500 mt-1 tarif-info" data-index="0">Tarif SBM akan
                                            ditampilkan</p>
                                    </div>
                                </div>

                                <!-- Row 2: Nama & NPWP -->
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-5">
                                        <label class="form-label">Nama Narasumber <span class="text-red-600">*</span></label>
                                        <input type="text" name="narasumber[0][nama_narasumber]" class="form-input" required
                                            placeholder="Masukkan nama narasumber">
                                    </div>
                                    <div class="col-span-4">
                                        <label class="form-label">NPWP <span class="text-red-600">*</span></label>
                                        <input type="text" name="narasumber[0][npwp]" class="form-input" required
                                            placeholder="01.234.567.8-901.000" maxlength="20">
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label">Jumlah OJ <span class="text-red-600">*</span></label>
                                        <input type="number" name="narasumber[0][jumlah_jam]" class="form-input jumlah-jam-input"
                                            required min="1" value="1" data-index="0">
                                        <p class="text-xs text-gray-500 mt-1">Orang Jam</p>
                                    </div>
                                </div>

                                <!-- Row 3: Tarif PPh & Honorarium -->
                                <div class="grid grid-cols-12 gap-3">
                                    <div class="col-span-4">
                                        <label class="form-label">Tarif PPh 21 <span class="text-red-600">*</span></label>
                                        <select name="narasumber[0][tarif_pph21]" class="form-input tarif-select" required
                                            data-index="0">
                                            <option value="">-- Pilih --</option>
                                            <option value="0">0%</option>
                                            <option value="5" selected>5%</option>
                                            <option value="6">6%</option>
                                            <option value="15">15%</option>
                                        </select>
                                    </div>
                                    <div class="col-span-5">
                                        <label class="form-label">Honorarium Bruto <span class="text-red-600">*</span></label>
                                        <input type="number" name="narasumber[0][honorarium_bruto]"
                                            class="form-input honor-input" required min="0" data-index="0"
                                            placeholder="Nominal honorarium">
                                        <p class="text-xs text-red-600 mt-1 hidden error-honorarium" data-index="0"></p>
                                    </div>
                                    <div class="col-span-3">
                                        <label class="form-label text-xs">Netto</label>
                                        <div class="px-3 py-2 bg-green-100 rounded text-sm font-bold text-green-700 netto-display"
                                            data-index="0">
                                            Rp 0
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Add Button & Total -->
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                    <button type="button" onclick="addNarasumberItem()"
                        class="text-sm text-primary hover:text-primary-dark font-medium">
                        + Tambah Narasumber
                    </button>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total Honorarium Bruto:</div>
                        <div id="total-honorarium" class="text-xl font-bold text-primary">Rp 0</div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t-2 border-gray-300">
                    <div class="flex gap-2">
                        <button type="submit" name="save_as_draft" value="0" class="btn-primary">
                            💾 Simpan & Validasi
                        </button>
                        <button type="submit" name="save_as_draft" value="1" class="btn-secondary">
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
            let narasumberIndex = {{ $hasDraft ? $draftData->count() : 1 }};

            // SBM Options for template
            const sbmOptions = `
                <option value="">-- Pilih Golongan --</option>
                @foreach($sbmHonorarium as $sbm)
                    <option value="{{ $sbm->golongan_jabatan }}" data-tarif="{{ $sbm->tarif_honorarium }}">
                        {{ $sbm->golongan_jabatan }} (Max: Rp {{ number_format($sbm->tarif_honorarium, 0, ',', '.') }})
                    </option>
                @endforeach
            `;

            // Add Narasumber Item
            function addNarasumberItem() {
                const container = document.getElementById('narasumber-container');
                const template = `
                    <div class="narasumber-item border border-gray-200 rounded-lg p-4 bg-gray-50 relative">
                        <button type="button" onclick="removeNarasumberItem(this)" class="absolute top-2 right-2 text-red-600 hover:text-red-800 text-sm font-medium">
                            ✕ Hapus
                        </button>
                        <div class="mb-4">
                            <h3 class="text-base font-semibold text-gray-900">👤 Narasumber #${narasumberIndex + 1}</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Row 1: Jenis & Golongan -->
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-6">
                                    <label class="form-label">Jenis <span class="text-red-600">*</span></label>
                                    <select name="narasumber[${narasumberIndex}][jenis]" class="form-input jenis-select" required>
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="narasumber">Narasumber</option>
                                        <option value="moderator">Moderator</option>
                                        <option value="pembawa_acara">Pembawa Acara</option>
                                        <option value="panitia">Panitia</option>
                                    </select>
                                </div>
                                <div class="col-span-6">
                                    <label class="form-label">Golongan Jabatan <span class="text-red-600">*</span></label>
                                    <select name="narasumber[${narasumberIndex}][golongan_jabatan]" class="form-input golongan-select" required data-index="${narasumberIndex}">
                                        ${sbmOptions}
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1 tarif-info" data-index="${narasumberIndex}">Tarif SBM akan ditampilkan</p>
                                </div>
                            </div>

                            <!-- Row 2: Nama & NPWP -->
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-5">
                                    <label class="form-label">Nama Narasumber <span class="text-red-600">*</span></label>
                                    <input type="text" name="narasumber[${narasumberIndex}][nama_narasumber]" class="form-input" required placeholder="Masukkan nama narasumber">
                                </div>
                                <div class="col-span-4">
                                    <label class="form-label">NPWP <span class="text-red-600">*</span></label>
                                    <input type="text" name="narasumber[${narasumberIndex}][npwp]" class="form-input" required placeholder="01.234.567.8-901.000" maxlength="20">
                                </div>
                                <div class="col-span-3">
                                    <label class="form-label">Jumlah OJ <span class="text-red-600">*</span></label>
                                    <input type="number" name="narasumber[${narasumberIndex}][jumlah_jam]" class="form-input jumlah-jam-input" required min="1" value="1" data-index="${narasumberIndex}">
                                    <p class="text-xs text-gray-500 mt-1">Orang Jam</p>
                                </div>
                            </div>

                            <!-- Row 3: Tarif PPh & Honorarium -->
                            <div class="grid grid-cols-12 gap-3">
                                <div class="col-span-4">
                                    <label class="form-label">Tarif PPh 21 <span class="text-red-600">*</span></label>
                                    <select name="narasumber[${narasumberIndex}][tarif_pph21]" class="form-input tarif-select" required data-index="${narasumberIndex}">
                                        <option value="">-- Pilih --</option>
                                        <option value="0">0%</option>
                                        <option value="5" selected>5%</option>
                                        <option value="6">6%</option>
                                        <option value="15">15%</option>
                                    </select>
                                </div>
                                <div class="col-span-5">
                                    <label class="form-label">Honorarium Bruto <span class="text-red-600">*</span></label>
                                    <input type="number" name="narasumber[${narasumberIndex}][honorarium_bruto]" class="form-input honor-input" required min="0" data-index="${narasumberIndex}" placeholder="Nominal honorarium">
                                    <p class="text-xs text-red-600 mt-1 hidden error-honorarium" data-index="${narasumberIndex}"></p>
                                </div>
                                <div class="col-span-3">
                                    <label class="form-label text-xs">Netto</label>
                                    <div class="px-3 py-2 bg-green-100 rounded text-sm font-bold text-green-700 netto-display" data-index="${narasumberIndex}">
                                        Rp 0
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', template);
                narasumberIndex++;
                attachEvents();
                updateNumbers();
            }

            // Remove Narasumber Item
            function removeNarasumberItem(btn) {
                if (document.querySelectorAll('.narasumber-item').length > 1) {
                    btn.closest('.narasumber-item').remove();
                    updateNumbers();
                    calculateTotals();
                } else {
                    alert('Minimal harus ada 1 narasumber!');
                }
            }

            // Update numbering
            function updateNumbers() {
                document.querySelectorAll('.narasumber-item').forEach((item, idx) => {
                    const title = item.querySelector('h3');
                    if (title) {
                        const draftBadge = title.querySelector('span');
                        title.innerHTML = `👤 Narasumber #${idx + 1}`;
                        if (draftBadge) {
                            title.appendChild(document.createTextNode(' '));
                            title.appendChild(draftBadge);
                        }
                    }
                });
            }

            // Calculate Totals
            function calculateTotals() {
                let totalBruto = 0;
                document.querySelectorAll('.honor-input').forEach(input => {
                    const bruto = parseInt(input.value) || 0;
                    totalBruto += bruto;
                });
                document.getElementById('total-honorarium').textContent = 'Rp ' + totalBruto.toLocaleString('id-ID');
            }

            // Calculate Netto for each item
            function calculateNetto(index) {
                const honorInput = document.querySelector(`.honor-input[data-index="${index}"]`);
                const tarifSelect = document.querySelector(`.tarif-select[data-index="${index}"]`);
                const nettoDisplay = document.querySelector(`.netto-display[data-index="${index}"]`);
                const golonganSelect = document.querySelector(`.golongan-select[data-index="${index}"]`);
                const errorEl = document.querySelector(`.error-honorarium[data-index="${index}"]`);

                if (!honorInput || !tarifSelect || !nettoDisplay) return;

                const bruto = parseInt(honorInput.value) || 0;
                const tarif = parseInt(tarifSelect.value) || 0;
                const pph = Math.floor((bruto * tarif) / 100);
                const netto = bruto - pph;

                nettoDisplay.textContent = 'Rp ' + netto.toLocaleString('id-ID');

                // Validate against SBM
                if (golonganSelect) {
                    const selectedOption = golonganSelect.options[golonganSelect.selectedIndex];
                    const maxTarif = parseInt(selectedOption.dataset?.tarif) || 0;

                    if (maxTarif > 0 && bruto > maxTarif) {
                        honorInput.classList.add('border-red-500', 'bg-red-50');
                        if (errorEl) {
                            errorEl.textContent = `⚠️ Melebihi SBM! Max: Rp ${maxTarif.toLocaleString('id-ID')}`;
                            errorEl.classList.remove('hidden');
                        }
                    } else {
                        honorInput.classList.remove('border-red-500', 'bg-red-50');
                        if (errorEl) {
                            errorEl.classList.add('hidden');
                        }
                    }
                }

                calculateTotals();
            }

            // Update tarif info
            function updateTarifInfo(index) {
                const golonganSelect = document.querySelector(`.golongan-select[data-index="${index}"]`);
                const tarifInfo = document.querySelector(`.tarif-info[data-index="${index}"]`);

                if (!golonganSelect || !tarifInfo) return;

                const selectedOption = golonganSelect.options[golonganSelect.selectedIndex];
                if (selectedOption.value) {
                    const maxTarif = parseInt(selectedOption.dataset?.tarif) || 0;
                    tarifInfo.textContent = `Max: Rp ${maxTarif.toLocaleString('id-ID')}`;
                    tarifInfo.classList.remove('text-gray-500');
                    tarifInfo.classList.add('text-primary', 'font-medium');
                } else {
                    tarifInfo.textContent = 'Tarif SBM akan ditampilkan';
                    tarifInfo.classList.add('text-gray-500');
                    tarifInfo.classList.remove('text-primary', 'font-medium');
                }

                calculateNetto(index);
            }

            // Attach events to all inputs
            function attachEvents() {
                document.querySelectorAll('.honor-input').forEach(input => {
                    input.removeEventListener('input', handleHonorInput);
                    input.addEventListener('input', handleHonorInput);
                });

                document.querySelectorAll('.tarif-select').forEach(select => {
                    select.removeEventListener('change', handleTarifChange);
                    select.addEventListener('change', handleTarifChange);
                });

                document.querySelectorAll('.golongan-select').forEach(select => {
                    select.removeEventListener('change', handleGolonganChange);
                    select.addEventListener('change', handleGolonganChange);
                });
            }

            function handleHonorInput(e) {
                const index = e.target.dataset.index;
                calculateNetto(index);
            }

            function handleTarifChange(e) {
                const index = e.target.dataset.index;
                calculateNetto(index);
            }

            function handleGolonganChange(e) {
                const index = e.target.dataset.index;
                updateTarifInfo(index);
            }

            // Initialize
            document.addEventListener('DOMContentLoaded', function () {
                attachEvents();
                // Calculate initial totals for draft data
                calculateTotals();
                // Update tarif info for all existing items
                document.querySelectorAll('.golongan-select').forEach(select => {
                    const index = select.dataset.index;
                    if (select.value) {
                        updateTarifInfo(index);
                    }
                });
            });
        </script>
    @endpush
@endsection
