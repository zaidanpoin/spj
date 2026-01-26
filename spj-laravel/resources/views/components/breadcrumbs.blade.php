@props(['items' => []])

@if(count($items) > 0)
<nav aria-label="Breadcrumb" class="mb-4">
    <ol class="flex items-center space-x-2 text-sm">
        <!-- Home -->
        <li>
            <a href="{{ route('home') }}" class="flex items-center text-gray-500 hover:text-primary transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-1.5">Home</span>
            </a>
        </li>

        @foreach($items as $index => $item)
            <li class="flex items-center">
                <!-- Separator -->
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>

                @if($index === count($items) - 1)
                    <!-- Last item (current page) -->
                    <span class="ml-2 text-gray-700 font-medium" aria-current="page">{{ $item['label'] }}</span>
                @else
                    <!-- Clickable items -->
                    <a href="{{ $item['url'] }}" class="ml-2 text-gray-500 hover:text-primary transition-colors">
                        {{ $item['label'] }}
                    </a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
