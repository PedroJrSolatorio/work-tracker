@props(['percentage', 'showLabel' => true])

<div class="mb-6">
    @if ($showLabel)
        <div class="flex justify-between text-sm text-gray-600 mb-2">
            <span>Progress</span>
            <span>{{number_format($percentage, 0)}}%</span>
        </div>
    @endif
    <div class="w-full bg-gray-200 rounded-full h-4">
        <div class="bg-blue-500 h-4 rounded-full transition-all duration-300" 
             style="width: {{ min(100, $percentage) }}%"></div>
    </div>
</div>