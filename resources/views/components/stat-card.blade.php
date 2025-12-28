@props(['label', 'value', 'detail' => null, 'color' => 'gray'])

@php
    $colorClasses = [
        'gray' => 'bg-gray-50 text-gray-800',
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'orange' => 'bg-orange-50 text-orange-600',
    ];
@endphp

<div class="text-center p-4 rounded-lg {{ $colorClasses[$color] ?? $colorClasses['gray'] }}">
    <p class="text-sm text-gray-600 mb-1">{{ $label }}</p>
    <p class="text-2xl font-bold {{ str_contains($colorClasses[$color], 'text-') ? explode(' ', $colorClasses[$color])[1] : 'text-gray-800' }}">
        {{ $value }}
    </p>
    @if($detail)
        <p class="text-xs text-gray-500 mt-1">{{ $detail }}</p>
    @endif
</div>