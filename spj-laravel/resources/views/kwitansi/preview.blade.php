<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi {{ $jenis }} - {{ $kegiatan->nama_kegiatan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            background-color: #f3f4f6;
        }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            padding: 1.27cm 15mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        @media print {
            .sheet {
                width: 100%;
                height: 100%;
                box-shadow: none;
                margin: 0;
                padding: 1.27cm 15mm;
            }

            body {
                background-color: white;
            }
        }

        /* Border box wrapper for the entire content */
        .main-box {
            border: 1.5px solid black;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .border-top-black {
            border-top: 1.5px solid black;
        }

        td {
            vertical-align: top;
            padding-bottom: 4px;
        }
    </style>
</head>

<body class="text-black">
    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white border-b border-gray-200 shadow-sm z-50">
        <div class="max-w-4xl mx-auto px-4 py-2 flex items-center justify-between">
            <a href="{{ route('kegiatan.pilih-detail', $kegiatan->id) }}"
                class="text-gray-600 hover:text-gray-900 text-sm flex items-center gap-2">
                &larr; Kembali
            </a>
            <div class="flex gap-2">
                <a href="{{ route('kwitansi.download', ['kegiatan_id' => $kegiatan->id, 'jenis' => $jenis]) }}"
                    class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700">
                    Download PDF
                </a>
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded hover:bg-gray-900">
                    Cetak
                </button>
            </div>
        </div>
    </div>

    <!-- Spacer -->
    <div class="h-16 no-print"></div>

    <!-- NEW PAGE 1: Kuitansi (refined to match sample) -->
   <!-- PAGE 1 : KUITANSI SESUAI CONTOH -->
<div class="sheet">



        <!-- HEADER KANAN -->
        <div class="flex justify-end mb-6">
            <table class="text-[10pt]" style="border-collapse:collapse;">
                <tr>
                    <td style="width:120px">Tahun Anggaran</td>
                    <td style="width:8px">:</td>
                    <td>{{ date('Y') }}</td>
                </tr>
                <tr>
                    <td>Nomor Bukti</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Akun</td>
                    <td>:</td>
                    <td>{{ $kegiatan->mak->akun ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kepada</td>
                    <td>:</td>
                    <td>BENDAHARA PENGELUARAN SATKER</td>
                </tr>
                <tr>
                    <td>Satker</td>
                    <td>:</td>
                    <td>{{ $kegiatan->unitKerja->nama_unit }}</td>
                </tr>
            </table>
        </div>

        <!-- JUDUL -->
        <div class="text-center mb-8">
            <h1 class="font-bold text-[13pt] uppercase">
                KUITANSI/ BUKTI PEMBAYARAN
            </h1>
        </div>

        <!-- ISI -->
        <table class="w-full text-[10pt]" style="border-collapse:collapse;">
            <tr>
                <td style="width:170px">Sudah Terima Dari</td>
                <td style="width:10px">:</td>
                <td>
                    Pejabat Pembuat Komitmen<br>
                    {{ $kegiatan->unitKerja->nama_unit }}
                </td>
            </tr>

            <tr>
                <td>Jumlah Uang</td>
                <td>:</td>
                <td class="font-bold">Rp. {{ number_format($totalKonsumsi,0,',','.') }}</td>
            </tr>

            <tr>
                <td>Terbilang</td>
                <td>:</td>
                <td class="font-bold capitalize">
                    {{ ucwords($terbilang) }} Rupiah
                </td>
            </tr>

            <tr>
                <td>Kuitansi Supplier</td>
                <td>:</td>
                <td></td>
            </tr>

            <tr>
                <td>Untuk Pembayaran</td>
                <td>:</td>
                <td>{{ $kegiatan->uraian_kegiatan }}</td>
            </tr>
        </table>

        <!-- TANGGAL + PENERIMA -->
      <!-- PENERIMA UANG (KANAN tapi text center) -->
<div class="flex justify-end mt-10">

    <table class="text-[10pt]" style="width:45%; text-align:center;">
        <tr>
            <td>
                KOTA JAKARTA SELATAN, {{ now()->translatedFormat('d F Y') }}
            </td>
        </tr>

        <tr>
            <td class="font-bold">
                PENERIMA UANG
            </td>
        </tr>

        <tr>
            <td>
                Pelaksana/Pembuat Daftar
            </td>
        </tr>

        <!-- ruang tanda tangan -->
        <tr>
            <td style="height:110px;"></td>
        </tr>

        <tr>
            <td class="font-bold underline">
                {{ auth()->user()->name }}
            </td>
        </tr>

        <tr>
            <td>
                NIP. {{ auth()->user()->nip }}
            </td>
        </tr>
    </table>

</div>


        <!-- GARIS PEMBATAS -->
        <div class="border-t border-black my-6"></div>

        <!-- TANDA TANGAN BAWAH -->
        <div class="flex justify-between text-center text-[10pt]">

            <div class="w-1/2">
                <p>a.n. Kuasa Pengguna Anggaran<br>Pejabat Pembuat Komitmen</p>

                <div style="height:70px"></div>

                <p class="font-bold underline">{{ $kegiatan->ppk->nama }}</p>
                <p>NIP. {{ $kegiatan->ppk->nip }}</p>
            </div>

            <div class="w-1/2">
                <p>Lunas dibayar, {{ now()->translatedFormat('d F Y') }}<br>
                Bendahara Pengeluaran</p>

                <div style="height:70px"></div>

                <p class="font-bold underline">{{ $kegiatan->bendahara->nama }}</p>
                <p>NIP. {{ $kegiatan->bendahara->nip }}</p>
            </div>

        </div>

        <!-- CATATAN BAWAH -->
        <div class="text-xs mt-6">
            <p>Barang/pekerjaan tersebut telah diterima/diselesaikan dengan lengkap dan</p>
            <p>Pejabat yang bertanggung jawab</p>
            <p>Penerima Barang</p>
        </div>



</div>


    <!-- END NEW PAGE 1 -->

    <div class="sheet">

        <!-- Main Content Box (One Big Border) -->
        <div class="main-box">

            <!-- Upper Content Section (Header + Body) -->
            <div class="p-6 pb-2 flex-grow">

                <!-- Header (Inside Box) -->
                <div class="flex justify-between items-start mb-6">
                    <!-- Left: Logo -->
                    <div class="pt-2">
                        <span class="text-green-700 font-bold italic text-lg">
                            <img src="{{ asset('images/logo_kementerian.png') }}" class="h-16"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='inline'">
                            <span style="display:none">qrsimbpk</span>
                        </span>
                    </div>

                    <!-- Right: Meta Data -->
                    <div class="text-sm font-bold w-[60%]">
                        <div class="mb-2 uppercase text-left">KUITANSI {{ $jenis }}</div>
                        <table class="w-full">
                            <tr>
                                <td class="w-32">Tahun Anggaran</td>
                                <td class="w-2">:</td>
                                <td>{{ date('Y') }}</td>
                            </tr>
                            <tr>
                                <td>No. Bukti</td>
                                <td>:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>MAK</td>
                                <td>:</td>
                                <td>{{ $kegiatan->mak->kode ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Akun</td>
                                <td>:</td>
                                <td>524113</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Title -->
                <div class="text-center mb-10">
                    <h1 class="text-xl font-bold uppercase">KUITANSI / BUKTI PEMBAYARAN</h1>
                </div>

                <!-- Table Content -->
                <table class="w-full text-[10.5pt] mb-8">
                    <tr>
                        <td class="w-40 py-1">Sudah terima dari</td>
                        <td class="w-4 py-1">:</td>
                        <td class="py-1">
                            Pejabat Pembuat Komitmen Satuan Kerja<br>
                            {{ $kegiatan->unitKerja->nama_unit ?? 'Sekretariat Badan Pengembangan Sumber Daya Manusia' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">Uang sebesar</td>
                        <td class="py-1">:</td>
                        <td class="py-1 font-bold">
                            Rp. {{ number_format($totalKonsumsi, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">Terbilang</td>
                        <td class="py-1">:</td>
                        <td class="py-1 font-bold bg-gray-100 print:bg-transparent capitalize">
                            {{ ucwords(trim($terbilang)) }} rupiah
                        </td>
                    </tr>
                    <tr>
                        <td class="py-1">Untuk Pembayaran</td>
                        <td class="py-1">:</td>
                        <td class="py-1 text-justify leading-snug">
                            {{ $kegiatan->uraian_kegiatan ?? ('Himpunan perjalanan dinas dalam rangka ' . $kegiatan->nama_kegiatan) }}
                        </td>
                    </tr>
                </table>

                <!-- Middle Signatures -->
                <div class="flex justify-between mt-12 mb-8 px-4">
                    <!-- Left: Mengetahui -->
                    <div class="text-center w-1/2">
                        <p class="mb-20 leading-tight">
                            Yang Mengetahui,<br>
                            {{ $kegiatan->ppk->jabatan ?? 'Pejabat Pembuat Komitmen, Bagian Hukum, Kerja Sama, Komunikasi Publik' }}
                        </p>
                        <div class="mt-4">
                            <p class="font-bold underline">{{ $kegiatan->ppk->nama ?? '..........................' }}
                            </p>
                            <p>{{ $kegiatan->ppk->nip ?? '' }}</p>
                        </div>
                    </div>

                    <!-- Right: Menerima -->
                    <div class="text-center w-1/2">
                        <p class="mb-1">Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                        <p class="mb-20 leading-tight">
                            Yang Menerima,<br>
                            <span class="font-bold">Pembuat Daftar</span>
                        </p>
                        <div class="mt-4">
                            <p class="font-bold underline">{{ auth()->user()->name ?? '..........................' }}
                            </p>
                            <p>{{ auth()->user()->nip ?? 'NIP: ..........................' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Section (Separated by Border Top) -->
            <div class="border-top-black relative p-6 pt-4 text-[10pt]">
                <!-- Top Right Date -->
                <div class="absolute top-1 right-6 text-sm">
                    Lunas dibayar Tgl {{ now()->translatedFormat('d F Y') }}
                </div>

                <div class="flex justify-between mt-8">
                    <!-- Left Footer -->
                    <div class="text-center w-1/2">
                        <p class="mb-20 leading-tight">
                            Setuju dibebankan pada mata anggaran<br>berkenan,<br>
                            an. Kuasa Pengguna Anggaran<br>
                            Pejabat Pembuat Komitmen
                        </p>
                        <div>
                            <p class="font-bold underline">{{ $kegiatan->ppk->nama ?? '.....................' }}</p>
                            <p>{{ $kegiatan->ppk->nip ?? '' }}</p>
                        </div>
                    </div>

                    <!-- Right Footer -->
                    <div class="text-center w-1/2 flex flex-col justify-end">
                        <p class="mb-20 leading-tight">
                            Bendahara Pengeluaran
                        </p>
                        <div>
                            <p class="font-bold underline">{{ $kegiatan->bendahara->nama ?? '.....................' }}
                            </p>
                            <p>{{ $kegiatan->bendahara->nip ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- PAGE 2: Daftar Belanja / Pembayaran UP -->
    <div class="sheet" style="page-break-before: always;">
        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-base font-bold uppercase mb-1">PEMBAYARAN UP</h1>
            <p class="text-sm">{{ $kegiatan->nama_kegiatan }}</p>
        </div>

        <!-- Data Table -->
        <table class="w-full border-collapse mb-6" style="font-size: 10pt;">
            <thead>
                <tr>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 40px;">No.</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-left" style="width: 40%;">Nama Barang</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 70px;">Jumlah</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 110px;">Harga</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 120px;">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($konsumsis as $index => $konsumsi)
                <tr>
                    <td class="border border-black px-2 py-1.5 text-center">{{ $index + 1 }}</td>
                    <td class="border border-black px-2 py-1.5 text-left">{{ $konsumsi->nama_konsumsi ?? 'Hidangan ' . ($konsumsi->waktuKonsumsi->nama ?? 'Konsumsi') }}</td>
                    <td class="border border-black px-2 py-1.5 text-center">{{ number_format($konsumsi->jumlah, 0, ',', '.') }}</td>
                    <td class="border border-black px-2 py-1.5 text-right">Rp&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($konsumsi->harga, 0, ',', '.') }}</td>
                    <td class="border border-black px-2 py-1.5 text-right">Rp&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($konsumsi->jumlah * $konsumsi->harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border border-black px-2 py-4 text-center">Tidak ada data konsumsi</td>
                </tr>
                @endforelse
                <tr class="bg-gray-200">
                    <td colspan="4" class="border border-black px-2 py-1.5 text-left font-bold">Total</td>
                    <td class="border border-black px-2 py-1.5 text-right font-bold">Rp&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($totalKonsumsi, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Signature Section -->
        <div class="mt-10" style="font-size: 10pt;">
            <div class="flex justify-between">
                <!-- Left: Bendahara Pengeluaran -->
                <div class="text-center" style="width: 45%;">
                    <p class="mb-1">Mengetahui/Menyetujui</p>
                    <p class="mb-16">Bendahara Pengeluaran</p>
                    <div class="mt-16">
                        <p class="font-bold underline">{{ $kegiatan->bendahara->nama ?? '..............................' }}</p>
                        <p class="text-sm">NIP. {{ $kegiatan->bendahara->nip ?? '..............................' }}</p>
                    </div>
                </div>

                <!-- Right: Pembuat Daftar -->
                <div class="text-center" style="width: 45%;">
                    <p class="mb-1">Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                    <p>Yang Menerima,</p>
                    <p class="font-bold mb-16">Pembuat Daftar</p>
                    <div class="mt-16">
                        <p class="font-bold underline">{{ auth()->user()->name ?? '..............................' }}</p>
                        <p class="text-sm">NIP. {{ auth()->user()->nip ?? '..............................' }}</p>
                    </div>
                </div>
            </div>

            <!-- Bottom: PPK -->
            <div class="mt-10 text-center" style="width: 50%;">
                <p class="mb-1">Setuju dibebankan pada mata anggaran berkenan,</p>
                <p>an. Kuasa Pengguna Anggaran</p>
                <p class="mb-16">Pejabat Pembuat Komitmen</p>
                <div class="mt-16">
                    <p class="font-bold underline">{{ $kegiatan->ppk->nama ?? '..............................' }}</p>
                    <p class="text-sm">NIP. {{ $kegiatan->ppk->nip ?? '..............................' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE 3: Daftar Hadir -->
    <div class="sheet" style="page-break-before: always;">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-base font-bold uppercase mb-1">DAFTAR HADIR</h1>
            <p class="text-sm">{{ $kegiatan->nama_kegiatan }}</p>
            <p class="text-xs text-gray-600 mt-1">
                {{ $kegiatan->tanggal_mulai ? $kegiatan->tanggal_mulai->translatedFormat('d F Y') : '-' }}
                @if($kegiatan->tanggal_selesai && $kegiatan->tanggal_selesai != $kegiatan->tanggal_mulai)
                    - {{ $kegiatan->tanggal_selesai->translatedFormat('d F Y') }}
                @endif
            </p>
        </div>

        @php $totalPeserta = $kegiatan->jumlah_peserta ?? 20; @endphp

        <!-- Daftar Hadir Table -->
        <table class="w-full border-collapse" style="font-size: 10pt;">
            <thead>
                <tr>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 40px;">No</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 35%;">Nama Lengkap</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 30%;">Instansi</th>
                    <th class="border border-black px-2 py-1.5 bg-gray-100 text-center" style="width: 35%;">Tanda Tangan</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= $totalPeserta; $i++)
                    <tr>
                        <td class="border border-black px-2 py-3 text-center">{{ $i }}</td>
                        <td class="border border-black px-2 py-3"></td>
                        <td class="border border-black px-2 py-3"></td>
                        <td class="border border-black px-2 py-3">
                            <div class="flex justify-between items-start h-5">
                                <div class="w-1/2">
                                    @if($i % 2 == 1)
                                        <span class="text-gray-500 text-xs">{{ $i }}.</span>
                                    @endif
                                </div>
                                <div class="w-1/2 text-right">
                                    @if($i % 2 == 0)
                                        <span class="text-gray-500 text-xs">{{ $i }}.</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <!-- Footer/Signature -->
        <div class="mt-8 flex justify-end">
            <div class="text-center" style="font-size: 10pt;">
                <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="mt-1">Mengetahui,</p>
                <div class="mt-16">
                    <p class="font-bold underline">{{ $kegiatan->ppk->nama ?? '..............................' }}</p>
                    <p class="text-sm">NIP. {{ $kegiatan->ppk->nip ?? '..............................' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
