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
            <div class="grid grid-cols-3 gap-2">
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
                <a href="{{ route('sppd.create', $kegiatan->id) }}"
                   class="flex items-center gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded hover:border-blue-400 hover:bg-blue-50 transition group">
                    <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center text-blue-600 text-sm font-semibold">
                        PD
                    </div>
                    <div class="flex-1 text-left">
                        <div class="text-xs font-medium text-gray-900 group-hover:text-blue-600">Perjalanan Dinas</div>
                        <div class="text-xs text-gray-500">SPPD</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Data Konsumsi: Compact Table -->
        <div class="bg-white rounded border border-gray-200">
            <div class="px-3 py-2 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-900">Data Belanja</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                        {{ $snacks->count() + $makanans->count() + $barangs->count() }} item
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    @php $hasKonsumsi = $snacks->count() + $makanans->count() + $barangs->count(); @endphp
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

            @if($snacks->count() > 0 || $makanans->count() > 0 || $barangs->count() > 0)
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

                    <!-- Barang grouped by Vendor -->
                    @if(isset($vendorGroups) && $vendorGroups->count() > 0)
                        @foreach($vendorGroups as $vendorName => $items)
                            @php
                                $vendor = $items->first()->vendor;
                                $vendorId = $vendor ? $vendor->id : null;
                                $vendorBank = $vendor ? $vendor->bank : '';
                                $vendorRekening = $vendor ? $vendor->rekening : '';
                                $vendorPpn = $vendor ? ($vendor->ppn ?? 11) : 11;
                            @endphp
                            <div class="p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-500 uppercase">Vendor: {{ $vendorName }}</div>
                                        @if($vendorId)
                                            <button onclick="openVendorEditModal({{ $vendorId }}, '{{ $vendorName }}', '{{ $vendorBank }}', '{{ $vendorRekening }}', {{ $vendorPpn }})"
                                                    class="text-xs px-2 py-0.5 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Edit Vendor
                                            </button>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-600">Items: {{ $items->count() }}</div>
                                </div>

                                <table class="w-full text-xs">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-2 py-1.5 text-left font-medium text-gray-600">Nama</th>
                                            <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-24">No Kwitansi</th>
                                            <th class="px-2 py-1.5 text-left font-medium text-gray-600 w-28">Tgl Pembelian</th>
                                            <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-12">Qty</th>
                                            <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-20">Harga</th>
                                            <th class="px-2 py-1.5 text-right font-medium text-gray-600 w-24">Subtotal</th>
                                            <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-16">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($items as $item)
                                            <tr>
                                                <td class="px-2 py-1.5">{{ $item->nama_konsumsi }}</td>
                                                <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->no_kwitansi ?? '-' }}</td>
                                                <td class="px-2 py-1.5 text-xs text-gray-600">{{ $item->tanggal_pembelian ? \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d/m/Y') : '-' }}</td>
                                                <td class="px-2 py-1.5 text-center">{{ $item->jumlah }}</td>
                                                <td class="px-2 py-1.5 text-right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td class="px-2 py-1.5 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
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
                                            <td colspan="5" class="px-2 py-1.5 text-right font-semibold">Subtotal:</td>
                                            <td class="px-2 py-1.5 text-right font-bold text-primary">Rp {{ number_format($vendorTotals[$vendorName] ?? 0, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td colspan="5" class="px-2 py-1.5 text-right font-semibold">PPN {{ number_format($vendorPpn, 0) }}%:</td>
                                            <td class="px-2 py-1.5 text-right text-red-600">Rp {{ number_format($vendorTaxes[$vendorName] ?? 0, 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>

                                        <tr>
                                            <td colspan="5" class="px-2 py-1.5 text-right font-semibold">Total (inc. PPN):</td>
                                            <td class="px-2 py-1.5 text-right font-bold text-primary">Rp {{ number_format($vendorTotalsWithTax[$vendorName] ?? ($vendorTotals[$vendorName] ?? 0), 0, ',', '.') }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endforeach
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

        <!-- Data SPPD (Perjalanan Dinas) -->
        <div class="bg-white rounded border border-gray-200 mt-4">
            <div class="px-3 py-2 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-semibold text-gray-900">Data Perjalanan Dinas (SPPD)</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">
                        {{ $sppds->count() }} item
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('sppd.create', $kegiatan->id) }}"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                        + Tambah
                    </a>
                </div>
            </div>

            @if($sppds->count() > 0)
                <div class="p-3">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Nama Pelaksana</th>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">NIP</th>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Jabatan</th>
                                <th class="px-2 py-1.5 text-left font-medium text-gray-600">Tujuan</th>
                                <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-24">Tanggal</th>
                                <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-16">Lama</th>
                                <th class="px-2 py-1.5 text-center font-medium text-gray-600 w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($sppds as $sppd)
                                <tr>
                                    <td class="px-2 py-1.5 font-medium">{{ $sppd->nama }}</td>
                                    <td class="px-2 py-1.5 text-gray-600">{{ $sppd->nip ?: '-' }}</td>
                                    <td class="px-2 py-1.5 text-gray-600">{{ $sppd->jabatan ?: '-' }}</td>
                                    <td class="px-2 py-1.5">{{ $sppd->tujuan ?: '-' }}</td>
                                    <td class="px-2 py-1.5 text-center text-xs">
                                        @if($sppd->tgl_brkt && $sppd->tgl_kbl)
                                            {{ $sppd->tgl_brkt->format('d/m/Y') }} - {{ $sppd->tgl_kbl->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-1.5 text-center">
                                        @if($sppd->tgl_brkt && $sppd->tgl_kbl)
                                            {{ $sppd->tgl_brkt->diffInDays($sppd->tgl_kbl) + 1 }} hari
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-2 py-1.5 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('sppd.preview', $sppd->id) }}"
                                               class="text-blue-600 hover:text-blue-800 text-xs font-medium"
                                               title="Preview">
                                                👁️
                                            </a>
                                            <a href="{{ route('sppd.edit', $sppd->id) }}"
                                               class="text-green-600 hover:text-green-800 text-xs font-medium"
                                               title="Edit">
                                                ✏️
                                            </a>
                                            <form action="{{ route('sppd.destroy', $sppd->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus SPPD ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium" title="Hapus">✕</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-6 text-center text-gray-400">
                    <div class="text-sm">Belum ada data perjalanan dinas</div>
                </div>
            @endif
        </div>

                <!-- Grand Total: Minimal Design -->
                <div class="px-3 py-3 bg-gray-50 border-t-2 border-gray-300 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-gray-900">Grand Total (excl. vendor PPN):</span>
                        <span class="text-lg font-bold text-primary">Rp {{ number_format($grandTotal ?? 0, 0, ',', '.') }}</span>
                        @if(isset($vendorTaxTotal) && $vendorTaxTotal > 0)
                            <span class="text-sm text-gray-700 mt-1">Total Vendor PPN: <span class="text-red-600 font-medium">Rp {{ number_format($vendorTaxTotal, 0, ',', '.') }}</span></span>
                        @endif
                    </div>

                    <div class="text-right">
                        <span class="text-sm font-semibold text-gray-900">Grand Total (inc. vendor PPN):</span>
                        <div class="text-lg font-bold text-primary">Rp {{ number_format($grandTotalWithVendorTax ?? $grandTotal ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
    </div>

    <!-- Modal Edit Vendor -->
    <div id="vendorEditModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Edit Data Vendor</h3>
                <button onclick="closeVendorEditModal()" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>

            <form id="vendorEditForm" onsubmit="submitVendorEdit(event)">
                <input type="hidden" id="vendorId" name="vendor_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
                    <input type="text" id="vendorName" readonly class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-50" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bank</label>
                    <input type="text" id="vendorBank" name="bank" required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                           list="bankList" />
                    <datalist id="bankList">
                        <option value="BRI">
                        <option value="BNI">
                        <option value="Mandiri">
                        <option value="BCA">
                        <option value="BTN">
                        <option value="BSI">
                    </datalist>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekening</label>
                    <input type="text" id="vendorRekening" name="rekening" required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">PPN (%)</label>
                    <select id="vendorPpn" name="ppn" required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="0">0% - Non-PKP</option>
                        <option value="11" selected>11% - PKP (Default)</option>
                        <option value="12">12% - PKP (New Rate)</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih persentase PPN sesuai status vendor</p>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeVendorEditModal()"
                            class="px-4 py-2 text-sm text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openVendorEditModal(vendorId, vendorName, bank, rekening, ppn) {
            document.getElementById('vendorId').value = vendorId;
            document.getElementById('vendorName').value = vendorName;
            document.getElementById('vendorBank').value = bank || '';
            document.getElementById('vendorRekening').value = rekening || '';
            document.getElementById('vendorPpn').value = ppn || 11;
            document.getElementById('vendorEditModal').classList.remove('hidden');
        }

        function closeVendorEditModal() {
            document.getElementById('vendorEditModal').classList.add('hidden');
        }

        async function submitVendorEdit(event) {
            event.preventDefault();

            const vendorId = document.getElementById('vendorId').value;
            const bank = document.getElementById('vendorBank').value;
            const rekening = document.getElementById('vendorRekening').value;
            const ppn = document.getElementById('vendorPpn').value;

            try {
                const response = await fetch(`/vendor/${vendorId}/update-bank`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ bank, rekening, ppn })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Data vendor berhasil diperbarui!');
                    closeVendorEditModal();
                    location.reload();
                } else {
                    alert('Gagal memperbarui data vendor: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memperbarui data vendor');
            }
        }
    </script>
@endsection
