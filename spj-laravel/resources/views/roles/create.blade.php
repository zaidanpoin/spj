@extends('layouts.app')

@section('title', 'Create Role')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Create New Role</h1>
        <p class="text-gray-600 mt-1">Define a new role and assign permissions</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-4xl">
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            <!-- Role Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                       placeholder="e.g., editor, manager" required>
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Permissions -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Permissions</label>

                @if($permissions->count() > 0)
                    @foreach($permissions as $category => $categoryPermissions)
                    <div class="mb-4 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="select-all-{{ $category }}" class="select-all-category mr-2" data-category="{{ $category }}">
                            <h3 class="text-lg font-semibold text-gray-800 capitalize">{{ $category }}</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($categoryPermissions as $permission)
                            <div class="flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       id="permission-{{ $permission->id }}"
                                       class="permission-checkbox category-{{ $category }} mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                <label for="permission-{{ $permission->id }}" class="text-sm text-gray-700">
                                    {{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-gray-500 italic">No permissions available. Please create permissions first.</p>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('roles.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg shadow-md transition duration-200">
                    Create Role
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Select all in category
    document.querySelectorAll('.select-all-category').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const category = this.dataset.category;
            const categoryCheckboxes = document.querySelectorAll('.category-' + category);
            categoryCheckboxes.forEach(cb => cb.checked = this.checked);
        });
    });

    // Update "select all" checkbox when individual checkboxes change
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const categoryClass = Array.from(this.classList).find(cls => cls.startsWith('category-'));
            if (categoryClass) {
                const category = categoryClass.replace('category-', '');
                const selectAllCheckbox = document.querySelector(`#select-all-${category}`);
                const categoryCheckboxes = document.querySelectorAll('.category-' + category);
                const allChecked = Array.from(categoryCheckboxes).every(cb => cb.checked);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                }
            }
        });
    });
</script>
@endsection
