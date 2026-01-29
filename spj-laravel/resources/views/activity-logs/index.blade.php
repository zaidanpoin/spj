@extends('layouts.app')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'Riwayat aktivitas sistem')

@section('content')
    <div class="space-y-4">
        <!-- Search & Filter Card -->
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <form action="{{ route('activity-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>

                <!-- Event -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                    <select name="event"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua Event</option>
                        @foreach($events as $event)
                            <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                {{ ucfirst($event) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select name="user_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-sm bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        Filter
                    </button>
                    <a href="{{ route('activity-logs.index') }}"
                        class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-center">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Riwayat Aktivitas</h3>
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">
                    {{ $activities->total() }} aktivitas
                </span>
            </div>

            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-12">NO</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600">DESKRIPSI</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-28">EVENT</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-36">USER</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 w-40">WAKTU</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-600 w-20">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($activities as $index => $activity)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-gray-500">{{ $activities->firstItem() + $index }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-gray-900">{{ $activity->description }}</span>
                                    @if($activity->subject_type)
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ class_basename($activity->subject_type) }}
                                            @if($activity->subject_id)
                                                #{{ $activity->subject_id }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
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
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $activity->causer->name ?? 'System' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $activity->created_at->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('activity-logs.show', $activity->id) }}"
                                        class="inline-block px-2 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100 text-xs font-medium">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div class="lg:hidden divide-y divide-gray-100">
                @forelse($activities as $index => $activity)
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900">{{ $activity->description }}</span>
                                @if($activity->subject_type)
                                    <p class="text-xs text-gray-500">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</p>
                                @endif
                            </div>
                            <span class="text-xs text-gray-400">#{{ $activities->firstItem() + $index }}</span>
                        </div>

                        <div class="flex items-center gap-3 text-xs mb-3">
                            @php
                                $eventColors = [
                                    'created' => 'bg-green-100 text-green-700',
                                    'updated' => 'bg-blue-100 text-blue-700',
                                    'deleted' => 'bg-red-100 text-red-700',
                                ];
                                $colorClass = $eventColors[$activity->event] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-2 py-0.5 {{ $colorClass }} rounded font-medium">{{ ucfirst($activity->event) }}</span>
                            <span class="text-gray-600">{{ $activity->causer->name ?? 'System' }}</span>
                            <span class="text-gray-500">{{ $activity->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        <a href="{{ route('activity-logs.show', $activity->id) }}"
                            class="block px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 text-xs font-medium text-center">
                            Lihat Detail
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        Belum ada aktivitas
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection
