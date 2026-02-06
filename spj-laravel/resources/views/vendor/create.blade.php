@extends('layouts.app')

@section('title', 'Tambah Vendor')
@section('page-title', 'Tambah Vendor')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Tambah Vendor</h3></div>
        <form action="{{ route('vendor.store') }}" method="POST" class="card-body">
            @csrf
            <div class="grid gap-3">
                <div>
                    <label class="form-label">Nama Vendor</label>
                    <input type="text" name="nama_vendor" class="form-input" value="{{ old('nama_vendor') }}" required>
                </div>
                <div>
                    <label class="form-label">Nama Direktur</label>
                    <input type="text" name="nama_direktur" class="form-input" value="{{ old('nama_direktur') }}">
                </div>
                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-input" value="{{ old('npwp') }}">
                </div>
                <div>
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-input">{{ old('alamat') }}</textarea>
                </div>
                <div>
                    <label class="form-label">Bank</label>
                    <select name="bank" class="form-input">
                        <option value="">-- Pilih Bank --</option>
                        @foreach(config('banks') as $bank)
                            @php $name = is_array($bank) ? ($bank['nama'] ?? $bank['name'] ?? $bank[0] ?? '') : $bank; @endphp
                            <option value="{{ $name }}" {{ old('bank') == $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">No. Rekening</label>
                    <input type="text" name="rekening" class="form-input" value="{{ old('rekening') }}">
                </div>
                <div>
                    <label class="form-label">PPN (%)</label>
                    <select name="ppn" class="form-input">
                        <option value="">(biarkan kosong)</option>
                        <option value="0" {{ old('ppn') === '0' ? 'selected' : '' }}>0% - Non-PKP</option>
                        <option value="11" {{ old('ppn') === '11' ? 'selected' : '' }}>11% - PKP</option>
                        <option value="12" {{ old('ppn') === '12' ? 'selected' : '' }}>12% - PKP</option>
                    </select>
                </div>
                <div class="flex gap-2 justify-end">
                    <a href="{{ route('vendor.index') }}" class="btn-secondary">Batal</a>
                    <button class="btn-primary" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
