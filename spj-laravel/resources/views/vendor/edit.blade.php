@extends('layouts.app')

@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card">
        <div class="card-header"><h3 class="card-title">Edit Vendor</h3></div>
        <form action="{{ route('vendor.update', $vendor->id) }}" method="POST" class="card-body">
            @csrf
            @method('PUT')
            <div class="grid gap-3">
                <div>
                    <label class="form-label">Nama Vendor</label>
                    <input type="text" name="nama_vendor" class="form-input" value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
                </div>
                <div>
                    <label class="form-label">Nama Direktur</label>
                    <input type="text" name="nama_direktur" class="form-input" value="{{ old('nama_direktur', $vendor->nama_direktur) }}">
                </div>
                <div>
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-input" value="{{ old('npwp', $vendor->npwp) }}">
                </div>
                <div>
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-input">{{ old('alamat', $vendor->alamat) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Bank</label>
                    <select name="bank" class="form-input">
                        <option value="">-- Pilih Bank --</option>
                        @foreach(config('banks') as $bank)
                            @php $name = is_array($bank) ? ($bank['nama'] ?? $bank['name'] ?? $bank[0] ?? '') : $bank; @endphp
                            <option value="{{ $name }}" {{ (old('bank', $vendor->bank) == $name) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">No. Rekening</label>
                    <input type="text" name="rekening" class="form-input" value="{{ old('rekening', $vendor->rekening) }}">
                </div>
                <div>
                    <label class="form-label">PPN (%)</label>
                    <select name="ppn" class="form-input">
                        <option value="">(biarkan kosong)</option>
                        <option value="0" {{ (string)old('ppn', $vendor->ppn) === '0' ? 'selected' : '' }}>0% - Non-PKP</option>
                        <option value="11" {{ (string)old('ppn', $vendor->ppn) === '11' ? 'selected' : '' }}>11% - PKP</option>
                        <option value="12" {{ (string)old('ppn', $vendor->ppn) === '12' ? 'selected' : '' }}>12% - PKP</option>
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
