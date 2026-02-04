@extends('layouts.app')

@section('title', 'Edit SBM SPPD')
@section('page-title', 'Edit SBM SPPD')
@section('page-subtitle', 'Ubah data satuan biaya SPPD')

@section('content')
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <form action="{{ route('master.sbm-sppd.update', $sbm->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis</label>
                    <input type="text" name="jenis" value="{{ old('jenis', $sbm->jenis) }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" name="kelas" value="{{ old('kelas', $sbm->kelas) }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Item</label>
                    <input type="text" name="item" value="{{ old('item', $sbm->item) }}" class="w-full px-3 py-2 border rounded" required />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan (singkat)</label>
                    <input type="text" name="satuan_sing" value="{{ old('satuan_sing', $sbm->satuan_sing) }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan (deskripsi)</label>
                    <input type="text" name="satuan_desk" value="{{ old('satuan_desk', $sbm->satuan_desk) }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nilai</label>
                    <input type="text" name="nilai" value="{{ old('nilai', $sbm->nilai) }}" class="w-full px-3 py-2 border rounded" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Anggaran</label>
                    <input type="number" name="thang" value="{{ old('thang', $sbm->thang) }}" class="w-full px-3 py-2 border rounded" />
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
                <a href="{{ route('master.sbm-sppd.index') }}" class="px-4 py-2 bg-gray-100 rounded">Batal</a>
            </div>
        </form>
    </div>
@endsection
