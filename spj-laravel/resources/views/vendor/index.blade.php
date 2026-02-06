@extends('layouts.app')

@section('title', 'Master Vendor')
@section('page-title', 'Master Vendor')
@section('page-subtitle', 'Kelola data vendor')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h3 class="card-title">Daftar Vendor</h3>
            <a href="{{ route('vendor.create') }}" class="btn-primary">Tambah Vendor</a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500">{{ session('success') }}</div>
            @endif

            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="text-left">Nama Vendor</th>
                        <th class="text-left">Direktur</th>
                        <th class="text-left">NPWP</th>
                        <th class="text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr class="border-t">
                            <td class="py-2">{{ $vendor->nama_vendor }}</td>
                            <td class="py-2">{{ $vendor->nama_direktur }}</td>
                            <td class="py-2">{{ $vendor->npwp }}</td>
                            <td class="py-2">
                                <a href="{{ route('vendor.edit', $vendor->id) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus vendor?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">{{ $vendors->links() }}</div>
        </div>
    </div>
</div>
@endsection
