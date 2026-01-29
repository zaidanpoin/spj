@extends('layouts.app')

@section('title', 'Detail Activity Log')
@section('page-title', 'Detail Activity Log')
@section('page-subtitle', 'Informasi lengkap aktivitas')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Informasi Aktivitas</h3>
                <a href="{{ route('activity-logs.index') }}"
                    class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm">
                    Kembali
                </a>
            </div>

            <div class="p-4 sm:p-6 space-y-4">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <p class="text-gray-900">{{ $activity->description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                        @php
                            $eventColors = [
                                'created' => 'bg-green-100 text-green-700',
                                'updated' => 'bg-blue-100 text-blue-700',
                                'deleted' => 'bg-red-100 text-red-700',
                            ];
                            $colorClass = $eventColors[$activity->event] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 {{ $colorClass }} text-xs font-medium rounded">
                            {{ ucfirst($activity->event) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <p class="text-gray-900">{{ $activity->causer->name ?? 'System' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <p class="text-gray-900">{{ $activity->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    @if($activity->subject_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject Type</label>
                            <p class="text-gray-900">{{ class_basename($activity->subject_type) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject ID</label>
                            <p class="text-gray-900">{{ $activity->subject_id }}</p>
                        </div>
                    @endif
                </div>

                <!-- Properties -->
                @if($activity->properties && $activity->properties->count() > 0)
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Detail Perubahan</h4>

                        <div class="space-y-4">
                            @if($activity->properties->has('attributes'))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Baru</label>
                                    <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                        <pre class="text-xs text-gray-800 overflow-auto">{{ json_encode($activity->properties->get('attributes'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif

                            @if($activity->properties->has('old'))
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Data Lama</label>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <pre class="text-xs text-gray-800 overflow-auto">{{ json_encode($activity->properties->get('old'), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Additional Info -->
                @if($activity->log_name)
                    <div class="border-t border-gray-200 pt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Log Name</label>
                            <p class="text-gray-900">{{ $activity->log_name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
