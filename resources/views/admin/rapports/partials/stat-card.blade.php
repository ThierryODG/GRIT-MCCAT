@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
    ];
    $color = $colors[$color ?? 'blue'];
@endphp

<div class="bg-white rounded-lg border {{ $color['border'] }} p-4">
    <div class="flex items-center">
        <div class="{{ $color['bg'] }} p-3 rounded-lg">
            <i class="fas fa-{{ $icon }} {{ $color['text'] }} text-lg"></i>
        </div>
        <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            @if(isset($description))
            <p class="mt-1 text-xs text-gray-500">{{ $description }}</p>
            @endif
        </div>
    </div>
</div>
