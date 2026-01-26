@extends('layouts.app')

@section('title', 'Detail Kegiatan')
@section('page-title', $kegiatan->nama_kegiatan)
@section('page-subtitle', ($kegiatan->unor->nama_unor ?? '-') . ' | ' . ($kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->format('d M Y') : '-'))

@section('content')
    <div class="space-y-3">
        <!-- Header: Compact & Professional -->
        <div class="bg-white px-4 py-3 rounded border border-gray-200 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-sm text-gray-600">
                <a href="{{ route('kegiatan.index') }}" class="hover:text-primary">← Kembali</a>
                <span class="text-gray-300">|</span>
                <span>{{ $kegiatan->unitKerja->nama_unit ?? '-' }}</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('kegiatan.daftar-hadir', $kegiatan->id) }}"
                    class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded hover:bg-gray-200 transition">
                    Daftar Hadir
                </a>
            </div>
        </div>


        <!-- Action Cards: Compact & Professional -->
        <div class="bg-white rounded border border-gray-200 p-3">
            <h3 class="text-xs font-semibold text-gray-500 uppercase mb-2">Tambah Detail</h3>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('konsumsi.create', $kegiatan->id) }}"
                   class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded hover:border-primary hover:bg-gray-100 transition group">
                    <div class="w-8 h-8 bg-primary bg-opacity-10 rounded flex items-center justify-center text-primary text-sm font-semibold">
                        K
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-xs font-medium text-gray-900 group-hover:text-primary">Konsumsi</div>
                        <div class="text-xs text-gray-500">Snack, Makanan, Barang</div>
                    </div>
                </a>
                <a href="{{ route('narasumber.create', $kegiatan->id) }}"
                   class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded hover:border-purple-400 hover:bg-purple-50 transition group">
                    <div class="w-8 h-8 bg-purple-100 rounded flex items-center justify-center text-purple-600 text-sm font-semibold">
                        JP
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-xs font-medium text-gray-900 group-hover:text-purple-600">Jasa Profesi</div>
                        <div class="text-xs text-gray-500">Narasumber, Moderator</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Data Konsumsi: Compact Table -->
        <div class="bg-white rounded border border-gray-200">
            <div class="px-3 py-2 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-900">Data Konsumsi</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                        {{ $snacks->count() + $makanans->count() }} item
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    @php $hasKonsumsi = $snacks->count() + $makanans->count(); @endphp
                    <a href="{{ $hasKonsumsi > 0 ? route('kwitansi.generate', ['kegiatan_id' => $kegiatan->id, 'jenis' => 'UP', 'type' => 'konsumsi']) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $hasKonsumsi > 0 ? 'bg-primary text-white hover:bg-primary-dark' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($hasKonsumsi == 0) onclick="event.preventDefault(); alert('Belum ada data konsumsi');" @endif>
                        📄 Kuitansi UP
                    </a>
                    <a href="{{ $hasKonsumsi > 0 ? route('kwitansi.generate', ['kegiatan_id' => $kegiatan->id, 'jenis' => 'LS', 'type' => 'konsumsi']) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $hasKonsumsi > 0 ? 'bg-gray-700 text-white hover:bg-gray-800' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($hasKonsumsi == 0) onclick="event.preventDefault(); alert('Belum ada data konsumsi');" @endif>
                        📄 Kuitansi LS
                    </a>
                    <a href="{{ route('konsumsi.create', $kegiatan->id) }}"
                        class="text-xs text-primary hover:text-primary-dark font-medium">
                        + Tambah
                    </a>
                </div>
            </div>

            @if($snacks->count() > 0 || $makanans->count() > 0)
                <div class="divide-y divide-gray-100">
                    <!-- Snack -->
                    @if($snacks->count() > 0)
                        <div class="p-3">
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Snack ({{ $snacks->count() }})</div>
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50">
                                    <tr>

                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600">Nama</th>
                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-24">No Kwitansi Per Item</th>
                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-24">Waktu</th>
                                        <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-12">Qty</th>
                                        <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-20">Harga</th>
                                        <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Subtotal</th>
                                        <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-16">Aksi</th>
                                    </tr>

                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($snacks as $item)

                                        <tr>

                                            <td class="px-2 py-1.5">{{ $item->nama_konsumsi }}</td>
                                             <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->no_kwitansi ?? '-' }}</td>
                                            <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->waktuKonsumsi->nama_waktu ?? '-' }}</td>
                                            <td class="px-2 py-1.5 text-center">{{ $item->jumlah }}</td>
                                            <td class="px-2 py-1.5 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="px-2 py-1.5 text-right font-medium">Rp
                                                {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td class="px-2 py-1.5 text-center">
                                                <form action="{{ route('konsumsi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">✕</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td colspan="5" class="px-2 py-1.5 text-right font-semibold">Total:</td>
                                        <td class="px-2 py-1.5 text-right font-bold text-primary">Rp
                                            {{ number_format($totalSnack, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif

                    <!-- Makanan -->
                    @if($makanans->count() > 0)
                        <div class="p-3">
                            <div class="text-xs font-semibold text-gray-500 uppercase mb-1.5">Makanan ({{ $makanans->count() }})
                            </div>
                            <table class="w-full text-xs">
                                <thead class="bg-gray-50">
                                    <tr>

                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600">Nama</th>
                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-24">No Kwitansi Per Item</th>
                                        <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-24">Waktu</th>
                                        <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-12">Qty</th>
                                        <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-20">Harga</th>
                                        <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Subtotal</th>
                                        <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-16">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($makanans as $item)
                                        <tr>

                                            <td class="px-2 py-1.5">{{ $item->nama_konsumsi }}</td>
                                            <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->no_kwitansi ?? '-' }}</td>
                                            <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->waktuKonsumsi->nama_waktu ?? '-' }}</td>
                                            <td class="px-2 py-1.5 text-center">{{ $item->jumlah }}</td>
                                            <td class="px-2 py-1.5 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="px-2 py-1.5 text-right font-medium">Rp
                                                {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td class="px-2 py-1.5 text-center">
                                                <form action="{{ route('konsumsi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">✕</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 border-t border-gray-200">
                                    <tr>
                                        <td colspan="5" class="px-2 py-1.5 text-right font-semibold">Total:</td>
                                        <td class="px-2 py-1.5 text-right font-bold text-primary">Rp
                                            {{ number_format($totalMakanan, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                <div class="p-6 text-center text-gray-400">
                    <div class="text-sm">Belum ada data konsumsi</div>
                </div>
            @endif
        </div>

        <!-- Data Jasa Profesi: Always visible like Konsumsi -->
        <div class="bg-white rounded border border-gray-200 mt-4">
            <div class="px-3 py-2 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-900">Data Jasa Profesi</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                        {{ $narasumbers->count() }} item
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Print Buttons: Disabled when no data -->
                    <a href="{{ $narasumbers->count() > 0 ? route('narasumber.daftar-hadir', $kegiatan->id) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $narasumbers->count() > 0 ? 'bg-purple-100 text-purple-700 hover:bg-purple-200' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($narasumbers->count() == 0) onclick="event.preventDefault(); alert('Belum ada data narasumber');" @endif>
                        📋 Daftar Hadir
                    </a>
                    <a href="{{ $narasumbers->count() > 0 ? route('narasumber.daftar-honorarium', $kegiatan->id) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $narasumbers->count() > 0 ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($narasumbers->count() == 0) onclick="event.preventDefault(); alert('Belum ada data narasumber');" @endif>
                        💰 Daftar Honorarium
                    </a>
                    <a href="{{ $narasumbers->count() > 0 ? route('kwitansi.generate', ['kegiatan_id' => $kegiatan->id, 'jenis' => 'UP', 'type' => 'honorarium']) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $narasumbers->count() > 0 ? 'bg-primary text-white hover:bg-primary-dark' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($narasumbers->count() == 0) onclick="event.preventDefault(); alert('Belum ada data narasumber');" @endif>
                        📄 Kuitansi UP
                    </a>
                    <a href="{{ $narasumbers->count() > 0 ? route('kwitansi.generate', ['kegiatan_id' => $kegiatan->id, 'jenis' => 'LS', 'type' => 'honorarium']) : '#' }}"
                        class="px-2 py-1 text-xs font-medium rounded transition {{ $narasumbers->count() > 0 ? 'bg-gray-700 text-white hover:bg-gray-800' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}"
                        @if($narasumbers->count() == 0) onclick="event.preventDefault(); alert('Belum ada data narasumber');" @endif>
                        📄 Kuitansi LS
                    </a>
                    <a href="{{ route('narasumber.create', $kegiatan->id) }}"
                        class="text-xs text-primary hover:text-primary-dark font-medium">
                        + Tambah
                    </a>
                </div>
            </div>

            @if($narasumbers->count() > 0)
                <div class="px-3 py-2">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Jenis</th>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Nama</th>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Golongan</th>
                                <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-20">PPh 21</th>
                                <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Bruto</th>
                                <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Pajak</th>
                                <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Netto</th>
                                <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($narasumbers as $narasumber)
                                <tr>
                                    <td class="px-2 py-1.5">
                                        <span class="inline-block px-1.5 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">
                                            {{ ucfirst(str_replace('_', ' ', $narasumber->jenis)) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-1.5 font-medium">{{ $narasumber->nama_narasumber }}</td>
                                    <td class="px-2 py-1.5 text-gray-600">{{ $narasumber->golongan_jabatan }}</td>
                                    <td class="px-2 py-1.5 text-center">
                                        <span class="inline-block px-1.5 py-0.5 bg-gray-100 text-gray-700 rounded">
                                            {{ $narasumber->tarif_pph21 }}%
                                        </span>
                                    </td>
                                    <td class="px-2 py-1.5 text-right text-gray-600">Rp {{ number_format($narasumber->honorarium_bruto, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1.5 text-right text-red-600">Rp {{ number_format($narasumber->pph21, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1.5 text-right font-medium text-primary">Rp {{ number_format($narasumber->honorarium_netto, 0, ',', '.') }}</td>
                                    <td class="px-2 py-1.5 text-center">
                                        <form action="{{ route('narasumber.destroy', $narasumber->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus narasumber ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">✕</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t border-gray-200">
                            <tr>
                                <td colspan="6" class="px-2 py-1.5 text-right font-semibold text-gray-700">Total Honorarium:</td>
                                <td class="px-2 py-1.5 text-right font-bold text-primary">Rp {{ number_format($totalHonorarium, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-400">
                    <div class="text-sm">Belum ada data jasa profesi</div>
                </div>
            @endif
        </div>

                <!-- Grand Total: Minimal Design -->
                <div class="px-3 py-2 bg-gray-50 border-t-2 border-gray-300 flex justify-between items-center">
                    <span class="text-sm font-semibold text-gray-900">Grand Total:</span>
                    <span class="text-lg font-bold text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
    </div>
@endsection
