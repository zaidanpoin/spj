@extends('layouts.app')

@section('title', 'Edit Permission')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Edit Permission: {{ $permission->name }}</h1>
        <p class="text-gray-600 mt-1">Update permission details</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Permission Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Permission Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $permission->name) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="e.g., view-users, create-posts, delete-comments" required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Use lowercase with hyphens (e.g., view-users, edit-posts)</p>
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" id="category" value="{{ old('category', $permission->category) }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                       placeholder="e.g., users, kegiatan, master-data" required
                       list="categories">
                <datalist id="categories">
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">
                    @endforeach
                </datalist>
                @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Group related permissions together (e.g., users, kegiatan)</p>
            </div>

            <!-- Warning Message -->
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Warning:</strong> Changing permission names may affect existing role assignments.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('permissions.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition duration-200">
                    Update Permission
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
