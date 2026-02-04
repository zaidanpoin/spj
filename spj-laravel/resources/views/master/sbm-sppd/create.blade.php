@extends('layouts.app')

@section('title', 'Tambah SBM SPPD')
@section('page-title', 'Tambah SBM SPPD')
@section('page-subtitle', 'Tambah data satuan biaya SPPD')

@section('content')
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form action="{{ route('master.sbm-sppd.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis</label>
                    <input type="text" name="jenis" value="{{ old('jenis') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Item</label>
                    <input type="text" name="item" value="{{ old('item') }}" class="w-full px-3 py-2 border rounded" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan (singkat)</label>
                    <input type="text" name="satuan_sing" value="{{ old('satuan_sing') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan (deskripsi)</label>
                    <input type="text" name="satuan_desk" value="{{ old('satuan_desk') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nilai</label>
                    <input type="text" name="nilai" value="{{ old('nilai') }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Anggaran</label>
                    <input type="number" name="thang" value="{{ old('thang') }}" class="w-full px-3 py-2 border rounded" />
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                <a href="{{ route('master.sbm-sppd.index') }}" class="px-4 py-2 bg-gray-100 rounded">Batal</a>
            </div>
        </form>
    </div>
@endsection
