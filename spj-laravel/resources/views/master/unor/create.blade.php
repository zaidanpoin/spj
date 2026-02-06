@extends('layouts.app')

@section('title', 'Tambah Unit Organisasi')
@section('page-title', 'Tambah Unit Organisasi')
@section('page-subtitle', 'Form Tambah Data Unor')

@section('content')
    <div class="max-w-2xl">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Form Tambah Unor</h2>
            </div>

            <form action="{{ route('master.unor.store') }}" method="POST" class="card-body space-y-4">
                @csrf

                <div>
                    <label class="form-label">
                        Kode Unor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_unor" value="{{ old('kode_unor') }}" required
                        class="form-input @error('kode_unor') border-red-500 @enderror" placeholder="Contoh: SETJEN">
                    @error('kode_unor')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">
                        Nama Unit Organisasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_unor" value="{{ old('nama_unor') }}" required
                        class="form-input @error('nama_unor') border-red-500 @enderror"
                        placeholder="Contoh: Sekretariat Jenderal">
                    @error('nama_unor')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">
                        Alamat
                    </label>
                    <textarea name="alamat" rows="3" class="form-input @error('alamat') border-red-500 @enderror" placeholder="Alamat lengkap (opsional)">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2 pt-3 border-t border-gray-200">
                    <button type="submit" class="btn-primary">
                        Simpan
                    </button>
                    <a href="{{ route('master.unor.index') }}" class="btn-secondary">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
