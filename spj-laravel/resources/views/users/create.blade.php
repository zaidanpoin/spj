@extends('layouts.app')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')
@section('page-subtitle', 'Form tambah user baru')

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <!-- NIP Lookup Section -->
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <label class="block text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Cari Pegawai dari EHRM
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="nip_search"
                            class="flex-1 px-3 py-2 border border-blue-300 dark:border-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-white"
                            placeholder="Masukkan NIP (18 digit)">
                        <button type="button" id="btn_search_nip"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>
                    </div>
                    <div id="search_result" class="mt-2 text-sm hidden"></div>
                </div>

                <!-- NIP (Hidden, auto-filled) -->
                <input type="hidden" name="nip" id="nip_value">

                <!-- Nama -->
                <div class="mb-4">
                    <label class="form-label">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="form-input @error('name') border-red-500 @enderror" placeholder="Contoh: John Doe">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="form-input @error('email') border-red-500 @enderror" placeholder="Contoh: john@spj.go.id">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required
                        class="form-input @error('password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="mb-4">
                    <label class="form-label">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password_confirmation" required class="form-input"
                        placeholder="Ketik ulang password">
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label class="form-label">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="form-input @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        @if(Auth::user()->role === 'super_admin')
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        @endif
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @if(Auth::user()->role === 'admin')
                        <p class="text-xs text-gray-500 mt-1">Admin hanya bisa membuat user biasa</p>
                    @endif
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="form-input @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Kerja (dari EHRM API) -->
                <div class="mb-4">
                    <label class="form-label">Unit Kerja</label>
                    <input type="text" id="unker_display" name="unker_display" readonly
                        class="form-input bg-gray-100 dark:bg-gray-700 cursor-not-allowed" value="-"
                        placeholder="Otomatis terisi dari NIP">
                    <input type="hidden" name="kdunit" id="kdunit_value">
                    <p class="text-xs text-gray-500 mt-1">Otomatis terisi saat cari NIP dari EHRM</p>
                </div>

                <!-- Unor (dari EHRM API) -->
                <div class="mb-4">
                    <label class="form-label">Unit Organisasi (Unor)</label>
                    <input type="text" id="unor_display" name="unor_display" readonly
                        class="form-input bg-gray-100 dark:bg-gray-700 cursor-not-allowed" value="-"
                        placeholder="Otomatis terisi dari NIP">
                    <input type="hidden" name="kdunor" id="kdunor_value">
                    <p class="text-xs text-gray-500 mt-1">Otomatis terisi saat cari NIP dari EHRM</p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Simpan User
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // NIP Search functionality
        document.getElementById('btn_search_nip').addEventListener('click', searchNIP);
        document.getElementById('nip_search').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchNIP();
            }
        });

        async function searchNIP() {
            const nip = document.getElementById('nip_search').value.trim();
            const resultDiv = document.getElementById('search_result');
            const btn = document.getElementById('btn_search_nip');

            if (!nip) {
                showResult('Masukkan NIP terlebih dahulu', 'error');
                return;
            }

            if (nip.length < 18) {
                showResult('NIP harus 18 digit', 'error');
                return;
            }

            // Show loading
            btn.disabled = true;
            btn.innerHTML = `
                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Mencari...
                    `;

            try {
                const response = await fetch(`/api/ehrm/pegawai/${nip}`);
                const data = await response.json();

                if (data.success && data.data) {
                    // Auto-fill form fields
                    const pegawai = data.data;

                    document.getElementById('nip_value').value = pegawai.nip || nip;
                    document.querySelector('input[name="name"]').value = pegawai.nama || '';
                    document.querySelector('input[name="email"]').value = pegawai.email || '';

                    // Fill Unit Kerja dan Unor dari EHRM
                    document.getElementById('unker_display').value = pegawai.nama_unit || pegawai.unker || '-';
                    document.getElementById('kdunit_value').value = pegawai.kdunit || '';
                    document.getElementById('unor_display').value = pegawai.unor || '-';
                    document.getElementById('kdunor_value').value = pegawai.kdunor || '';

                    showResult(`
                                <div class="text-green-700 dark:text-green-400">
                                    <strong>✓ Data ditemukan:</strong><br>
                                    Nama: ${pegawai.nama}<br>
                                    Jabatan: ${pegawai.jabatan || '-'}<br>
                                    Golongan: ${pegawai.golongan || '-'}<br>
                                    Unit Kerja: ${pegawai.nama_unit || pegawai.unker || '-'}<br>
                                    Unor: ${pegawai.unor || '-'}
                                </div>
                            `, 'success');
                } else {
                    showResult('Pegawai tidak ditemukan', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showResult('Gagal menghubungi server EHRM', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = `
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        `;
            }
        }

        function showResult(message, type) {
            const resultDiv = document.getElementById('search_result');
            resultDiv.innerHTML = message;
            resultDiv.classList.remove('hidden', 'text-red-600', 'text-green-600');
            resultDiv.classList.add(type === 'error' ? 'text-red-600' : 'text-green-600');
        }

        function updateUnor() {
            const select = document.getElementById('id_unker');
            const unorDisplay = document.getElementById('unor_display');

            if (select && unorDisplay) {
                const selectedOption = select.options[select.selectedIndex];
                const unor = selectedOption.getAttribute('data-unor') || '-';
                unorDisplay.value = unor;
            }
        }
    </script>
@endpush