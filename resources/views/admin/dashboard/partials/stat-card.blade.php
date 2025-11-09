@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
        'purple' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200'],
        'red' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'border' => 'border-red-200'],
        'green' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'border' => 'border-green-200'],
    ];
    $color = $colors[$color ?? 'blue'];
@endphp

<div class="bg-white rounded-lg border {{ $color['border'] }} p-4">
    <div class="flex items-center">
        <div class="{{ $color['bg'] }} p-2 rounded-lg">
            <i class="fas fa-{{ $icon }} {{ $color['text'] }}"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="text-xl font-bold text-gray-900">{{ $value }}</p>
        </div>
    </div>
    @if(isset($trend) || isset($alert))
    <div class="mt-2">
        @if(isset($alert))
            <span class="text-xs font-medium text-red-600">⚠️ Action requise</span>
        @elseif(isset($trend))
            <span class="text-xs text-gray-500">{{ $trend }}</span>
        @endif
    </div>
    @endif
</div>
