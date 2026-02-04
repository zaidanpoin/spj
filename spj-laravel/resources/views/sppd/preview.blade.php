@extends('layouts.app')

@section('title', 'Preview SPPD')
@section('page-title', 'Preview SPPD')
@section('page-subtitle', 'Preview Surat Perintah Perjalanan Dinas')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="card">
            <div class="card-header flex justify-between items-center">
                <h2 class="card-title">Preview SPPD</h2>
                <div class="flex gap-2">
                    <a href="{{ route('sppd.edit', $sppd->id) }}" class="btn btn-sm btn-secondary">
                        ✏️ Edit
                    </a>
                    <button onclick="window.print()" class="btn btn-sm btn-primary">
                        🖨️ Cetak
                    </button>
                </div>
            </div>

            <div class="card-body" id="printable-area">
                <!-- Header SPPD -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold mb-2">SURAT PERINTAH PERJALANAN DINAS</h1>
                    <h2 class="text-xl font-semibold">(SPPD)</h2>
                    @if($sppd->no)
                        <p class="mt-2 text-lg">Nomor: {{ $sppd->no }}</p>
                    @endif
                </div>

                <!-- Data Pelaksana -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">I. DATA PELAKSANA</h3>

                    <table class="w-full">
                        <tr>
                            <td class="py-2 w-1/3">Nama</td>
                            <td class="py-2 w-10">:</td>
                            <td class="py-2 font-semibold">{{ $sppd->nama }}</td>
                        </tr>
                        @if($sppd->nip)
                        <tr>
                            <td class="py-2">NIP</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->nip }}</td>
                        </tr>
                        @endif
                        @if($sppd->pangkat_gol)
                        <tr>
                            <td class="py-2">Pangkat/Golongan</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->pangkat_gol }}</td>
                        </tr>
                        @endif
                        @if($sppd->jabatan)
                        <tr>
                            <td class="py-2">Jabatan</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->jabatan }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-2">Status</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->is_pns ? 'PNS' : 'Non PNS' }}</td>
                        </tr>
                    </table>
                </div>

                <!-- Detail Perjalanan -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">II. DETAIL PERJALANAN DINAS</h3>

                    <table class="w-full">
                        @if($sppd->maksud)
                        <tr>
                            <td class="py-2 w-1/3 align-top">Maksud Perjalanan Dinas</td>
                            <td class="py-2 w-10 align-top">:</td>
                            <td class="py-2">{{ $sppd->maksud }}</td>
                        </tr>
                        @endif
                        @if($sppd->tempat_berangkat)
                        <tr>
                            <td class="py-2">Tempat Berangkat</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->tempat_berangkat }}</td>
                        </tr>
                        @endif
                        @if($sppd->tujuan)
                        <tr>
                            <td class="py-2">Tempat Tujuan</td>
                            <td class="py-2">:</td>
                            <td class="py-2 font-semibold">{{ $sppd->tujuan }}</td>
                        </tr>
                        @endif
                        @if($sppd->tgl_brkt)
                        <tr>
                            <td class="py-2">Tanggal Berangkat</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->tgl_brkt->format('d F Y') }}</td>
                        </tr>
                        @endif
                        @if($sppd->tgl_kbl)
                        <tr>
                            <td class="py-2">Tanggal Kembali</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->tgl_kbl->format('d F Y') }}</td>
                        </tr>
                        @endif
                        @if($sppd->tgl_brkt && $sppd->tgl_kbl)
                        <tr>
                            <td class="py-2">Lama Perjalanan</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->tgl_brkt->diffInDays($sppd->tgl_kbl) + 1 }} Hari</td>
                        </tr>
                        @endif
                        @if($sppd->alat_angkut)
                        <tr>
                            <td class="py-2">Alat Angkut</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->alat_angkut }}</td>
                        </tr>
                        @endif
                        @if($sppd->tingkat_biaya)
                        <tr>
                            <td class="py-2">Tingkat Biaya Perjalanan Dinas</td>
                            <td class="py-2">:</td>
                            <td class="py-2">{{ $sppd->tingkat_biaya }}</td>
                        </tr>
                        @endif
                    </table>
                </div>

                <!-- Pejabat -->
                @if($sppd->perintah_nama || $sppd->ppk_nama || $sppd->bendahara_nama)
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">III. PEJABAT TERKAIT</h3>

                    @if($sppd->perintah_nama)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Pejabat Pemberi Perintah:</h4>
                        <table class="w-full ml-4">
                            <tr>
                                <td class="py-1 w-1/3">Nama</td>
                                <td class="py-1 w-10">:</td>
                                <td class="py-1">{{ $sppd->perintah_nama }}</td>
                            </tr>
                            @if($sppd->perintah_nip)
                            <tr>
                                <td class="py-1">NIP</td>
                                <td class="py-1">:</td>
                                <td class="py-1">{{ $sppd->perintah_nip }}</td>
                            </tr>
                            @endif
                            @if($sppd->perintah_jabatan)
                            <tr>
                                <td class="py-1">Jabatan</td>
                                <td class="py-1">:</td>
                                <td class="py-1">{{ $sppd->perintah_jabatan }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif

                    @if($sppd->ppk_nama)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Pejabat Pembuat Komitmen (PPK):</h4>
                        <table class="w-full ml-4">
                            <tr>
                                <td class="py-1 w-1/3">Nama</td>
                                <td class="py-1 w-10">:</td>
                                <td class="py-1">{{ $sppd->ppk_nama }}</td>
                            </tr>
                            @if($sppd->ppk_nip)
                            <tr>
                                <td class="py-1">NIP</td>
                                <td class="py-1">:</td>
                                <td class="py-1">{{ $sppd->ppk_nip }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif

                    @if($sppd->bendahara_nama)
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Bendahara:</h4>
                        <table class="w-full ml-4">
                            <tr>
                                <td class="py-1 w-1/3">Nama</td>
                                <td class="py-1 w-10">:</td>
                                <td class="py-1">{{ $sppd->bendahara_nama }}</td>
                            </tr>
                            @if($sppd->bendahara_nip)
                            <tr>
                                <td class="py-1">NIP</td>
                                <td class="py-1">:</td>
                                <td class="py-1">{{ $sppd->bendahara_nip }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Catatan -->
                @if($sppd->catatan)
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">IV. CATATAN</h3>
                    <p>{{ $sppd->catatan }}</p>
                </div>
                @endif

                <!-- Footer dengan TTD -->
                <div class="mt-12 grid grid-cols-2 gap-8">
                    <div class="text-center">
                        <p class="mb-16">Pejabat Pemberi Perintah</p>
                        <p class="font-bold border-b-2 border-black inline-block px-8">{{ $sppd->perintah_nama ?? '_______________' }}</p>
                        <p class="text-sm mt-1">NIP. {{ $sppd->perintah_nip ?? '_______________' }}</p>
                    </div>
                    <div class="text-center">
                        <p class="mb-16">Pelaksana SPD</p>
                        <p class="font-bold border-b-2 border-black inline-block px-8">{{ $sppd->nama }}</p>
                        <p class="text-sm mt-1">NIP. {{ $sppd->nip ?? '_______________' }}</p>
                    </div>
                </div>
            </div>

            <div class="card-footer flex justify-between items-center">
                <a href="{{ route('kegiatan.pilih-detail', $sppd->kegiatan_id) }}"
                   class="btn btn-secondary">
                    ← Kembali
                </a>
                <div class="text-sm text-gray-600">
                    Status: <span class="font-semibold">{{ strtoupper($sppd->status) }}</span>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printable-area, #printable-area * {
                visibility: visible;
            }
            #printable-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .card-header, .card-footer, .btn {
                display: none !important;
            }
        }
    </style>
    @endpush
@endsection
